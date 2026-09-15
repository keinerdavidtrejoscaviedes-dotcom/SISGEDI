@extends('sisgedi::layouts.dashboard')
@section('title', 'Realizar Entrevista')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label'=>'Inicio','url'=>route('sisgedi.dashboard')],
                ['label'=>'Realizar Entrevistas','url'=>route('sisgedi.entrevistas.index')],
                ['label'=>$postulacion->aprendiz_nombre,'url'=>null],
            ],
            'titulo' => 'Formulario de Entrevista',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('sisgedi.entrevistas.store', $postulacion->postulacion_id) }}" id="formEntrevista">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- ── Columna izquierda: Info del postulante ── --}}
                    <div class="lg:col-span-1 space-y-4">

                        {{-- Tarjeta: Datos del postulante --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-11 h-11 rounded-full flex items-center justify-center font-bold text-white text-sm flex-shrink-0"
                                     style="background:#39A900;">
                                    {{ strtoupper(substr($postulacion->aprendiz_nombre, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm leading-tight">{{ $postulacion->aprendiz_nombre }}</p>
                                    <p class="text-xs text-gray-400">Postulante</p>
                                </div>
                            </div>

                            <div class="space-y-2 text-xs text-gray-600">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Postulación #</span>
                                    <span class="font-semibold">{{ $postulacion->postulacion_id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Fecha</span>
                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($postulacion->fecha_postulacion)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Estado</span>
                                    <span class="px-2 py-0.5 rounded-full font-semibold"
                                          style="background:#FEF3C7; color:#B45309;">{{ $postulacion->estado }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tarjeta: Cargos postulados --}}
                        @if($opciones->count())
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                            <h3 class="text-sm font-bold text-gray-700 mb-3">Cargos Postulados</h3>
                            <div class="space-y-2">
                                @foreach($opciones as $op)
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0 text-[10px]"
                                          style="background:#39A900;">{{ $op->orden_preferencia }}</span>
                                    <span class="text-gray-700">{{ $op->nombre_cargo }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Tarjeta: Observaciones generales --}}
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                            <h3 class="text-sm font-bold text-gray-700 mb-3">Observaciones Generales</h3>
                            <textarea name="observaciones" rows="5"
                                      placeholder="Notas adicionales sobre la entrevista..."
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none
                                             focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all">{{ old('observaciones') }}</textarea>
                        </div>

                    </div>

                    {{-- ── Columna derecha: Checklist de evaluación ── --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                            <div class="flex items-center justify-between mb-1">
                                <h2 class="font-bold text-gray-800 text-sm">Criterios de Evaluación</h2>
                                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
                                    v{{ $checklist->version }} — {{ $checklist->nombre }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 mb-5">
                                Califica cada ítem del 1 al 5 (1 = Deficiente, 5 = Excelente) o responde según el tipo de pregunta.
                            </p>

                            <div class="space-y-4">
                                @foreach($items as $i => $item)
                                <div class="border border-gray-100 rounded-xl p-4 hover:border-green-200 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0 mt-0.5"
                                              style="background:#39A900;">{{ $i + 1 }}</span>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-800 mb-3">{{ $item->nombre_item }}</p>

                                            @if($item->tipo_respuesta === 'calificacion')
                                                {{-- Escala de calificación 1–5 con botones visuales --}}
                                                <div class="flex gap-2 flex-wrap">
                                                    @for($n = 1; $n <= 5; $n++)
                                                    <label class="cursor-pointer">
                                                        <input type="radio"
                                                               name="calificaciones[{{ $item->item_id }}]"
                                                               value="{{ $n }}"
                                                               class="sr-only calificacion-radio"
                                                               data-group="{{ $item->item_id }}"
                                                               required>
                                                        <span class="calificacion-btn w-10 h-10 flex items-center justify-center rounded-lg border-2 text-sm font-bold transition-all cursor-pointer border-gray-200 text-gray-400 hover:border-green-400 hover:text-green-600"
                                                              data-value="{{ $n }}"
                                                              data-item="{{ $item->item_id }}"
                                                              onclick="seleccionarCalificacion({{ $item->item_id }}, {{ $n }}, this)">
                                                            {{ $n }}
                                                        </span>
                                                    </label>
                                                    @endfor
                                                    <div class="flex items-center ml-1">
                                                        <span class="text-xs text-gray-400" id="label-{{ $item->item_id }}">Sin calificar</span>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Campo de texto libre --}}
                                                <textarea name="calificaciones[{{ $item->item_id }}]"
                                                          rows="2"
                                                          placeholder="Escribe la respuesta..."
                                                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none
                                                                 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all"></textarea>
                                                {{-- Hidden input to handle "texto" as numeric 0 if empty --}}
                                                <input type="hidden" name="calificaciones[{{ $item->item_id }}]" value="0" class="hidden-text-fallback-{{ $item->item_id }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Resumen de puntaje en tiempo real --}}
                            <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="text-center">
                                        <p class="text-2xl font-black" style="color:#39A900;" id="puntajeTotal">0.0</p>
                                        <p class="text-[10px] text-gray-400">Puntaje promedio</p>
                                    </div>
                                    <div class="w-px h-8 bg-gray-100"></div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-gray-700" id="itemsCalificados">0/{{ $items->where('tipo_respuesta','calificacion')->count() }}</p>
                                        <p class="text-[10px] text-gray-400">Ítems calificados</p>
                                    </div>
                                </div>
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 text-white text-sm font-bold rounded-lg transition-all hover:opacity-90 shadow-sm"
                                        style="background:#39A900;">
                                    <i class="fas fa-save text-xs"></i> Guardar Entrevista
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </main>
    </div>
</div>
@endsection

@section('script')
<script>
const labels = {1:'Deficiente',2:'Regular',3:'Aceptable',4:'Bueno',5:'Excelente'};
const totalCalificacion = {{ $items->where('tipo_respuesta','calificacion')->count() }};
var valores = {};

function seleccionarCalificacion(itemId, valor, clicked) {
    // Actualizar estado visual de todos los botones de ese grupo
    document.querySelectorAll('[data-item="'+itemId+'"]').forEach(function(btn) {
        var v = parseInt(btn.dataset.value);
        if (v <= valor) {
            btn.style.background = '#39A900';
            btn.style.borderColor = '#39A900';
            btn.style.color = '#fff';
        } else {
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        }
    });

    // Seleccionar el radio correspondiente
    var radio = document.querySelector('input[name="calificaciones['+itemId+']"][value="'+valor+'"]');
    if (radio) radio.checked = true;

    // Actualizar label
    var lbl = document.getElementById('label-'+itemId);
    if (lbl) lbl.textContent = labels[valor] || '';

    valores[itemId] = valor;
    actualizarResumen();
}

function actualizarResumen() {
    var keys = Object.keys(valores);
    var sum = 0;
    keys.forEach(function(k) { sum += valores[k]; });
    var promedio = keys.length > 0 ? (sum / keys.length).toFixed(1) : '0.0';
    document.getElementById('puntajeTotal').textContent = promedio;
    document.getElementById('itemsCalificados').textContent = keys.length + '/' + totalCalificacion;
}

// Manejar campos texto: evitar que el hidden sobreescriba el textarea
document.querySelectorAll('textarea[name^="calificaciones"]').forEach(function(ta) {
    ta.addEventListener('input', function() {
        // Cuando hay contenido en el textarea, el hidden no deberia enviarse
        // PHP toma el último value para el mismo name; haremos que el textarea
        // esté DESPUÉS del hidden, así prevalece su valor.
    });
});
</script>
@endsection
