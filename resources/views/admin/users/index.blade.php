<x-admin-layout
    title="Usuarios | Dendro Medical"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route ('admin.dashboard'),
        ],

        [
            'name' => 'Usuarios',
        ],
    ]">

    <x-slot name="action">
        <x-wire-button blue href="{{route('admin.admin.users.create')}}">
            <i class="fa-solid fa-plus"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    {{-- ¡HEMOS ELIMINADO LA LÍNEA @livewire DE AQUÍ! --}}
    @livewire('admin.data-tables.user-table')
    {{-- Ahora la página cargará sin errores y mostrará el fondo vacío. --}}

</x-admin-layout>
