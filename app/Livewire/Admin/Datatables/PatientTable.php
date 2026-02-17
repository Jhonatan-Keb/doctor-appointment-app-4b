<?php

namespace App\Livewire\Admin\DataTables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;

class PatientTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return Patient::query()->with(['user', 'bloodType']);
    }

    protected $model = Patient::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->searchable(),
            Column::make("Nombre", "user.name")
                ->sortable()
                ->searchable(),
            Column::make("Email", "user.email")
                ->sortable()
                ->searchable(),
            Column::make("Teléfono", "user.phone")
                ->sortable()
                ->searchable(),

            Column::make("Tipo Sangre", "bloodType.name")
                ->sortable(),
            Column::make("Acciones")
                ->label(function($row){
                    return view('admin.patients.actions',
                        ['patient' => $row]);
                }),
        ];
    }
}