<div class="flex items-center gap-2">
    {{-- Editar --}}
    <x-wire-button href="{{ route('admin.admin.appointments.edit', $appointment) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Consulta médica --}}
    <x-wire-button href="{{ route('admin.admin.appointments.consult', $appointment) }}" green xs>
        <i class="fa-solid fa-stethoscope"></i>
    </x-wire-button>

    {{-- Finalizar cita (solo si está programada) --}}
    @if($appointment->status === \App\Models\Appointment::STATUS_SCHEDULED)
        <form action="{{ route('admin.admin.appointments.complete', $appointment) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit"
                onclick="return confirm('¿Marcar esta cita como completada?')"
                class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-orange-500 hover:bg-orange-600 text-white transition">
                <i class="fa-solid fa-circle-check"></i>
            </button>
        </form>
    @endif

    {{-- Eliminar cita --}}
    <form action="{{ route('admin.admin.appointments.destroy', $appointment) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit"
            onclick="return confirm('¿Eliminar esta cita permanentemente?')"
            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-red-600 hover:bg-red-700 text-white transition">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
