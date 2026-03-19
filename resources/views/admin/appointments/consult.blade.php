<x-admin-layout title="Consulta | MediCitas" :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.admin.appointments.index')],
        ['name' => 'Consulta'],
    ]">

    @livewire('admin.consultation-manager', ['appointmentId' => $appointment->id])
</x-admin-layout>