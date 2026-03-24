<x-admin-layout
    title="Horario de {{ $doctor->user->name }} | Medify"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Doctores',
            'href' => route('admin.admin.doctors.index'),
        ],
        [
            'name' => 'Horario',
        ],
    ]"
>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('admin.schedule.store', $doctor) }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Horario: {{ $doctor->user->name }}</h2>
                        <p class="text-gray-500 text-sm mt-1">Configura la disponibilidad semanal profesional del doctor.</p>
                    </div>
                    <button type="submit" 
                            class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                        Guardar horario
                    </button>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs text-gray-400 uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">DÍA/HORA</th>
                                @foreach (['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'] as $day)
                                    <th class="px-4 py-3">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Generar bloques de horas (ej: 08:00, 09:00...) --}}
                            @for ($h = 8; $h <= 18; $h++)
                                @php $hourStr = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00:00'; @endphp
                                <tr>
                                    <td class="px-4 py-6 align-top">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500">
                                            <span class="font-bold text-gray-700">{{ $hourStr }}</span>
                                        </div>
                                    </td>
                                    
                                    @foreach (range(0, 6) as $dayIndex)
                                        @php
                                            $workDay = $work_days->firstWhere('day', $dayIndex);
                                            $activeSlots = $workDay ? $workDay->slots : [];
                                        @endphp
                                        <td class="px-4 py-4 align-top">
                                            {{-- Generar los 4 slots de 15 min de cada hora --}}
                                            <div class="space-y-3">
                                                <div class="flex items-center gap-2 group">
                                                    <input type="checkbox" disabled class="rounded text-gray-300">
                                                    <span class="text-[10px] font-bold text-gray-300">Todos</span>
                                                </div>
                                                
                                                @foreach (['00', '15', '30', '45'] as $min)
                                                    @php 
                                                        $slotTime = str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $min;
                                                        $timeRange = $slotTime . ' - ' . \Carbon\Carbon::parse($slotTime)->addMinutes(15)->format('H:i');
                                                        $isChecked = in_array($slotTime, $activeSlots ?? []);
                                                    @endphp
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input type="checkbox" 
                                                               name="slots[{{ $dayIndex }}][]" 
                                                               value="{{ $slotTime }}"
                                                               {{ $isChecked ? 'checked' : '' }}
                                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 transition-all">
                                                        <span class="text-xs text-gray-500 group-hover:text-indigo-600 transition-colors">{{ $timeRange }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Inputs ocultos para marcar días activos si hay algún slot seleccionado --}}
            @foreach (range(0, 6) as $dayIndex)
                <input type="hidden" name="active[]" value="{{ $dayIndex }}">
            @endforeach
        </form>
    </div>

</x-admin-layout>