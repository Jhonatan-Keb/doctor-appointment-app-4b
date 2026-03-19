<x-admin-layout
    title="Detalles del Rol | Dendro Medical"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Roles',
            'href' => route('admin.roles.index'),
        ],
        [
            'name' => $role->name,
        ],
    ]">

    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.roles.edit', $role) }}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </x-wire-button>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Información del Rol</h2>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Rol</label>
                <div class="p-4 bg-gray-50 rounded-xl text-gray-900 font-semibold border border-gray-100">
                    {{ $role->name }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Permisos Asignados</label>
                
                @if($role->permissions->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                        @foreach($role->permissions as $permission)
                            <div class="p-3 bg-blue-50 text-blue-700 rounded-lg border border-blue-100 font-medium text-sm flex items-center">
                                <i class="fa-solid fa-check-circle text-blue-500 mr-2"></i>
                                {{ $permission->name }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 bg-yellow-50 text-yellow-700 border border-yellow-100 rounded-xl text-center">
                        <i class="fa-solid fa-triangle-exclamation text-2xl mb-2 text-yellow-500 block"></i>
                        <p class="font-medium">Este rol no tiene permisos asignados.</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <a href="{{ route('admin.roles.index') }}" class="px-6 py-2.5 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-colors">
                Volver
            </a>
        </div>
    </div>

</x-admin-layout>
