<x-admin-layout
    title="Usuarios | Dendro Medical"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Usuarios',
            'href' => route('admin.admin.users.index'),
        ],
        [
            'name' =>'Nuevo',
        ],
    ]">
    <x-wire-card>
        <form action="{{ route('admin.admin.users.store') }}" method="post">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Se eliminó 'required' --}}
                <x-wire-input name="name" label="Nombre" :value="old('name')"
                              placeholder="Nombre" autocomplete="name"/>

                {{-- Se eliminó 'required' --}}
                <x-wire-input name="email" label="Email" :value="old('email')"
                              placeholder="usuario@email.com" autocomplete="email" inputmode="email"/>

                {{-- Se eliminó 'required' --}}
                <x-wire-input name="password" label="Contraseña" type="password"
                              placeholder="Mínimo 8 caracteres" autocomplete="new-password"/>

                {{-- Se eliminó 'required' --}}
                <x-wire-input name="password_confirmation" label="Confirmar contraseña" type="password"
                              placeholder="Repita la contraseña" autocomplete="new-password"/>

                <x-wire-input name="id_number" label="Número de ID" :value="old('id_number')"
                              placeholder="Ej. 123456789"/>

                <x-wire-input name="phone" label="Teléfono" :value="old('phone')"
                              placeholder="Ej. 123456789" inputmode="tel"/>

                <div class="col-span-1 md:col-span-2">
                    <x-wire-input name="address" label="Dirección" :value="old('address')"
                                  placeholder="Ej. Calle 123"/>
                </div>

                <div class="col-span-1 md:col-span-2 space-y-1">
                    {{-- Se eliminó 'required' --}}
                    <x-wire-native-select name="role_id" label="Rol">
                        <option value="">Seleccione un rol</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
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
                <x-wire-button type="submit" label="Guardar" primary />
            </div>

        </form>
    </x-wire-card>
</x-admin-layout>
