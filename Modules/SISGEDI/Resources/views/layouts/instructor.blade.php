<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SISGEDI - Dashboard Instructor</title>
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

            <!-- Context Selector -->
            <div class="border-b border-green-600 px-4 py-3">
                <select class="w-full px-3 py-2 rounded text-sm font-medium bg-green-600 text-white border border-green-500 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-white cursor-pointer">
                    <option class="bg-white text-gray-800">Instructor</option>
                    <option class="bg-white text-gray-800">Líder</option>
                </select>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('sisgedi.instructor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.instructor.dashboard') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('sisgedi.instructor.fichas-asignadas') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.instructor.fichas-asignadas') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <span class="font-medium">Mis Fichas</span>
                </a>

                <a href="{{ route('sisgedi.instructor.revisar-entregables') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.instructor.revisar-entregables') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Revisión Entregables</span>
                </a>

                <a href="{{ route('sisgedi.instructor.gestionar-firma') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('sisgedi.instructor.gestionar-firma') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                    </svg>
                    <span class="font-medium">Firma Digital</span>
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
                <h1 class="text-gray-600 text-sm font-medium">Dashboard — Instructor</h1>
                <div class="flex items-center space-x-6">
                    <!-- Notifications -->
                    <button onclick="toggleNotificationsModal()" class="relative hover:opacity-80 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span id="notificationBadge" class="absolute top-0 right-0 w-5 h-5 bg-red-500 rounded-full text-white text-xs flex items-center justify-center font-bold hidden">0</span>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div class="relative group border-l border-gray-200 pl-6">
                        <button class="flex items-center space-x-3 hover:opacity-80 transition" onclick="toggleProfileMenu()">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                                AT
                            </div>
                            <div class="text-left">
                                <p class="font-semibold text-gray-800 text-sm">Ana Torres</p>
                                <p class="text-gray-500 text-xs">Ver perfil</p>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileMenu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl z-50 border border-gray-200 overflow-hidden">
                            <!-- User Info Section -->
                            <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-6 border-b border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-2xl">
                                        AT
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">Ana Torres</p>
                                        <p class="text-sm text-gray-600">Instructora</p>
                                        <p class="text-xs text-gray-500 mt-1">ana.torres@sena.edu.co</p>
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
                                            <span class="font-semibold text-gray-800">Ana Torres Martínez</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Documento:</span>
                                            <span class="font-semibold text-gray-800">1.098.765.432</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Centro:</span>
                                            <span class="font-semibold text-gray-800">La Angostura</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Teléfono:</span>
                                            <span class="font-semibold text-gray-800">+57 310 987 6543</span>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Información de Rol</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-block w-3 h-3 bg-green-600 rounded-full"></span>
                                            <span class="text-gray-700">Rol: <strong>Instructor</strong></span>
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
                let unreadNotifications = [];

                function toggleNotificationsModal() {
                    const modal = document.getElementById('notificationsModal');
                    modal.classList.toggle('hidden');
                    
                    if (!modal.classList.contains('hidden')) {
                        loadNotifications();
                    }
                }

                function loadNotifications() {
                    fetch('{{ route("sisgedi.instructor.get-notifications") }}')
                        .then(response => response.json())
                        .then(data => {
                            unreadNotifications = data.notifications;
                            renderNotifications();
                        })
                        .catch(error => console.error('Error:', error));
                }

                function renderNotifications() {
                    const content = document.getElementById('notificationsContent');
                    const badge = document.getElementById('notificationBadge');

                    if (unreadNotifications.length === 0) {
                        content.innerHTML = '<div class="text-center py-12"><p class="text-gray-500 text-lg">No tienes notificaciones</p></div>';
                        badge.classList.add('hidden');
                        return;
                    }

                    badge.textContent = unreadNotifications.length;
                    badge.classList.remove('hidden');

                    content.innerHTML = unreadNotifications.map((notif, index) => `
                        <div class="border-b border-gray-100 px-6 py-4 hover:bg-gray-50 transition cursor-pointer" onclick="markAsRead(${notif.id}, ${index})">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">${notif.colaborador_name}</p>
                                    <p class="text-sm text-gray-600 mt-1">Subió una evidencia</p>
                                    <p class="text-xs text-gray-500 mt-2">${notif.time}</p>
                                </div>
                                <div class="w-3 h-3 bg-blue-600 rounded-full flex-shrink-0 mt-1"></div>
                            </div>
                        </div>
                    `).join('');
                }

                function markAsRead(notificationId, index) {
                    fetch('{{ route("sisgedi.instructor.mark-notification-read") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({ notification_id: notificationId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        unreadNotifications.splice(index, 1);
                        renderNotifications();
                    })
                    .catch(error => console.error('Error:', error));
                }

                function markAllAsRead() {
                    if (unreadNotifications.length === 0) return;
                    
                    Promise.all(unreadNotifications.map((notif, index) => 
                        fetch('{{ route("sisgedi.instructor.mark-notification-read") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({ notification_id: notif.id })
                        })
                    )).then(() => {
                        unreadNotifications = [];
                        renderNotifications();
                    });
                }

                function toggleProfileMenu() {
                    const menu = document.getElementById('profileMenu');
                    menu.classList.toggle('hidden');
                    event.stopPropagation();
                }

                document.addEventListener('click', function(event) {
                    const menu = document.getElementById('profileMenu');
                    const profileBtn = event.target.closest('[onclick="toggleProfileMenu()"]');
                    const profileDiv = event.target.closest('.relative.group');
                    const notifModal = document.getElementById('notificationsModal');
                    const notifBtn = event.target.closest('[onclick="toggleNotificationsModal()"]');
                    
                    if (!profileDiv && !profileBtn) {
                        menu.classList.add('hidden');
                    }

                    if (!notifModal.contains(event.target) && !notifBtn) {
                        notifModal.classList.add('hidden');
                    }
                });

                document.getElementById('profileMenu').addEventListener('click', function(event) {
                    event.stopPropagation();
                });

                // Cargar notificaciones al cargar la página
                window.addEventListener('load', function() {
                    loadNotifications();
                });
            </script>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Modal de Notificaciones -->
    <div id="notificationsModal" class="hidden fixed top-20 right-8 w-96 bg-white rounded-lg shadow-2xl z-40 overflow-hidden">
        <!-- Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Notificaciones</h2>
            <div class="flex items-center space-x-2">
                <button onclick="markAllAsRead()" class="text-green-600 hover:text-green-700 transition" title="Marcar todo como leído">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <button onclick="toggleNotificationsModal()" class="text-gray-600 hover:text-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Contenido -->
        <div id="notificationsContent" class="max-h-96 overflow-y-auto">
            <!-- Se llena dinámicamente con JavaScript -->
        </div>

        <!-- Footer - Ver Todo -->
        <div class="border-t border-gray-200 px-6 py-4 text-center bg-gray-50">
            <a href="{{ route('sisgedi.instructor.revisar-entregables') }}" class="text-green-600 hover:text-green-700 font-semibold transition">Ver todo</a>
        </div>
    </div>

</body>
</html>
