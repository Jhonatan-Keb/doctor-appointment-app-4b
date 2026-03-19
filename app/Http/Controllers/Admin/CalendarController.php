<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }

    /**
     * Retorna eventos en formato JSON para FullCalendar.
     */
    public function events(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereBetween('date', [$start, $end])
            ->where('status', '!=', 'Cancelado')
            ->get();

        $events = $appointments->map(function ($appointment) {
            // Colores por estado
            $color = '#3b82f6'; // Azul por defecto (Programado)
            if ($appointment->status === 'Completado') $color = '#10b981'; // Verde

            return [
                'id' => $appointment->id,
                'title' => $appointment->patient->user->name,
                'start' => $appointment->date . 'T' . $appointment->time,
                'end' => $appointment->date . 'T' . ($appointment->end_time ?? $appointment->time),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'doctor' => $appointment->doctor->user->name,
                    'status' => $appointment->status,
                    'patient' => $appointment->patient->user->name,
                ]
            ];
        });

        return response()->json($events);
    }
}