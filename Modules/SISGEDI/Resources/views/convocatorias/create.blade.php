@extends('sisgedi::layouts.dashboard')
@section('title', 'Crear Convocatoria')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label'=>'Inicio','url'=>route('sisgedi.dashboard')],
                ['label'=>'Convocatorias y Selección','url'=>route('sisgedi.convocatorias.index')],
                ['label'=>'Crear Convocatoria','url'=>null],
            ],
            'titulo' => 'Crear Convocatoria',
        ])

        <main class="flex-1 p-6 space-y-5" style="background:#f3f4f6;">

            <form action="{{ route('sisgedi.convocatorias.store') }}" method="POST" id="formConvocatoria">
                @csrf

                {{-- Errores --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-3">
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-0.5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- ── INFORMACIÓN GENERAL ── --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h2 class="font-bold text-gray-800 text-base">Información General</h2>

                    {{-- Título --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titulo" value="{{ old('titulo') }}"
                               placeholder="Ej: Convocatoria Líder Sector Lácteos"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm
                                      focus:outline-none focus:border-green-500 focus:ring-2
                                      focus:ring-green-500/20 transition-all
                                      @error('titulo') border-red-400 @enderror">
                    </div>

                    {{-- Fase + Fechas --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                Fase Destino <span class="text-red-500">*</span>
                            </label>
                            <select name="fase_id"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm
                                           focus:outline-none focus:border-green-500 focus:ring-2
                                           focus:ring-green-500/20 transition-all
                                           @error('fase_id') border-red-400 @enderror">
                                <option value="">Seleccionar fase...</option>
                                @foreach($fases as $fase)
                                <option value="{{ $fase->fase_id }}"
                                    {{ old('fase_id') == $fase->fase_id ? 'selected' : '' }}>
                                    {{ $fase->nombre_fase }}
                                </option>
                                @endforeach
                            </select>
                            @error('fase_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                Fecha de Apertura <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="apertura" value="{{ old('apertura') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm
                                          focus:outline-none focus:border-green-500 focus:ring-2
                                          focus:ring-green-500/20 transition-all
                                          @error('apertura') border-red-400 @enderror">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">
                                Fecha de Cierre <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="cierre" value="{{ old('cierre') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm
                                          focus:outline-none focus:border-green-500 focus:ring-2
                                          focus:ring-green-500/20 transition-all
                                          @error('cierre') border-red-400 @enderror">
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="3"
                                  placeholder="Descripción de la convocatoria..."
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm
                                         focus:outline-none focus:border-green-500 focus:ring-2
                                         focus:ring-green-500/20 transition-all resize-none">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                {{-- ── CARGOS DISPONIBLES ── --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6" id="seccionCargos">
                    <h2 class="font-bold text-gray-800 text-base mb-4">Cargos Disponibles</h2>

                    <div id="listaCargos" class="space-y-5">
                        {{-- Cargo inicial --}}
                        <div class="cargo-item border border-gray-100 rounded-xl p-4 space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cargo</label>
                                    <input type="text" name="cargos[0][nombre]"
                                           placeholder="Ej: Líder de Producción"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                                                  focus:outline-none focus:border-green-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cupos</label>
                                    <input type="number" name="cargos[0][cupos]" value="1" min="1"
                                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm
                                                  focus:outline-none focus:border-green-500 transition-all">
                                </div>
                            </div>

                            {{-- Perfiles compatibles --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-600 mb-2">Perfiles de Tecnólogo Compatibles</p>
                                <div class="grid grid-cols-2 gap-x-6 gap-y-1.5">
                                    @foreach([
                                        'Tecnólogo en Producción Agroindustrial',
                                        'Tecnólogo en Producción Agrícola',
                                        'Tecnólogo en Producción Pecuaria',
                                        'Tecnólogo en Gestión Administrativa',
                                        'Tecnólogo en Gestión Ambiental',
                                    ] as $perfil)
                                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                                        <input type="checkbox" name="cargos[0][perfiles][]" value="{{ $perfil }}"
                                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        {{ $perfil }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Documentos requeridos --}}
                            <div>
                                <p class="text-xs font-semibold text-gray-600 mb-2">Documentos Requeridos</p>
                                <div class="flex flex-wrap gap-4">
                                    @foreach(['Hoja de vida','Certificados','APE'] as $doc)
                                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                                        <input type="checkbox" name="cargos[0][documentos][]" value="{{ $doc }}"
                                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        {{ $doc }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botón agregar cargo --}}
                    <button type="button" onclick="agregarCargo()"
                            class="mt-4 flex items-center gap-2 text-sm font-semibold text-green-600
                                   hover:text-green-700 transition-colors">
                        <i class="fas fa-plus-circle"></i> Agregar Cargo
                    </button>
                </div>

                {{-- ── CONFIGURACIÓN DE SELECCIÓN ── --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <h2 class="font-bold text-gray-800 text-base">Configuración de Selección</h2>

                    {{-- Umbral --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-semibold text-gray-700">
                                Umbral Mínimo de Aprobación:
                                <span id="umbralVal" class="text-green-600">70%</span>
                            </label>
                        </div>
                        <input type="range" name="umbral" min="50" max="100" value="70" step="5"
                               id="umbralRange"
                               oninput="document.getElementById('umbralVal').textContent=this.value+'%'"
                               class="w-full accent-green-600">
                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                            <span>50%</span><span>100%</span>
                        </div>
                    </div>

                    {{-- Política si nadie alcanza umbral --}}
                    <div>
                        <p class="text-sm font-semibold text-gray-700 mb-2">
                            Política si nadie alcanza el umbral
                        </p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="radio" name="politica" value="mayor_puntaje"
                                       checked class="text-green-600 focus:ring-green-500">
                                Seleccionar al de mayor puntaje
                            </label>
                            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                <input type="radio" name="politica" value="declarar_vacante"
                                       class="text-green-600 focus:ring-green-500">
                                Declarar vacante
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Botones finales --}}
                <div class="flex items-center gap-3 pb-4">
                    <button type="submit"
                            class="px-6 py-2.5 text-white text-sm font-bold rounded-lg
                                   transition-all hover:opacity-90"
                            style="background:#39A900;">
                        Guardar Convocatoria
                    </button>
                    <a href="{{ route('sisgedi.convocatorias.index') }}"
                       class="px-6 py-2.5 text-gray-600 text-sm font-semibold rounded-lg
                              border border-gray-200 hover:bg-gray-50 transition-all">
                        Cancelar
                    </a>
                </div>

            </form>
        </main>
    </div>
</div>
@endsection

@section('script')
<script>
var cargoIndex = 1;

function agregarCargo() {
    var idx = cargoIndex++;
    var perfiles = [
        'Tecnólogo en Producción Agroindustrial',
        'Tecnólogo en Producción Agrícola',
        'Tecnólogo en Producción Pecuaria',
        'Tecnólogo en Gestión Administrativa',
        'Tecnólogo en Gestión Ambiental',
    ];
    var documentos = ['Hoja de vida','Certificados','APE'];

    var perfilesHtml = perfiles.map(function(p) {
        return '<label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">' +
               '<input type="checkbox" name="cargos['+idx+'][perfiles][]" value="'+p+'" ' +
               'class="rounded border-gray-300 text-green-600 focus:ring-green-500">'+p+'</label>';
    }).join('');

    var docsHtml = documentos.map(function(d) {
        return '<label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">' +
               '<input type="checkbox" name="cargos['+idx+'][documentos][]" value="'+d+'" ' +
               'class="rounded border-gray-300 text-green-600 focus:ring-green-500">'+d+'</label>';
    }).join('');

    var html = '<div class="cargo-item border border-gray-100 rounded-xl p-4 space-y-3 relative">' +
        '<button type="button" onclick="this.closest(\'.cargo-item\').remove()" ' +
        'class="absolute top-3 right-3 text-gray-300 hover:text-red-400 transition-colors">' +
        '<i class="fas fa-times text-sm"></i></button>' +
        '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
        '<div><label class="block text-xs font-semibold text-gray-600 mb-1">Cargo</label>' +
        '<input type="text" name="cargos['+idx+'][nombre]" placeholder="Ej: Líder de Producción" ' +
        'class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 transition-all"></div>' +
        '<div><label class="block text-xs font-semibold text-gray-600 mb-1">Cupos</label>' +
        '<input type="number" name="cargos['+idx+'][cupos]" value="1" min="1" ' +
        'class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500 transition-all"></div></div>' +
        '<div><p class="text-xs font-semibold text-gray-600 mb-2">Perfiles de Tecnólogo Compatibles</p>' +
        '<div class="grid grid-cols-2 gap-x-6 gap-y-1.5">'+perfilesHtml+'</div></div>' +
        '<div><p class="text-xs font-semibold text-gray-600 mb-2">Documentos Requeridos</p>' +
        '<div class="flex flex-wrap gap-4">'+docsHtml+'</div></div></div>';

    document.getElementById('listaCargos').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection
