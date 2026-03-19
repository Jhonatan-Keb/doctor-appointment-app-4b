<?php

namespace App\Livewire\Admin\Datatables;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Appointment::query()->with('patient.user', 'doctor.user');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")->sortable(),
            Column::make("Paciente", "patient.user.name")->sortable(),
            Column::make("Doctor", "doctor.user.name")->sortable(),
            Column::make("Fecha", "date")->sortable()
                ->format(fn($value) => $value ? $value->format('d/m/Y') : ''),
            Column::make("Hora", "start_time")->sortable(),
            Column::make("Hora Fin", "end_time")->sortable(),
            Column::make("Estado", "status")
                ->sortable()
                ->format(function ($value) {
                    $status = (int) $value;
                    $labels = [
                        1 => 'Programado',
                        2 => 'Completado',
                        3 => 'Cancelado',
                    ];
                    $colors = [
                        1 => 'bg-blue-100 text-blue-800',
                        2 => 'bg-green-100 text-green-800',
                        3 => 'bg-red-100 text-red-800',
                    ];
                    $label = $labels[$status] ?? 'Desconocido';
                    $color = $colors[$status] ?? 'bg-gray-100 text-gray-800';
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $color . '">' . $label . '</span>';
                })
                ->html(),
            Column::make("Acciones")
                ->label(fn($row) => view('admin.appointments.actions', ['appointment' => $row])),
        ];
    }
}
