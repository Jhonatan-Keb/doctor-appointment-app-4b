<x-admin-layout
    title="Pacientes | Dendro Medical"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route ('admin.dashboard'),
        ],

        [
            'name' => 'Pacientes',

        ],
    ]">

    <div class="flex justify-end mb-4 mt-4">
        <x-wire-button href="{{ route('admin.admin.patients.import-form') }}" teal>
            <i class="fa-solid fa-file-arrow-up mr-2"></i>
            Importación Masiva
        </x-wire-button>
    </div>

    @livewire('admin.data-tables.patient-table')
</x-admin-layout>
