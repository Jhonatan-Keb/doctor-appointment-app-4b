<x-admin-layout
    title="Roles | Medify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
        ['name' => 'Nuevo'],
    ]"
>
    <x-slot name="action">
        <x-wire-button href="{{ route('admin.patients.index') }}" gray>
            Cancelar
        </x-wire-button>
        <x-wire-button form="create-patient-form" type="submit" blue class="ml-2">
            <i class="fa-solid fa-floppy-disk"></i>
            Guardar
        </x-wire-button>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form id="create-patient-form" method="POST" action="{{ route('admin.patients.store') }}">
                @csrf

                {{-- Errores generales --}}
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                        <strong class="text-red-700">Se encontraron errores:</strong>
                        <ul class="mt-2 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-wire-input name="name" label="Nombre" :value="old('name')" required />

                    <x-wire-input name="email" label="Email" type="email" :value="old('email')" required />

                    <x-wire-input name="id_number" label="Número de identificación" :value="old('id_number')" required />

                    <x-wire-input name="phone" label="Teléfono" :value="old('phone')" required />

                    <x-wire-input name="password" label="Contraseña" type="password" required />

                    <x-wire-input name="password_confirmation" label="Confirmar contraseña" type="password" required />

                    <div class="md:col-span-2">
                        <x-wire-input name="address" label="Dirección" :value="old('address')" required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo Sangre</label>
                        <select name="blood_type_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">-- Seleccionar --</option>
                            @foreach($bloodTypes as $id => $name)
                                <option value="{{ $id }}" {{ old('blood_type_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <x-wire-input name="allergies" label="Alergias" :value="old('allergies')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-wire-input name="chronic_conditions" label="Enfermedades crónicas" :value="old('chronic_conditions')" />
                    </div>

                    <div class="md:col-span-2">
                        <x-wire-input name="surgical_history" label="Antecedentes quirúrgicos" :value="old('surgical_history')" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones</label>
                        <textarea name="observations" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('observations') }}</textarea>
                    </div>

                    <x-wire-input name="emergency_contact_name" label="Contacto de emergencia (nombre)" :value="old('emergency_contact_name')" required />

                    <x-wire-input name="emergency_contact_phone" label="Contacto de emergencia (teléfono)" :value="old('emergency_contact_phone')" required />
                </div>

                <div class="flex justify-end mt-6">
                    <x-wire-button type="submit" blue>Crear paciente</x-wire-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>