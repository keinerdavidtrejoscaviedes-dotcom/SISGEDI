@extends('sisgedi::layouts.colaborador')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio • Colaborador</p>
        <h1 class="text-3xl font-bold text-gray-800">Mis Evidencias</h1>
    </div>

    <!-- Obtener datos del colaborador actual -->
    @php
        $usuario = session('sisgedi_user');
        $colaborador_id = $usuario['id'] ?? null;
        
        // Obtener todas las tareas/aprobaciones del colaborador
        $allApprovals = $colaborador_id ? \Modules\SISGEDI\Entities\Approval::where('colaborador_id', $colaborador_id)->get() : collect();
        
        // Separar por estado
        $pendientes = $allApprovals->where('status', 'pendiente');
        $aprobadas = $allApprovals->where('status', 'aprobado');
        $rechazadas = $allApprovals->where('status', 'rechazado');
    @endphp

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded shadow p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm font-semibold uppercase">Total Enviadas</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $allApprovals->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Todas tus evidencias</p>
        </div>

        <div class="bg-white rounded shadow p-6 border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm font-semibold uppercase">En Revisión</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $pendientes->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Pendientes de aprobación</p>
        </div>

        <div class="bg-white rounded shadow p-6 border-l-4" style="border-left-color: #075547;">
            <p class="text-gray-600 text-sm font-semibold uppercase">Aprobadas</p>
            <p class="text-3xl font-bold mt-2" style="color: #075547;">{{ $aprobadas->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Firmadas y completadas</p>
        </div>

        <div class="bg-white rounded shadow p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm font-semibold uppercase">Rechazadas</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $rechazadas->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Requieren resubmisión</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mb-6 flex space-x-2">
        <button onclick="filterEvidences('all')" class="px-4 py-2 text-white rounded font-semibold text-sm filter-btn active-btn" style="background-color: #075547;" data-filter="all">
            Todas ({{ $allApprovals->count() }})
        </button>
        <button onclick="filterEvidences('pending')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm filter-btn" data-filter="pending">
            En Revisión ({{ $pendientes->count() }})
        </button>
        <button onclick="filterEvidences('approved')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm filter-btn" data-filter="approved">
            Aprobadas ({{ $aprobadas->count() }})
        </button>
        <button onclick="filterEvidences('rejected')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm filter-btn" data-filter="rejected">
            Rechazadas ({{ $rechazadas->count() }})
        </button>
    </div>

    <!-- Lista de Evidencias -->
    <div class="space-y-4">
        @if($allApprovals->count() > 0)
            @foreach($allApprovals as $approval)
                @php
                    $statusClass = '';
                    $badgeClass = '';
                    $statusLabel = '';
                    $statusKey = '';
                    
                    if($approval->status === 'pendiente') {
                        $statusClass = 'border-yellow-500';
                        $badgeClass = 'bg-yellow-100 text-yellow-700';
                        $statusLabel = '⏳ En Revisión';
                        $statusKey = 'pending';
                    } elseif($approval->status === 'aprobado') {
                        $statusClass = 'border-0';
                        $badgeClass = 'px-3 py-1 rounded text-xs font-semibold';
                        $statusLabel = '✓ Aprobada';
                        $statusKey = 'approved';
                    } elseif($approval->status === 'rechazado') {
                        $statusClass = 'border-red-500';
                        $badgeClass = 'bg-red-100 text-red-700';
                        $statusLabel = '✕ Rechazada';
                        $statusKey = 'rejected';
                    }
                @endphp

                <div class="bg-white rounded shadow p-6 border-l-4 {{ $statusClass }} evidence-item" data-status="{{ $statusKey }}">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <span class="inline-block {{ $badgeClass }} px-3 py-1 rounded text-xs font-semibold mb-2">
                                {{ $statusLabel }}
                            </span>
                            <h3 class="text-lg font-bold text-gray-800">{{ $approval->deliverable_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <strong>Instructor:</strong> {{ $approval->instructor_name }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <strong>Entregable ID:</strong> {{ $approval->deliverable_id }} • 
                                <strong>Estado:</strong> 
                                @if($approval->status === 'pendiente')
                                    Pendiente de revisión
                                @elseif($approval->status === 'aprobado')
                                    Aprobado y firmado
                                @elseif($approval->status === 'rechazado')
                                    Rechazado - Requiere corrección
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Evidencias de esta tarea -->
                    @php
                        $evidences = \Modules\SISGEDI\Entities\Evidence::where('approval_id', $approval->id)->get();
                    @endphp

                    @if($evidences && count($evidences) > 0)
                        <div class="bg-gray-50 rounded p-4 mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">📎 Archivos Subidos ({{ count($evidences) }}):</p>
                            <div class="space-y-2">
                                @foreach($evidences as $evidence)
                                    <div class="flex items-center justify-between bg-white p-3 rounded border border-gray-200">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">{{ $evidence->file_name }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ strtoupper(pathinfo($evidence->file_path, PATHINFO_EXTENSION)) }} • 
                                                {{ number_format($evidence->file_size / 1024, 2) }} KB • 
                                                {{ $evidence->uploaded_at->format('d M Y - H:i a') }}
                                            </p>
                                            @if($evidence->description)
                                                <p class="text-xs text-gray-600 italic mt-1">"{{ $evidence->description }}"</p>
                                            @endif
                                        </div>
                                        <a href="{{ route('sisgedi.colaborador.evidences.download', $evidence->id) }}" 
                                           class="ml-4 text-white px-3 py-1 rounded text-xs font-semibold whitespace-nowrap" style="background-color: #075547;">
                                            Descargar
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded p-4 mb-4 text-center">
                            <p class="text-xs text-gray-500">Sin evidencias cargadas</p>
                        </div>
                    @endif

                    <!-- Información de aprobación -->
                    @if($approval->status === 'aprobado' && $approval->approved_by)
                        <div class="bg-green-50 border border-green-200 rounded p-4 mb-4">
                            <p class="text-sm font-semibold text-green-800 mb-2">✓ Aprobada por:</p>
                            @if(is_array($approval->approved_by))
                                @foreach($approval->approved_by as $approver)
                                    <div class="text-sm text-green-700 mb-2">
                                        <p>👤 {{ $approver['instructor_name'] ?? 'Desconocido' }}</p>
                                        <p class="text-xs text-green-600">
                                            📅 {{ \Carbon\Carbon::parse($approver['approved_at'])->format('d M Y - H:i a') }}
                                        </p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif

                    <!-- Información de rechazo -->
                    @if($approval->status === 'rechazado' && $approval->feedback)
                        <div class="bg-red-50 border border-red-200 rounded p-4 mb-4">
                            <p class="text-sm font-semibold text-red-800 mb-2">📌 Motivo del Rechazo:</p>
                            <p class="text-sm text-red-700">{{ $approval->feedback }}</p>
                            @if($approval->reviewed_at)
                                <p class="text-xs text-red-600 mt-2">
                                    ⏰ Rechazado el: {{ $approval->reviewed_at->format('d M Y - H:i a') }}
                                </p>
                            @endif
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('sisgedi.colaborador.mis-tareas') }}" 
                               class="text-white px-4 py-2 rounded text-sm font-semibold" style="background-color: #075547;">
                                ↻ Resubmitir Evidencia
                            </a>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    @if($approval->assigned_at || $approval->deadline)
                        <div class="text-xs text-gray-500 pt-4 border-t border-gray-200 mt-4 space-y-1">
                            @if($approval->assigned_at)
                                <p>📅 Asignada: {{ $approval->assigned_at->format('d M Y') }}</p>
                            @endif
                            @if($approval->deadline)
                                <p>
                                    ⏰ Plazo: {{ $approval->deadline->format('d M Y') }}
                                    @if($approval->deadline < now() && $approval->status === 'pendiente')
                                        <span class="text-red-600 font-semibold ml-2">⚠ VENCIDO</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="bg-white rounded shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <p class="text-lg font-semibold text-gray-600 mb-2">No hay evidencias</p>
                <p class="text-gray-500 mb-4">Aún no has subido ninguna evidencia</p>
                <a href="{{ route('sisgedi.colaborador.mis-tareas') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold inline-block">
                    Ir a Mis Tareas
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function filterEvidences(filter) {
        const items = document.querySelectorAll('.evidence-item');
        const buttons = document.querySelectorAll('.filter-btn');

        // Actualizar botones
        buttons.forEach(btn => {
            btn.classList.remove('text-white');
            btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            btn.style.backgroundColor = '';
        });
        
        event.target.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        event.target.classList.add('text-white');
        event.target.style.backgroundColor = '#075547';

        // Filtrar items
        const statusMap = {
            'all': null,
            'pending': 'pending',
            'approved': 'approved',
            'rejected': 'rejected'
        };

        items.forEach(item => {
            if (filter === 'all') {
                item.style.display = '';
            } else {
                item.style.display = item.dataset.status === statusMap[filter] ? '' : 'none';
            }
        });
    }
</script>
@endsection
