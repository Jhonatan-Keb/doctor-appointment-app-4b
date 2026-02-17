<x-admin-layout
    title="Roles | Medify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
        ['name' => 'Editar'],
    ]">

    @if(session('swal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire(@json(session('swal')));
            });
        </script>
    @endif

    {{-- ================= HEADER ================= --}}
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm mb-6">
        <div class="flex items-center justify-between px-6 py-5">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-semibold text-xl ring-4 ring-blue-100">
                    {{ strtoupper(substr($patient->user->name, 0, 2)) }}
                </div>

                <div>
                    <p class="text-xs text-gray-500">Editar</p>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $patient->user->name }}
                    </h2>
                </div>
            </div>

            <div class="flex gap-3">
                <x-wire-button href="{{ route('admin.patients.index') }}" gray>
                    Volver
                </x-wire-button>

                <button
                    type="submit"
                    form="update-patient-form"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white font-medium shadow-sm"
                >
                    ✓ Guardar cambios
                </button>
            </div>
        </div>
        <div class="border-t border-gray-100"></div>
    </div>

    {{-- ================= CONTENIDO ================= --}}
    <div x-data="{ tab: 'datos-personales' }">

        {{-- ================= TABS ================= --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm mb-4">
            <div class="flex gap-6 px-6 pt-4 text-sm font-medium">
                <a href="#"
                   @click.prevent="tab = 'datos-personales'"
                   :class="tab === 'datos-personales'
                        ? 'text-blue-600 border-blue-600'
                        : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-gray-300'"
                   class="inline-flex items-center gap-2 pb-3 border-b-2">
                    👤 Datos personales
                </a>

                <a href="#"
                   @click.prevent="tab = 'antecedentes'"
                   :class="tab === 'antecedentes'
                        ? 'text-blue-600 border-blue-600'
                        : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-gray-300'"
                   class="inline-flex items-center gap-2 pb-3 border-b-2">
                    📄 Antecedentes
                </a>

                <a href="#"
                   @click.prevent="tab = 'informacion'"
                   :class="tab === 'informacion'
                        ? 'text-blue-600 border-blue-600'
                        : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-gray-300'"
                   class="inline-flex items-center gap-2 pb-3 border-b-2">
                    ℹ️ Información general
                </a>

                <a href="#"
                   @click.prevent="tab = 'emergencia'"
                   :class="tab === 'emergencia'
                        ? 'text-blue-600 border-blue-600'
                        : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-gray-300'"
                   class="inline-flex items-center gap-2 pb-3 border-b-2">
                    ❤️ Contacto de emergencia
                </a>
            </div>
            <div class="border-t border-gray-100"></div>
        </div>

        {{-- ================= FORM ================= --}}
        <x-wire-card>
            <form id="update-patient-form" action="{{ route('admin.patients.update', $patient) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ================= TAB: DATOS PERSONALES ================= --}}
                <div x-show="tab === 'datos-personales'" x-cloak>

                    {{-- Banner edición usuario --}}
                    <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50/70 p-4">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex gap-3">
                                <div class="w-1 bg-blue-500 rounded"></div>
                                <div>
                                    <h3 class="text-sm font-semibold text-blue-900">
                                        Edición de cuenta de usuario
                                    </h3>
                                    <p class="text-sm text-blue-700">
                                        La información de acceso (Nombre, Email y Contraseña)
                                        debe gestionarse desde la cuenta de usuario asociada.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('admin.users.edit', $patient->user_id) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium">
                                Editar usuario ↗
                            </a>
                        </div>
                    </div>

                    {{-- ===== RESUMEN SOLO LECTURA ===== --}}
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div class="space-y-2">
                            <div class="flex gap-2">
                                <span class="font-medium text-gray-700">Teléfono:</span>
                                <span class="text-gray-900">{{ $patient->user->phone ?? '—' }}</span>
                            </div>

                            <div class="flex gap-2">
                                <span class="font-medium text-gray-700">Dirección:</span>
                                <span class="text-gray-900">{{ $patient->user->address ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="space-y-2 md:text-right">
                            <div class="flex gap-2 md:justify-end">
                                <span class="font-medium text-gray-700">Email:</span>
                                <span class="text-gray-900">{{ $patient->user->email ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ================= TAB: ANTECEDENTES ================= --}}
                <div x-show="tab === 'antecedentes'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- ✅ FIX: antes estaba familiar_history; en tu modelo tienes family_history --}}
                        <x-wire-input
                            name="family_history"
                            label="Antecedentes familiares"
                            :value="old('family_history', $patient->family_history)"
                        />

                        <x-wire-input
                            name="allergies"
                            label="Alergias"
                            :value="old('allergies', $patient->allergies)"
                        />

                        <x-wire-input
                            name="chronic_conditions"
                            label="Enfermedades crónicas"
                            :value="old('chronic_conditions', $patient->chronic_conditions)"
                        />

                        <x-wire-input
                            name="surgical_history"
                            label="Antecedentes quirúrgicos"
                            :value="old('surgical_history', $patient->surgical_history)"
                        />
                    </div>
                </div>

                {{-- ================= TAB: INFORMACIÓN GENERAL ================= --}}
                <div x-show="tab === 'informacion'" x-cloak>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo Sangre</label>
                        <select name="blood_type_id" class="mt-1 block w-50* rounded-md border-gray-300">
                            <option value="">-- Seleccionar --</option>
                            @foreach($bloodTypes as $id => $name)
                                <option value="{{ $id }}"
                                    {{ old('blood_type_id', $patient->blood_type_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label class="block text-sm font-medium text-gray-700 mt-4">Observaciones</label>

                    {{-- ✅ FIX: textarea sin indentación para no guardar espacios/saltos --}}
                    <textarea
                        name="observations"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300"
                    >{{ old('observations', $patient->observations) }}</textarea>
                </div>

                {{-- ================= TAB: CONTACTO EMERGENCIA ================= --}}
                <div x-show="tab === 'emergencia'" x-cloak>
                    <div class="grid grid-cols-1 gap-4">
                        <x-wire-input
                            name="emergency_contact_name"
                            label="Nombre del contacto de emergencia"
                            :value="old('emergency_contact_name', $patient->emergency_contact_name)"
                            required
                            placeholder="Nombre del contacto de emergencia"
                        />

                        <x-wire-input
                            name="emergency_contact_phone"
                            label="Contacto de emergencia (teléfono)"
                            :value="old('emergency_contact_phone', $patient->emergency_contact_phone)"
                            required
                            placeholder="Ej: (999)999 999"
                        />

                        <x-wire-input
                            name="emergency_contact_relationship"
                            label="Relación con el paciente"
                            :value="old('emergency_contact_relationship', $patient->emergency_contact_relationship)"
                            required
                            placeholder="Familiar, Amigo, etc."
                        />
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <x-wire-button type="submit" blue>
                        Actualizar paciente
                    </x-wire-button>
                </div>
            </form>
        </x-wire-card>
    </div>

    <style>[x-cloak]{ display:none !important }</style>

</x-admin-layout>
