<x-admin-layout
    title="Detalle del Doctor | Medify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.admin.doctors.index')],
        ['name' => $doctor->user->name],
    ]"
>

    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.admin.doctors.edit', $doctor) }}">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar
        </x-wire-button>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- Tarjeta de Perfil Superior --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row items-center md:items-start gap-8">
            <div class="flex-shrink-0 relative">
                <div class="w-32 h-32 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center text-4xl font-bold shadow-sm border border-blue-100">
                    {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                </div>
                {{-- Badge de Especialidad Flotante --}}
                <div class="absolute -bottom-3 -right-3 bg-blue-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-xl shadow-lg border-2 border-white flex items-center gap-1">
                    <i class="fa-solid fa-user-doctor"></i>
                    {{ $doctor->speciality->name ?? 'General' }}
                </div>
            </div>
            
            <div class="flex-1 text-center md:text-left mt-4 md:mt-0">
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mb-2">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $doctor->user->name }}</h1>
                    @if($doctor->is_active)
                        <span class="inline-flex px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg items-center self-center">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                            Activo
                        </span>
                    @else
                        <span class="inline-flex px-2.5 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg items-center self-center">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                            Inactivo
                        </span>
                    @endif
                </div>
                
                <p class="text-gray-500 font-medium mb-4"><i class="fa-solid fa-envelope mr-2"></i> {{ $doctor->user->email }}</p>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <span class="px-4 py-2 bg-gray-50 text-gray-700 rounded-xl text-sm font-semibold border border-gray-100">
                        <i class="fa-solid fa-phone text-gray-400 mr-2"></i> {{ $doctor->user->phone ?? 'N/A' }}
                    </span>
                    <span class="px-4 py-2 bg-gray-50 text-gray-700 rounded-xl text-sm font-semibold border border-gray-100">
                        <i class="fa-solid fa-id-card text-gray-400 mr-2"></i> DNI: {{ $doctor->user->id_number ?? 'N/A' }}
                    </span>
                    <span class="px-4 py-2 bg-yellow-50 text-yellow-700 rounded-xl text-sm font-semibold border border-yellow-100">
                        <i class="fa-solid fa-star text-yellow-500 mr-2"></i> {{ $doctor->experience_years ?? 0 }} Años de Experiencia
                    </span>
                </div>
            </div>
        </div>

        {{-- Información Profesional y Biografía --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Columna Más Ancha: Bio & Detalles --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Biografía Profesional --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-id-badge text-blue-500 mr-2"></i> Perfil Profesional</h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-gray-600 text-sm leading-relaxed">
                            @if($doctor->bio)
                                {{ $doctor->bio }}
                            @else
                                <div class="text-center py-6 text-gray-400 italic">
                                    El doctor aún no ha agregado una biografía profesional.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Detalles Adicionales --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-address-book text-blue-500 mr-2"></i> Información Adicional</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Estado Civil</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1 capitalize">{{ $doctor->user->marital_status ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ocupación</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1 capitalize">{{ $doctor->user->occupation ?? 'Doctor' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dirección Comercial/Consultorio</p>
                            <p class="text-sm font-semibold text-gray-800 mt-1">{{ $doctor->user->address ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Columna Delgada: Acciones & Stats --}}
            <div class="space-y-6 lg:col-span-1">
                
                {{-- Panel de Control Rápido --}}
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden">
                    {{-- Decoración de fondo --}}
                    <i class="fa-solid fa-stethoscope absolute -bottom-4 -right-4 text-7xl text-white opacity-10 rotate-12"></i>
                    
                    <h3 class="text-xl font-bold mb-1 relative z-10">Gestión de Doctor</h3>
                    <p class="text-blue-100 text-xs mb-6 relative z-10">Administra disponibilidad y turnos.</p>
                    
                    <div class="flex flex-col gap-3 relative z-10">
                        <a href="{{ route('admin.admin.doctors.schedules', $doctor) }}" class="px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition-all border border-white/20 flex items-center justify-between group">
                            <span class="flex items-center gap-2"><i class="fa-regular fa-calendar-check w-4"></i> Asignar Horario</span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:translate-x-1 group-hover:opacity-100 transition-all"></i>
                        </a>
                        <a href="{{ route('admin.admin.appointments.index') }}" class="px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition-all border border-white/20 flex items-center justify-between group">
                            <span class="flex items-center gap-2"><i class="fa-solid fa-list-ul w-4"></i> Ver Citas</span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:translate-x-1 group-hover:opacity-100 transition-all"></i>
                        </a>
                    </div>
                </div>

                {{-- Info Métrica Rápida --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Citas Totales</p>
                            <p class="text-2xl font-black text-gray-800 tracking-tight">{{ $doctor->appointments()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
