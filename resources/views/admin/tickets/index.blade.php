<x-admin-layout
    title="Soporte | Medify"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Soporte',
        ],
    ]"
>

    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.admin.tickets.create') }}">
            <i class="fa-solid fa-plus"></i>
            Nuevo Ticket
        </x-wire-button>
    </x-slot>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Soporte</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">USUARIO</th>
                        <th scope="col" class="px-6 py-3">TÍTULO</th>
                        <th scope="col" class="px-6 py-3">ESTADO</th>
                        <th scope="col" class="px-6 py-3">FECHA</th>
                        <th scope="col" class="px-6 py-3">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $ticket->id }}</td>
                            <td class="px-6 py-4">{{ $ticket->user->name }}</td>
                            <td class="px-6 py-4">{{ $ticket->title }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->status === 'Abierto' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($ticket->status === 'Cerrado' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.admin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.admin.tickets.destroy', $ticket) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                                No hay tickets registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4">
            {{ $tickets->links() }}
        </div>
    </div>

</x-admin-layout>