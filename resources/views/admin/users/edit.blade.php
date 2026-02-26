<x-admin-layout title="Usuarios | Dendro Medical" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.admin.users.index')],
    ['name' => 'Editar'],
]">
    <x-wire-card>
        {{-- Asegúrate que la ruta de update reciba el ID o el modelo --}}
        <form action="{{ route('admin.admin.users.update', $user) }}" method="post">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <x-wire-input name="name" label="Nombre"
                              :value="old('name', $user->name)"
                              placeholder="Nombre" autocomplete="name"/>

                <x-wire-input name="email" label="Email"
                              :value="old('email', $user->email)"
                              placeholder="usuario@email.com" autocomplete="email" inputmode="email"/>

                {{-- Contraseña se deja vacía por seguridad al editar --}}
                <x-wire-input name="password" label="Contraseña" type="password"
                              placeholder="Dejar en blanco para mantener la actual" autocomplete="new-password"/>

                <x-wire-input name="password_confirmation" label="Confirmar contraseña" type="password"
                              placeholder="Repita la contraseña" autocomplete="new-password"/>

                <x-wire-input name="id_number" label="Número de ID"
                              :value="old('id_number', $user->id_number)"
                              placeholder="Ej. 123456789"/>

                <x-wire-input name="phone" label="Teléfono"
                              :value="old('phone', $user->phone)"
                              placeholder="Ej. 123456789" inputmode="tel"/>

                <div class="col-span-1 md:col-span-2">
                    <x-wire-input name="address" label="Dirección"
                                  :value="old('address', $user->address)"
                                  placeholder="Ej. Calle 123"/>
                </div>

                <div class="col-span-1 md:col-span-2 space-y-1">
                    <x-wire-native-select name="role_id" label="Rol">
                        <option value="">Seleccione un rol</option>

                        @foreach ($roles as $role)


                            <option value="{{ $role->id }}"
                                @selected(old('role_id', $user->roles->first()?->id) == $role->id)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </x-wire-native-select>

                    <p class="text-sm text-gray-500">
                        Define los permisos y accesos del usuario
                    </p>
                </div>

            </div>

            <div class="flex justify-end mt-6">
                <x-wire-button type="submit" label="Actualizar" primary />
            </div>

        </form>
    </x-wire-card>
</x-admin-layout>
