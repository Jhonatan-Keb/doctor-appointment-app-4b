<x-admin-layout title="Roles | Simify" :breadcrumbs="[
        [
          'name' => 'Dashboard',
          'href' => route('admin.dashboard')
        ],
        [
          'name' => 'Roles',
          'href' => route('admin.roles.index')
        ],
        [
          'name' => 'Editar'
        ],
    ]">
 <x-card>
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
              <x-input label="Nombre" name="name" placeholder="Ingrese el nombre del rol" value="{{ old('name', $role->name) }}">

              </x-input>
              <div class="flex justify-end mt-4">
                <x-button type="submit" primary>Guardar</x-button>
              </div>
            </form>
        </x-card>


</x-admin-layout>
