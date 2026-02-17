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
          'name' => 'Editar'
        ],
    ]">

    <x-wire-card>
      



      
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
<div class=" grid grid-cols-1 gap-4 justify-between mb-6">
          @csrf

          @method('PUT')
    <div>
      <h1>Usuario</h1>
    </div>
                    <div class="w-1/2">
                    <x-input
                        label="Nombre"
                        name="name"
                        placeholder="Nombre del usuario"
                        value="{{ old('name', $user->name) }}"
                    />
                </div>

    <div>
      <h1>Correo</h1>
    </div>
              <div class="w-1/2">
                <x-input
                label="Email" name="email" type="email" placeholder="usuario@ejemplo.com" value="{{ old('email', $user->email) }}">
              />
              </div>
              
    <div>
      <h1>Contraseña</h1>
    </div>
              <x-input

                label="Contraseña (opcional)" name="password" type="password" placeholder="Nueva contraseña">

              </x-input>

              <x-input
         
            label="Confirmar Contraseña" name="password_confirmation" type="password" placeholder="Confirmar nueva contraseña">

          </x-input>

          <div class="mb-5 mt-4 ">
            <label for="role" class=" w-50% block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
            <select name="role" id="role" class="mt-1 block w-*50% border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
              <option value="">Seleccionar rol</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ (old('role') ?? $user->roles->first()?->id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
              @endforeach
            </select>
          </div>

            <div class="flex justify-end mt-4">

              <x-wire-button type='submit' black>Actualizar</x-wire-button>

            </div>
          </div>
        </form>

    </x-wire-card>

</x-admin-layout>