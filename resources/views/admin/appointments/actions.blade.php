<div class="flex items-center gap-2">
    <x-wire-button href="{{ route('admin.admin.appointments.edit', $appointment) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    <x-wire-button href="{{ route('admin.admin.appointments.consult', $appointment) }}" green xs>
        <i class="fa-solid fa-stethoscope"></i>
    </x-wire-button>
</div>