@extends('sisgedi::layouts.master')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('sisgedi.gerente.dashboard') }}"
               class="text-xs font-semibold text-gray-400 hover:text-sena-green">
                <i class="fas fa-arrow-left mr-1"></i> Volver al panel
            </a>
            <h1 class="text-2xl font-extrabold mt-2" style="color:#001A29;">
                Plan de trabajo general — {{ $fase->nombre }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Entregas, reuniones de seguimiento e hitos de la fase, visibles para todos los roles (RN-028).
            </p>
        </div>
        @if($plan)
            <a href="{{ route('sisgedi.plan-trabajo.show') }}" target="_blank"
               class="inline-flex items-center gap-2 text-sena-green text-sm font-semibold px-4 py-2.5 rounded-lg border border-sena-green hover:bg-green-50">
                <i class="fas fa-eye"></i> Ver como lo verán los demás roles
            </a>
        @endif
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

    <form action="{{ route('sisgedi.gerente.plan-trabajo.save') }}" method="POST"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción general del plan</label>
            <textarea name="descripcion" rows="3" required
                      class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green"
                      placeholder="Ej: Plan de trabajo para el segundo trimestre de la fase.">{{ old('descripcion', $plan->descripcion ?? '') }}</textarea>
        </div>

        <hr class="border-gray-100">

        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-sm" style="color:#001A29;">
                    <i class="fas fa-calendar-days text-sena-green mr-1"></i> Hitos, entregas y reuniones
                </h2>
                <button type="button" id="btn-agregar-hito"
                        class="text-xs font-semibold text-sena-green hover:underline">
                    <i class="fas fa-plus mr-1"></i> Agregar hito
                </button>
            </div>

            <div id="hitos-contenedor" class="space-y-3">
                @php $hitosIniciales = old('hitos', $plan?->hitos->map(fn($h) => ['titulo' => $h->titulo, 'fecha' => $h->fecha->toDateString(), 'tipo' => $h->tipo])->toArray() ?? [[]]); @endphp
                @foreach($hitosIniciales as $i => $hito)
                    <div class="hito-fila grid grid-cols-1 md:grid-cols-[1fr_160px_150px_auto] gap-2 items-start">
                        <input type="text" name="hitos[{{ $i }}][titulo]" value="{{ $hito['titulo'] ?? '' }}" required
                               placeholder="Ej: Entrega de informe mensual"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                        <input type="date" name="hitos[{{ $i }}][fecha]" value="{{ $hito['fecha'] ?? '' }}" required
                               class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                        <select name="hitos[{{ $i }}][tipo]" required
                                class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                            <option value="entrega" @selected(($hito['tipo'] ?? '') == 'entrega')>Entrega</option>
                            <option value="reunion" @selected(($hito['tipo'] ?? '') == 'reunion')>Reunión</option>
                            <option value="hito" @selected(($hito['tipo'] ?? '') == 'hito')>Hito</option>
                        </select>
                        <button type="button"
                                class="btn-quitar-hito text-red-400 hover:text-red-600 px-2 py-2.5"
                                title="Quitar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
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
                <i class="fas fa-paper-plane"></i> {{ $plan ? 'Actualizar plan' : 'Publicar plan de trabajo' }}
            </button>
        </div>
    </form>
</div>

@section('script')
<script>
    (function () {
        const contenedor = document.getElementById('hitos-contenedor');
        const btnAgregar = document.getElementById('btn-agregar-hito');
        let indice = {{ count($hitosIniciales) }};

        function filaHtml(i) {
            return `
                <div class="hito-fila grid grid-cols-1 md:grid-cols-[1fr_160px_150px_auto] gap-2 items-start">
                    <input type="text" name="hitos[${i}][titulo]" required
                           placeholder="Ej: Entrega de informe mensual"
                           class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                    <input type="date" name="hitos[${i}][fecha]" required
                           class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                    <select name="hitos[${i}][tipo]" required
                            class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sena-green">
                        <option value="entrega">Entrega</option>
                        <option value="reunion">Reunión</option>
                        <option value="hito" selected>Hito</option>
                    </select>
                    <button type="button" class="btn-quitar-hito text-red-400 hover:text-red-600 px-2 py-2.5" title="Quitar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>`;
        }

        btnAgregar.addEventListener('click', function () {
            contenedor.insertAdjacentHTML('beforeend', filaHtml(indice));
            indice++;
        });

        contenedor.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-quitar-hito');
            if (!btn) return;
            if (contenedor.querySelectorAll('.hito-fila').length <= 1) return;
            btn.closest('.hito-fila').remove();
        });
    })();
</script>
@endsection
@endsection
