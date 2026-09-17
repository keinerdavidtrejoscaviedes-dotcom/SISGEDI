<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISGEDI - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-48 bg-gradient-to-b from-green-700 to-green-800 text-white flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-green-600">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-green-700 font-bold text-lg">SG</span>
                    </div>
                    <div>
                        <p class="font-bold text-sm">SISGEDI</p>
                        <p class="text-xs text-green-100">Gestión integral</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('sisgedi.colaborador.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.dashboard') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('sisgedi.colaborador.mis-tareas') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.mis-tareas') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <span class="font-medium">Mis Tareas</span>
                </a>

                <a href="{{ route('sisgedi.colaborador.bitacoras') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.bitacoras') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Bitácoras</span>
                </a>

                <a href="{{ route('sisgedi.colaborador.paz-y-salvo') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.paz-y-salvo') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Paz y Salvo</span>
                </a>

                <a href="{{ route('sisgedi.colaborador.plan-innovacion') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.plan-innovacion') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v4h8v-4zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                    <span class="font-medium">Plan de Innovación</span>
                </a>

                <a href="{{ route('sisgedi.colaborador.convocatorias') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.convocatorias') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V3z"></path>
                    </svg>
                    <span class="font-medium">Convocatorias</span>
                </a>

                <a href="{{ route('sisgedi.colaborador.mis-evidencias') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.mis-evidencias') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Mis Evidencias</span>
                </a>

                <a href="{{ route('sisgedi.colaborador.doc-final-fase') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.colaborador.doc-final-fase') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                    </svg>
                    <span class="font-medium">Doc. Final de Fase</span>
                </a>
            </nav>

            <!-- Footer -->
            <div class="border-t border-green-600 p-4 text-xs text-green-100">
                <p class="mb-2">Centro La Angostura</p>
                <p>Campoalegre - Huila</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
                <h1 class="text-gray-600 text-sm font-medium">Dashboard Principal</h1>
                <div class="flex items-center space-x-6">
                    <!-- Notifications -->
                    <button class="relative">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative group border-l border-gray-200 pl-6">
                        <button class="flex items-center space-x-3 hover:opacity-80 transition" onclick="toggleProfileMenu()">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                                JR
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-gray-800 text-sm">Juan Rodriguez</p>
                                <p class="text-gray-500 text-xs">Ver perfil</p>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200 overflow-hidden">
                            <!-- User Info Section -->
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-6 border-b border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-2xl">
                                        JR
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">Juan Rodriguez</p>
                                        <p class="text-sm text-gray-600">Colaborador</p>
                                        <p class="text-xs text-gray-500 mt-1">juan.rodriguez@email.com</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Data -->
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Información Personal</p>
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nombre Completo:</span>
                                            <span class="font-semibold text-gray-800">Juan Camilo Rodríguez Parra</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Documento:</span>
                                            <span class="font-semibold text-gray-800">1.234.567.890</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Ficha SENA:</span>
                                            <span class="font-semibold text-gray-800">2893451</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Programa:</span>
                                            <span class="font-semibold text-gray-800">Técnico Agropecuaria</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Centro:</span>
                                            <span class="font-semibold text-gray-800">La Angostura</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Teléfono:</span>
                                            <span class="font-semibold text-gray-800">+57 310 123 4567</span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Información de Rol</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-block w-3 h-3 bg-green-600 rounded-full"></span>
                                            <span class="text-gray-700">Rol: <strong>Colaborador</strong></span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-block w-3 h-3 bg-blue-600 rounded-full"></span>
                                            <span class="text-gray-700">Estado: <strong>Activo</strong></span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-block w-3 h-3 bg-yellow-600 rounded-full"></span>
                                            <span class="text-gray-700">Último acceso: <strong>Hoy 14:32</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="border-t border-gray-200 px-6 py-4 space-y-2">
                                <form action="{{ route('sisgedi.logout') }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function toggleProfileMenu() {
                    const menu = document.getElementById('profileMenu');
                    menu.classList.toggle('hidden');
                    event.stopPropagation();
                }

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    const menu = document.getElementById('profileMenu');
                    const profileBtn = event.target.closest('[onclick="toggleProfileMenu()"]');
                    const profileDiv = event.target.closest('.relative.group');
                    
                    if (!profileDiv && !profileBtn) {
                        menu.classList.add('hidden');
                    }
                });

                // Prevent menu from closing when clicking inside it
                document.getElementById('profileMenu').addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            </script>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
