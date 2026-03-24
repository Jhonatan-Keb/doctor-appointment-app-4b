<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentConfirmation;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index');
    }

    public function create()
    {
        return view('admin.appointments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration' => 'nullable|integer|min:5|max:120',
            'reason' => 'required|string|max:1000',
            'status' => 'nullable|integer|in:1,2,3',
        ]);

        // Calcular duración automáticamente
        $start = \Carbon\Carbon::createFromFormat('H:i', $data['start_time']);
        $end = \Carbon\Carbon::createFromFormat('H:i', $data['end_time']);
        $data['duration'] = $start->diffInMinutes($end);

        Appointment::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Cita creada!',
            'text' => 'La cita médica se ha registrado correctamente.',
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }

    public function edit(Appointment $appointment)
    {
        $appointment->load('patient.user', 'doctor.user', 'doctor.speciality');
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user', 'speciality')->get();
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration' => 'nullable|integer|min:5|max:120',
            'reason' => 'required|string|max:1000',
            'status' => 'nullable|integer|in:1,2,3',
        ]);

        $start = \Carbon\Carbon::createFromFormat('H:i', $data['start_time']);
        $end = \Carbon\Carbon::createFromFormat('H:i', $data['end_time']);
        $data['duration'] = $start->diffInMinutes($end);

        $appointment->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Cita actualizada!',
            'text' => 'La cita médica se ha actualizado correctamente.',
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }

    public function consult(Appointment $appointment)
    {
        $appointment->load('patient.user', 'doctor.user', 'doctor.speciality', 'consultation');
        return view('admin.appointments.consult', compact('appointment'));
    }

    public function sendTestEmail()
    {
        $appointment = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])
            ->latest()->first();

        if (!$appointment) {
            session()->flash('swal', [
                'icon' => 'warning',
                'title' => 'Sin citas',
                'text' => 'No hay ninguna cita registrada. Crea una primero.',
            ]);
            return redirect()->route('admin.admin.appointments.index');
        }

        $errors = [];

        // Mailtrap: paciente + doctor
        try {
            Mail::to($appointment->patient->user->email)
                ->cc($appointment->doctor->user->email)
                ->send(new AppointmentConfirmation($appointment));
        } catch (\Throwable $e) {
            $errors[] = 'Mailtrap: ' . $e->getMessage();
            \Log::warning('Test email Mailtrap falló: ' . $e->getMessage());
        }

        // Gmail real
        $realEmail = config('mail.admin_real_email');
        if ($realEmail && env('GMAIL_APP_PASSWORD')) {
            try {
                Mail::mailer('gmail')
                    ->to($realEmail)
                    ->send(new AppointmentConfirmation($appointment));
            } catch (\Throwable $e) {
                $errors[] = 'Gmail: ' . $e->getMessage();
                \Log::warning('Test email Gmail falló: ' . $e->getMessage());
            }
        }

        session()->flash('swal', empty($errors) ? [
            'icon' => 'success',
            'title' => '¡Correo de prueba enviado!',
            'text' => 'Enviado a Mailtrap y a ' . $realEmail . ' (cita #' . $appointment->id . ').',
        ] : [
            'icon' => 'warning',
            'title' => 'Envío parcial',
            'text' => implode(' | ', $errors),
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }

    public function complete(Appointment $appointment)
    {
        $appointment->update(['status' => Appointment::STATUS_COMPLETED]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Cita finalizada!',
            'text' => 'La cita ha sido marcada como completada.',
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Cita eliminada!',
            'text' => 'La cita médica se ha eliminado correctamente.',
        ]);

        return redirect()->route('admin.admin.appointments.index');
    }
}
