cat > resources/views/admin/doctors/schedules.blade.php << 'BLADE'
<x-admin-layout title="Horarios | {{ $doctor->user->name }}" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.admin.doctors.index')],
    ['name' => $doctor->user->name],
    ['name' => 'Horarios'],
]">
    @livewire('admin.schedule-manager', ['doctorId' => $doctor->id])
</x-admin-layout>
BLADE