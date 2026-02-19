@props([
    'title' => config('app.name', 'Laravel'),
    'breadcrumbs' => [],
    'action' => null,
    ])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/e9e74fca35.js" crossorigin="anonymous"></script>

        <!-- Sweet Alert 2-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- WireUI --}}
        <wireui:scripts />
        
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50">
        
        @include('layouts.includes.admin.navigation')

        @include('layouts.includes.admin.sidebar')

        <div class="p-4 sm:ml-64">
            <!-- Margin top 14px -->
            <div class="mt-14 flex items-center justify-between w-full" ></div>
            <div class="container mx-auto flex justify-between items-center">
                @include('layouts.includes.admin.breadcrumb')
                @if($action)
                    {{ $action }}
                @endif
            </div>
            {{ $slot }}
        </div>
        @stack('modals')

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

        {{-- Mostrar Sweet Alert --}}
        @if (session('swal'))
            <script>
                Swal.fire(@json(session('swal')));
            </script>
        @endif
        <script>
            const forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    try {
                        Swal.fire({
                            title: 'Estas seguro?',
                            text: "No podras revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                                
                            }
                        })
                    } catch (error) {
                        console.error('Error submitting form:', error);
                    }
                });
            });
            
            </script>
    </body>
</html>
