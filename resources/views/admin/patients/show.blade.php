<x-admin-layout
    title="Pacientes | Dendro Medical"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route ('admin.dashboard'),
        ],

        [
            'name' => 'Pacientes',
            'href' => route('admin.admin.patients.index')
        ],
        [
            'name' => 'Detalle'
        ]
    ]">

</x-admin-layout>
