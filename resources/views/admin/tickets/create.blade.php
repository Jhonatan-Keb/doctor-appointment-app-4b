<x-admin-layout
    title="Nuevo Ticket | Medify"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Soporte',
            'href' => route('admin.admin.tickets.index'),
        ],
        [
            'name' => 'Nuevo Ticket',
        ],
    ]"
>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <div class="p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Reportar un problema</h2>
                    <p class="text-gray-500 mt-1">Describe tu problema o duda y nuestro equipo de soporte se pondrá en contacto contigo.</p>
                </div>

                <form action="{{ route('admin.admin.tickets.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Titulo del problema</label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                            placeholder="Breve descripción del problema">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Descripción detallada</label>
                        <textarea name="description" id="description" rows="5" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none resize-none"
                            placeholder="Explica detalladamente qué está sucediendo..."></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{ route('admin.admin.tickets.index') }}" 
                           class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-8 py-2.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all">
                            Enviar Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>