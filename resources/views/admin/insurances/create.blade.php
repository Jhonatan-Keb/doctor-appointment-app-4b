<x-admin-layout
    title="Registrar Aseguradora"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Aseguradoras',
            'href' => route('admin.admin.insurances.index'),
        ],
        [
            'name' => 'Nueva Aseguradora',
        ],
    ]">

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-6">Completa el siguiente formulario para registrar una nueva aseguradora en el sistema.</p>

            {{-- Form to create a new insurance record --}}
            <form action="{{ route('admin.admin.insurances.store') }}" method="POST">
                @csrf
                
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="nombre_empresa" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de la empresa <span class="text-red-500">*</span></label>
                        <input type="text" id="nombre_empresa" name="nombre_empresa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ej. Seguros Monterrey" value="{{ old('nombre_empresa') }}" required>
                        @error('nombre_empresa')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="telefono_contacto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono de contacto <span class="text-red-500">*</span></label>
                        <input type="tel" id="telefono_contacto" name="telefono_contacto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ej. 55 1234 5678" value="{{ old('telefono_contacto') }}" required>
                        @error('telefono_contacto')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="notas_adicionales" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción detallada / Notas (Opcional)</label>
                    <textarea id="notas_adicionales" name="notas_adicionales" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Añade cualquier detalle relevante sobre esta aseguradora...">{{ old('notas_adicionales') }}</textarea>
                    @error('notas_adicionales')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 mt-6 pt-5 border-t border-gray-100">
                    <a href="{{ route('admin.admin.insurances.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        Cancelar
                    </a>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Guardar Aseguradora
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
