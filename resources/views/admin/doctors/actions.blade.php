<div class="flex items-center gap-2">
    {{-- Editar doctor --}}
    <x-wire-button href="{{ route('admin.admin.doctors.edit', $doctor) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Horarios del doctor --}}
    <x-wire-button href="{{ route('admin.admin.doctors.schedules', $doctor) }}" violet xs>
        <i class="fa-solid fa-clock"></i>
    </x-wire-button>
</div>
