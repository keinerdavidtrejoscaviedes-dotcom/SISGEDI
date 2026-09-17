@extends('sisgedi::layouts.panel')

@section('titulo-topbar', 'Dashboard Principal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ── Encabezado ── --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <p class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-sena-green mb-1">
                <i class="fas fa-shield-halved"></i> Panel del Gerente Administrativo
            </p>
            <h1 class="text-2xl md:text-3xl font-extrabold" style="color:#001A29;">
                Cascada de tareas — {{ $fase->nombre }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Cargo: <strong>{{ $usuarioRol->cargo->nombre }}</strong>
                · Gerencia: <strong>{{ $usuarioRol->cargo->gerencia->nombre }}</strong>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('sisgedi.gerente.plan-trabajo.edit') }}"
               class="inline-flex items-center justify-center gap-2 text-sm font-semibold
                      px-5 py-3 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                <i class="fas fa-calendar-days"></i> Plan de trabajo de la fase
            </a>
            <a href="{{ route('sisgedi.gerente.tareas.create') }}"
               class="inline-flex items-center justify-center gap-2 text-white text-sm font-semibold
                      px-5 py-3 rounded-xl transition-all hover:scale-105"
               style="background:#39A900; box-shadow:0 4px 14px rgba(57,169,0,.4);">
                <i class="fas fa-plus"></i> Generar tarea con documento guía
            </a>
        </div>
    </div>

    {{-- ── KPI cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $kpis = [
                ['label' => 'Tareas generadas', 'valor' => $totalTareas, 'icon' => 'fa-diagram-project', 'color' => '#39A900'],
                ['label' => 'Asignadas / en curso', 'valor' => $porEstado['asignada'] + $porEstado['en_desarrollo'], 'icon' => 'fa-hourglass-half', 'color' => '#0EA5E9'],
                ['label' => 'En revisión', 'valor' => $porEstado['enviada'] + $porEstado['en_revision'], 'icon' => 'fa-magnifying-glass', 'color' => '#F59E0B'],
                ['label' => 'Aprobadas', 'valor' => $porEstado['aprobada'], 'icon' => 'fa-circle-check', 'color' => '#16A34A'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ $kpi['label'] }}</p>
                    <p class="text-3xl font-extrabold mt-1" style="color:#001A29;">{{ $kpi['valor'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: {{ $kpi['color'] }}1a; color: {{ $kpi['color'] }};">
                    <i class="fas {{ $kpi['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Distribución por estado ── --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="font-bold text-sm mb-4" style="color:#001A29;">
                <i class="fas fa-chart-pie text-sena-green mr-1"></i> Estado de la cascada
            </h2>
            @foreach($porEstado as $estado => $cantidad)
                @php
                    $pct = $totalTareas > 0 ? round(($cantidad / $totalTareas) * 100) : 0;
                @endphp
                <div class="mb-3">
                    <div class="flex justify-between items-center text-xs mb-1">
                        <span class="font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $estado) }}</span>
                        <span class="text-gray-400 font-bold">{{ $cantidad }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full" style="width: {{ $pct }}%; background:#39A900;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── Últimas tareas generadas ── --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-sm" style="color:#001A29;">
                    <i class="fas fa-clock-rotate-left text-sena-green mr-1"></i> Últimas tareas generadas hacia Gestores
                </h2>
                <a href="{{ route('sisgedi.gerente.tareas.index') }}"
                   class="text-xs font-semibold text-sena-green hover:underline">
                    Ver todas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-400 uppercase border-b">
                            <th class="py-2 pr-3">Título</th>
                            <th class="py-2 pr-3">Sector</th>
                            <th class="py-2 pr-3">Fecha límite</th>
                            <th class="py-2 pr-3">Doc. guía</th>
                            <th class="py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasTareas as $tarea)
                            @php
                                $badgeColor = match($tarea->estado) {
                                    'aprobada' => 'bg-green-100 text-green-700',
                                    'rechazada', 'vencida' => 'bg-red-100 text-red-700',
                                    'en_revision', 'enviada' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-sky-100 text-sky-700',
                                };
                            @endphp
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-3 pr-3 font-semibold text-gray-800">{{ Str::limit($tarea->titulo, 32) }}</td>
                                <td class="py-3 pr-3 text-gray-500">{{ $tarea->sector->nombre }}</td>
                                <td class="py-3 pr-3 text-gray-500">{{ $tarea->fecha_limite->format('d/m/Y') }}</td>
                                <td class="py-3 pr-3">
                                    @if($tarea->documentoGuia)
                                        <i class="fas fa-check-circle text-sena-green"></i>
                                    @else
                                        <i class="fas fa-xmark text-gray-300"></i>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                        {{ str_replace('_', ' ', $tarea->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-400 py-8">
                                    Aún no has generado tareas en esta fase.
                                    <a href="{{ route('sisgedi.gerente.tareas.create') }}" class="text-sena-green font-semibold hover:underline">
                                        Genera la primera
                                    </a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
