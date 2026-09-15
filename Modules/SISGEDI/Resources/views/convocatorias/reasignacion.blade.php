@extends('sisgedi::layouts.dashboard')
@section('title', 'Reasignación')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label'=>'Inicio','url'=>route('sisgedi.dashboard')],
                ['label'=>'Convocatorias y Selección','url'=>route('sisgedi.convocatorias.index')],
                ['label'=>'Reasignación','url'=>null],
            ],
            'titulo' => 'Reasignación',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            @if(session('success'))
            <div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700
                        text-sm px-4 py-3 rounded-xl">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Reasignaciones Pendientes</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                <th class="px-6 py-4 text-left">Cargo Original</th>
                                <th class="px-6 py-4 text-left">Colaborador Actual</th>
                                <th class="px-6 py-4 text-left">Nuevo Asignado</th>
                                <th class="px-6 py-4 text-left">Estado</th>
                                <th class="px-6 py-4 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($reasignaciones as $rea)
                            <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $loop->index }}">
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $rea->cargo_original }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $rea->colaborador_actual }}</td>
                                <td class="px-6 py-4 text-gray-600" id="nuevo-{{ $loop->index }}">
                                    @if($rea->nuevo_asignado)
                                        {{ $rea->nuevo_asignado }}
                                    @else
                                        <span class="text-gray-400 italic">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($rea->estado === 'Pendiente')
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                              style="background:#FEF9C3; color:#92400E;">Pendiente</span>
                                    @else
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                              style="background:#DCFCE7; color:#15803D;">Reasignado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button"
                                            onclick="abrirModal({{ $loop->index }}, '{{ $rea->cargo_original }}', '{{ $rea->colaborador_actual }}')"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold
                                                   text-gray-600 hover:text-gray-800 transition-colors px-3 py-1.5
                                                   border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <i class="fas fa-exchange-alt text-[10px]"></i> Reasignar
                                    </button>
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

{{-- Modal de reasignación --}}
<div id="modalReasig" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-base">Reasignar Cargo</h3>
            <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-1 text-sm text-gray-600 bg-gray-50 rounded-xl p-4">
            <p><span class="font-semibold">Cargo:</span> <span id="modalCargo"></span></p>
            <p><span class="font-semibold">Colaborador actual:</span> <span id="modalColaborador"></span></p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Nuevo Asignado</label>
            <input type="text" id="nuevoNombre" placeholder="Nombre del nuevo colaborador"
                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm
                          focus:outline-none focus:border-green-500 focus:ring-2
                          focus:ring-green-500/20 transition-all">
        </div>
        <div class="flex gap-3 pt-2">
            <button onclick="confirmarReasig()"
                    class="flex-1 py-2.5 text-white text-sm font-bold rounded-lg transition-all hover:opacity-90"
                    style="background:#39A900;">
                Confirmar Reasignación
            </button>
            <button onclick="cerrarModal()"
                    class="flex-1 py-2.5 text-gray-600 text-sm font-semibold rounded-lg
                           border border-gray-200 hover:bg-gray-50 transition-all">
                Cancelar
            </button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
var filaActual = null;

function abrirModal(idx, cargo, colaborador) {
    filaActual = idx;
    document.getElementById('modalCargo').textContent       = cargo;
    document.getElementById('modalColaborador').textContent = colaborador;
    document.getElementById('nuevoNombre').value            = '';
    document.getElementById('modalReasig').classList.remove('hidden');
    document.getElementById('nuevoNombre').focus();
}

function cerrarModal() {
    document.getElementById('modalReasig').classList.add('hidden');
    filaActual = null;
}

function confirmarReasig() {
    var nombre = document.getElementById('nuevoNombre').value.trim();
    if (!nombre) {
        document.getElementById('nuevoNombre').style.borderColor = '#EF4444';
        return;
    }
    if (filaActual !== null) {
        // Actualizar la celda en la tabla
        document.getElementById('nuevo-' + filaActual).textContent = nombre;
        // Actualizar el badge de estado
        var fila   = document.getElementById('row-' + filaActual);
        var badges = fila.querySelectorAll('td span[style]');
        badges.forEach(function(b) {
            b.style.background = '#DCFCE7';
            b.style.color      = '#15803D';
            b.textContent      = 'Reasignado';
        });
    }
    cerrarModal();
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalReasig').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endsection
