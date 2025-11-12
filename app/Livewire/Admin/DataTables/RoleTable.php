<?php

namespace App\Livewire\Admin\DataTables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
// Si usas Spatie (recomendado):
use Spatie\Permission\Models\Role;
// Si a propósito tienes App\Models\Role, déjalo como lo tenías.

class RoleTable extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')->sortable(),
            Column::make('Nombre', 'name')->sortable()->searchable(),
            Column::make('Fecha de Creación', 'created_at')
                ->sortable()
                ->format(fn($v) => optional($v)->format('d/m/Y H:i')),
            Column::make('Acciones')
                ->label(fn($row) => view('admin.roles.actions', ['role' => $row])),
        ];
    }
}
