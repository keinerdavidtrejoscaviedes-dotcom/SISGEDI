<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SISGEDI | @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('general/assets/img/cefaempresa.png') }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="{{ asset('libs/Fontawesome6/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/Fontawesome6/css/solid.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/Fontawesome6/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/Fontawesome6/css/v5-font-face.css') }}" rel="stylesheet">

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css') }}">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sena-green': '#39A900',
                        'sena-lime':  '#62E31D',
                        'sena-navy':  '#001A29',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'Poppins', sans-serif; }
        html, body { height: 100%; }
        body { background: #f0f4f8; margin: 0; }

        /* Scrollbar delgada verde */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f0f4f8; }
        ::-webkit-scrollbar-thumb { background: #39A900; border-radius: 3px; }

        /* Pulse verde (punto de estado activo en el sidebar) */
        @keyframes pulse-green {
            0%   { box-shadow: 0 0 0 0 rgba(57,169,0,.7); }
            70%  { box-shadow: 0 0 0 8px rgba(57,169,0,0); }
            100% { box-shadow: 0 0 0 0 rgba(57,169,0,0); }
        }
        .pulse-green { animation: pulse-green 2.5s infinite; }

        /* Shimmer verde debajo del topbar */
        .shimmer-green {
            height: 3px;
            background: linear-gradient(90deg, #39A900, #62E31D, #39A900, #00324D, #39A900);
            background-size: 300% 100%;
            animation: shimmer 4s linear infinite;
        }
        @keyframes shimmer {
            0%   { background-position: 100%; }
            100% { background-position: -100%; }
        }
    </style>
</head>
<body class="antialiased">

{{-- Topbar del sistema (siempre visible) --}}
<header class="fixed top-0 left-0 right-0 z-50 h-[56px] flex items-center px-5 gap-4"
        style="background: rgba(0,26,41,0.97);
               border-bottom: 3px solid #39A900;
               box-shadow: 0 4px 20px rgba(0,0,0,0.35);">

    {{-- Logo --}}
    <a href="{{ route('sisgedi.index') }}"
       class="flex items-center gap-2.5 flex-shrink-0">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
             style="background:#39A900; box-shadow:0 3px 10px rgba(57,169,0,.45);">
            <i class="fas fa-folder-open text-white text-xs"></i>
        </div>
        <div class="hidden sm:block">
            <p class="text-white font-bold text-sm leading-none">SISGEDI</p>
            <p class="text-[10px] font-medium leading-none mt-0.5" style="color:#62E31D;">
                Gestión Documental
            </p>
        </div>
    </a>

    <div class="flex-1"></div>

    {{-- Flash messages en topbar (info) --}}
    @if(session('success'))
    <div id="flashMsg"
         class="hidden sm:flex items-center gap-2 text-xs font-semibold text-green-300
                bg-green-900/40 border border-green-700/50 px-3 py-1.5 rounded-lg">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Enlace a SICEFA --}}
    <a href="{{ route('home') }}"
       class="hidden md:flex items-center gap-1.5 text-xs font-medium
              text-white/60 hover:text-white transition-colors">
        <i class="fas fa-th-large text-[10px]"></i> SICEFA
    </a>

    {{-- Botón hamburger móvil --}}
    <button onclick="toggleDashboardSidebar()"
            class="lg:hidden p-1.5 rounded-lg text-white/70 hover:text-white
                   hover:bg-white/10 transition-colors">
        <i class="fas fa-bars text-base"></i>
    </button>
</header>

{{-- Shimmer debajo del topbar --}}
<div class="fixed top-[56px] left-0 right-0 z-40 shimmer-green"></div>

{{-- Espaciador --}}
<div style="height: 59px;"></div>

{{-- Contenido de la página --}}
@yield('content')

<!-- jQuery -->
<script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap (para modales si se necesitan) -->
<script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('AdminLTE/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(function () {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 4500,
    };

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
});

function toggleDashboardSidebar() {
    const sidebar = document.getElementById('dashboardSidebar');
    const overlay = document.getElementById('dashboardOverlay');
    if (sidebar) sidebar.classList.toggle('-translate-x-full');
    if (overlay) overlay.classList.toggle('hidden');
}
</script>

@yield('script')

</body>
</html>
