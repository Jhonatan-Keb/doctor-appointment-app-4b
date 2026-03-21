<x-admin-layout title="Nueva Cita | MediCitas" :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.admin.appointments.index')],
        ['name' => 'Nuevo'],
    ]">
    @livewire(\App\Livewire\Admin\AppointmentCreator::class)
</x-admin-layout>
