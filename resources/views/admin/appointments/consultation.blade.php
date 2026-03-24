<x-admin-layout
    title="Consulta Médica | Medify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.admin.appointments.index')],
        ['name' => 'Consulta'],
    ]"
>

    <div class="max-w-6xl mx-auto h-full">
        
        {{-- Header del Paciente --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $appointment->patient->user->name }}</h2>
                    <p class="text-gray-500 font-medium tracking-tight">DNI: {{ $appointment->patient->user->id_number ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <x-wire-button xs label="Ver Historia" icon="document-text" class="px-4 py-2" onclick="openModal('modal-historia')" />
                <x-wire-button xs outline label="Consultas Anteriores" class="px-4 py-2" onclick="openModal('modal-consultas')" />
            </div>
        </div>

        {{-- Contenedor Principal con Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col min-h-[600px]">
            
            {{-- Tabs Navigation --}}
            <div class="flex border-b border-gray-100 px-6">
                <button type="button" onclick="switchTab('tab-consulta')" id="btn-tab-consulta" 
                        class="px-8 py-4 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600 transition-all tab-btn">
                    <i class="fa-solid fa-stethoscope mr-2"></i> Consulta
                </button>
                <button type="button" onclick="switchTab('tab-receta')" id="btn-tab-receta"
                        class="px-8 py-4 text-sm font-bold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-all tab-btn">
                    <i class="fa-solid fa-prescription mr-2"></i> Receta
                </button>
            </div>

            <form action="{{ route('admin.appointments.consultation.store', $appointment) }}" method="POST" id="main-consultation-form">
                @csrf
                
                {{-- Contenido de Consulta --}}
                <div id="tab-consulta" class="p-8 space-y-8 tab-content">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Diagnóstico</label>
                        <textarea name="diagnosis" rows="4" required
                                  placeholder="Describa el diagnóstico del paciente aquí..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">{{ $appointment->consultation->diagnosis ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tratamiento</label>
                        <textarea name="treatment" rows="4" required
                                  placeholder="Describa el tratamiento recomendado aquí..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">{{ $appointment->consultation->treatment ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Notas</label>
                        <textarea name="notes" rows="3"
                                  placeholder="Agregue notas adicionales sobre la consulta..."
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">{{ $appointment->consultation->notes ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Contenido de Receta --}}
                <div id="tab-receta" class="p-8 hidden tab-content">
                    
                    <div class="border border-gray-100 rounded-2xl overflow-hidden bg-gray-50/30">
                        <table class="w-full text-left" id="prescription-table">
                            <thead class="bg-gray-50 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Medicamento</th>
                                    <th class="px-6 py-4">Dosis</th>
                                    <th class="px-6 py-4 w-1/3">Frecuencia / Duración</th>
                                    <th class="px-6 py-4 w-10"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                {{-- Si ya tiene medicamentos, los cargamos --}}
                                @if($appointment->consultation && $appointment->consultation->prescriptionItems->count() > 0)
                                    @foreach($appointment->consultation->prescriptionItems as $item)
                                        <tr class="medicine-row">
                                            <td class="p-4">
                                                <input type="text" name="medicines[]" value="{{ $item->medicine_name }}" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                            </td>
                                            <td class="p-4">
                                                <input type="text" name="dosages[]" value="{{ $item->dosage }}" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                            </td>
                                            <td class="p-4">
                                                <input type="text" name="frequencies[]" value="{{ $item->frequency_duration }}" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                            </td>
                                            <td class="p-4 text-right">
                                                <button type="button" onclick="removeRow(this)" class="p-2 text-red-400 hover:text-red-600 transition-colors">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="medicine-row">
                                        <td class="p-4">
                                            <input type="text" name="medicines[]" placeholder="Amoxicilina 500mg" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                        </td>
                                        <td class="p-4">
                                            <input type="text" name="dosages[]" placeholder="1 cada 8 horas" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                        </td>
                                        <td class="p-4">
                                            <input type="text" name="frequencies[]" placeholder="Por 7 días" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                                        </td>
                                        <td class="p-4 text-right">
                                            <button type="button" onclick="removeRow(this)" class="p-2 text-red-400 hover:text-red-600 transition-colors">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="p-4 bg-gray-50/50">
                            <button type="button" onclick="addRow()" class="px-4 py-2 border border-gray-200 bg-white text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-100 transition-all flex items-center gap-2">
                                <i class="fa-solid fa-plus text-indigo-500"></i> Añadir Medicamento
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Footer con Botón Unico --}}
                <div class="p-8 border-t border-gray-100 bg-gray-50/30 flex justify-end">
                    <button type="submit" 
                            class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Consulta
                    </button>
                </div>
            </form>

        </div>

    </div>

    {{-- MODAL: Historia Médica del Paciente --}}
    <div id="modal-historia" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeModal('modal-historia')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Historia médica del paciente</h3>
                        <button onclick="closeModal('modal-historia')" class="text-gray-400 hover:text-gray-500">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tipo de sangre:</p>
                            <p class="text-sm font-bold text-indigo-600 mt-1">{{ $appointment->patient->bloodType->name ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alergias:</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">{{ $appointment->patient->allergies ?? 'No registradas' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Enfermedades crónicas:</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">{{ $appointment->patient->chronic_conditions ?? 'No registradas' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Antecedentes quirúrgicos:</p>
                            <p class="text-sm font-semibold text-gray-700 mt-1">{{ $appointment->patient->surgical_history ?? 'No registradas' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <a href="{{ route('admin.patients.edit', $appointment->patient) }}" class="text-indigo-600 text-sm font-bold hover:underline">
                            Ver / Editar Historia Médica
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Consultas Anteriores --}}
    <div id="modal-consultas" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeModal('modal-consultas')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="p-6 bg-white overflow-hidden flex flex-col max-h-[80vh]">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Consultas Anteriores</h3>
                        <button onclick="closeModal('modal-consultas')" class="text-gray-400 hover:text-gray-500">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="overflow-y-auto space-y-4 pr-2">
                        @forelse($previousAppointments as $prev)
                            <div class="p-6 rounded-2xl border border-blue-100 bg-blue-50/30">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm">
                                            <i class="fa-solid fa-calendar-day"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($prev->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($prev->time)->format('H:i') }}</p>
                                            <p class="text-[10px] text-gray-500 font-medium">Atendido por: {{ $prev->doctor->user->name }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.admin.appointments.consult', $prev) }}" class="px-3 py-1 border border-blue-400 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-600 hover:text-white transition-all">
                                        Consultar Detalle
                                    </a>
                                </div>
                                <div class="space-y-3 bg-white p-4 rounded-xl border border-gray-50">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">Diagnóstico:</p>
                                            <p class="text-xs text-gray-700 font-medium line-clamp-2">{{ $prev->consultation->diagnosis ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">Tratamiento:</p>
                                            <p class="text-xs text-gray-700 font-medium line-clamp-2">{{ $prev->consultation->treatment ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">Notas:</p>
                                        <p class="text-xs text-gray-600 italic">{{ $prev->consultation->notes ?? 'Sin observaciones' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                                <p>No existen registros de consultas previas para este paciente.</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function switchTab(tabId) {
            // Content
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');

            // Buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-indigo-600', 'text-indigo-600');
                b.classList.add('border-transparent', 'text-gray-400');
            });

            const currentBtn = document.getElementById('btn-' + tabId);
            currentBtn.classList.add('border-indigo-600', 'text-indigo-600');
            currentBtn.classList.remove('border-transparent', 'text-gray-400');
        }

        function addRow() {
            const tbody = document.querySelector('#prescription-table tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'medicine-row transition-all';
            newRow.innerHTML = `
                <td class="p-4">
                    <input type="text" name="medicines[]" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                </td>
                <td class="p-4">
                    <input type="text" name="dosages[]" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                </td>
                <td class="p-4">
                    <input type="text" name="frequencies[]" class="w-full px-3 py-2 border border-gray-100 rounded-lg text-sm outline-none focus:ring-1 focus:ring-indigo-500">
                </td>
                <td class="p-4 text-right">
                    <button type="button" onclick="removeRow(this)" class="p-2 text-red-400 hover:text-red-600 transition-colors">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);
        }

        function removeRow(btn) {
            const rows = document.querySelectorAll('.medicine-row');
            if(rows.length > 1) {
                btn.closest('tr').remove();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Operación no permitida',
                    text: 'Debe haber al menos un campo para medicamento.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }

        document.getElementById('main-consultation-form').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Guardando...';
            btn.disabled = true;
        });
    </script>
    @endpush

</x-admin-layout>