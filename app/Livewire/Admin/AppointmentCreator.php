<?php

namespace App\Livewire\Admin;

use App\Mail\AppointmentConfirmation;
use App\Mail\DailyAppointmentReport;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Carbon\Carbon;

class AppointmentCreator extends Component
{
    public $searchDate = '';
    public $searchTime = '';
    public $searchSpeciality = '';
    public $timeOptions = [];
    public $availableDoctors = [];
    public $hasSearched = false;
    public $selectedDoctorId = null;
    public $selectedDoctorName = '';
    public $selectedSlotStart = '';
    public $selectedSlotEnd = '';
    public $selectedDate = '';
    public $patientId = '';
    public $reason = '';
    public $specialities = [];
    public $patients = [];

    public function mount()
    {
        $this->searchDate = date('Y-m-d');
        $this->specialities = Speciality::orderBy('name')->get()->toArray();
        $this->patients = Patient::with('user')->get()->toArray();
        $this->loadTimeOptions();
    }

    private function loadTimeOptions()
    {
        $this->timeOptions = DoctorSchedule::select('start_time', 'end_time')
            ->distinct()
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                $start = Carbon::parse($schedule->start_time)->format('H:i:s');
                $end = Carbon::parse($schedule->end_time)->format('H:i:s');
                return [
                    'value' => $start,
                    'label' => $start . ' – ' . $end,
                ];
            })
            ->unique('value')
            ->values()
            ->toArray();
    }

    public function searchAvailability()
    {
        $this->validate([
            'searchDate' => 'required|date|after_or_equal:today',
        ], [
            'searchDate.required' => 'La fecha es obligatoria.',
            'searchDate.after_or_equal' => 'No se permiten fechas en el pasado.',
        ]);

        $date = Carbon::parse($this->searchDate);
        $dayOfWeek = $date->dayOfWeekIso;

        $query = Doctor::with([
            'user',
            'speciality',
            'schedules' => function ($q) use ($dayOfWeek) {
                $q->where('day_of_week', $dayOfWeek)->orderBy('start_time');
            }
        ])->whereHas('schedules', function ($q) use ($dayOfWeek) {
            $q->where('day_of_week', $dayOfWeek);
            if (!empty($this->searchTime)) {
                $q->where('start_time', $this->searchTime);
            }
        });

        if (!empty($this->searchSpeciality)) {
            $query->where('speciality_id', $this->searchSpeciality);
        }

        $doctors = $query->get();

        $existingAppointments = Appointment::where('date', $this->searchDate)
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_COMPLETED])
            ->get()
            ->groupBy('doctor_id');

        $this->availableDoctors = $doctors->map(function ($doctor) use ($existingAppointments) {
            $bookedSlots = [];
            if (isset($existingAppointments[$doctor->id])) {
                foreach ($existingAppointments[$doctor->id] as $appt) {
                    $bookedSlots[] = Carbon::parse($appt->start_time)->format('H:i:s');
                }
            }

            $availableSlots = $doctor->schedules->filter(function ($schedule) use ($bookedSlots) {
                $startTime = Carbon::parse($schedule->start_time)->format('H:i:s');
                return !in_array($startTime, $bookedSlots);
            })->map(function ($schedule) {
                return [
                    'start' => Carbon::parse($schedule->start_time)->format('H:i:s'),
                    'end' => Carbon::parse($schedule->end_time)->format('H:i:s'),
                ];
            })->values()->toArray();

            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'speciality' => $doctor->speciality->name ?? 'Sin especialidad',
                'initials' => collect(explode(' ', $doctor->user->name))
                    ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))
                    ->take(2)->implode(''),
                'slots' => $availableSlots,
            ];
        })->filter(fn($d) => count($d['slots']) > 0)->values()->toArray();

        $this->hasSearched = true;
        $this->selectedDoctorId = null;
        $this->selectedDoctorName = '';
        $this->selectedSlotStart = '';
        $this->selectedSlotEnd = '';
    }

    public function selectSlot($doctorId, $slotStart, $slotEnd)
    {
        $this->selectedDoctorId = $doctorId;
        $this->selectedSlotStart = $slotStart;
        $this->selectedSlotEnd = $slotEnd;
        $this->selectedDate = $this->searchDate;

        foreach ($this->availableDoctors as $doctor) {
            if ($doctor['id'] == $doctorId) {
                $this->selectedDoctorName = $doctor['name'];
                break;
            }
        }
    }

    public function confirmAppointment()
    {
        $this->validate([
            'selectedDoctorId' => 'required',
            'selectedSlotStart' => 'required',
            'selectedSlotEnd' => 'required',
            'selectedDate' => 'required|date|after_or_equal:today',
            'patientId' => 'required|exists:patients,id',
            'reason' => 'required|string|max:1000',
        ], [
            'patientId.required' => 'Debe seleccionar un paciente.',
            'reason.required' => 'El motivo de la cita es obligatorio.',
            'selectedDoctorId.required' => 'Debe seleccionar un doctor y horario.',
        ]);

        $start = Carbon::createFromFormat('H:i:s', $this->selectedSlotStart);
        $end = Carbon::createFromFormat('H:i:s', $this->selectedSlotEnd);

        $appointment = Appointment::create([
            'patient_id' => $this->patientId,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->selectedDate,
            'start_time' => $this->selectedSlotStart,
            'end_time' => $this->selectedSlotEnd,
            'duration' => $start->diffInMinutes($end),
            'reason' => $this->reason,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $appointment->load(['patient.user', 'doctor.user', 'doctor.speciality']);

        Mail::to($appointment->patient->user->email)
            ->send(new AppointmentConfirmation($appointment));

        Mail::to($appointment->doctor->user->email)
            ->send(new AppointmentConfirmation($appointment));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Cita creada!',
            'text' => 'La cita médica se ha registrado y se enviaron los correos.',
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }

    public function sendTestReport(): void
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])
            ->where('date', today()->toDateString())
            ->where('status', Appointment::STATUS_SCHEDULED)
            ->orderBy('start_time')
            ->get();

        if ($appointments->isEmpty()) {
            session()->flash('report_msg', 'warning|No hay citas agendadas para hoy.');
            return;
        }

        $admins = User::role('Administrador')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)
                ->send(new DailyAppointmentReport($appointments, $admin->name));
        }

        $byDoctor = $appointments->groupBy('doctor_id');
        foreach ($byDoctor as $doctorAppointments) {
            $doctor = $doctorAppointments->first()->doctor->user;
            Mail::to($doctor->email)
                ->send(new DailyAppointmentReport($doctorAppointments, $doctor->name));
        }

        session()->flash('report_msg', 'success|Reporte enviado a ' . $admins->count() . ' admin(s) y ' . $byDoctor->count() . ' doctor(es). Revisa Mailtrap.');
    }

    public function render()
    {
        return view('livewire.admin.appointment-creator');
    }
}