<div class="flex items-center space-x-2">
    {{-- Editar --}}
    <x-wire-button href="{{ route('admin.users.edit', $user) }}" blue xs title="Editar">
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    {{-- Eliminar --}}
    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form inline">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red xs title="Eliminar">
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>
