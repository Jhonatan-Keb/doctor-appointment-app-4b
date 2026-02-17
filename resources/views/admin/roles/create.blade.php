<x-admin-layout
    title="Roles | Medify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Roles', 'href' => route('admin.roles.index')],
        ['name' => 'Nuevo'],
    ]"
>
    <x-slot name="action">
        <x-wire-button href="{{ route('admin.roles.index') }}" gray>
            Cancelar
        </x-wire-button>
        <x-wire-button form="create-role-form" type="submit" blue class="ml-2">
            <i class="fa-solid fa-floppy-disk"></i>
            Guardar
        </x-wire-button>
    </x-slot>

    {{-- ÚNICO formulario (el de la tarjeta). Se eliminó el grupo superior duplicado --}}
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form id="create-role-form" method="POST" action="{{ route('admin.roles.store') }}">
                @csrf

                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Nombre del rol
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    placeholder="Ingrese el nombre del rol"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                    autofocus
                    required
                />

                @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </form>
        </div>
    </div>
</x-admin-layout>