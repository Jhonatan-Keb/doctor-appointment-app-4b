<x-admin-layout title="Doctores | Dendro Medical" :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores'],
    ]">
    @livewire('admin.data-tables.doctor-table')
</x-admin-layout>