@extends('sisgedi::layouts.landing')

@section('content')

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('sisgedi.aprendiz.panel') }}" class="hover:text-gray-600 transition-colors">
            Mis Convocatorias
        </a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-700 font-semibold">{{ $conv->titulo }}</span>
    </div>

    {{-- Ya postulado --}}
    @if($postulacion)
    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                 style="background:#DCFCE7;">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
                <h2 class="font-bold text-green-800 text-base">Ya estás postulado/a</h2>
                <p class="text-xs text-green-600">
                    Postulación registrada el {{ \Carbon\Carbon::parse($postulacion->fecha_postulacion)->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
        <p class="text-sm font-semibold text-gray-700 mb-3">Tus opciones de cargo elegidas:</p>
        <ol class="space-y-2">
            @foreach($opcionesElegidas as $op)
            <li class="flex items-center gap-3 bg-white rounded-xl px-4 py-2.5 border border-green-100 shadow-sm">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black text-white flex-shrink-0"
                      style="background:#1B8C3E;">
                    {{ $op->orden_preferencia }}
                </span>
                <span class="text-sm font-semibold text-gray-800">{{ $op->nombre_cargo }}</span>
            </li>
            @endforeach
        </ol>
        <div class="mt-4">
            <a href="{{ route('sisgedi.aprendiz.panel') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-green-700 hover:underline">
                <i class="fas fa-arrow-left text-xs"></i> Volver al panel
            </a>
        </div>
    </div>
    @else

    {{-- Cabecera de la convocatoria --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
        <div class="flex items-start justify-between gap-3 mb-3">
            <h1 class="text-xl font-extrabold text-gray-900 leading-tight">
                {{ $conv->titulo }}
            </h1>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0"
                  style="background:#EFF6FF; color:#1D4ED8;">Abierta</span>
        </div>
        @if(isset($conv->descripcion))
        <p class="text-sm text-gray-600 mb-4">{{ $conv->descripcion }}</p>
        @endif
        <div class="flex gap-6 text-xs text-gray-500">
            <span><i class="fas fa-calendar-alt mr-1"></i>
                Apertura: <strong>{{ $conv->fecha_apertura }}</strong>
            </span>
            <span><i class="fas fa-calendar-times mr-1"></i>
                Cierre: <strong>{{ $conv->fecha_cierre }}</strong>
            </span>
        </div>
    </div>

    {{-- Instrucciones --}}
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl
                px-4 py-3 mb-5 text-sm text-amber-800">
        <i class="fas fa-info-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
        <p>
            Selecciona exactamente <strong>3 cargos</strong> en orden de preferencia
            (1° = tu primera opción). Arrastra para reordenar o usa los números.
        </p>
    </div>

    {{-- Errores --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5 text-sm text-red-700">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
    </div>
    @endif

    {{-- Formulario de postulación --}}
    <form action="{{ route('sisgedi.aprendiz.postular', $conv->convocatoria_id) }}"
          method="POST" id="formPostulacion">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Lista de cargos seleccionables --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-sm mb-4">
                    Cargos disponibles — selecciona 3
                </h2>

                <div class="space-y-2" id="listaCargosDisponibles">
                    @foreach($cargos as $cargo)
                    <div class="cargo-opcion flex items-start gap-4 p-4 rounded-xl border border-gray-100
                                hover:border-green-300 transition-all cursor-pointer"
                         data-id="{{ $cargo->convocatoria_cargo_id }}"
                         onclick="seleccionarCargo({{ $cargo->convocatoria_cargo_id }}, '{{ addslashes($cargo->nombre_cargo) }}')">

                        {{-- Indicador de selección --}}
                        <div class="orden-badge w-8 h-8 rounded-full border-2 border-gray-200
                                    flex items-center justify-center text-xs font-black text-gray-400
                                    flex-shrink-0 transition-all"
                             id="badge-{{ $cargo->convocatoria_cargo_id }}">
                            —
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 text-sm">{{ $cargo->nombre_cargo }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $cargo->cupos }} cupo{{ $cargo->cupos != 1 ? 's' : '' }}
                                @if($cargo->titulacion_requerida)
                                · {{ $cargo->titulacion_requerida }}
                                @endif
                            </p>
                            @if($cargo->competencias_minimas)
                            <p class="text-xs text-gray-400 mt-1">{{ $cargo->competencias_minimas }}</p>
                            @endif
                        </div>

                        <i class="fas fa-check-circle text-green-500 text-lg check-icon hidden flex-shrink-0"
                           id="check-{{ $cargo->convocatoria_cargo_id }}"></i>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Resumen de selección --}}
            <div class="px-6 py-5 bg-gray-50">
                <h3 class="font-bold text-gray-700 text-sm mb-3">
                    Tus opciones elegidas
                    <span id="contadorElegidos" class="text-gray-400 font-normal">(0/3)</span>
                </h3>
                <ol class="space-y-2" id="resumenOpciones">
                    <li class="text-xs text-gray-400 italic" id="sinSeleccion">
                        Haz clic en los cargos de arriba para seleccionarlos...
                    </li>
                </ol>
            </div>

            {{-- Inputs hidden para enviar al servidor --}}
            <div id="inputsHidden"></div>

            {{-- Botones --}}
            <div class="px-6 py-4 border-t border-gray-100 flex items-center gap-3">
                <button type="submit" id="btnPostular"
                        disabled
                        class="px-6 py-2.5 text-white text-sm font-bold rounded-xl
                               transition-all opacity-40 cursor-not-allowed"
                        style="background:#1B8C3E;">
                    <i class="fas fa-paper-plane mr-1"></i> Confirmar Postulación
                </button>
                <a href="{{ route('sisgedi.aprendiz.panel') }}"
                   class="px-5 py-2.5 text-gray-600 text-sm font-semibold rounded-xl
                          border border-gray-200 hover:bg-gray-100 transition-all">
                    Cancelar
                </a>
            </div>
        </div>

    </form>
    @endif

</section>

@endsection

@section('scripts')
<script>
// Estado de selección: array de 3 items [{id, nombre}, ...]
var seleccionados = [];
var MAX = 3;

function seleccionarCargo(id, nombre) {
    // ¿Ya está seleccionado? → deseleccionar
    var idx = seleccionados.findIndex(function(s) { return s.id === id; });
    if (idx !== -1) {
        seleccionados.splice(idx, 1);
        actualizarUI();
        return;
    }
    // ¿Ya hay 3? → no hacer nada
    if (seleccionados.length >= MAX) {
        // Feedback visual
        var badge = document.getElementById('badge-' + id);
        if (badge) {
            badge.style.borderColor = '#EF4444';
            setTimeout(function() { badge.style.borderColor = ''; }, 800);
        }
        return;
    }
    seleccionados.push({ id: id, nombre: nombre });
    actualizarUI();
}

function actualizarUI() {
    var itemIds = seleccionados.map(function(s) { return s.id; });

    // Actualizar cada cargo en la lista
    document.querySelectorAll('.cargo-opcion').forEach(function(el) {
        var cid  = parseInt(el.dataset.id);
        var badge = document.getElementById('badge-' + cid);
        var check = document.getElementById('check-' + cid);
        var orden = itemIds.indexOf(cid);

        if (orden !== -1) {
            // Seleccionado
            el.style.borderColor      = '#1B8C3E';
            el.style.background       = '#F0FAF4';
            badge.textContent         = orden + 1;
            badge.style.borderColor   = '#1B8C3E';
            badge.style.color         = '#1B8C3E';
            badge.style.background    = '#DCFCE7';
            check.classList.remove('hidden');
        } else {
            // No seleccionado
            el.style.borderColor      = '';
            el.style.background       = '';
            badge.textContent         = '—';
            badge.style.borderColor   = '';
            badge.style.color         = '';
            badge.style.background    = '';
            check.classList.add('hidden');
        }
    });

    // Actualizar resumen
    var resumen  = document.getElementById('resumenOpciones');
    var sinSel   = document.getElementById('sinSeleccion');
    var contador = document.getElementById('contadorElegidos');
    var btn      = document.getElementById('btnPostular');
    var inputs   = document.getElementById('inputsHidden');

    contador.textContent = '(' + seleccionados.length + '/3)';

    if (seleccionados.length === 0) {
        resumen.innerHTML = '<li class="text-xs text-gray-400 italic" id="sinSeleccion">Haz clic en los cargos de arriba para seleccionarlos...</li>';
    } else {
        resumen.innerHTML = seleccionados.map(function(s, i) {
            return '<li class="flex items-center gap-3 bg-white rounded-xl px-4 py-2.5 border border-green-100 shadow-sm">' +
                   '<span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black text-white flex-shrink-0" style="background:#1B8C3E;">' +
                   (i+1) + '</span>' +
                   '<span class="text-sm font-semibold text-gray-800">' + s.nombre + '</span>' +
                   '<button type="button" onclick="seleccionarCargo(' + s.id + ',\'' + s.nombre.replace(/'/g, "\\'") + '\')" ' +
                   'class="ml-auto text-gray-300 hover:text-red-400 transition-colors">' +
                   '<i class="fas fa-times text-xs"></i></button></li>';
        }).join('');
    }

    // Actualizar inputs hidden
    inputs.innerHTML = seleccionados.map(function(s, i) {
        return '<input type="hidden" name="opciones[' + i + ']" value="' + s.id + '">';
    }).join('');

    // Habilitar / deshabilitar botón
    if (seleccionados.length === MAX) {
        btn.disabled = false;
        btn.classList.remove('opacity-40', 'cursor-not-allowed');
    } else {
        btn.disabled = true;
        btn.classList.add('opacity-40', 'cursor-not-allowed');
    }
}
</script>
@endsection
