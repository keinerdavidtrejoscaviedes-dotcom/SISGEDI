{{-- Topbar interior reutilizable para todas las vistas del módulo --}}
@php $sesion = session('sisgedi_user'); @endphp

<div class="flex items-center justify-between px-6 py-3 bg-white border-b border-gray-200">

    <button onclick="toggleDashboardSidebar()"
            class="lg:hidden p-1.5 rounded text-gray-500 hover:bg-gray-100 mr-2">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="text-sm text-gray-500 flex items-center gap-1 flex-1 min-w-0 flex-wrap">
        @foreach($breadcrumb as $i => $crumb)
            @if($i > 0)<span class="text-gray-300 mx-1">&rsaquo;</span>@endif
            @if($crumb['url'])
                <a href="{{ $crumb['url'] }}" class="hover:text-gray-700 transition-colors">
                    {{ $crumb['label'] }}
                </a>
            @else
                <span class="text-gray-800 font-semibold">{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    </div>

    {{-- Acciones derecha --}}
    <div class="flex items-center gap-3 flex-shrink-0">
        <div class="relative">
            <button class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100">
                <i class="fas fa-bell text-base"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 border border-white"></span>
            </button>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                 style="background:linear-gradient(135deg,#39A900,#002336);">
                {{ strtoupper(substr($sesion['nombre'] ?? 'YI', 0, 2)) }}
            </div>
            <div class="hidden sm:block">
                <p class="text-xs font-semibold text-gray-800 leading-none">
                    {{ ucwords(str_replace('.', ' ', $sesion['nombre'] ?? 'Yina')) }}
                </p>
                <p class="text-[10px] text-gray-400 leading-none mt-0.5">
                    {{ $sesion['rol'] ?? 'Administrador' }}
                </p>
            </div>
        </div>
    </div>

</div>

{{-- Título de la página --}}
<div class="px-6 py-4 bg-white border-b border-gray-100">
    <p class="text-xs text-gray-400 mb-0.5">
        @foreach($breadcrumb as $i => $crumb)
            @if($i > 0) &rsaquo; @endif {{ $crumb['label'] }}
        @endforeach
    </p>
    <h1 class="text-xl font-bold text-gray-900">{{ $titulo }}</h1>
</div>
