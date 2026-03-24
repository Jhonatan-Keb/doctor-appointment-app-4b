<div x-data="{ tab: @entangle('activeTab') }" class="space-y-4">

    {{-- ── Header de la cita ──────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Paciente</p>
            <p class="text-lg font-bold text-gray-800 dark:text-white">
                {{ $appointment->patient->user->name ?? '—' }}
            </p>
            <p class="text-sm text-gray-500">
                Dr. {{ $appointment->doctor->user->name ?? '—' }}
                &nbsp;·&nbsp;
                {{ $appointment->date->format('d/m/Y') }}
                &nbsp;·&nbsp;
                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
            </p>
        </div>

        <div class="flex gap-2">
            {{-- Ver Historia (redirige al módulo de pacientes) --}}
            <a href="{{ route('admin.admin.patients.show', $appointment->patient) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                <i class="fa-solid fa-book-medical"></i> Ver Historia
            </a>

            {{-- Consultas Anteriores --}}
            <button wire:click="loadPreviousConsultations"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                <i class="fa-solid fa-clock-rotate-left"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    {{-- ── Pestañas ────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">

        {{-- Tab nav --}}
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button @click="tab = 'consulta'"
                    :class="tab === 'consulta'
                        ? 'border-b-2 border-blue-600 text-blue-600 font-semibold'
                        : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm transition">
                <i class="fa-solid fa-notes-medical mr-1"></i> Consulta
            </button>
            <button @click="tab = 'receta'"
                    :class="tab === 'receta'
                        ? 'border-b-2 border-blue-600 text-blue-600 font-semibold'
                        : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm transition">
                <i class="fa-solid fa-prescription-bottle-medical mr-1"></i> Receta
            </button>
        </div>

        {{-- ── TAB: CONSULTA ──────────────────────────────────────────── --}}
        <div x-show="tab === 'consulta'" class="p-6 space-y-5">

            @if (session('success_consulta'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
                    {{ session('success_consulta') }}
                </div>
            @endif

            {{-- Diagnóstico --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Diagnóstico <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="diagnosis" rows="3"
                          placeholder="Escribe el diagnóstico..."
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('diagnosis') border-red-500 @enderror"></textarea>
                @error('diagnosis')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tratamiento --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tratamiento <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="treatment" rows="3"
                          placeholder="Describe el tratamiento indicado..."
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('treatment') border-red-500 @enderror"></textarea>
                @error('treatment')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notas (opcional) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Notas <span class="text-gray-400 text-xs">(opcional)</span>
                </label>
                <textarea wire:model="notes" rows="2"
                          placeholder="Observaciones adicionales..."
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-end">
                <button wire:click="saveConsulta" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-60">
                    <span wire:loading.remove wire:target="saveConsulta">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar y continuar a Receta
                    </span>
                    <span wire:loading wire:target="saveConsulta">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Guardando...
                    </span>
                </button>
            </div>
        </div>

        {{-- ── TAB: RECETA ────────────────────────────────────────────── --}}
        <div x-show="tab === 'receta'" class="p-6 space-y-4">

            <div class="flex justify-between items-center mb-2">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Medicamentos
                </h3>
                <button wire:click="addMedicine"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition">
                    <i class="fa-solid fa-plus"></i> Agregar medicamento
                </button>
            </div>

            @foreach ($medicines as $i => $med)
                <div class="grid grid-cols-12 gap-3 items-start bg-gray-50 dark:bg-gray-700/40 rounded-lg p-4">
                    {{-- Nombre --}}
                    <div class="col-span-12 sm:col-span-4">
                        <label class="block text-xs text-gray-500 mb-1">Medicamento <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="medicines.{{ $i }}.name"
                               placeholder="Nombre del medicamento"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 @error('medicines.'.$i.'.name') border-red-500 @enderror">
                        @error('medicines.'.$i.'.name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Dosis --}}
                    <div class="col-span-12 sm:col-span-3">
                        <label class="block text-xs text-gray-500 mb-1">Dosis</label>
                        <input type="text" wire:model="medicines.{{ $i }}.dose"
                               placeholder="500mg"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    {{-- Frecuencia --}}
                    <div class="col-span-12 sm:col-span-3">
                        <label class="block text-xs text-gray-500 mb-1">Frecuencia</label>
                        <input type="text" wire:model="medicines.{{ $i }}.frequency"
                               placeholder="Cada 8 horas"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    {{-- Duración + eliminar --}}
                    <div class="col-span-12 sm:col-span-2 flex flex-col gap-1">
                        <label class="block text-xs text-gray-500 mb-1">Duración</label>
                        <div class="flex gap-2">
                            <input type="text" wire:model="medicines.{{ $i }}.duration"
                                   placeholder="7 días"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                            @if (count($medicines) > 1)
                                <button wire:click="removeMedicine({{ $i }})"
                                        class="flex-shrink-0 text-red-500 hover:text-red-700 transition mt-0.5">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end pt-2">
                <button wire:click="saveReceta" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-60">
                    <span wire:loading.remove wire:target="saveReceta">
                        <i class="fa-solid fa-check-circle mr-1"></i> Finalizar Consulta
                    </span>
                    <span wire:loading wire:target="saveReceta">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Guardando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal: Consultas Anteriores ────────────────────────────────── --}}
    @if ($showPreviousModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                {{-- Header modal --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-500"></i>
                        Consultas Anteriores — {{ $appointment->patient->user->name ?? '' }}
                    </h3>
                    <button wire:click="$set('showPreviousModal', false)"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                {{-- Body modal --}}
                <div class="overflow-y-auto px-6 py-4 space-y-4">
                    @forelse ($previousConsultations as $prev)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 space-y-2">
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span>
                                    <i class="fa-solid fa-calendar-day mr-1"></i>
                                    {{ $prev['date'] }}
                                </span>
                                <span>
                                    <i class="fa-solid fa-user-doctor mr-1"></i>
                                    Dr. {{ $prev['doctor_name'] }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Diagnóstico</p>
                                <p class="text-sm text-gray-800 dark:text-white">{{ $prev['diagnosis'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tratamiento</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $prev['treatment'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-gray-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2"></i>
                            <p class="text-sm">Este paciente no tiene consultas anteriores.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Footer modal --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button wire:click="$set('showPreviousModal', false)"
                            class="px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
