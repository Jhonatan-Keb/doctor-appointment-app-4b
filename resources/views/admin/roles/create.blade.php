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
          'name' => 'Nuevo'
        ],
    ]">
        
        <x-card>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
              <x-input label="Nombre" name="name" placeholder="Ingrese el nombre del rol" value="{{ old('name') }}">
                  
              </x-input>
              <div class="flex justify-end mt-4">
                <x-button type="submit" primary>Guardar</x-button>
              </div>
            </form>
        </x-card>

</x-admin-layout>