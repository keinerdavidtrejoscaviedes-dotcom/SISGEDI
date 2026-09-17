{{-- Barra superior del panel operativo --}}
<header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-6 flex-shrink-0">
    <div>
        <p class="text-xs text-gray-400">Inicio</p>
        <p class="text-sm font-semibold text-gray-700">@yield('titulo-topbar', 'Panel')</p>
    </div>

    <div class="flex items-center gap-4">
        <button class="relative w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-50" title="Notificaciones">
            <i class="fas fa-bell"></i>
            <span class="absolute top-1.5 right-2 w-1.5 h-1.5 rounded-full bg-red-500"></span>
        </button>

        @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background: linear-gradient(135deg,#39A900,#001A29);">
                        {{ Auth::user()->initials }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800 leading-none">{{ Auth::user()->full_name }}</p>
                        <p class="text-xs text-sena-green leading-none mt-1">Ver perfil</p>
                    </div>
                </button>
                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                    <div class="px-4 py-2 border-b border-gray-50">
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('panel-logout-form').submit();"
                       class="flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt text-xs"></i> Cerrar sesión
                    </a>
                </div>
            </div>
            <form id="panel-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        @endauth
    </div>
</header>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
