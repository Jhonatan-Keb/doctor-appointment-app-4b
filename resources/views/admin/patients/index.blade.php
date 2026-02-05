<x-admin-layout title="Pacientes | Dendro Medical" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Pacientes',
        ],
    ]"
>

    @livewire('admin.data-tables.patient-table')

</x-admin-layout>