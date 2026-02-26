<x-admin-layout title="Doctores | Dendro Medical" :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.admin.doctors.index')],
        ['name' => 'Crear'],
    ]">
    <form action="{{ route('admin.admin.doctors.store') }}" method="POST">
        @csrf
        <x-wire-card class="mt-10">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        Crear nuevo doctor
                    </p>
                    <p class="text-sm text-gray-500">
                        Seleccione un usuario con rol Doctor y complete los datos médicos.
                    </p>
                </div>
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.admin.doctors.index') }}">
                        Volver
                    </x-wire-button>
                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check mr-2"></i>
                        Crear doctor
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        <x-wire-card class="mt-6">
            <div class="space-y-6">
                {{-- Seleccionar usuario --}}
                <div>
                    <x-wire-native-select label="Usuario" name="user_id">
                        <option value="">Seleccione un usuario</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </x-wire-native-select>
                </div>

                {{-- Especialidad --}}
                <div>
                    <x-wire-native-select label="Especialidad" name="speciality_id">
                        <option value="">Seleccione una especialidad</option>
                        @foreach ($specialities as $speciality)
                            <option value="{{ $speciality->id }}" @selected(old('speciality_id') == $speciality->id)>
                                {{ $speciality->name }}
                            </option>
                        @endforeach
                    </x-wire-native-select>
                </div>

                {{-- Número de licencia médica --}}
                <x-wire-input label="Número de licencia médica" name="medical_license_number"
                    value="{{ old('medical_license_number') }}" />

                {{-- Biografía --}}
                <x-wire-textarea label="Biografía" name="biography">
                    {{ old('biography') }}
                </x-wire-textarea>
            </div>
        </x-wire-card>
    </form>
</x-admin-layout>