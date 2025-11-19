@php
    // Protegidos por ID (1..4)
    $isProtected = (int) $user->id <= 4;
@endphp

<div class="flex items-center space-x-2">
    {{-- Editar --}}
    @if($isProtected)
        <button type="button"
            onclick="Swal.fire({
                icon:'error',
                title:'Acción no permitida',
                text:'Este usuario no puede modificarse.'
            })"
            class="inline-flex items-center px-2.5 py-1.5 bg-blue-400 text-white rounded cursor-not-allowed"
            title="Usuario protegido">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    @else
        <x-wire-button href="{{ route('admin.users.edit', $user) }}" blue xs title="Editar">
            <i class="fa-solid fa-pen-to-square"></i>
        </x-wire-button>
    @endif

    {{-- Eliminar --}}
    @if($isProtected)
        <button type="button"
            onclick="Swal.fire({
                icon:'error',
                title:'Acción no permitida',
                text:'Este usuario no se puede eliminar.'
            })"
            class="inline-flex items-center px-2.5 py-1.5 bg-red-400 text-white rounded cursor-not-allowed"
            title="Usuario protegido">
            <i class="fa-solid fa-trash"></i>
        </button>
    @else
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form inline">
            @csrf
            @method('DELETE')
            <x-wire-button type="submit" red xs title="Eliminar">
                <i class="fa-solid fa-trash"></i>
            </x-wire-button>
        </form>
    @endif
</div>
