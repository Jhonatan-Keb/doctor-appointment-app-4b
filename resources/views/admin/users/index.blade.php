<x-admin-layout
    title="Usuarios | HouseMD"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Usuarios',
        ],
    ]"
>

    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.users.create') }}">
            <i class="fa-solid fa-plus"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    <div class="bg-white rounded-lg shadow p-6">
        @livewire('admin.data-tables.user-table')
    </div>

</x-admin-layout>
