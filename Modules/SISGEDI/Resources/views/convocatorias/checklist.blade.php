@extends('sisgedi::layouts.dashboard')
@section('title', 'Checklist de Entrevista')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label'=>'Inicio','url'=>route('sisgedi.dashboard')],
                ['label'=>'Convocatorias y Selección','url'=>route('sisgedi.convocatorias.index')],
                ['label'=>'Checklist Entrevista','url'=>null],
            ],
            'titulo' => 'Checklist de Entrevista',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            {{-- Aviso de versión --}}
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl
                        px-4 py-3 mb-5 text-sm text-amber-800">
                <i class="fas fa-exclamation-triangle mt-0.5 flex-shrink-0 text-amber-500"></i>
                <p>
                    Existen <strong>{{ $entrevistasExistentes }}</strong> entrevistas realizadas
                    con la versión actual del checklist. Las modificaciones crearán una nueva versión
                    sin afectar las entrevistas existentes.
                </p>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-xl">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="font-bold text-gray-800 text-sm">Criterios de Evaluación</h2>
                </div>
                <p class="text-xs text-gray-400 mb-5">Versión actual: {{ $version }}</p>

                {{-- Lista de criterios --}}
                <div id="listaCriterios" class="space-y-2 mb-6">
                    @foreach($criterios as $i => $criterio)
                    <div class="criterio-item flex items-center gap-3 bg-gray-50 border border-gray-100
                                rounded-lg px-4 py-3" data-index="{{ $i }}">
                        <div class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0"
                             style="background:#DCFCE7;">
                            <i class="fas fa-check-square text-green-600 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 leading-tight">
                                {{ $criterio->nombre }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $criterio->tipo }}</p>
                        </div>
                        <button type="button"
                                onclick="eliminarCriterio({{ $i }})"
                                class="text-gray-300 hover:text-red-400 transition-colors flex-shrink-0
                                       w-6 h-6 flex items-center justify-center rounded">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                {{-- Formulario para agregar nuevo criterio --}}
                <div class="border-t border-gray-100 pt-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nuevo criterio</label>
                            <input type="text" id="nuevoCriterio"
                                   placeholder="Nombre del criterio"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                                          focus:outline-none focus:border-green-500 focus:ring-2
                                          focus:ring-green-500/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label>
                            <div class="flex gap-2">
                                <select id="tipoCriterio"
                                        class="flex-1 border border-gray-200 rounded-lg px-2 py-2 text-xs
                                               focus:outline-none focus:border-green-500 transition-all">
                                    <option value="Calificación 1-5">Calificación 1-5</option>
                                    <option value="Sí / No">Sí / No</option>
                                    <option value="Texto libre">Texto libre</option>
                                </select>
                                <button type="button" onclick="agregarCriterio()"
                                        class="px-3 py-2 text-white text-xs font-bold rounded-lg
                                               transition-all hover:opacity-90 flex-shrink-0"
                                        style="background:#39A900;">
                                    + Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botón guardar nueva versión --}}
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <button type="button" onclick="guardarVersion()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm
                                   font-bold rounded-lg transition-all hover:opacity-90"
                            style="background:#39A900;">
                        <i class="fas fa-save text-xs"></i> Guardar Nueva Versión
                    </button>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- Toast de éxito --}}
<div id="toastOk"
     class="fixed bottom-6 right-6 z-50 hidden flex items-center gap-3 bg-white
            border border-green-200 shadow-lg rounded-xl px-5 py-3 text-sm font-semibold text-green-700">
    <i class="fas fa-check-circle text-green-500"></i>
    <span id="toastMsg">Acción realizada.</span>
</div>
@endsection

@section('script')
<script>
var criterioCounter = {{ count($criterios) }};

function agregarCriterio() {
    var nombre = document.getElementById('nuevoCriterio').value.trim();
    var tipo   = document.getElementById('tipoCriterio').value;
    if (!nombre) {
        document.getElementById('nuevoCriterio').style.borderColor = '#EF4444';
        return;
    }
    document.getElementById('nuevoCriterio').style.borderColor = '';

    var idx  = criterioCounter++;
    var html = '<div class="criterio-item flex items-center gap-3 bg-gray-50 border border-gray-100 ' +
               'rounded-lg px-4 py-3" data-index="'+idx+'">' +
               '<div class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0" ' +
               'style="background:#DCFCE7;"><i class="fas fa-check-square text-green-600 text-xs"></i></div>' +
               '<div class="flex-1 min-w-0">' +
               '<p class="text-sm font-semibold text-gray-800 leading-tight">'+nombre+'</p>' +
               '<p class="text-xs text-gray-400 mt-0.5">'+tipo+'</p></div>' +
               '<button type="button" onclick="eliminarCriterio('+idx+')" ' +
               'class="text-gray-300 hover:text-red-400 transition-colors flex-shrink-0 ' +
               'w-6 h-6 flex items-center justify-center rounded">' +
               '<i class="fas fa-times text-xs"></i></button></div>';

    document.getElementById('listaCriterios').insertAdjacentHTML('beforeend', html);
    document.getElementById('nuevoCriterio').value = '';
    mostrarToast('Criterio agregado correctamente.');
}

function eliminarCriterio(idx) {
    var el = document.querySelector('[data-index="'+idx+'"]');
    if (el) {
        el.style.transition = 'opacity 0.2s, transform 0.2s';
        el.style.opacity    = '0';
        el.style.transform  = 'translateX(10px)';
        setTimeout(function() { el.remove(); }, 200);
        mostrarToast('Criterio eliminado.');
    }
}

function guardarVersion() {
    mostrarToast('¡Nueva versión del checklist guardada exitosamente!');
}

function mostrarToast(msg) {
    var toast = document.getElementById('toastOk');
    document.getElementById('toastMsg').textContent = msg;
    toast.classList.remove('hidden');
    setTimeout(function() { toast.classList.add('hidden'); }, 3000);
}
</script>
@endsection
