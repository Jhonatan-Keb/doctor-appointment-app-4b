<x-admin-layout title="Editar Cita | MediCitas" :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.admin.appointments.index')],
        ['name' => 'Editar'],
    ]">

    <form action="{{ route('admin.admin.appointments.update', $appointment) }}" method="POST">
        @csrf
        @method('PUT')

        <x-wire-card>
            <h2 class="text-lg font-bold text-gray-800 mb-1">Editar cita</h2>
            <p class="text-sm text-gray-500 mb-4">Modifica los datos de la cita médica.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Fecha --}}
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" name="date" id="date"
                        value="{{ old('date', $appointment->date->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('date')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Hora inicio y fin --}}
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
                    <div class="flex items-center gap-2">
                        <input type="time" name="start_time" id="start_time"
                            value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <span class="text-gray-400">–</span>
                        <input type="time" name="end_time" id="end_time"
                            value="{{ old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('H:i')) }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    @error('start_time')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                    @error('end_time')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Doctor --}}
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                    <select name="doctor_id" id="doctor_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccione un doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                                {{ $doctor->user->name }} — {{ $doctor->speciality->name ?? 'Sin especialidad' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </x-wire-card>

        <x-wire-card class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Paciente --}}
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                    <select name="patient_id" id="patient_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccione un paciente</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                                {{ $patient->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Motivo --}}
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Motivo de la cita</label>
                    <textarea name="reason" id="reason" rows="3" placeholder="Describa el motivo de la consulta..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('reason', $appointment->reason) }}</textarea>
                    @error('reason')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Estado --}}
            <div class="mt-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" id="status"
                    class="block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1" @selected(old('status', $appointment->status) == 1)>Programado</option>
                    <option value="2" @selected(old('status', $appointment->status) == 2)>Completado</option>
                    <option value="3" @selected(old('status', $appointment->status) == 3)>Cancelado</option>
                </select>
            </div>

            <div class="flex justify-end mt-6 gap-3">
                <x-wire-button outline gray href="{{ route('admin.admin.appointments.index') }}">
                    Volver
                </x-wire-button>
                <x-wire-button type="submit">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Guardar cambios
                </x-wire-button>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>