<div class="space-y-6">

    {{-- Buscador --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4">
            <i class="fa-solid fa-magnifying-glass mr-2 text-blue-500"></i>
            Buscar disponibilidad
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Fecha <span class="text-red-500">*</span>
                </label>
                <input type="date" wire:model="searchDate"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('searchDate') border-red-500 @enderror">
                @error('searchDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Hora <span class="text-gray-400 text-xs">(opcional)</span>
                </label>
                <select wire:model="searchTime"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Cualquier hora</option>
                    @foreach($timeOptions as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Especialidad <span class="text-gray-400 text-xs">(opcional)</span>
                </label>
                <select wire:model="searchSpeciality"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    @foreach($specialities as $sp)
                        <option value="{{ $sp['id'] }}">{{ $sp['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button wire:click="searchAvailability" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-60">
                <span wire:loading.remove wire:target="searchAvailability">
                    <i class="fa-solid fa-search mr-1"></i> Buscar doctores
                </span>
                <span wire:loading wire:target="searchAvailability">
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Buscando...
                </span>
            </button>
        </div>
    </div>

    {{-- Resultados --}}
    @if($hasSearched)
        @if(count($availableDoctors) === 0)
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 text-yellow-700 dark:text-yellow-400 rounded-xl px-5 py-4 text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                No hay doctores disponibles para los filtros seleccionados.
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4">
                    <i class="fa-solid fa-user-doctor mr-2 text-green-500"></i> Doctores disponibles
                </h2>
                <div class="space-y-4">
                    @foreach($availableDoctors as $doctor)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-semibold text-sm">
                                    {{ $doctor['initials'] }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $doctor['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $doctor['speciality'] }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($doctor['slots'] as $slot)
                                    <button wire:click="selectSlot({{ $doctor['id'] }}, '{{ $slot['start'] }}', '{{ $slot['end'] }}')"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition
                                            {{ $selectedDoctorId == $doctor['id'] && $selectedSlotStart == $slot['start']
                                                ? 'bg-green-600 text-white'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-green-50 hover:text-green-700' }}">
                                        <i class="fa-regular fa-clock mr-1"></i>{{ $slot['start'] }} – {{ $slot['end'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    {{-- Confirmar cita --}}
    @if($selectedDoctorId)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fa-solid fa-calendar-check mr-2 text-indigo-500"></i> Confirmar cita
            </h2>
            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700 rounded-lg p-4 mb-5 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Doctor</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $selectedDoctorName }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Fecha</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $selectedDate }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Horario</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $selectedSlotStart }} – {{ $selectedSlotEnd }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Paciente <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="patientId"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('patientId') border-red-500 @enderror">
                        <option value="">Seleccionar paciente...</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}">{{ $patient['user']['name'] }}</option>
                        @endforeach
                    </select>
                    @error('patientId') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Motivo <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="reason" rows="3" placeholder="Describe el motivo de la consulta..."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('reason') border-red-500 @enderror"></textarea>
                    @error('reason') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-5 flex justify-end">
                <button wire:click="confirmAppointment" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition disabled:opacity-60">
                    <span wire:loading.remove wire:target="confirmAppointment">
                        <i class="fa-solid fa-check mr-1"></i> Confirmar y enviar correo
                    </span>
                    <span wire:loading wire:target="confirmAppointment">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Guardando...
                    </span>
                </button>
            </div>
        </div>
    @endif

    {{-- Reporte diario --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-1">
            <i class="fa-solid fa-clock mr-2 text-amber-500"></i> Reporte diario automático
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
            Se envía automáticamente cada día a las
            <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">{{ env('DAILY_REPORT_TIME', '08:00') }}</code>
            al administrador y a cada doctor con sus citas del día.
        </p>

        @if($reportMsg)
            <div class="mb-4 px-4 py-3 rounded-lg text-sm {{ $reportMsgType === 'success' ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 text-green-700 dark:text-green-400' : 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 text-yellow-700 dark:text-yellow-400' }}">
                <i class="fa-solid {{ $reportMsgType === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation' }} mr-2"></i>
                {{ $reportMsg }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Estado del scheduler</p>
                <div class="flex items-center gap-3 mb-3">
                    <input type="time" value="{{ env('DAILY_REPORT_TIME', '08:00') }}" disabled
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 cursor-not-allowed text-sm">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-lg">
                        <i class="fa-solid fa-circle text-[8px]"></i> Activo
                    </span>
                </div>
                <p class="text-xs text-gray-400">
                    Edita <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">DAILY_REPORT_TIME</code> en tu <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">.env</code> para cambiar la hora.
                </p>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-3">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Prueba manual</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Envía el reporte con las citas de hoy. Llegará a Mailtrap y al correo configurado en <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded">ADMIN_REAL_EMAIL</code>.
                </p>
                <button wire:click="sendTestReport" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition disabled:opacity-60">
                    <span wire:loading.remove wire:target="sendTestReport">
                        <i class="fa-solid fa-paper-plane mr-1"></i> Enviar reporte ahora
                    </span>
                    <span wire:loading wire:target="sendTestReport">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Enviando...
                    </span>
                </button>

                <hr class="border-gray-200 dark:border-gray-700">

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-flask mr-1 text-purple-500"></i>
                    <strong>Email de confirmación de prueba</strong> — envía el correo de la última cita registrada al instante.
                </p>
                <button wire:click="sendTestEmail" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition disabled:opacity-60">
                    <span wire:loading.remove wire:target="sendTestEmail">
                        <i class="fa-solid fa-envelope mr-1"></i> Enviar email de prueba
                    </span>
                    <span wire:loading wire:target="sendTestEmail">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Enviando...
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>