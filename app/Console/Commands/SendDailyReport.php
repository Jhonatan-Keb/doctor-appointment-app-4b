<?php

namespace App\Console\Commands;

use App\Mail\DailyAppointmentReport;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Envía el reporte de citas del día al administrador y doctores';

    public function handle()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user', 'doctor.speciality'])
            ->where('date', today()->toDateString())
            ->where('status', Appointment::STATUS_SCHEDULED)
            ->orderBy('start_time')
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas para hoy.');
            return;
        }

        // Enviar al administrador
        $admins = User::role('Administrador')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)
                ->send(new DailyAppointmentReport($appointments, $admin->name));
        }

        // Enviar a cada doctor sus citas del día
        $byDoctor = $appointments->groupBy('doctor_id');
        foreach ($byDoctor as $doctorId => $doctorAppointments) {
            $doctor = $doctorAppointments->first()->doctor->user;
            Mail::to($doctor->email)
                ->send(new DailyAppointmentReport($doctorAppointments, $doctor->name));
        }

        $this->info('Reporte enviado correctamente a ' . $admins->count() . ' admin(s) y ' . $byDoctor->count() . ' doctor(es).');
    }
}