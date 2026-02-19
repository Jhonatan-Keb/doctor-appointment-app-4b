<x-admin-layout title="Roles | Simify" :breadcrumbs="[
        [
          'name' => 'Dashboard', 
          'href' => route('admin.dashboard')
        ],
        [
          'name' => 'Roles'
        ],
    ]">
        <x-slot name="action">
            <x-button href="{{ route('admin.roles.create') }}" primary>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>

        @livewire('admin.datatables.role-table')

</x-admin-layout>