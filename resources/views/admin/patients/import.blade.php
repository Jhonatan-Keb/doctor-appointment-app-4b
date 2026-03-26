<x-admin-layout
    title="Importar Pacientes | Dendro Medical"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Pacientes',  'href' => route('admin.admin.patients.index')],
        ['name' => 'Importación Masiva'],
    ]">

    {{-- Encabezado de acción --}}
    <x-wire-card class="mt-10">
        <div class="lg:flex lg:justify-between lg:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fa-solid fa-file-arrow-up text-blue-500 mr-2"></i>
                    Importación Masiva de Pacientes
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Sube un archivo CSV con la información de múltiples pacientes. El procesamiento
                    se realizará en <strong>segundo plano</strong> para no bloquear el sistema.
                </p>
            </div>
            <div class="mt-4 lg:mt-0 flex space-x-3">
                <x-wire-button outline gray href="{{ route('admin.admin.patients.index') }}">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    Volver
                </x-wire-button>
                <x-wire-button outline teal href="{{ route('admin.admin.patients.import-template') }}">
                    <i class="fa-solid fa-download mr-2"></i>
                    Descargar plantilla CSV
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>

    {{-- Instrucciones --}}
    <x-wire-card class="mt-4">
        <h2 class="text-base font-semibold text-gray-800 mb-3">
            <i class="fa-solid fa-circle-info text-blue-400 mr-1"></i>
            Formato requerido del archivo CSV
        </h2>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-xs text-left text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-2">Columna</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Obligatorio</th>
                        <th class="px-4 py-2">Ejemplo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ([
                        ['nombre',                 'Nombre completo del paciente',            true,  'Juan Pérez'],
                        ['email',                  'Correo electrónico (único)',               true,  'juan@ejemplo.com'],
                        ['cedula',                 'Número de identidad (único)',              true,  '1234567890'],
                        ['telefono',               'Teléfono de contacto',                    false, '8888-8888'],
                        ['direccion',              'Dirección de residencia',                 false, 'Calle 1, San José'],
                        ['tipo_sangre',            'Tipo de sangre (debe existir en el sistema)', false, 'O+'],
                        ['alergias',               'Alergias conocidas',                      false, 'Polen, polvo'],
                        ['enfermedades_cronicas',  'Enfermedades crónicas',                   false, 'Diabetes tipo 2'],
                        ['historial_quirurgico',   'Historial de cirugías',                   false, 'Apendicectomía 2010'],
                        ['historial_familiar',     'Antecedentes familiares',                 false, 'Hipertensión paterna'],
                        ['contacto_emergencia',    'Nombre del contacto de emergencia',       false, 'María Pérez'],
                        ['telefono_emergencia',    'Teléfono del contacto de emergencia',     false, '7777-7777'],
                        ['relacion_emergencia',    'Relación con el paciente',                false, 'Esposa'],
                    ] as [$col, $desc, $req, $ej])
                    <tr>
                        <td class="px-4 py-2 font-mono font-semibold text-blue-700">{{ $col }}</td>
                        <td class="px-4 py-2">{{ $desc }}</td>
                        <td class="px-4 py-2">
                            @if($req)
                                <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">Sí</span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-gray-500 italic">{{ $ej }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-xs text-gray-400 mt-3">
            <i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i>
            La primera fila debe ser el encabezado. El sistema generará una contraseña temporal aleatoria para cada paciente.
        </p>
    </x-wire-card>

    {{-- Formulario de carga --}}
    <x-wire-card class="mt-4">
        <h2 class="text-base font-semibold text-gray-800 mb-4">
            <i class="fa-solid fa-upload text-blue-400 mr-1"></i>
            Subir archivo CSV
        </h2>

        <form action="{{ route('admin.admin.patients.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div x-data="{ fileName: '', dragging: false }"
                 class="relative border-2 border-dashed rounded-xl p-8 text-center transition-colors duration-200"
                 :class="dragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-400 bg-gray-50'"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; fileName = $event.dataTransfer.files[0]?.name; $refs.fileInput.files = $event.dataTransfer.files">

                <input type="file"
                       name="file"
                       accept=".csv,.txt"
                       x-ref="fileInput"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                       @change="fileName = $event.target.files[0]?.name">

                <i class="fa-solid fa-file-csv text-5xl mb-3"
                   :class="fileName ? 'text-green-400' : 'text-gray-300'"></i>

                <p class="text-gray-600 font-medium" x-text="fileName || 'Arrastra tu archivo CSV aquí'"></p>
                <p class="text-gray-400 text-sm mt-1" x-show="!fileName">o haz clic para seleccionarlo</p>
                <p class="text-green-600 text-sm mt-1 font-semibold" x-show="fileName">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    Archivo seleccionado
                </p>
            </div>

            @error('file')
                <p class="text-red-600 text-sm mt-2 flex items-center">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    {{ $message }}
                </p>
            @enderror

            <div class="mt-6 flex justify-end">
                <x-wire-button type="submit">
                    <i class="fa-solid fa-paper-plane mr-2"></i>
                    Iniciar importación en segundo plano
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>

    {{-- Historial de importaciones --}}
    @if($imports->isNotEmpty())
    <x-wire-card class="mt-4 mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-800">
                <i class="fa-solid fa-clock-rotate-left text-blue-400 mr-1"></i>
                Historial de importaciones recientes
            </h2>
            <button onclick="window.location.reload()"
                    class="text-xs text-blue-500 hover:text-blue-700 flex items-center gap-1">
                <i class="fa-solid fa-arrows-rotate"></i>
                Actualizar estado
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Archivo</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-center">Total filas</th>
                        <th class="px-4 py-3 text-center">Procesadas</th>
                        <th class="px-4 py-3 text-center">Fallidas</th>
                        <th class="px-4 py-3">Iniciado</th>
                        <th class="px-4 py-3">Errores</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($imports as $import)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800 max-w-xs truncate">
                            <i class="fa-solid fa-file-csv text-blue-400 mr-1"></i>
                            {{ $import->file_name }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $badge = match($import->status) {
                                    'pending'    => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'completed'  => 'bg-green-100 text-green-700',
                                    'failed'     => 'bg-red-100 text-red-700',
                                    default      => 'bg-gray-100 text-gray-600',
                                };
                                $icon = match($import->status) {
                                    'pending'    => 'fa-hourglass-start',
                                    'processing' => 'fa-spinner fa-spin',
                                    'completed'  => 'fa-circle-check',
                                    'failed'     => 'fa-circle-xmark',
                                    default      => 'fa-question',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                <i class="fa-solid {{ $icon }}"></i>
                                {{ $import->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">{{ $import->total_rows }}</td>
                        <td class="px-4 py-3 text-center text-green-600 font-semibold">{{ $import->processed_rows }}</td>
                        <td class="px-4 py-3 text-center {{ $import->failed_rows > 0 ? 'text-red-600 font-semibold' : '' }}">
                            {{ $import->failed_rows }}
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $import->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            @if($import->errors && count($import->errors))
                                <details class="text-xs">
                                    <summary class="text-red-500 cursor-pointer hover:text-red-700 font-medium">
                                        Ver {{ count($import->errors) }} error(es)
                                    </summary>
                                    <ul class="mt-2 space-y-1 text-gray-500 max-h-32 overflow-y-auto list-disc list-inside">
                                        @foreach($import->errors as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </details>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-wire-card>
    @endif

</x-admin-layout>
