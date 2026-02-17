<x-admin-layout title="Usuarios | Simify" :breadcrumbs="[
        [
          'name' => 'Dashboard',
          'href' => route('admin.dashboard')
        ],
        [
          'name' => 'Usuarios',
          'href' => route('admin.users.index')
        ],
        [
          'name' => 'Nuevo'
        ],
    ]">

    <x-wire-card>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- Mostrar errores de validación --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                    <strong class="text-red-700">Se encontraron errores:</strong>
                    <ul class="mt-2 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mensajes de sesión --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- NOMBRE -->
                <x-wire-input
                    name="name"
                    label="Nombre"
                    placeholder="Nombre del usuario"
                    required
                    :value="old('name')"
                    autocomplete="name"
                />

                <!-- EMAIL -->
                <x-wire-input
                    name="email"
                    label="Email"
                    placeholder="usuario@ejemplo.com"
                    required
                    :value="old('email')"
                    autocomplete="email"
                    inputmode="email"
                />

                <!-- IDENTIFICACIÓN -->
                <x-wire-input
                    name="id_numero"
                    label="Número de identificación"
                    placeholder="Ej. 12345678"
                    required
                    :value="old('id_numero')"
                />

                <!-- TELÉFONO -->
                <x-wire-input
                    name="phone"
                    label="Teléfono"
                    placeholder="Ej. 9991234567"
                    required
                    :value="old('phone')"
                    inputmode="numeric"
                    autocomplete="tel"
                />

                <!-- DIRECCIÓN (ancho completo) -->
                <div class="md:col-span-2">
                    <x-wire-input
                        name="address"
                        label="Dirección"
                        placeholder="Dirección del usuario"
                        required
                        :value="old('address')"
                    />
                </div>

                <!-- PASSWORD -->
                <x-wire-input
                    name="password"
                    label="Contraseña"
                    type="password"
                    placeholder="Contraseña"
                    required
                    autocomplete="new-password"
                />

                <!-- CONFIRM PASSWORD -->
                <x-wire-input
                    name="password_confirmation"
                    label="Confirmar Contraseña"
                    type="password"
                    placeholder="Confirmar contraseña"
                    required
                    autocomplete="new-password"
                />

                <!-- ROL (ancho completo) -->
                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Rol
                    </label>
                    <select
                        name="role"
                        id="role"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                               focus:border-indigo-500 dark:focus:border-indigo-600
                               focus:ring-indigo-500 dark:focus:ring-indigo-600
                               rounded-md shadow-sm"
                        required
                    >
                        <option value="">Seleccionar rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" black>Guardar</x-wire-button>
            </div>

        </form>
    </x-wire-card>

</x-admin-layout>
