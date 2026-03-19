<x-admin-layout
    title="Detalle del Ticket | Medify"
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
            'name' => 'Ticket #' . $ticket->id,
        ],
    ]"
>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            {{-- Header del Ticket --}}
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $ticket->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Enviado por <span class="font-semibold text-gray-700">{{ $ticket->user->name }}</span> 
                        el {{ $ticket->created_at->format('d/m/Y \a \l\a\s H:i') }}
                    </p>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-sm font-bold
                        {{ $ticket->status === 'Abierto' ? 'bg-yellow-100 text-yellow-800' : 
                           ($ticket->status === 'Cerrado' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $ticket->status }}
                    </span>
                </div>
            </div>

            {{-- Contenido del Ticket --}}
            <div class="p-8">
                <div class="prose max-w-none text-gray-700">
                    <h3 class="text-lg font-semibold mb-3 text-gray-900">Descripción del problema:</h3>
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 italic">
                        {{ $ticket->description }}
                    </div>
                </div>

                {{-- Acciones de Administración --}}
                <div class="mt-10 pt-6 border-t border-gray-100 flex flex-wrap gap-4 items-center justify-between">
                    <div class="flex gap-3">
                        <form action="{{ route('admin.admin.tickets.update', $ticket) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @if($ticket->status === 'Abierto')
                                <input type="hidden" name="status" value="Cerrado">
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center gap-2">
                                    <i class="fa-solid fa-check-double"></i>
                                    Marcar como Resuelto
                                </button>
                            @else
                                <input type="hidden" name="status" value="Abierto">
                                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition-colors flex items-center gap-2">
                                    <i class="fa-solid fa-envelope-open"></i>
                                    Reabrir Ticket
                                </button>
                            @endif
                        </form>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('admin.admin.tickets.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                            Volver al listado
                        </a>
                        <form action="{{ route('admin.admin.tickets.destroy', $ticket) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-6 py-2 bg-red-100 text-red-600 rounded-lg font-semibold hover:bg-red-200 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-trash"></i>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>