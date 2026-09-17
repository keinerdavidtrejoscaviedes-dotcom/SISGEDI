@extends('sisgedi::layouts.colaborador')

@section('content')
<div class="p-8 bg-white">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio • Colaborador</p>
        <h1 class="text-4xl font-bold text-gray-800">Mis Tareas Asignadas</h1>
    </div>

    <!-- Mensajes de Éxito/Error -->
    @if ($message = Session::get('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded text-green-700 text-sm">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
            {{ $message }}
        </div>
    @endif

    <!-- Tabla -->
    <div class="bg-white rounded shadow overflow-hidden">
        @if($tasksWithEvidence && count($tasksWithEvidence) > 0)
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tarea</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Instructor</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Evidencias</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($tasksWithEvidence as $task)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 text-sm">{{ $task->deliverable_name }}</div>
                                <div class="text-xs text-gray-500 mt-1">Entregable ID: {{ $task->deliverable_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $task->instructor_name }}</td>
                            <td class="px-6 py-4">
                                @if($task->status === 'pendiente')
                                    <span class="inline-block bg-orange-100 text-orange-700 px-3 py-1 rounded text-xs font-semibold">
                                        Pendiente
                                    </span>
                                @elseif($task->status === 'aprobado')
                                    <span class="inline-block px-3 py-1 rounded text-xs font-semibold" style="background-color: #e6f4f1; color: #075547;">
                                        ✓ Aprobado
                                    </span>
                                @elseif($task->status === 'rechazado')
                                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-semibold">
                                        ✕ Rechazado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="text-gray-700 font-medium">{{ $task->evidences_count }} archivo(s)</span>
                                @if($task->evidences_count > 0)
                                    <div class="mt-2 space-y-1">
                                        @foreach($task->evidences as $evidence)
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('sisgedi.colaborador.evidences.download', $evidence->id) }}" 
                                                   class="text-blue-600 hover:underline text-xs truncate"
                                                   title="{{ $evidence->file_name }}">
                                                    📎 {{ substr($evidence->file_name, 0, 25) }}{{ strlen($evidence->file_name) > 25 ? '...' : '' }}
                                                </a>
                                                @if($task->status === 'pendiente' || $task->status === 'rechazado')
                                                    <form action="{{ route('sisgedi.colaborador.evidences.delete', $evidence->id) }}" 
                                                          method="POST" class="inline"
                                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta evidencia?')">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs">✕</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($task->status === 'pendiente' || $task->status === 'rechazado')
                                    <button 
                                        onclick="toggleUploadForm({{ $task->id }})" 
                                        class="text-white text-xs px-4 py-2 rounded font-semibold" 
                                        style="background-color: #075547;">
                                        + Subir Evidencia
                                    </button>
                                @elseif($task->status === 'aprobado')
                                    <span class="text-gray-500 text-xs px-3 py-2">Completada</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Formulario oculto de carga -->
                        @if($task->status === 'pendiente' || $task->status === 'rechazado')
                            <tr id="uploadForm-{{ $task->id }}" class="hidden bg-blue-50">
                                <td colspan="5" class="px-6 py-6">
                                    <div class="max-w-2xl">
                                        <h3 class="font-semibold text-gray-800 mb-4">Cargar Evidencia: {{ $task->deliverable_name }}</h3>
                                        
                                        <form action="{{ route('sisgedi.colaborador.evidences.upload', $task->id) }}" 
                                              method="POST" 
                                              enctype="multipart/form-data"
                                              class="space-y-4">
                                            @csrf

                                            <!-- Zona de carga -->
                                            <div class="border-2 border-dashed border-gray-300 rounded p-8 text-center cursor-pointer transition" 
                                                 style="border-color: #075547;"
                                                 onmouseover="this.style.borderColor='#054239'"
                                                 onmouseout="this.style.borderColor='#075547'"
                                                 onclick="document.getElementById('file-{{ $task->id }}').click()">
                                                <input type="file" 
                                                       id="file-{{ $task->id }}" 
                                                       name="evidence_file" 
                                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.zip"
                                                       class="hidden"
                                                       required
                                                       onchange="updateFileName({{ $task->id }}, this)">
                                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                <p class="text-gray-700 font-semibold" id="fileLabel-{{ $task->id }}">Haz clic para seleccionar archivo</p>
                                                <p class="text-sm text-gray-600 mt-2">PDF, Word, Excel, PowerPoint, Imagen o ZIP (máx. 10MB)</p>
                                            </div>

                                            <!-- Descripción -->
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción (opcional):</label>
                                                <textarea name="description" 
                                                          rows="3"
                                                          class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-600"
                                                          placeholder="Describe brevemente qué contiene este archivo..."></textarea>
                                            </div>

                                            <!-- Botones -->
                                            <div class="flex space-x-3">
                                                <button type="submit" class="flex-1 text-white font-bold py-2 px-4 rounded" style="background-color: #075547;">
                                                    Subir Evidencia
                                                </button>
                                                <button type="button" 
                                                        onclick="toggleUploadForm({{ $task->id }})"
                                                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        <!-- Fila de Comentarios/Rechazos -->
                        @if($task->status === 'rechazado' && $task->feedback)
                            <tr class="bg-red-50">
                                <td colspan="5" class="px-6 py-4">
                                    <div class="bg-red-100 border border-red-300 rounded p-4">
                                        <h4 class="font-semibold text-red-800 mb-2">Comentarios del Instructor:</h4>
                                        <p class="text-sm text-red-700">{{ $task->feedback }}</p>
                                        <p class="text-xs text-red-600 mt-2">Rechazado el: {{ $task->reviewed_at ? $task->reviewed_at->format('d M Y - H:i a') : 'N/A' }}</p>
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
                <p class="text-lg font-semibold mb-2">No hay tareas asignadas</p>
                <p class="text-sm">Las tareas que te asigne tu instructor aparecerán aquí.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleUploadForm(taskId) {
        const form = document.getElementById('uploadForm-' + taskId);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }

    function updateFileName(taskId, input) {
        if (input.files && input.files[0]) {
            document.getElementById('fileLabel-' + taskId).textContent = input.files[0].name;
        }
    }
</script>
@endsection
