@extends('sisgedi::layouts.dashboard')
@section('title', 'Realizar Entrevistas')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label'=>'Inicio','url'=>route('sisgedi.dashboard')],
                ['label'=>'Realizar Entrevistas','url'=>null],
            ],
            'titulo' => 'Postulantes para Entrevistar',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700
                            text-sm px-4 py-3 rounded-xl mb-5">
                    <i class="fas fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700
                            text-sm px-4 py-3 rounded-xl mb-5">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Contadores rápidos --}}
            @php
                $pendientes   = $postulaciones->whereNull('entrevista_id')->count();
                $completadas  = $postulaciones->whereNotNull('entrevista_id')->count();
                $total        = $postulaciones->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:rgba(57,169,0,0.12);">
                        <i class="fas fa-users text-sm" style="color:#39A900;"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-800">{{ $total }}</p>
                        <p class="text-xs text-gray-400">Total postulantes</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:rgba(245,158,11,0.12);">
                        <i class="fas fa-clock text-sm text-amber-500"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-800">{{ $pendientes }}</p>
                        <p class="text-xs text-gray-400">Pendientes de entrevista</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:rgba(16,185,129,0.12);">
                        <i class="fas fa-check-double text-sm text-emerald-500"></i>
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-800">{{ $completadas }}</p>
                        <p class="text-xs text-gray-400">Entrevistados</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800 text-sm">Listado de Postulantes</h2>
                    <span class="text-xs text-gray-400">Todos los postulantes registrados</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid #f0f0f0;">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Aprendiz</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Convocatoria</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Fecha Postulación</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Estado Entrevista</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($postulaciones as $p)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $p->postulacion_id }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                             style="background:#39A900;">
                                            {{ strtoupper(substr($p->aprendiz_nombre ?? 'A', 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-xs">{{ $p->aprendiz_nombre }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $p->aprendiz_correo }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600 max-w-[200px] truncate">
                                    {{ $p->convocatoria_titulo }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($p->fecha_postulacion)->format('d/m/Y g:i A') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($p->entrevista_id)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                              style="background:#DCFCE7; color:#16A34A;">
                                            <i class="fas fa-check-circle text-[9px]"></i> Entrevistado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                              style="background:#FEF3C7; color:#D97706;">
                                            <i class="fas fa-clock text-[9px]"></i> Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if(!$p->entrevista_id)
                                        <a href="{{ route('sisgedi.entrevistas.create', $p->postulacion_id) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold transition-all hover:opacity-90 shadow-sm"
                                           style="background:#39A900;">
                                            <i class="fas fa-clipboard-check text-[10px]"></i> Evaluar
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-semibold"
                                              style="background:#f3f4f6; color:#9ca3af;">
                                            <i class="fas fa-check text-[10px]"></i> Completado
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-14 h-14 rounded-full flex items-center justify-center mb-1"
                                             style="background:#f3f4f6;">
                                            <i class="fas fa-user-slash text-xl text-gray-300"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-400">No hay postulantes registrados</p>
                                        <p class="text-xs text-gray-300">Los postulantes aparecerán aquí una vez que apliquen a una convocatoria.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection
