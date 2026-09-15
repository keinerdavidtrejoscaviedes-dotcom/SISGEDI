@extends('sisgedi::layouts.dashboard')
@section('title', 'Listado de Convocatorias')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        {{-- Topbar interior --}}
        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [['label'=>'Inicio','url'=>route('sisgedi.dashboard')],
                             ['label'=>'Convocatorias y Selección','url'=>null]],
            'titulo'     => 'Listado de Convocatorias',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            {{-- Flash Messages / Errors --}}
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
                    <span>Restricción de activación:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Barra de búsqueda + botón crear --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-5">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text"
                           id="buscador"
                           placeholder="Buscar convocatoria..."
                           oninput="filtrarTarjetas(this.value)"
                           class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white
                                  focus:outline-none focus:border-green-500 focus:ring-2
                                  focus:ring-green-500/20 transition-all w-64">
                </div>
                <a href="{{ route('sisgedi.convocatorias.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold
                          rounded-lg transition-all hover:opacity-90"
                   style="background:#39A900;">
                    <i class="fas fa-plus text-xs"></i> Crear Convocatoria
                </a>
            </div>

            {{-- Grid de tarjetas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="gridConvocatorias">
                @foreach($convocatorias as $conv)
                <div class="conv-card bg-white rounded-xl border border-gray-100 shadow-sm p-5
                            hover:shadow-md transition-shadow flex flex-col gap-3">

                    {{-- Título + badge estado --}}
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-bold text-gray-900 text-sm leading-snug flex-1">
                            {{ $conv->titulo }}
                        </h3>
                        @php
                            $badgeMap = [
                                'Abierta'      => ['bg'=>'#DCFCE7','color'=>'#15803D','label'=>'Abierta'],
                                'En Selección' => ['bg'=>'#FEF9C3','color'=>'#92400E','label'=>'En Selección'],
                                'Finalizada'   => ['bg'=>'#F3F4F6','color'=>'#374151','label'=>'Finalizada'],
                                'Cerrada'      => ['bg'=>'#FEE2E2','color'=>'#991B1B','label'=>'Cerrada'],
                            ];
                            $badge = $badgeMap[$conv->estado] ?? ['bg'=>'#F3F4F6','color'=>'#374151','label'=>$conv->estado];
                        @endphp
                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full flex-shrink-0"
                              style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }};">
                            {{ $badge['label'] }}
                        </span>
                    </div>

                    {{-- Fase --}}
                    <p class="text-xs text-gray-400 -mt-1">{{ $conv->fase }}</p>

                    {{-- Descripción --}}
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $conv->descripcion }}</p>

                    {{-- Fechas --}}
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span><span class="font-semibold">Apertura:</span> {{ $conv->apertura }}</span>
                        <span><span class="font-semibold">Cierre:</span> {{ $conv->cierre }}</span>
                    </div>

                    {{-- Footer: postulantes + acciones --}}
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100">

                        {{-- Fila 1: contador de postulantes --}}
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            @if($conv->estado === 'Finalizada')
                                <i class="fas fa-trophy text-amber-400"></i>
                                <a href="{{ route('sisgedi.convocatorias.resultados') }}"
                                   class="font-semibold text-amber-600 hover:underline">
                                    Resultados
                                </a>
                                <span class="text-gray-400 ml-1">· {{ $conv->postulantes }} postulante{{ $conv->postulantes != 1 ? 's' : '' }}</span>
                            @else
                                <i class="fas fa-users text-gray-400"></i>
                                <span>{{ $conv->postulantes }} postulante{{ $conv->postulantes != 1 ? 's' : '' }}</span>
                            @endif
                        </div>

                        {{-- Fila 2: botones de acción --}}
                        <div class="flex items-center gap-2 flex-wrap">

                            {{-- Abrir / Cerrar --}}
                            <form action="{{ route('sisgedi.convocatorias.toggleEstado', $conv->id) }}" method="POST" class="inline">
                                @csrf
                                @if(strtolower($conv->estado) === 'abierta')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                                   text-xs font-semibold bg-red-50 text-red-700
                                                   hover:bg-red-100 transition-colors border border-red-100">
                                        <i class="fas fa-power-off text-[10px]"></i> Cerrar
                                    </button>
                                @else
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                                   text-xs font-semibold bg-green-50 text-green-700
                                                   hover:bg-green-100 transition-colors border border-green-100">
                                        <i class="fas fa-play text-[10px]"></i> Activar
                                    </button>
                                @endif
                            </form>

                            {{-- Ver detalle --}}
                            <a href="{{ route('sisgedi.convocatorias.show', $conv->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                      text-xs font-semibold text-gray-600 bg-gray-50
                                      hover:bg-gray-100 transition-colors border border-gray-100"
                               title="Ver postulantes y cargos">
                                <i class="fas fa-eye text-[10px]"></i> Ver
                            </a>

                            {{-- Eliminar --}}
                            <button type="button"
                                    onclick="confirmarEliminar({{ $conv->id }}, '{{ addslashes($conv->titulo) }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                           text-xs font-semibold text-red-600 bg-red-50
                                           hover:bg-red-100 transition-colors border border-red-100"
                                    title="Eliminar convocatoria">
                                <i class="fas fa-trash text-[10px]"></i> Eliminar
                            </button>

                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            {{-- Sin resultados --}}
            <div id="sinResultados" class="hidden text-center py-16 text-gray-400">
                <i class="fas fa-search text-3xl mb-3 block"></i>
                No se encontraron convocatorias con ese criterio.
            </div>

        </main>
    </div>
</div>

{{-- Form único para eliminar — fuera de cualquier otro form y del loop --}}
<form id="formEliminarConvocatoria"
      action=""
      method="POST"
      style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
function filtrarTarjetas(valor) {
    valor = valor.toLowerCase();
    var tarjetas = document.querySelectorAll('.conv-card');
    var visibles = 0;
    tarjetas.forEach(function(t) {
        var texto = t.innerText.toLowerCase();
        if (texto.includes(valor)) { t.style.display = ''; visibles++; }
        else t.style.display = 'none';
    });
    document.getElementById('sinResultados').classList.toggle('hidden', visibles > 0);
}

function confirmarEliminar(id, titulo) {
    Swal.fire({
        title: '¿Eliminar convocatoria?',
        html: 'Se eliminará permanentemente <strong>"' + titulo + '"</strong> junto con todos sus cargos y postulaciones.<br><br>Esta acción <strong>no se puede deshacer</strong>.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        reverseButtons: true,
        focusCancel: true,
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.getElementById('formEliminarConvocatoria');
            form.action = '/sisgedi/convocatorias/' + id;
            form.style.display = 'block';
            form.submit();
        }
    });
}
</script>
@endsection
