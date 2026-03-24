<?php

namespace App\Livewire\Admin;

use App\Mail\AppointmentConfirmation;
use App\Mail\DailyAppointmentReport;
use App\Models\Appointment;
use App\Models\Doctor;
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

    // Report feedback (component property, avoids Livewire session flash issues)
    public $reportMsg = '';
    public $reportMsgType = '';

    public function mount()
    {
        $this->searchDate = date('Y-m-d');
        $this->specialities = Speciality::orderBy('name')->get()->toArray();
        $this->patients = Patient::with('user')->get()->toArray();
        $this->loadTimeOptions();
    }

    private function loadTimeOptions()
    {
        // Generate all 24 hourly slots: 00:00–01:00, 01:00–02:00, ..., 23:00–00:00
        $slots = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $start = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $endHour = ($hour + 1) % 24;
            $end = str_pad($endHour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $slots[] = [
                'value' => $start,
                'label' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00 – ' . str_pad($endHour, 2, '0', STR_PAD_LEFT) . ':00',
            ];
        }
        $this->timeOptions = $slots;
    }

    public function searchAvailability()
    {
        $this->validate([
            'searchDate' => 'required|date|after_or_equal:today',
        ], [
            'searchDate.required' => 'La fecha es obligatoria.',
            'searchDate.after_or_equal' => 'No se permiten fechas en el pasado.',
        ]);

        // Build doctor query (no DoctorSchedule restriction — show all doctors)
        $query = Doctor::with(['user', 'speciality']);
        if (!empty($this->searchSpeciality)) {
            $query->where('speciality_id', $this->searchSpeciality);
        }
        $doctors = $query->get();

        // Get all booked slots for this date grouped by doctor
        $existingAppointments = Appointment::where('date', $this->searchDate)
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_COMPLETED])
            ->get()
            ->groupBy('doctor_id');

        // All 24 hourly slots
        $allSlots = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $endHour = ($hour + 1) % 24;
            $allSlots[] = [
                'start' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00',
                'end'   => str_pad($endHour, 2, '0', STR_PAD_LEFT) . ':00:00',
            ];
        }

        $this->availableDoctors = $doctors->map(function ($doctor) use ($existingAppointments, $allSlots) {
            // Collect already-booked start times for this doctor
            $bookedSlots = [];
            if (isset($existingAppointments[$doctor->id])) {
                foreach ($existingAppointments[$doctor->id] as $appt) {
                    $bookedSlots[] = Carbon::parse($appt->start_time)->format('H:i:s');
                }
            }

            // If a time filter is active, restrict to that slot only
            $slotsToCheck = $allSlots;
            if (!empty($this->searchTime)) {
                $slotsToCheck = array_values(array_filter($allSlots, fn($s) => $s['start'] === $this->searchTime));
            }

            $availableSlots = array_values(array_filter($slotsToCheck, fn($s) => !in_array($s['start'], $bookedSlots)));

            if (empty($availableSlots)) {
                return null;
            }

            return [
                'id'         => $doctor->id,
                'name'       => $doctor->user->name,
                'speciality' => $doctor->speciality->name ?? 'Sin especialidad',
                'initials'   => collect(explode(' ', $doctor->user->name))
                    ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))
                    ->take(2)->implode(''),
                'slots' => $availableSlots,
            ];
        })->filter()->values()->toArray();

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

        // Prevent double-booking: same doctor, date and start time
        $conflict = Appointment::where('doctor_id', $this->selectedDoctorId)
            ->where('date', $this->selectedDate)
            ->where('start_time', $this->selectedSlotStart)
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_COMPLETED])
            ->exists();

        if ($conflict) {
            $this->addError('selectedSlotStart', 'Este horario ya fue reservado. Por favor selecciona otro.');
            return;
        }

        $appointment = Appointment::create([
            'patient_id' => $this->patientId,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->selectedDate,
            'start_time' => $this->selectedSlotStart,
            'end_time' => $this->selectedSlotEnd,
            'duration' => 60, // all slots are 1-hour blocks
            'reason' => $this->reason,
            'status' => Appointment::STATUS_SCHEDULED,
        ]);

        $appointment->load(['patient.user', 'doctor.user', 'doctor.speciality']);

        $errors = [];

        // 1) Mailtrap sandbox: paciente (To) + doctor (CC) en una sola transacción
        try {
            Mail::to($appointment->patient->user->email)
                ->cc($appointment->doctor->user->email)
                ->send(new AppointmentConfirmation($appointment));
        } catch (\Throwable $e) {
            \Log::warning('Confirmación de cita (Mailtrap) falló: ' . $e->getMessage());
            $errors[] = 'Mailtrap';
        }

        // 2) Gmail real: envía copia al correo real configurado en ADMIN_REAL_EMAIL
        $realEmail = config('mail.admin_real_email');
        if ($realEmail && env('GMAIL_APP_PASSWORD')) {
            try {
                Mail::mailer('gmail')
                    ->to($realEmail)
                    ->send(new AppointmentConfirmation($appointment));
            } catch (\Throwable $e) {
                \Log::warning('Confirmación de cita (Gmail) falló: ' . $e->getMessage());
                $errors[] = 'Gmail';
            }
        }

        $mailIcon = empty($errors) ? 'success' : 'warning';
        $mailNote = empty($errors)
            ? 'Cita registrada. Correos enviados a paciente, doctor y ' . $realEmail . '.'
            : 'Cita registrada, pero falló el envío en: ' . implode(', ', $errors) . '. Revisa logs.';

        session()->flash('swal', [
            'icon' => $mailIcon,
            'title' => '¡Cita creada!',
            'text' => $mailNote,
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }

    public function sendTestReport(): void
    {
        $this->reportMsg = '';
        $this->reportMsgType = '';

        $appointments = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])
            ->where('date', today()->toDateString())
            ->where('status', Appointment::STATUS_SCHEDULED)
            ->orderBy('start_time')
            ->get();

        if ($appointments->isEmpty()) {
            $this->reportMsgType = 'warning';
            $this->reportMsg = 'No hay citas agendadas para hoy.';
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

        // Also send to the real admin email via Gmail SMTP if configured
        $realEmail = config('mail.admin_real_email');
        $realMailNote = '';
        if ($realEmail && env('GMAIL_APP_PASSWORD')) {
            try {
                Mail::mailer('gmail')
                    ->to($realEmail)
                    ->send(new DailyAppointmentReport($appointments, 'Administrador'));
                $realMailNote = ' También enviado a ' . $realEmail . ' (Gmail).';
            } catch (\Throwable $e) {
                \Log::warning('Gmail real email failed: ' . $e->getMessage());
                $realMailNote = ' (Fallo envío a Gmail: ' . $e->getMessage() . ')';
            }
        } elseif ($realEmail) {
            $realMailNote = ' [GMAIL_APP_PASSWORD no configurado — no se envió a ' . $realEmail . ']';
        }

        $this->reportMsgType = 'success';
        $this->reportMsg = 'Reporte enviado a ' . $admins->count() . ' admin(s) y ' . $byDoctor->count() . ' doctor(es). Revisa Mailtrap.' . $realMailNote;
    }

    // Quick test: sends a confirmation email using the most recent appointment
    public function sendTestEmail(): void
    {
        $this->reportMsg = '';
        $this->reportMsgType = '';

        $appointment = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])
            ->latest()
            ->first();

        if (!$appointment) {
            $this->reportMsgType = 'warning';
            $this->reportMsg = 'No hay ninguna cita en la base de datos. Crea una primero.';
            return;
        }

        try {
            Mail::to($appointment->patient->user->email)
                ->send(new AppointmentConfirmation($appointment));

            $realNote = '';
            $realEmail = config('mail.admin_real_email');
            if ($realEmail && env('GMAIL_APP_PASSWORD')) {
                try {
                    Mail::mailer('gmail')
                        ->to($realEmail)
                        ->send(new AppointmentConfirmation($appointment));
                    $realNote = ' + Gmail a ' . $realEmail;
                } catch (\Throwable $e2) {
                    $realNote = ' (Gmail falló: ' . $e2->getMessage() . ')';
                }
            }

            $this->reportMsgType = 'success';
            $this->reportMsg = 'Correo de prueba enviado (cita #' . $appointment->id . '). Revisa Mailtrap.' . $realNote;
        } catch (\Throwable $e) {
            $this->reportMsgType = 'warning';
            $this->reportMsg = 'Error al enviar: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.admin.appointment-creator');
    }
}