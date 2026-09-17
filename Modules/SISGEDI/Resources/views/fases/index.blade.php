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

            {{-- Aviso: fase vigente bloquea la creación de nuevas fases --}}
            @if($faseActiva)
            <div class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-800 flex items-center gap-2">
                <i class="fas fa-lock text-amber-600"></i>
                <div>
                    La fase <strong>"{{ $faseActiva->nombre_fase }}"</strong> está activa
                    @if($faseActiva->fecha_fin)
                        hasta el <strong>{{ $faseActiva->fecha_fin }}</strong>.
                    @else
                        actualmente.
                    @endif
                    No se puede crear una nueva fase mientras esta siga activa; podrás crear una nueva
                    en cuanto finalice.
                </div>
            </div>
            @endif

            {{-- Botón Crear Fase --}}
            <div class="flex items-center justify-between mb-5">
                <div class="text-xs text-gray-400">
                    {{ $fases->count() }} {{ $fases->count() === 1 ? 'fase registrada' : 'fases registradas' }}
                </div>
                @unless($faseActiva)
                <a href="{{ route('sisgedi.fases.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold
                          rounded-lg transition-all hover:opacity-90 shadow-sm"
                   style="background:#39A900;">
                    <i class="fas fa-plus text-xs"></i> Crear Fase
                </a>
                @endunless
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
                                'activa',
                                'en curso'  => ['bg' => '#DCFCE7', 'color' => '#15803D', 'label' => 'Activa'],
                                'finalizada'=> ['bg' => '#F3F4F6', 'color' => '#374151', 'label' => 'Finalizada'],
                                'pendiente' => ['bg' => '#FEF9C3', 'color' => '#92400E', 'label' => 'Pendiente'],
                                'cerrada'   => ['bg' => '#FEE2E2', 'color' => '#991B1B', 'label' => 'Cerrada'],
                                default     => ['bg' => '#EEF2FF', 'color' => '#4338CA', 'label' => ucfirst($fase->estado ?? 'Pendiente')],
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
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    {{-- Ver (placeholder) --}}
                                    <button type="button"
                                            title="Ver detalle"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                                                   text-gray-500 bg-gray-50 border border-gray-200
                                                   hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    {{-- Editar --}}
                                    <a href="{{ route('sisgedi.fases.edit', $fase->fase_id) }}"
                                       title="Editar fase"
                                       class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                                              text-blue-600 bg-blue-50 border border-blue-100
                                              hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    {{-- Desactivar: solo visible si esta fase está Activa --}}
                                    @if(strtolower($fase->estado ?? '') === 'activa')
                                    <button type="button"
                                            onclick="confirmarDesactivarFase({{ $fase->fase_id }}, '{{ addslashes($fase->nombre_fase) }}')"
                                            title="Desactivar fase"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                                                   text-amber-600 bg-amber-50 border border-amber-100
                                                   hover:bg-amber-100 transition-colors">
                                        <i class="fas fa-power-off text-xs"></i>
                                    </button>
                                    @endif
                                    {{-- Eliminar --}}
                                    <button type="button"
                                            onclick="confirmarEliminarFase({{ $fase->fase_id }}, '{{ addslashes($fase->nombre_fase) }}')"
                                            title="Eliminar fase"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                                                   text-red-600 bg-red-50 border border-red-100
                                                   hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
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
                          style="background:#39A900;">Activa</span>
                </div>
                @endif

            </div>{{-- fin tabla --}}

        </main>
    </div>
</div>

{{-- Form único para eliminar — fuera del loop --}}
<form id="formEliminarFase" action="" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- Form único para desactivar — fuera del loop --}}
<form id="formDesactivarFase" action="" method="POST" style="display:none;">
    @csrf
</form>
@endsection

@section('script')
<script>
function confirmarEliminarFase(id, nombre) {
    Swal.fire({
        title: 'Eliminar fase',
        html: '¿Seguro que deseas eliminar <strong class="text-gray-800">"' + nombre + '"</strong>?' +
              '<div class="mt-3 flex items-center gap-2 justify-center text-xs font-semibold text-red-500 bg-red-50 border border-red-100 rounded-lg py-2 px-3">' +
              '<i class="fas fa-triangle-exclamation"></i> Esta acción no se puede deshacer</div>',
        icon: 'warning',
        iconHtml: '<i class="fas fa-trash"></i>',
        iconColor: '#DC2626',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash text-xs"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-2xl !shadow-2xl',
            icon: '!border-red-100 !text-red-500',
            title: '!text-lg !font-bold !text-gray-800 !pt-1',
            htmlContainer: '!text-sm !text-gray-500 !mt-1',
            actions: '!gap-2 !mt-5',
            confirmButton: 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 shadow-sm transition-colors',
            cancelButton: 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors',
        },
    }).then(function (result) {
        if (result.isConfirmed) {
            var form = document.getElementById('formEliminarFase');
            form.action = '/sisgedi/fases/' + id;
            form.submit();
        }
    });
}

function confirmarDesactivarFase(id, nombre) {
    Swal.fire({
        title: 'Desactivar fase',
        html: 'Se cerrará <strong class="text-gray-800">"' + nombre + '"</strong> antes de su fecha de fin.' +
              '<div class="mt-3 flex items-center gap-2 justify-center text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-100 rounded-lg py-2 px-3">' +
              '<i class="fas fa-circle-check"></i> Podrás crear una nueva fase de inmediato</div>',
        icon: 'question',
        iconHtml: '<i class="fas fa-power-off"></i>',
        iconColor: '#D97706',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-power-off text-xs"></i> Sí, desactivar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        focusCancel: true,
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-2xl !shadow-2xl',
            icon: '!border-amber-100 !text-amber-500',
            title: '!text-lg !font-bold !text-gray-800 !pt-1',
            htmlContainer: '!text-sm !text-gray-500 !mt-1',
            actions: '!gap-2 !mt-5',
            confirmButton: 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 shadow-sm transition-colors',
            cancelButton: 'inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors',
        },
    }).then(function (result) {
        if (result.isConfirmed) {
            var form = document.getElementById('formDesactivarFase');
            form.action = '/sisgedi/fases/' + id + '/desactivar';
            form.submit();
        }
    });
}
</script>
@endsection
