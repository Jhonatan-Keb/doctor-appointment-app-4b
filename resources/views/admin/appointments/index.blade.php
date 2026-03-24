<x-admin-layout title="Citas médicas | MediCitas" :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas'],
    ]">

    <div class="flex justify-end items-center gap-3 mb-4">
        {{-- Botón prueba: envía el correo de la última cita a Mailtrap + Gmail al instante --}}
        <form action="{{ route('admin.admin.appointments.send-test-email') }}" method="POST">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition shadow">
                <i class="fa-solid fa-paper-plane"></i>
                Enviar correo de prueba
            </button>
        </form>

        <x-wire-button href="{{ route('admin.admin.appointments.create') }}" primary>
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo
        </x-wire-button>
    </div>

    @livewire('admin.datatables.appointment-table')
</x-admin-layout>