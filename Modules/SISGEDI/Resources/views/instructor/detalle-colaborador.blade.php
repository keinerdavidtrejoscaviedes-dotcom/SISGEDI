@extends('sisgedi::layouts.instructor')

@section('content')
<div class="p-8 bg-white">
    <!-- Encabezado -->
    <div class="mb-8">
        <a href="{{ route('sisgedi.instructor.fichas-asignadas') }}" class="text-blue-600 hover:underline text-sm">
            ← Volver a Fichas y Colaboradores
        </a>
        <p class="text-sm text-gray-500 mb-2 mt-2">Inicio > Instructor > Fichas > Detalle Colaborador</p>
        @if($colaboradorInfo)
            <h1 class="text-3xl font-bold text-gray-800">{{ $colaboradorInfo->colaborador_name }}</h1>
            <p class="text-gray-600 mt-2">ID: {{ $colaboradorInfo->colaborador_id }}</p>
        @else
            <h1 class="text-3xl font-bold text-gray-800">Detalles del Colaborador</h1>
        @endif
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded shadow p-6 border-l-4 border-orange-600">
            <p class="text-gray-600 text-sm font-semibold uppercase">Tareas Pendientes</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $pending->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Por revisar y firmar</p>
        </div>

        <div class="bg-white rounded shadow p-6 border-l-4" style="border-left-color: #075547;">
            <p class="text-gray-600 text-sm font-semibold uppercase">Aprobadas</p>
            <p class="text-3xl font-bold mt-2" style="color: #075547;">{{ $approved->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Firmadas y completadas</p>
        </div>

        <div class="bg-white rounded shadow p-6 border-l-4 border-red-600">
            <p class="text-gray-600 text-sm font-semibold uppercase">Rechazadas</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejected->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Requieren resubmisión</p>
        </div>

        <div class="bg-white rounded shadow p-6 border-l-4 border-blue-600">
            <p class="text-gray-600 text-sm font-semibold uppercase">Total</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $tasks->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Todas las tareas</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mb-6 flex space-x-2">
        <button onclick="filterTasks('all')" class="px-4 py-2 text-white rounded font-semibold text-sm filter-btn" style="background-color: #075547;" data-filter="all">
            Todas ({{ $tasks->count() }})
        </button>
        <button onclick="filterTasks('pending')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm filter-btn" data-filter="pending">
            Pendientes ({{ $pending->count() }})
        </button>
        <button onclick="filterTasks('approved')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm filter-btn" data-filter="approved">
            Aprobadas ({{ $approved->count() }})
        </button>
        <button onclick="filterTasks('rejected')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm filter-btn" data-filter="rejected">
            Rechazadas ({{ $rejected->count() }})
        </button>
    </div>

    <!-- Tabla de Tareas -->
    <div class="bg-white rounded shadow overflow-hidden">
        @if($tasks->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tarea</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Asignada Por</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Asignada</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Plazo</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aprobada Por</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($tasks as $task)
                        <tr class="hover:bg-gray-50 task-row" data-status="{{ $task->status }}">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $task->deliverable_name }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $task->deliverable_id }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $task->leader_name ?? 'No asignado' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($task->assigned_at)
                                    {{ $task->assigned_at->format('d M Y - H:i a') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($task->deadline)
                                    <span class="text-gray-600">{{ $task->deadline->format('d M Y') }}</span>
                                    @if($task->deadline < now() && $task->status === 'pendiente')
                                        <span class="block text-xs text-red-600 font-semibold">⚠ Vencido</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($task->status === 'pendiente')
                                    <span class="inline-block bg-orange-100 text-orange-700 px-3 py-1 rounded text-xs font-semibold">
                                        ⏳ Pendiente
                                    </span>
                                @elseif($task->status === 'aprobado')
                                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">
                                        ✓ Aprobado
                                    </span>
                                @elseif($task->status === 'rechazado')
                                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-semibold">
                                        ✕ Rechazado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($task->approved_by && is_array($task->approved_by) && count($task->approved_by) > 0)
                                    <div class="space-y-1">
                                        @foreach($task->approved_by as $approver)
                                            <div class="text-xs">
                                                <p class="font-semibold text-gray-800">{{ $approver['instructor_name'] ?? 'Desconocido' }}</p>
                                                <p class="text-gray-500">{{ \Carbon\Carbon::parse($approver['approved_at'])->format('d M Y - H:i a') }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Fila de Detalles (expandible) -->
                        @if($task->status === 'rechazado' && $task->feedback)
                            <tr class="bg-red-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="bg-red-100 border border-red-300 rounded p-3">
                                        <p class="text-sm font-semibold text-red-800 mb-1">📌 Motivo del Rechazo:</p>
                                        <p class="text-sm text-red-700">{{ $task->feedback }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-12 text-center text-gray-600">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-semibold">No hay tareas asignadas</p>
            </div>
        @endif
    </div>
</div>

<script>
    function filterTasks(filter) {
        const rows = document.querySelectorAll('.task-row');
        const buttons = document.querySelectorAll('.filter-btn');

        // Actualizar botones
        buttons.forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        });
        event.target.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        event.target.classList.add('bg-blue-600', 'text-white');

        // Filtrar filas
        rows.forEach(row => {
            if (filter === 'all') {
                row.style.display = '';
            } else {
                row.style.display = row.dataset.status === filter ? '' : 'none';
            }
        });
    }
</script>
@endsection
