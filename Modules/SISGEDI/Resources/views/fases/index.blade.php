@extends('sisgedi::layouts.dashboard')
@section('title', 'Gestión de Fases')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        {{-- Topbar interior --}}
        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label' => 'Inicio', 'url' => route('sisgedi.dashboard')],
                ['label' => 'Gestión de Fases', 'url' => null],
            ],
            'titulo' => 'Listado de Fases',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-sm text-green-800 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-800">
                <div class="font-bold flex items-center gap-2 mb-1">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    <span>Error:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Botón Crear Fase --}}
            <div class="flex items-center justify-between mb-5">
                <div class="text-xs text-gray-400">
                    {{ $fases->count() }} {{ $fases->count() === 1 ? 'fase registrada' : 'fases registradas' }}
                </div>
                <a href="{{ route('sisgedi.fases.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold
                          rounded-lg transition-all hover:opacity-90 shadow-sm"
                   style="background:#39A900;">
                    <i class="fas fa-plus text-xs"></i> Crear Fase
                </a>
            </div>

            {{-- Tabla de Fases --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

                @if($fases->isEmpty())
                {{-- Estado vacío --}}
                <div class="text-center py-20">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                         style="background:#EBF9EB;">
                        <i class="fas fa-calendar-alt text-2xl" style="color:#39A900;"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">No hay fases registradas</h3>
                    <p class="text-xs text-gray-400 mb-5">Crea la primera fase del sistema para comenzar.</p>
                    <a href="{{ route('sisgedi.fases.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm font-semibold
                              rounded-lg transition-all hover:opacity-90"
                       style="background:#39A900;">
                        <i class="fas fa-plus text-xs"></i> Crear Primera Fase
                    </a>
                </div>

                @else
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#F9FAFB; border-bottom:1px solid #F3F4F6;">
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nombre</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipo</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Fecha Inicio</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Fecha Fin</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Sectores</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($fases as $fase)
                        @php
                            $estadoBadge = match(strtolower($fase->estado ?? '')) {
                                'en curso'  => ['bg' => '#DCFCE7', 'color' => '#15803D', 'label' => 'En Curso'],
                                'finalizada'=> ['bg' => '#F3F4F6', 'color' => '#374151', 'label' => 'Finalizada'],
                                'pendiente' => ['bg' => '#FEF9C3', 'color' => '#92400E', 'label' => 'Pendiente'],
                                'cerrada'   => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'label' => 'Cerrada'],
                                default     => ['bg' => '#EEF2FF', 'color' => '#4338CA', 'label' => ucfirst($fase->estado ?? 'En Curso')],
                            };
                            $esSol = stripos($fase->nombre_fase, 'sol') !== false
                                  || strtolower($fase->tipo ?? '') === 'sol';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Nombre --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    @if($esSol)
                                        <span class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"
                                              style="background:#FFF8E1;">
                                            <i class="fas fa-sun text-[10px]" style="color:#F59E0B;"></i>
                                        </span>
                                    @else
                                        <span class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"
                                              style="background:#EEF2FF;">
                                            <i class="fas fa-moon text-[10px]" style="color:#6366F1;"></i>
                                        </span>
                                    @endif
                                    <span class="font-semibold text-gray-800 text-sm">{{ $fase->nombre_fase }}</span>
                                </div>
                            </td>

                            {{-- Tipo --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                    @if($esSol)
                                        <i class="fas fa-sun text-amber-400 text-[10px]"></i>
                                        <span>Sol</span>
                                    @else
                                        <i class="fas fa-moon text-indigo-400 text-[10px]"></i>
                                        <span>Luna</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Fecha Inicio --}}
                            <td class="px-4 py-3.5 text-xs text-gray-600">
                                {{ $fase->fecha_inicio ?? '—' }}
                            </td>

                            {{-- Fecha Fin --}}
                            <td class="px-4 py-3.5 text-xs text-gray-600">
                                {{ $fase->fecha_fin ?? '—' }}
                            </td>

                            {{-- Estado --}}
                            <td class="px-4 py-3.5">
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full"
                                      style="background:{{ $estadoBadge['bg'] }}; color:{{ $estadoBadge['color'] }};">
                                    {{ $estadoBadge['label'] }}
                                </span>
                            </td>

                            {{-- Sectores --}}
                            <td class="px-4 py-3.5 text-xs text-gray-500">
                                {{ $fase->convocatorias > 0 ? $fase->convocatorias . ' convocatoria(s)' : 'Sin asignar' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Ver (placeholder) --}}
                                    <button type="button"
                                            title="Ver detalle"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center
                                                   text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    {{-- Editar --}}
                                    <a href="{{ route('sisgedi.fases.edit', $fase->fase_id) }}"
                                       title="Editar fase"
                                       class="w-7 h-7 rounded-lg flex items-center justify-center
                                              text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Nota de transición --}}
                <div class="px-5 py-3 border-t border-gray-50 flex items-center gap-2 text-xs text-gray-400">
                    <span>Transición entre fases</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                    <span class="font-bold px-2 py-0.5 rounded-full text-white text-[10px]"
                          style="background:#39A900;">En Curso</span>
                </div>
                @endif

            </div>{{-- fin tabla --}}

        </main>
    </div>
</div>
@endsection
