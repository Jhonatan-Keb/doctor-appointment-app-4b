<!-- resources/views/admin/roles/create.blade.php -->
<x-admin-layout
    title="Pacientes | Dendro Medical"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Pacientes',
            'href' => route('admin.admin.patients.create.index'),
        ],
        [
            'name' => 'Crear'
        ]
    ]">

</x-admin-layout>
