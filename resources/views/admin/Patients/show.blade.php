<x-admin-layout
    title="Detalle Paciente | MediMatch"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Pacientes',
            'href' => route('admin.patients.index'),
        ],
        [
            'name' => $patient->user->name,
        ],
    ]"
>
    <x-wire-card>
        <div class="grid lg:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Identificación</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->user->id_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->user->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->user->address }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Médica</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Sangre</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->bloodType?->name ?? 'No especificado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alergias</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->allergies ?? 'Ninguna registrada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Condiciones Crónicas</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->chronic_conditions ?? 'Ninguna registrada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Historial Quirúrgico</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->surgical_history ?? 'Sin historial' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Observaciones</h3>
                <p class="text-sm text-gray-900">{{ $patient->observations ?? 'Sin observaciones' }}</p>
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contacto de Emergencia</h3>
                <dl class="grid lg:grid-cols-3 gap-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->emergency_contact_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="text-sm text-gray-900">{{ $patient->emergency_contact_phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Parentesco</dt>
                      
                    </div>
                </dl>
            </div>
        </div>
    </x-wire-card>

    <div class="flex justify-end mt-4 space-x-2">
        <x-wire-button flat secondary href="{{ route('admin.patients.index') }}">
            Volver
        </x-wire-button>
        <x-wire-button blue href="{{ route('admin.patients.edit', $patient) }}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </x-wire-button>
    </div>

</x-admin-layout>