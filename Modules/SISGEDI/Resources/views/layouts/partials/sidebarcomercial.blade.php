<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="" type="image/x-icon">
    <title>SISGEDI - GESTION DOCUMENTAL</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & SweetAlert2 -->
    <script src="https://kit.fontawesome.com/dcb1bbced2.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @yield('css')
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50 selection:bg-[#39A900] selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="h-screen overflow-hidden flex flex-col lg:flex-row">
        <!-- Sidebar Navigation Executive -->
        <aside class="w-full lg:w-72 bg-[#001A29] text-slate-300 flex-shrink-0 flex flex-col justify-between border-r border-[#39A900]/20 lg:h-screen lg:sticky lg:top-0">
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-6 flex items-center justify-between bg-[#001A29]/60 border-b border-[#39A900]/20">
                    <a href="" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#39A900] to-[#00324D] flex items-center justify-center text-white shadow-lg shadow-[#39A900]/40">
                            <i class="fas fa-seedling text-lg"></i>
                        </div>
                        <div>
                            <span class="font-heading font-extrabold text-xl text-white tracking-tight">SISGEDI<span class="text-[#39A900]"></span></span>
                            <span class="block text-[10px] text-[#39A900] font-semibold tracking-widest uppercase">Gestión Documentacional</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-400 hover:text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Nav Menu -->
                <div class="px-4 py-6 space-y-8 overflow-y-auto max-h-[calc(100vh-140px)]" :class="{ 'block': sidebarOpen, 'hidden lg:block': !sidebarOpen }">
                    
                    <div class="space-y-1">
                        <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-4">Fase Inicial</p>
                        <!-- 1. Dashboard Comercial -->
                        <a href="{{ route('sisgedi.comercial.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                            <i class="fas fa-chart-pie text-lg w-5 text-center"></i>
                            <span>Dashboard Comercial</span>
                        </a>

                        <!-- 2. Plan Comercial -->
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-clipboard-list w-5 text-center"></i>
                            <span>Plan Comercial</span>
                        </a>

                        <!-- 3. Portafolio de Productos -->
                        <a href="{{ route('sisgedi.comercial.producto') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('sisgedi.comercial.producto') ? 'bg-[#39A900] text-white shadow-md shadow-[#39A900]/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40' }}">
                            <i class="fas fa-box-open w-5 text-center"></i>
                            <span>Portafolio de Productos</span>
                        </a>

                        <!-- 4. Gestión de Equipo -->
                        <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-8">Ejecución y Ventas</p>
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span>Gestión de Equipo</span>
                        </a>

                        <!-- 5. Seguimiento de Ventas -->
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-chart-line w-5 text-center"></i>
                            <span>Seguimiento de Ventas</span>
                        </a>

                        <!-- 6. Campañas Promocionales -->
                        <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-8">Marketing y Promoción</p>
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-bullhorn w-5 text-center"></i>
                            <span>Campañas Promocionales</span>
                        </a>

                        <!-- 7. Eventos y Showroom -->
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-calendar-alt w-5 text-center"></i>
                            <span>Eventos y Showroom</span>
                        </a>

                        <!-- 8. Supervisión de Puntos de Venta -->
                        <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-8">Control y Supervisión</p>
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-store w-5 text-center"></i>
                            <span>Supervisión de Puntos de Venta</span>
                        </a>

                        <!-- 9. Gestión Digital -->
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-laptop-code w-5 text-center"></i>
                            <span>Gestión Digital</span>
                        </a>

                        <!-- 10. Evidencias y Mejora Continua (SIG) -->
                        <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2 mt-8">Evaluación y Cierre</p>
                        <a href="" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 text-slate-400 hover:text-slate-200 hover:bg-slate-800/40">
                            <i class="fas fa-check-circle w-5 text-center"></i>
                            <span>Evidencias y Mejora Continua (SIG)</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Footer User Badge -->
            <div class="p-4 bg-[#001A29]/80 border-t border-[#39A900]/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#39A900] text-white flex items-center justify-center font-bold font-heading">
                        {{ strtoupper(substr(session('sisgedi_user')['nombre'] ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ session('sisgedi_user')['nombre'] ?? 'Usuario' }}</p>
                        <p class="text-xs text-[#39A900] font-medium">Administrador ERP</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Canvas -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <!-- Header Navbar Sticky -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-50 text-slate-700 border border-slate-200/60">
                        <span class="w-2 h-2 rounded-full bg-[#39A900] animate-pulse"></span>
                        Sistema En Línea
                    </span>
                </div>

                <!-- User Dropdown Menu -->
                <div class="flex items-center gap-4" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                            <span class="text-sm font-semibold text-slate-700">{{ session('sisgedi_user')['nombre'] ?? 'Usuario' }}</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transition-all">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Sesión activa</p>
                                <p class="text-sm font-bold text-slate-800 truncate">Rol ID: {{ session('sisgedi_user')['id_rol'] ?? 'Sin rol' }}</p>
                            </div>
                            <a href="" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="fas fa-user-cog text-slate-400"></i> Mi Perfil
                            </a>
                            <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="fas fa-external-link-alt text-slate-400"></i> Ver Sitio Web
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors font-medium">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Alert Toast Notifications -->
            <main class="flex-1 p-6 lg:p-10 max-w-7xl w-full mx-auto overflow-y-auto">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @yield('js')
</body>
</html>
