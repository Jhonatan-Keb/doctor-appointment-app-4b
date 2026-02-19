<x-admin-layout title="Usuarios | Simify" :breadcrumbs="[
        [
          'name' => 'Dashboard',
          'href' => route('admin.dashboard')
        ],
        [
          'name' => 'Usuarios'
        ],
    ]">
        <x-slot name="action">
            <x-button href="{{ route('admin.users.create') }}" primary>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>

        @livewire('admin.datatables.user-table')

</x-admin-layout>
