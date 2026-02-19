<div class="flex items-center space-x-2">
    @if($user->id !== auth()->id())
        <x-button href="{{ route('admin.users.edit', $user) }}" primary xs>
            <i class="fa-solid fa-pen-to-square"></i>
        </x-button>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <x-button type="submit" negative xs>
                <i class="fa-solid fa-trash"></i>
            </x-button>
        </form>
    @endif
</div>
