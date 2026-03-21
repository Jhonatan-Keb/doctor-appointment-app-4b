cat > resources/views/livewire/admin/schedule-manager.blade.php << 'BLADE'
<div class="space-y-6">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                    <i class="fa-solid fa-calendar-days mr-2 text-blue-500"></i>
                    Horario semanal — {{ $doctor->user->name }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Selecciona los bloques de 15 minutos disponibles para cada día
                </p>
            </div>
            <button wire:click="saveSchedules" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-60">
                <span wire:loading.remove wire:target="saveSchedules">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar horario
                </span>
                <span wire:loading wire:target="saveSchedules">
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Guardando...
                </span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr>
                        <th class="p-2 text-left text-gray-500 dark:text-gray-400 w-24">Hora</th>
                        @foreach($days as $dayNum => $dayName)
                            <th class="p-2 text-center text-gray-700 dark:text-gray-300 font-semibold">
                                {{ $dayName }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $currentHour = null; @endphp
                    @foreach($timeSlots as $slot)
                        @if($slot['hour'] !== $currentHour)
                            @php $currentHour = $slot['hour']; @endphp
                            <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                <td class="p-2 font-semibold text-gray-600 dark:text-gray-300" rowspan="4">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $slot['hour'])->format('H:i') }}
                                    <button wire:click="toggleAllInHour('{{ $slot['hour'] }}', 1)" class="hidden"></button>
                                </td>
                                @foreach($days as $dayNum => $dayName)
                                    <td class="p-1 text-center border border-gray-100 dark:border-gray-700">
                                        <button wire:click="toggleSlot({{ $dayNum }}, '{{ $slot['start'] }}')"
                                            class="w-full py-1 rounded text-xs transition
                                                {{ ($selectedSlots[$dayNum][$slot['start']] ?? false)
                                                    ? 'bg-blue-500 text-white'
                                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-400 hover:bg-blue-100' }}">
                                            {{ $slot['start'] }}
                                        </button>
                                    </td>
                                @endforeach
                            </tr>
                        @else
                            <tr>
                                @foreach($days as $dayNum => $dayName)
                                    <td class="p-1 text-center border border-gray-100 dark:border-gray-700">
                                        <button wire:click="toggleSlot({{ $dayNum }}, '{{ $slot['start'] }}')"
                                            class="w-full py-1 rounded text-xs transition
                                                {{ ($selectedSlots[$dayNum][$slot['start']] ?? false)
                                                    ? 'bg-blue-500 text-white'
                                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-400 hover:bg-blue-100' }}">
                                            {{ $slot['start'] }}
                                        </button>
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded bg-blue-500 inline-block"></span> Disponible
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded bg-gray-100 dark:bg-gray-700 inline-block"></span> No disponible
            </span>
        </div>
    </div>

</div>
BLADE