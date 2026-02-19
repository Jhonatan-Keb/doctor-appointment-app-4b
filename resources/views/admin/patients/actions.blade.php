<div class="flex items-center space-x-2">
    @if($role->id > 4)
        <x-button href="{{ route('admin.roles.edit', $role) }}" primary xs>
            <i class="fa-solid fa-pen-to-square"></i>
        </x-button>

        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <x-button type="submit" negative xs>
                <i class="fa-solid fa-trash"></i>
            </x-button>
        </form>
    @endif
</div>
