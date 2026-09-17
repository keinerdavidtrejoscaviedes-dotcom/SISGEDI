@extends('sisgedi::layouts.dashboard')

@section('title', 'Dashboard Principal')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    {{-- ══ SIDEBAR ══ --}}
    @include('sisgedi::layouts.partials.sidebar-dashboard')

    {{-- ══ ÁREA PRINCIPAL ══ --}}
    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        {{-- ── Topbar interior ── --}}
        <div class="flex items-center justify-between px-6 py-3 bg-white border-b border-gray-200">

            {{-- Hamburger móvil --}}
            <button onclick="toggleDashboardSidebar()"
                    class="lg:hidden p-1.5 rounded text-gray-500 hover:bg-gray-100">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Breadcrumb --}}
            <div class="text-sm text-gray-500">
                Inicio &rsaquo;
                <span class="text-gray-800 font-semibold ml-1">Dashboard Principal</span>
            </div>

            {{-- Acciones derecha --}}
            <div class="flex items-center gap-3">
                {{-- Campana --}}
                <div class="relative">
                    <button class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100">
                        <i class="fas fa-bell text-base"></i>
                        @if($convocatoriasAbiertas > 0)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 border border-white"></span>
                        @endif
                    </button>
                </div>
                {{-- Avatar usuario + botón logout --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                         style="background:linear-gradient(135deg,#39A900,#002336);">
                        {{ strtoupper(substr($usuario['nombre'] ?? 'US', 0, 2)) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-xs font-semibold text-gray-800 leading-none">
                            {{ ucwords(str_replace('.', ' ', $usuario['nombre'] ?? 'Usuario')) }}
                        </p>
                        <p class="text-[10px] text-gray-400 leading-none mt-0.5">
                            {{ $usuario['rol'] ?? 'Sin rol' }}
                        </p>
                    </div>
                    <form action="{{ route('sisgedi.logout') }}" method="POST" class="inline ml-1">
                        @csrf
                        <button type="submit" title="Cerrar sesión"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Cuerpo ── --}}
        <main class="flex-1 p-6 space-y-5" style="background:#f3f4f6;">

            {{-- Título adaptado al rol --}}
            <div>
                <p class="text-xs text-gray-500 mb-0.5">Inicio &rsaquo; {{ $usuario['rol'] ?? 'Dashboard' }}</p>
                <h1 class="text-xl font-bold text-gray-900">
                    @if(($usuario['id_rol'] ?? 0) == 1)
                        Panel de Gerencia General
                    @elseif(($usuario['id_rol'] ?? 0) == 2)
                        Panel de Gerencia Administrativa
                    @elseif(($usuario['id_rol'] ?? 0) == 3)
                        Panel de Gerencia de Producción
                    @elseif(($usuario['id_rol'] ?? 0) == 26)
                        Panel de Administración SISGEDI
                    @else
                        Dashboard — {{ $usuario['rol'] ?? 'Principal' }}
                    @endif
                </h1>
            </div>

            {{-- ══════════════════════════════════════════════
                 TARJETAS DE RESUMEN — 6 columnas
            ══════════════════════════════════════════════ --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">

                {{-- 1. Usuarios Registrados --}}
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-[11px] text-gray-500 font-medium leading-tight">Usuarios Registrados</p>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background:#EBF9EB;">
                            <i class="fas fa-users text-xs" style="color:#39A900;"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 counter" data-target="{{ $totalUsuarios }}">0</p>
                </div>

                {{-- 2. Sectores Activos --}}
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-[11px] text-gray-500 font-medium leading-tight">Sectores Activos</p>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background:#FFF8E1;">
                            <i class="fas fa-map-marker-alt text-xs" style="color:#F59E0B;"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 counter" data-target="{{ $totalSectores }}">0</p>
                </div>

                {{-- 3. Fase Vigente — dato real desde tabla `fase` --}}
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-[11px] text-gray-500 font-medium leading-tight">Fase Vigente</p>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background:#E8F5FF;">
                            <i class="fas fa-calendar-alt text-xs" style="color:#3B82F6;"></i>
                        </div>
                    </div>
                    <p class="text-sm font-black text-gray-900 leading-tight">
                        {{ $faseVigente->nombre_fase ?? 'Sin fase' }}
                    </p>
                </div>

                {{-- 4. Convocatorias Abiertas --}}
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-[11px] text-gray-500 font-medium leading-tight">Convocatorias Abiertas</p>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background:#EEF2FF;">
                            <i class="fas fa-bullhorn text-xs" style="color:#6366F1;"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 counter" data-target="{{ $convocatoriasAbiertas }}">0</p>
                </div>

                {{-- 5. Documentos Pendientes (listado_maestro_documento) --}}
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-[11px] text-gray-500 font-medium leading-tight">Docs. Pendientes</p>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background:#FFF1F0;">
                            <i class="fas fa-file-alt text-xs" style="color:#EF4444;"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-900 counter" data-target="{{ $evidenciasPendientes }}">0</p>
                </div>

                {{-- 6. Paz y Salvo Completos --}}
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-[11px] text-gray-500 font-medium leading-tight">Paz y Salvo Completos</p>
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background:#ECFDF5;">
                            <i class="fas fa-check-circle text-xs" style="color:#10B981;"></i>
                        </div>
                    </div>
                    @if($pazYSalvoTotal > 0)
                    <p class="text-base font-black text-gray-900">
                        {{ $pazYSalvoCompletos }}/<span class="text-gray-400">{{ $pazYSalvoTotal }}</span>
                    </p>
                    @else
                    <p class="text-sm font-semibold text-gray-400">Sin datos</p>
                    @endif
                </div>

            </div>

            {{-- ══════════════════════════════════════════════
                 FILA MEDIA — Actividad Reciente + Progreso Fase
            ══════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- ── Actividad Reciente — datos reales de BD ── --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100">
                        <i class="fas fa-chart-line text-green-500 text-sm"></i>
                        <h2 class="font-bold text-gray-800 text-sm">Actividad Reciente</h2>
                    </div>
                    <ul class="divide-y divide-gray-50">
                        @forelse($actividadReciente as $act)
                        <li class="flex items-start gap-3 px-5 py-3 hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                                 style="background:{{ $act['bg'] }};">
                                <i class="{{ $act['icon'] }} text-xs" style="color:{{ $act['color'] }};"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-800 leading-snug">{{ $act['texto'] }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $act['tiempo'] }}</p>
                            </div>
                        </li>
                        @empty
                        <li class="px-5 py-6 text-center">
                            <i class="fas fa-inbox text-gray-300 text-2xl mb-2 block"></i>
                            <p class="text-xs text-gray-400">No hay actividad registrada aún.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>

                {{-- ── Progreso de Fase Actual — solo fase vigente de la BD ── --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-3.5 border-b border-gray-100">
                        <i class="fas fa-sun text-amber-400 text-sm"></i>
                        <h2 class="font-bold text-gray-800 text-sm">Fase Actual</h2>
                    </div>
                    <div class="px-5 py-5 space-y-5">

                        @if($faseVigente)
                        {{-- Nombre + badge "En Curso" --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if(stripos($faseVigente->nombre_fase, 'sol') !== false)
                                    <i class="fas fa-sun text-amber-400 text-xs"></i>
                                @elseif(stripos($faseVigente->nombre_fase, 'luna') !== false)
                                    <i class="fas fa-moon text-indigo-400 text-xs"></i>
                                @else
                                    <i class="fas fa-calendar-check text-green-500 text-xs"></i>
                                @endif
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ $faseVigente->nombre_fase }}
                                </span>
                            </div>
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full text-white"
                                  style="background:#39A900;">En Curso</span>
                        </div>

                        {{-- Descripción si existe --}}
                        @if(!empty($faseVigente->descripcion))
                        <p class="text-xs text-gray-500 leading-relaxed -mt-2">
                            {{ $faseVigente->descripcion }}
                        </p>
                        @endif

                        {{-- Barra de progreso basada en convocatorias cerradas --}}
                        <div>
                            <div class="h-3 rounded-full bg-gray-200 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-1000"
                                     style="width:0%; background: linear-gradient(90deg,#39A900,#62E31D);"
                                     data-width="{{ $progresoFase }}%"
                                     id="barFase"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1.5">
                                @if($progresoFase > 0)
                                    {{ $progresoFase }}% de convocatorias completadas en esta fase
                                @else
                                    Fase activa — sin convocatorias cerradas aún
                                @endif
                            </p>
                        </div>

                        @else
                        {{-- Sin fase registrada --}}
                        <div class="text-center py-6">
                            <i class="fas fa-calendar-times text-gray-300 text-3xl mb-3 block"></i>
                            <p class="text-sm font-semibold text-gray-500 mb-1">Sin fase registrada</p>
                            <p class="text-xs text-gray-400">
                                Debes crear una fase desde
                                <a href="{{ route('sisgedi.fases.create') }}"
                                   class="text-green-600 font-semibold hover:underline">
                                    Gestión de Fases
                                </a>
                                antes de crear convocatorias.
                            </p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 GRÁFICO DOCUMENTOS POR SECTOR — datos reales
            ══════════════════════════════════════════════ --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Documentos por Sector</h2>
                    <span class="text-[11px] text-gray-400">Listado Maestro Documental</span>
                </div>

                <div class="px-5 py-5">
                    @php
                        // Filtrar sectores que tengan al menos 1 documento o mostrar todos si no hay datos
                        $sectoresConDatos = array_filter($documentosPorSector, fn($s) => $s['total'] > 0);
                        $mostrarSectores  = !empty($sectoresConDatos) ? $sectoresConDatos : $documentosPorSector;
                    @endphp

                    @if(!empty($mostrarSectores))
                    <div class="space-y-2.5" id="chartSectores">
                        @foreach($mostrarSectores as $s)
                        @php
                            $suma = $s['aprobadas'] + $s['rechazadas'] + $s['pendientes'];
                            // Si no hay documentos, mostrar barra vacía con indicador
                            $pctAprobadas  = $suma > 0 ? round($s['aprobadas']  / $suma * 100) : 0;
                            $pctRechazadas = $suma > 0 ? round($s['rechazadas'] / $suma * 100) : 0;
                            $pctPendientes = $suma > 0 ? round($s['pendientes'] / $suma * 100) : 0;
                            // Si no hay docs, la barra aparece gris (pendiente 100%)
                            if ($suma === 0) { $pctPendientes = 100; }
                        @endphp
                        <div class="flex items-center gap-3">
                            {{-- Nombre del sector --}}
                            <span class="text-xs text-gray-600 text-right flex-shrink-0 truncate"
                                  style="width:150px;" title="{{ $s['nombre'] }}">
                                {{ $s['nombre'] }}
                            </span>
                            {{-- Barra compuesta --}}
                            <div class="flex-1 flex h-5 rounded overflow-hidden bg-gray-100 gap-px">
                                @if($pctAprobadas > 0)
                                <div class="bar-seg h-full {{ $pctRechazadas === 0 && $pctPendientes === 0 ? 'rounded' : 'rounded-l' }} transition-all duration-700"
                                     style="width:0%; background:#22C55E;"
                                     data-width="{{ $pctAprobadas }}%"></div>
                                @endif
                                @if($pctRechazadas > 0)
                                <div class="bar-seg h-full transition-all duration-700"
                                     style="width:0%; background:#F87171;"
                                     data-width="{{ $pctRechazadas }}%"></div>
                                @endif
                                @if($pctPendientes > 0)
                                <div class="bar-seg h-full {{ $pctAprobadas === 0 && $pctRechazadas === 0 ? 'rounded' : 'rounded-r' }} transition-all duration-700"
                                     style="width:0%; background:{{ $suma === 0 ? '#E5E7EB' : '#FBBF24' }};"
                                     data-width="{{ $pctPendientes }}%"></div>
                                @endif
                            </div>
                            {{-- Total --}}
                            <span class="text-xs font-bold flex-shrink-0 w-6 text-right
                                         {{ $s['total'] > 0 ? 'text-gray-700' : 'text-gray-300' }}">
                                {{ $s['total'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Leyenda --}}
                    <div class="flex items-center gap-6 mt-5 justify-center flex-wrap">
                        <span class="flex items-center gap-1.5 text-xs text-gray-600">
                            <span class="w-3 h-3 rounded-sm inline-block" style="background:#22C55E;"></span>
                            Con archivo
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-gray-600">
                            <span class="w-3 h-3 rounded-sm inline-block" style="background:#F87171;"></span>
                            Rechazados
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-gray-600">
                            <span class="w-3 h-3 rounded-sm inline-block" style="background:#FBBF24;"></span>
                            Sin archivo
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-gray-400">
                            <span class="w-3 h-3 rounded-sm inline-block" style="background:#E5E7EB;"></span>
                            Sin documentos
                        </span>
                    </div>

                    @if(empty($sectoresConDatos))
                    <p class="text-center text-xs text-gray-400 mt-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        No hay documentos cargados en el listado maestro aún. El gráfico mostrará datos reales cuando se registren documentos.
                    </p>
                    @endif

                    @else
                    {{-- Sin sectores en BD --}}
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-gray-200 text-3xl mb-3 block"></i>
                        <p class="text-sm text-gray-400">No hay sectores registrados en el sistema.</p>
                    </div>
                    @endif
                </div>
            </div>

        </main>
    </div>{{-- fin área principal --}}
</div>{{-- fin flex --}}
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Contadores animados
    document.querySelectorAll('.counter').forEach(function (el) {
        var target = parseInt(el.dataset.target) || 0;
        if (target === 0) { el.textContent = '0'; return; }
        var current = 0;
        var step = Math.max(1, Math.ceil(target / 40));
        var t = setInterval(function () {
            current = Math.min(current + step, target);
            el.textContent = current;
            if (current >= target) clearInterval(t);
        }, 30);
    });

    // Barras animadas con pequeño delay para que se vea la transición
    setTimeout(function () {
        // Barra de progreso de fase
        var bf = document.getElementById('barFase');
        if (bf) bf.style.width = (bf.dataset.width || '0%');

        // Barras del gráfico por sector
        document.querySelectorAll('.bar-seg').forEach(function (el) {
            el.style.width = el.dataset.width;
        });
    }, 250);
});
</script>
@endsection
