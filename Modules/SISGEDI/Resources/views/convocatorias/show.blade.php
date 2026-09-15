@extends('sisgedi::layouts.dashboard')
@section('title', 'Detalle de Convocatoria')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label' => 'Inicio',                       'url' => route('sisgedi.dashboard')],
                ['label' => 'Convocatorias y Selección',    'url' => route('sisgedi.convocatorias.index')],
                ['label' => $conv->titulo,                  'url' => null],
            ],
            'titulo' => 'Detalle de Convocatoria',
        ])

        <main class="flex-1 p-6 space-y-5" style="background:#f3f4f6;">

            {{-- ── Cabecera de la convocatoria ── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @php
                                $estadoMap = [
                                    'abierta'    => ['bg'=>'#DCFCE7','color'=>'#15803D','label'=>'Abierta'],
                                    'cerrada'    => ['bg'=>'#FEE2E2','color'=>'#991B1B','label'=>'Cerrada'],
                                    'finalizada' => ['bg'=>'#F3F4F6','color'=>'#374151','label'=>'Finalizada'],
                                ];
                                $badge = $estadoMap[strtolower($conv->estado)] ?? ['bg'=>'#F3F4F6','color'=>'#374151','label'=>ucfirst($conv->estado)];
                            @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                  style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }};">
                                {{ $badge['label'] }}
                            </span>
                            @if($fase)
                            <span class="text-xs text-gray-400 font-medium">
                                · {{ $fase->nombre_fase }}
                            </span>
                            @endif
                        </div>
                        <h1 class="text-xl font-extrabold text-gray-900 leading-tight mb-2">
                            {{ $conv->titulo }}
                        </h1>
                        @if($conv->descripcion)
                        <p class="text-sm text-gray-600 mb-3">{{ $conv->descripcion }}</p>
                        @endif
                        <div class="flex flex-wrap gap-5 text-xs text-gray-500">
                            <span>
                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                Apertura: <strong class="text-gray-700">{{ $conv->fecha_apertura }}</strong>
                            </span>
                            <span>
                                <i class="fas fa-calendar-times mr-1 text-gray-400"></i>
                                Cierre: <strong class="text-gray-700">{{ $conv->fecha_cierre }}</strong>
                            </span>
                            <span>
                                <i class="fas fa-users mr-1 text-gray-400"></i>
                                <strong class="text-gray-700">{{ $postulaciones->count() }}</strong>
                                postulante{{ $postulaciones->count() !== 1 ? 's' : '' }}
                            </span>
                            <span>
                                <i class="fas fa-briefcase mr-1 text-gray-400"></i>
                                <strong class="text-gray-700">{{ $cargos->count() }}</strong>
                                cargo{{ $cargos->count() !== 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('sisgedi.convocatorias.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold
                              text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50
                              transition-all flex-shrink-0">
                        <i class="fas fa-arrow-left text-xs"></i> Volver
                    </a>
                </div>
            </div>

            {{-- ── Cargos de la convocatoria ── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-briefcase text-green-600 text-sm"></i>
                        <h2 class="font-bold text-gray-800 text-sm">
                            Cargos Disponibles
                        </h2>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-green-50 text-green-700">
                            {{ $cargos->count() }}
                        </span>
                    </div>
                </div>

                @if($cargos->isEmpty())
                <div class="text-center py-10">
                    <i class="fas fa-briefcase text-gray-200 text-3xl mb-3 block"></i>
                    <p class="text-sm text-gray-400">Esta convocatoria no tiene cargos asignados aún.</p>
                </div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($cargos as $cargo)
                    <div class="flex items-start gap-4 px-5 py-4 hover:bg-gray-50 transition-colors">
                        {{-- Ícono --}}
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background:#EBF9EB;">
                            <i class="fas fa-user-tie text-xs" style="color:#39A900;"></i>
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-bold text-gray-800 text-sm">{{ $cargo->nombre_cargo }}</p>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">
                                    {{ $cargo->cupos }} cupo{{ $cargo->cupos != 1 ? 's' : '' }}
                                </span>
                            </div>
                            @if($cargo->titulacion_requerida)
                            <p class="text-xs text-gray-500 mt-0.5">
                                <span class="font-semibold">Titulación:</span> {{ $cargo->titulacion_requerida }}
                            </p>
                            @endif
                            @if($cargo->competencias_minimas)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $cargo->competencias_minimas }}</p>
                            @endif
                            @if($cargo->documentos_obligatorios)
                            <p class="text-xs text-gray-400 mt-0.5">
                                <i class="fas fa-paperclip mr-1"></i>{{ $cargo->documentos_obligatorios }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ── Postulantes y sus 3 opciones ── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-indigo-500 text-sm"></i>
                        <h2 class="font-bold text-gray-800 text-sm">Postulantes</h2>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">
                            {{ $postulaciones->count() }}
                        </span>
                    </div>
                </div>

                @if($postulaciones->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-user-clock text-gray-200 text-3xl mb-3 block"></i>
                    <p class="text-sm font-semibold text-gray-500 mb-1">Sin postulantes aún</p>
                    <p class="text-xs text-gray-400">
                        Cuando los aprendices se postulen, verás aquí sus 3 opciones de cargo.
                    </p>
                </div>
                @else

                {{-- Tabla de postulantes --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Aprendiz
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <span class="flex items-center justify-center gap-1">
                                        <span class="w-5 h-5 rounded-full text-white text-[10px] font-black flex items-center justify-center"
                                              style="background:#39A900;">1</span>
                                        Primera opción
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <span class="flex items-center justify-center gap-1">
                                        <span class="w-5 h-5 rounded-full text-white text-[10px] font-black flex items-center justify-center"
                                              style="background:#3B82F6;">2</span>
                                        Segunda opción
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <span class="flex items-center justify-center gap-1">
                                        <span class="w-5 h-5 rounded-full text-white text-[10px] font-black flex items-center justify-center"
                                              style="background:#8B5CF6;">3</span>
                                        Tercera opción
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Fecha
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($postulaciones as $post)
                            @php
                                $op1 = $post->opciones->firstWhere('orden_preferencia', 1);
                                $op2 = $post->opciones->firstWhere('orden_preferencia', 2);
                                $op3 = $post->opciones->firstWhere('orden_preferencia', 3);

                                $estadoPostMap = [
                                    'postulado'       => ['bg'=>'#EFF6FF','color'=>'#1D4ED8','label'=>'Postulado'],
                                    'no_evaluado'     => ['bg'=>'#F3F4F6','color'=>'#374151','label'=>'Sin evaluar'],
                                    'seleccionado'    => ['bg'=>'#DCFCE7','color'=>'#15803D','label'=>'Seleccionado'],
                                    'no_seleccionado' => ['bg'=>'#FEE2E2','color'=>'#991B1B','label'=>'No seleccionado'],
                                    'reasignado'      => ['bg'=>'#FEF9C3','color'=>'#92400E','label'=>'Reasignado'],
                                ];
                                $bp = $estadoPostMap[$post->estado] ?? ['bg'=>'#F3F4F6','color'=>'#374151','label'=>ucfirst($post->estado)];
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">

                                {{-- Aprendiz --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                    text-white text-xs font-bold flex-shrink-0"
                                             style="background:linear-gradient(135deg,#39A900,#002336);">
                                            {{ strtoupper(substr($post->aprendiz_nombre, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-800 text-sm leading-none">
                                                {{ $post->aprendiz_nombre }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $post->aprendiz_correo }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Opción 1 --}}
                                <td class="px-4 py-4">
                                    @if($op1)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold
                                                 text-green-800 bg-green-50 border border-green-200
                                                 px-2.5 py-1 rounded-full">
                                        <span class="w-4 h-4 rounded-full bg-green-600 text-white
                                                     text-[9px] font-black flex items-center justify-center flex-shrink-0">1</span>
                                        {{ $op1->nombre_cargo }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Opción 2 --}}
                                <td class="px-4 py-4">
                                    @if($op2)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold
                                                 text-blue-800 bg-blue-50 border border-blue-200
                                                 px-2.5 py-1 rounded-full">
                                        <span class="w-4 h-4 rounded-full bg-blue-600 text-white
                                                     text-[9px] font-black flex items-center justify-center flex-shrink-0">2</span>
                                        {{ $op2->nombre_cargo }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Opción 3 --}}
                                <td class="px-4 py-4">
                                    @if($op3)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold
                                                 text-purple-800 bg-purple-50 border border-purple-200
                                                 px-2.5 py-1 rounded-full">
                                        <span class="w-4 h-4 rounded-full bg-purple-600 text-white
                                                     text-[9px] font-black flex items-center justify-center flex-shrink-0">3</span>
                                        {{ $op3->nombre_cargo }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Fecha --}}
                                <td class="px-4 py-4 text-center">
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($post->fecha_postulacion)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-[10px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($post->fecha_postulacion)->format('H:i') }}
                                    </p>
                                </td>

                                {{-- Estado postulación --}}
                                <td class="px-4 py-4 text-center">
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full"
                                          style="background:{{ $bp['bg'] }}; color:{{ $bp['color'] }};">
                                        {{ $bp['label'] }}
                                    </span>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

        </main>
    </div>
</div>
@endsection
