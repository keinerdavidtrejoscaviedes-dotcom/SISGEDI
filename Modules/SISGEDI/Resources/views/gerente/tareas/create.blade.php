@extends('sisgedi::layouts.master')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('sisgedi.gerente.dashboard') }}"
           class="text-xs font-semibold text-gray-400 hover:text-sena-green">
            <i class="fas fa-arrow-left mr-1"></i> Volver al panel
        </a>
        <h1 class="text-2xl font-extrabold mt-2" style="color:#001A29;">
            Generar tarea en cascada para los Gestores
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Fase vigente: <strong>{{ $fase->nombre }}</strong>. La tarea quedará visible de inmediato
            para los Gestores del sector seleccionado, junto con el documento guía.
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 mb-6">
            <p class="font-semibold mb-1"><i class="fas fa-triangle-exclamation mr-1"></i> Revisa los siguientes campos:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sisgedi.gerente.tareas.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf

        {{-- ── Datos de la tarea ── --}}
        <div>
            <h2 class="font-bold text-sm mb-3" style="color:#001A29;">
                <i class="fas fa-diagram-project text-sena-green mr-1"></i> Datos de la tarea
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Título</label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="255"
                           class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green"
                           placeholder="Ej: Actualizar inventario de insumos del trimestre">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="3" required
                              class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">{{ old('descripcion') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sector productivo</label>
                    <select name="sector_id" required
                            class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                        <option value="">Selecciona un sector activo…</option>
                        @foreach($sectores as $sector)
                            <option value="{{ $sector->id }}" @selected(old('sector_id') == $sector->id)>
                                {{ $sector->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @if($sectores->isEmpty())
                        <p class="text-xs text-amber-600 mt-1">
                            <i class="fas fa-triangle-exclamation"></i> No hay sectores activos para tu gerencia en esta fase.
                        </p>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha límite</label>
                    <input type="date" id="fecha-limite-input" name="fecha_limite" value="{{ old('fecha_limite') }}" required
                           min="{{ now()->toDateString() }}"
                           class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">
                <i class="fas fa-circle-info mr-1"></i> El entregable que devolverá el Gestor es siempre un
                documento (Word/PDF); las evidencias en foto o video solo aplican a las tareas de campo que
                el Líder asigna a los Colaboradores.
            </p>
        </div>

        <hr class="border-gray-100">

        {{-- ── Documento guía (RN-013: obligatorio) ── --}}
        <div>
            <h2 class="font-bold text-sm mb-3" style="color:#001A29;">
                <i class="fas fa-file-lines text-sena-green mr-1"></i> Documento guía
            </h2>
            <p class="text-xs text-gray-500 mb-3">
                Toda tarea en cascada debe acompañarse de instrucciones, entregables esperados y plazos
                para el nivel receptor (RN-013).
            </p>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Instrucciones</label>
                    <textarea name="instrucciones" rows="3" required
                              class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">{{ old('instrucciones') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Entregables esperados</label>
                    <textarea name="entregables_esperados" rows="2" required
                              class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">{{ old('entregables_esperados') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Plazos</label>
                        <input type="text" id="plazos-input" name="plazos" value="{{ old('plazos') }}" required
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green"
                               placeholder="Se completa solo según la fecha límite">
                        <p class="text-[11px] text-gray-400 mt-1">
                            Se sugiere automáticamente a partir de la fecha límite; puedes editarlo si el
                            Gestor debe cumplir un plazo distinto (por ejemplo, entregas parciales).
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Archivo adjunto (opcional)</label>
                        <input type="file" name="archivo"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('sisgedi.gerente.dashboard') }}"
               class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:scale-105"
                    style="background:#39A900; box-shadow:0 4px 14px rgba(57,169,0,.4);">
                <i class="fas fa-paper-plane"></i> Publicar tarea para Gestores
            </button>
        </div>
    </form>
</div>

@section('script')
<script>
    (function () {
        const fechaInput = document.getElementById('fecha-limite-input');
        const plazosInput = document.getElementById('plazos-input');
        let autollenado = plazosInput.value === '';

        plazosInput.addEventListener('input', function () {
            // Si el usuario escribe algo distinto al texto autogenerado, deja de autocompletar.
            autollenado = plazosInput.value === '' || plazosInput.dataset.autofilled === plazosInput.value;
        });

        fechaInput.addEventListener('change', function () {
            if (!autollenado || !fechaInput.value) return;

            const [anio, mes, dia] = fechaInput.value.split('-');
            const texto = `Antes del ${dia}/${mes}/${anio}.`;

            plazosInput.value = texto;
            plazosInput.dataset.autofilled = texto;
        });
    })();
</script>
@endsection
@endsection
