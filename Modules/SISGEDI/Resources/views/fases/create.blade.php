@extends('sisgedi::layouts.dashboard')
@section('title', isset($fase) ? 'Editar Fase' : 'Crear Fase')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        {{-- Topbar interior --}}
        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label' => 'Inicio',          'url' => route('sisgedi.dashboard')],
                ['label' => 'Gestión de Fases','url' => route('sisgedi.fases.index')],
                ['label' => isset($fase) ? 'Editar Fase' : 'Crear Fase', 'url' => null],
            ],
            'titulo' => isset($fase) ? 'Editar Fase' : 'Crear Fase',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            {{-- Errores de validación --}}
            @if($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-800">
                <div class="font-bold flex items-center gap-2 mb-1">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    <span>Corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Formulario principal --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">

                <h2 class="text-base font-bold text-gray-800 mb-5 pb-3 border-b border-gray-100">
                    {{ isset($fase) ? 'Editar Fase' : 'Crear Nueva Fase' }}
                </h2>

                <form method="POST"
                      action="{{ isset($fase) ? route('sisgedi.fases.update', $fase->fase_id) : route('sisgedi.fases.store') }}"
                      id="formFase">
                    @csrf
                    @if(isset($fase))
                        @method('PUT')
                    @endif

                    {{-- ── Nombre de la Fase ── --}}
                    <div class="mb-5">
                        <label for="nombre_fase"
                               class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nombre de la Fase <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nombre_fase"
                               name="nombre_fase"
                               value="{{ old('nombre_fase', $fase->nombre_fase ?? '') }}"
                               placeholder="Ej: Sol - Trimestre 4 2026"
                               class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white
                                      focus:outline-none transition-all
                                      {{ $errors->has('nombre_fase') ? 'border-red-400 focus:border-red-400 focus:ring-2 focus:ring-red-400/20' : 'border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20' }}"
                               required>
                        @error('nombre_fase')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Tipo ── --}}
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Tipo <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-6">
                            {{-- Sol --}}
                            <label class="flex items-center gap-2 cursor-pointer group" id="label-tipo-sol">
                                <div class="relative">
                                    <input type="radio" name="tipo" value="Sol" id="tipo_sol"
                                           {{ old('tipo', $fase->tipo ?? 'Sol') === 'Sol' ? 'checked' : '' }}
                                           class="sr-only peer"
                                           onchange="actualizarTipo()">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-amber-400
                                                flex items-center justify-center transition-all">
                                        <div class="w-2 h-2 rounded-full bg-amber-400 scale-0 peer-checked:scale-100 transition-transform"
                                             id="dot-sol"></div>
                                    </div>
                                </div>
                                <span class="flex items-center gap-1.5 text-sm text-gray-700 font-medium" id="txt-sol">
                                    <i class="fas fa-sun text-amber-400 text-xs"></i> Sol
                                </span>
                            </label>

                            {{-- Luna --}}
                            <label class="flex items-center gap-2 cursor-pointer group" id="label-tipo-luna">
                                <div class="relative">
                                    <input type="radio" name="tipo" value="Luna" id="tipo_luna"
                                           {{ old('tipo', $fase->tipo ?? '') === 'Luna' ? 'checked' : '' }}
                                           class="sr-only peer"
                                           onchange="actualizarTipo()">
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-indigo-400
                                                flex items-center justify-center transition-all">
                                        <div class="w-2 h-2 rounded-full bg-indigo-400 scale-0 peer-checked:scale-100 transition-transform"
                                             id="dot-luna"></div>
                                    </div>
                                </div>
                                <span class="flex items-center gap-1.5 text-sm text-gray-700 font-medium" id="txt-luna">
                                    <i class="fas fa-moon text-indigo-400 text-xs"></i> Luna
                                </span>
                            </label>
                        </div>
                        @error('tipo')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Fechas ── --}}
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label for="fecha_inicio"
                                   class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Fecha de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="fecha_inicio"
                                   name="fecha_inicio"
                                   value="{{ old('fecha_inicio', $fase->fecha_inicio ?? '') }}"
                                   class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white
                                          focus:outline-none transition-all
                                          {{ $errors->has('fecha_inicio') ? 'border-red-400 focus:border-red-400 focus:ring-2 focus:ring-red-400/20' : 'border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20' }}"
                                   required>
                            @error('fecha_inicio')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="fecha_fin"
                                   class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Fecha de Fin <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="fecha_fin"
                                   name="fecha_fin"
                                   value="{{ old('fecha_fin', $fase->fecha_fin ?? '') }}"
                                   class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white
                                          focus:outline-none transition-all
                                          {{ $errors->has('fecha_fin') ? 'border-red-400 focus:border-red-400 focus:ring-2 focus:ring-red-400/20' : 'border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20' }}"
                                   required>
                            @error('fecha_fin')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Sectores Asociados ── --}}
                    @if($sectores->isNotEmpty())
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Sectores Asociados
                        </label>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                            @foreach($sectores as $sector)
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer
                                          hover:text-gray-900 transition-colors">
                                <input type="checkbox"
                                       name="sectores[]"
                                       value="{{ $sector->id }}"
                                       class="w-3.5 h-3.5 rounded border-gray-300 text-green-600
                                              focus:ring-green-500 focus:ring-1 cursor-pointer">
                                <span class="text-xs">{{ $sector->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @else
                    {{-- Sectores de demostración si la BD está vacía --}}
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Sectores Asociados
                        </label>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                            @foreach(['Cultivo de Maíz', 'Planta de Lácteos', 'Porcicultura', 'Avicultura', 'Empaque Agroindustrial', 'Mercadeo', 'Gestión ASIG'] as $demo)
                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer hover:text-gray-900 transition-colors">
                                <input type="checkbox" name="sectores_demo[]" value="{{ $demo }}"
                                       class="w-3.5 h-3.5 rounded border-gray-300 cursor-pointer">
                                <span class="text-xs">{{ $demo }}</span>
                            </label>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Los sectores se vincularán una vez estén registrados en el sistema.
                        </p>
                    </div>
                    @endif

                    {{-- ── Descripción ── --}}
                    <div class="mb-6">
                        <label for="descripcion"
                               class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Descripción
                        </label>
                        <textarea id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  placeholder="Descripción de la fase..."
                                  class="w-full px-3.5 py-2.5 text-sm border border-gray-200 rounded-lg bg-white
                                         focus:outline-none focus:border-green-500 focus:ring-2
                                         focus:ring-green-500/20 transition-all resize-none">{{ old('descripcion', $fase->descripcion ?? '') }}</textarea>
                    </div>

                    {{-- ── Acciones ── --}}
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                id="btnGuardar"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-white text-sm
                                       font-semibold rounded-lg transition-all hover:opacity-90"
                                style="background:#39A900;">
                            <i class="fas fa-save text-xs"></i>
                            {{ isset($fase) ? 'Actualizar Fase' : 'Guardar Fase' }}
                        </button>
                        <a href="{{ route('sisgedi.fases.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-600 font-semibold rounded-lg
                                  border border-gray-200 hover:bg-gray-50 transition-all">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>{{-- fin card --}}

        </main>
    </div>
</div>
@endsection

@section('script')
<script>
// ── Sincronizar estado visual de los radio buttons ────────────────
function actualizarTipo() {
    var solChecked  = document.getElementById('tipo_sol').checked;
    var lunaChecked = document.getElementById('tipo_luna').checked;

    // Dot Sol
    var dotSol = document.getElementById('dot-sol');
    if (dotSol) dotSol.style.transform = solChecked ? 'scale(1)' : 'scale(0)';

    // Dot Luna
    var dotLuna = document.getElementById('dot-luna');
    if (dotLuna) dotLuna.style.transform = lunaChecked ? 'scale(1)' : 'scale(0)';
}

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', function () {
    actualizarTipo();

    // Autocompletar nombre según tipo + fecha
    var inputNombre = document.getElementById('nombre_fase');
    function actualizarNombreSugerido() {
        if (inputNombre.value.trim() !== '') return; // No sobreescribir si ya tienen algo
        var tipo = document.querySelector('input[name="tipo"]:checked');
        var fecha = document.getElementById('fecha_inicio').value;
        if (tipo && fecha) {
            var d = new Date(fecha);
            var trimestre = Math.ceil((d.getMonth() + 1) / 3);
            var anio = d.getFullYear();
            inputNombre.placeholder = tipo.value + ' - Trimestre ' + trimestre + ' ' + anio;
        }
    }
    document.getElementById('fecha_inicio').addEventListener('change', actualizarNombreSugerido);
    document.querySelectorAll('input[name="tipo"]').forEach(function(r) {
        r.addEventListener('change', function() { actualizarTipo(); actualizarNombreSugerido(); });
    });
});
</script>
@endsection
