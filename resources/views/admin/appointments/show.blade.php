<x-admin-layout
    title="Detalles de Cita | Medify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.admin.appointments.index')],
        ['name' => 'Detalle de Cita #' . $appointment->id],
    ]"
>

    <x-slot name="action">
        <div class="flex gap-2">
            @if($appointment->status !== 'Cancelado' && $appointment->status !== 'Completado')
                <x-wire-button blue href="{{ route('admin.admin.appointments.edit', $appointment) }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Editar
                </x-wire-button>
            @endif
            @if($appointment->status === 'Completado' || $appointment->consultation)
                <x-wire-button indigo href="{{ route('admin.admin.appointments.consult', $appointment) }}">
                    <i class="fa-solid fa-stethoscope"></i>
                    Ir a Consulta
                </x-wire-button>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- Banner Principal de Estado --}}
        @php
            $statusColors = [
                'Programado' => 'bg-blue-50 border-blue-200 text-blue-700',
                'Completado' => 'bg-green-50 border-green-200 text-green-700',
                'Cancelado' => 'bg-red-50 border-red-200 text-red-700',
            ];
            $bgColor = $statusColors[$appointment->status] ?? 'bg-gray-50 border-gray-200 text-gray-700';
            
            $statusIcons = [
                'Programado' => 'fa-calendar-check',
                'Completado' => 'fa-clipboard-check',
                'Cancelado' => 'fa-ban',
            ];
            $icon = $statusIcons[$appointment->status] ?? 'fa-circle-info';
        @endphp

        <div class="rounded-2xl border-2 {{ $bgColor }} p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold">Estado de la Cita: {{ $appointment->status }}</h2>
                    <p class="text-sm font-medium opacity-80">Creada el {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="text-center sm:text-right">
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-70">Fecha Programada</p>
                <p class="text-2xl font-black tracking-tight">{{ \Carbon\Carbon::parse($appointment->date)->format('d \d\e M, Y') }}</p>
                <p class="text-sm font-bold">{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }} - {{ $appointment->end_time ? \Carbon\Carbon::parse($appointment->end_time)->format('H:i') : '--:--' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Columna Izquierda: Actores (Paciente y Doctor) --}}
            <div class="space-y-6 lg:col-span-1">
                
                {{-- Tarjeta Paciente --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bed-pulse"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-4">Información del Paciente</p>
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl border border-indigo-100">
                                {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $appointment->patient->user->name }}</h3>
                                <p class="text-sm text-gray-500 font-medium">DNI: {{ $appointment->patient->user->id_number ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fa-solid fa-phone w-6 text-gray-400"></i>
                                {{ $appointment->patient->user->phone ?? 'Sin teléfono' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fa-solid fa-envelope w-6 text-gray-400"></i>
                                {{ $appointment->patient->user->email }}
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('admin.admin.patients.show', $appointment->patient) }}" class="block text-center w-full py-2 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-xl hover:bg-indigo-100 transition-colors">
                                Ver Perfil Completo
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Doctor --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-doctor"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-4">Médico Tratante</p>
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl border border-blue-100">
                                {{ strtoupper(substr($appointment->doctor->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $appointment->doctor->user->name }}</h3>
                                <p class="text-sm text-blue-500 font-bold">{{ $appointment->doctor->speciality->name ?? 'General' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.admin.doctors.show', $appointment->doctor) }}" class="block text-center w-full py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-xl hover:bg-blue-100 transition-colors">
                                Ver Perfil del Doctor
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Columna Derecha: Motivo y Consulta --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Motivo Original --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-comment-medical text-indigo-500 mr-2"></i> Motivo de la Consulta</h3>
                    </div>
                    <div class="p-6">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-gray-700 font-medium italic">
                            "{{ $appointment->reason ?? 'Sin motivo especificado al momento de agendar.' }}"
                        </div>
                    </div>
                </div>

                {{-- Detalles de la Consulta Médica (solo si existe) --}}
                @if($appointment->consultation)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden border-t-4 border-t-indigo-500">
                        <div class="p-6 border-b border-gray-100 bg-indigo-50/30 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-indigo-900"><i class="fa-solid fa-file-medical text-indigo-500 mr-2"></i> Registro Clínico</h3>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-200">
                                Completado
                            </span>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            
                            {{-- Diagnóstico --}}
                            <div>
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-microscope text-indigo-400"></i> Diagnóstico
                                </h4>
                                <p class="text-gray-800 text-sm bg-white p-4 rounded-xl border border-gray-100 shadow-sm">{{ $appointment->consultation->diagnosis }}</p>
                            </div>

                            {{-- Tratamiento --}}
                            <div>
                                <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-hand-holding-medical text-indigo-400"></i> Plan de Tratamiento
                                </h4>
                                <p class="text-gray-800 text-sm bg-white p-4 rounded-xl border border-gray-100 shadow-sm">{{ $appointment->consultation->treatment }}</p>
                            </div>

                            {{-- Notas --}}
                            @if($appointment->consultation->notes)
                                <div>
                                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                        <i class="fa-solid fa-note-sticky text-indigo-400"></i> Notas Adicionales
                                    </h4>
                                    <p class="text-gray-600 text-sm italic bg-yellow-50/50 p-4 rounded-xl border border-yellow-100/50">{{ $appointment->consultation->notes }}</p>
                                </div>
                            @endif

                            {{-- Receta Médica --}}
                            @if($appointment->consultation->prescriptionItems && $appointment->consultation->prescriptionItems->count() > 0)
                                <div class="mt-8 pt-6 border-t border-gray-100">
                                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-prescription-bottle-medical text-indigo-400"></i> Receta Emitida
                                    </h4>
                                    
                                    <div class="overflow-x-auto border border-gray-100 rounded-xl">
                                        <table class="w-full text-left text-sm">
                                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px]">
                                                <tr>
                                                    <th class="px-4 py-3">Medicamento</th>
                                                    <th class="px-4 py-3">Dosis</th>
                                                    <th class="px-4 py-3">Frecuencia / Duración</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach($appointment->consultation->prescriptionItems as $item)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 font-bold text-gray-800">{{ $item->medicine_name }}</td>
                                                        <td class="px-4 py-3 text-gray-600">{{ $item->dosage }}</td>
                                                        <td class="px-4 py-3 text-gray-600">{{ $item->frequency_duration }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                @else
                    {{-- Si no hay consulta médica --}}
                    @if($appointment->status === 'Programado')
                       <div class="bg-indigo-50/50 rounded-2xl border border-indigo-100 p-8 text-center">
                           <div class="w-16 h-16 mx-auto bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                               <i class="fa-solid fa-stethoscope text-2xl text-indigo-400"></i>
                           </div>
                           <h3 class="text-lg font-bold text-indigo-900 mb-2">Consulta Pendiente</h3>
                           <p class="text-sm text-indigo-600/80 mb-6">El paciente aún no ha sido evaluado médicamente en esta cita.</p>
                           <a href="{{ route('admin.admin.appointments.consult', $appointment) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all hover:-translate-y-0.5">
                               Iniciar Consulta Médica <i class="fa-solid fa-arrow-right"></i>
                           </a>
                       </div>
                    @else
                        <div class="bg-gray-50 rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-4 text-gray-300"></i>
                            <p class="font-medium">No se registraron datos de consulta para esta cita.</p>
                        </div>
                    @endif
                @endif

            </div>

        </div>
    </div>
</x-admin-layout>
