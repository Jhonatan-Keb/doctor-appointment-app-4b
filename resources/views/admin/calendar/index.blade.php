<x-admin-layout
    title="Calendario | Medify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Calendario'],
    ]"
>

    {{-- FullCalendar CDN (Usamos v6) --}}
    @push('css')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <style>
        .fc {
            --fc-button-bg-color: #1e293b;
            --fc-button-border-color: #1e293b;
            --fc-button-hover-bg-color: #334155;
            --fc-button-hover-border-color: #334155;
            --fc-button-active-bg-color: #0f172a;
            --fc-button-active-border-color: #0f172a;
            
            --fc-event-bg-color: #3b82f6;
            --fc-event-border-color: #3b82f6;
            
            background: white;
            padding: 20px;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        
        .fc .fc-toolbar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            text-transform: capitalize;
        }

        .fc .fc-col-header-cell-cushion {
            padding: 10px 0;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .fc .fc-daygrid-day-number {
            color: #475569;
            font-weight: 500;
        }

        .fc-event {
            cursor: pointer;
            transition: transform 0.1s;
        }

        .fc-event:hover {
            transform: scale(1.02);
        }
    </style>
    @endpush

    <div class="max-w-7xl mx-auto">
        <div id='calendar-container'>
            <div id='calendar'></div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },
                events: '{{ route('admin.calendar.events') }}',
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    Swal.fire({
                        title: `Cita: ${info.event.title}`,
                        html: `
                            <div class="text-left space-y-2 mt-4">
                                <p><strong>Doctor:</strong> ${props.doctor}</p>
                                <p><strong>Paciente:</strong> ${props.patient}</p>
                                <p><strong>Estado:</strong> <span class="px-2 py-0.5 rounded-full text-xs font-bold ${props.status === 'Completado' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'}">${props.status}</span></p>
                                <p><strong>Hora:</strong> ${new Date(info.event.start).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}hs</p>
                            </div>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Ver Cita',
                        cancelButtonText: 'Cerrar',
                        confirmButtonColor: '#3b82f6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                             window.location.href = `/admin/appointments/${info.event.id}`;
                        }
                    });
                },
                // Personalización de labels de días
                dayHeaderContent: function(arg) {
                    const days = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];
                    return days[arg.date.getDay()];
                }
            });
            calendar.render();
        });
    </script>
    @endpush

</x-admin-layout>