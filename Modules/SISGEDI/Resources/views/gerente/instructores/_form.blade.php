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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Nombre completo</label>
            <input type="text" name="nombre_completo" value="{{ old('nombre_completo', $instructor->nombre_completo ?? '') }}" required maxlength="255"
                   class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Área / especialidad</label>
            <input type="text" name="area_especialidad" value="{{ old('area_especialidad', $instructor->area_especialidad ?? '') }}" required maxlength="255"
                   placeholder="Ej: Producción Agropecuaria"
                   class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
            <select name="estado" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                <option value="activo" @selected(old('estado', $instructor->estado ?? 'activo') == 'activo')>Activo</option>
                <option value="inactivo" @selected(old('estado', $instructor->estado ?? '') == 'inactivo')>Inactivo</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Correo electrónico</label>
            <input type="email" name="correo" value="{{ old('correo', $instructor->correo ?? '') }}" required
                   class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono', $instructor->telefono ?? '') }}" required maxlength="50"
                   class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Firma digital (imagen)</label>
            @if(!empty($instructor?->firma_digital_url))
                <div class="flex items-center gap-3 mb-2">
                    <img src="{{ asset('storage/'.$instructor->firma_digital_url) }}" class="h-12 rounded border border-gray-100" alt="Firma actual">
                    <span class="text-xs text-gray-400">Firma actual — sube una imagen para reemplazarla.</span>
                </div>
            @endif
            <input type="file" name="firma_digital" accept="image/*"
                   class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100">
        </div>
    </div>
</div>

<div class="flex justify-end gap-3 pt-4">
    <a href="{{ route('sisgedi.gerente.instructores.index') }}"
       class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-50">
        Cancelar
    </a>
    <button type="submit"
            class="inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-all hover:scale-105"
            style="background:#39A900; box-shadow:0 4px 14px rgba(57,169,0,.4);">
        <i class="fas fa-check"></i> {{ isset($instructor) ? 'Guardar cambios' : 'Registrar instructor' }}
    </button>
</div>
