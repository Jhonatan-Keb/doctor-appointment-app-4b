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
        
        <x-card>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <x-input label="Nombre" name="name" placeholder="Ingrese el nombre del usuario" value="{{ old('name') }}" />
                </div>

                <div class="mb-4">
                    <x-input label="Email" name="email" type="email" placeholder="Ingrese el email del usuario" value="{{ old('email') }}" />
                </div>

                <div class="mb-4">
                    <x-input label="Contraseña" name="password" type="password" placeholder="Ingrese la contraseña" />
                </div>

                <div class="mb-4">
                    <x-input label="Confirmar Contraseña" name="password_confirmation" type="password" placeholder="Confirme la contraseña" />
                </div>

                <div class="mb-4">
                    <x-input label="Número de Identificación" name="id_number" placeholder="Ingrese el número de identificación" value="{{ old('id_number') }}" />
                </div>

                <div class="mb-4">
                    <x-input label="Teléfono" name="phone" placeholder="Ingrese el teléfono" value="{{ old('phone') }}" />
                </div>

                <div class="mb-4">
                    <x-input label="Dirección" name="address" placeholder="Ingrese la dirección" value="{{ old('address') }}" />
                </div>

                <div class="mb-4">
                    <x-select 
                        label="Rol" 
                        name="role_id" 
                        placeholder="Seleccione un rol"
                        :options="$roles"
                        option-label="name"
                        option-value="id"
                    />
                </div>

                <div class="flex justify-end mt-4">
                    <x-button type="submit" primary>Guardar</x-button>
                </div>
            </form>
        </x-card>

</x-admin-layout>
