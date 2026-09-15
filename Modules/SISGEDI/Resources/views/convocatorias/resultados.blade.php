@extends('sisgedi::layouts.dashboard')
@section('title', 'Resultados de Selección')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label'=>'Inicio','url'=>route('sisgedi.dashboard')],
                ['label'=>'Convocatorias y Selección','url'=>route('sisgedi.convocatorias.index')],
                ['label'=>'Resultados de Selección','url'=>null],
            ],
            'titulo' => 'Resultados de Selección',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                <th class="px-6 py-4 text-left">Cargo</th>
                                <th class="px-6 py-4 text-left">Postulantes</th>
                                <th class="px-6 py-4 text-left">Seleccionado</th>
                                <th class="px-6 py-4 text-left">Puntaje</th>
                                <th class="px-6 py-4 text-left">Estado</th>
                                <th class="px-6 py-4 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($resultados as $r)
                            <tr class="hover:bg-gray-50 transition-colors" id="fila-{{ $loop->index }}">
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $r->cargo }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $r->postulantes }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $r->seleccionado }}</td>
                                <td class="px-6 py-4 font-bold text-green-600">{{ $r->puntaje }}%</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                          style="background:#DCFCE7; color:#15803D;">
                                        {{ $r->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="toggleDetalle({{ $loop->index }})"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center
                                                   text-gray-400 hover:bg-gray-100 hover:text-gray-600
                                                   transition-colors">
                                        <i class="fas fa-chevron-down text-xs" id="ico-{{ $loop->index }}"></i>
                                    </button>
                                </td>
                            </tr>
                            {{-- Fila de detalle colapsable --}}
                            <tr id="detalle-{{ $loop->index }}" class="hidden bg-gray-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="text-xs text-gray-600 space-y-1">
                                        <p class="font-semibold text-gray-700 mb-2">Detalle del proceso</p>
                                        <p>· Candidato seleccionado tras evaluación de {{ $r->postulantes }} postulantes.</p>
                                        <p>· Puntaje obtenido: <strong class="text-green-600">{{ $r->puntaje }}%</strong> (umbral mínimo: 70%)</p>
                                        <p>· Estado final: <strong>{{ $r->estado }}</strong></p>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@section('script')
<script>
function toggleDetalle(idx) {
    var fila = document.getElementById('detalle-' + idx);
    var ico  = document.getElementById('ico-' + idx);
    fila.classList.toggle('hidden');
    ico.classList.toggle('rotate-180');
    ico.style.transition = 'transform 0.2s';
}
</script>
@endsection
