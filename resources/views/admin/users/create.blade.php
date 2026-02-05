<x-admin-layout title="Usuarios | Dendro Medical" :breadcrumbs="[

[
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => 'Usuarios',
    'href' => route('admin.users.index'),
],
[
    'name' => 'Nuevo',
]

]">

<x-wire-card>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="space-y-4">

            <div class="grid grid-cols-2 gap-4">

            <x-wire-input name="name" label="Nombre" :value="old('name')"
            placeholder="Nombre" autocomplete="name" inputmode="name"/>
                
            <x-wire-input name="email" label="Email" :value="old('email')"
            placeholder="usuario@email.com" autocomplete="email" inputmode="email"/>

            <x-wire-input name="password" label="Contraseña" type="password" :value="old('password')"
            placeholder="Minimo 8 caracteres" autocomplete="new-password" inputmode="password"/>


            <x-wire-input name="password_confirmation" label="Confirmar Contraseña" type="password" :value="old('password_confirmation')"
            placeholder="Repita la contraseña" autocomplete="new-password" inputmode="password"/>

            <x-wire-input name="id_number" label="Numero de ID" :value="old('id_number')"
            placeholder="Ej. 123456789" autocomplete="off" inputmode="numeric"/>
            

            <x-wire-input name="phone" label="Telefono" :value="old('phone')"
            placeholder="Ej. 123456789" autocomplete="tel" inputmode="tel"/>

            </div>

            <x-wire-input name="address" label="Direccion" :value="old('address')"
            placeholder="Ej. Calle 123" autocomplete="street-address"/>

            <div class="space-y-1">
                <x-wire-native-select name="role_id" label="Rol"
                placeholder="Selecciona un rol">

                <option value="">
                    
                    Seleccione un rol</option>

                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                        {{ $role->name }}
                    </option>
                @endforeach

            </x-wire-native-select>

            <p class="text-sm text-gray-500">
                Define los permisos y el acceso del usuario
            </p>

            <div class="flex justify-end">
                <x-wire-button type="submit">
                    Guardar
                </x-wire-button>
            </div>
            </div>
            
        </div>
    </form>
</x-wire-card>

</x-admin-layout>