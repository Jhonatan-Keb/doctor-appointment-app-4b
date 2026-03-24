<x-admin-layout
    title="Detalle del Paciente | Dendro Medical"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
        ['name' => $patient->user->name],
    ]"
>

    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.patients.edit', $patient) }}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </x-wire-button>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- Tarjeta de Perfil --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row items-center md:items-start gap-8">
            <div class="flex-shrink-0">
                <div class="w-32 h-32 rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-4xl font-bold shadow-sm border border-indigo-100">
                    {{ strtoupper(substr($patient->user->name, 0, 1)) }}
                </div>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $patient->user->name }}</h1>
                <p class="text-gray-500 font-medium mb-4"><i class="fa-solid fa-envelope mr-2"></i> {{ $patient->user->email }}</p>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="px-4 py-2 bg-gray-50 text-gray-700 rounded-xl text-sm font-semibold border border-gray-100">
                        <i class="fa-solid fa-id-card text-gray-400 mr-2"></i> DNI: {{ $patient->user->id_number ?? 'N/A' }}
                    </span>
                    <span class="px-4 py-2 bg-gray-50 text-gray-700 rounded-xl text-sm font-semibold border border-gray-100">
                        <i class="fa-solid fa-phone text-gray-400 mr-2"></i> {{ $patient->user->phone ?? 'N/A' }}
                    </span>
                    @if($patient->user->birth_date)
                        <span class="px-4 py-2 bg-green-50 text-green-700 rounded-xl text-sm font-semibold border border-green-100">
                            <i class="fa-solid fa-cake-candles text-green-500 mr-2"></i> {{ \Carbon\Carbon::parse($patient->user->birth_date)->age }} años
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Información Médica e Historial --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Historia Clínica Básica --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-notes-medical text-indigo-500 mr-2"></i> Historia Médica</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tipo de Sangre</p>
                        <div class="inline-flex px-4 py-2 bg-red-50 text-red-700 rounded-xl font-bold border border-red-100">
                            {{ $patient->bloodType->name ?? 'No especificado' }}
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alergias</p>
                        <p class="text-sm text-gray-800 font-medium p-4 bg-yellow-50/50 rounded-xl border border-yellow-100/50">
                            {{ $patient->allergies ?? 'Sin información de alergias registrada.' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Condiciones Crónicas</p>
                        <p class="text-sm text-gray-800 font-medium p-4 bg-blue-50/50 rounded-xl border border-blue-100/50">
                            {{ $patient->chronic_conditions ?? 'Sin enfermedades crónicas registradas.' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Historial Quirúrgico</p>
                        <p class="text-sm text-gray-800 font-medium p-4 bg-gray-50 rounded-xl border border-gray-100">
                            {{ $patient->surgical_history ?? 'Sin antecedentes quirúrgicos.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Información Adicional y Citas Recientes --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-address-book text-indigo-500 mr-2"></i> Datos Demográficos</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Género</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1 capitalize">{{ $patient->user->gender ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nacimiento</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1">{{ $patient->user->birth_date ? \Carbon\Carbon::parse($patient->user->birth_date)->format('d/m/Y') : 'No especificado' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dirección Completa</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1">{{ $patient->user->address ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Accesos rápidos --}}
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-sm p-8 text-white">
                    <h3 class="text-xl font-bold mb-2">Acciones Rápidas</h3>
                    <p class="text-indigo-100 text-sm mb-6">Gestiona la atención médica de este paciente de forma ágil y rápida.</p>
                    
                    <div class="flex gap-4">
                        <a href="{{ route('admin.admin.appointments.create') }}" class="px-6 py-3 bg-white text-indigo-600 text-sm font-bold rounded-xl hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 inline-block text-center">
                            <i class="fa-solid fa-calendar-plus mr-2"></i> Agendar Cita
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
