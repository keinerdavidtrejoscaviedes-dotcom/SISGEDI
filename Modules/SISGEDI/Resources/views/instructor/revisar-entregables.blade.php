@extends('sisgedi::layouts.instructor')

@section('content')
<div class="p-8 bg-white">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio > Instructor</p>
        <h1 class="text-3xl font-bold text-gray-800">Revisión de Entregables</h1>
    </div>

    <!-- Dos Columnas: Pendientes + Detalle -->
    <div class="grid grid-cols-2 gap-8">
        <!-- Entregables Pendientes -->
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Entregables Pendientes</h2>
            
            <div class="space-y-3">
                @forelse($pending as $item)
                    <div class="p-4 bg-green-50 border-l-4 border-green-600 rounded cursor-pointer hover:bg-green-100 transition" onclick="selectApproval({{ $item->id }})">
                        <p class="font-semibold text-gray-800">{{ $item->colaborador_name }}</p>
                        <p class="text-sm text-gray-600">{{ $item->deliverable_name }}</p>
                        <span class="inline-block bg-orange-100 text-orange-700 px-2 py-1 text-xs rounded font-semibold mt-2">
                            Pendiente
                        </span>
                    </div>
                @empty
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded text-center">
                        <p class="text-gray-600">No hay entregables pendientes por revisar.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Detalle del Entregable Seleccionado -->
        <div class="bg-white rounded shadow p-6">
            @if($approval)
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">{{ $approval->deliverable_name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Colaborador: {{ $approval->colaborador_name }} · Formato: {{ $approval->file_type }}</p>
                    </div>
                    <span class="inline-block bg-orange-100 text-orange-700 px-3 py-1 text-xs rounded font-semibold">
                        {{ ucfirst($approval->status) }}
                    </span>
                </div>

                <!-- Vista Previa del Documento -->
                <div class="bg-gray-100 rounded p-8 mb-6 text-center">
                    @if($evidences && count($evidences) > 0)
                        <div class="space-y-4">
                            <h4 class="font-semibold text-gray-800 mb-4">📎 Evidencias Cargadas ({{ count($evidences) }})</h4>
                            
                            @foreach($evidences as $evidence)
                                <div class="bg-white rounded p-4 border border-gray-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ $evidence->file_name }}</p>
                                            <p class="text-xs text-gray-600">
                                                {{ $evidence->file_type }} • {{ $evidence->file_size }} KB • 
                                                {{ $evidence->uploaded_at->format('d M Y - H:i a') }}
                                            </p>
                                            @if($evidence->description)
                                                <p class="text-sm text-gray-700 mt-2 italic">{{ $evidence->description }}</p>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex space-x-2">
                                            @if(strtolower($evidence->file_type) === 'pdf')
                                                <a href="{{ route('sisgedi.colaborador.evidences.download', $evidence->id) }}" 
                                                   class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-sm font-semibold">
                                                   📥 Descargar PDF
                                                </a>
                                            @elseif(in_array(strtolower($evidence->file_type), ['jpg', 'jpeg', 'png', 'gif']))
                                                <button onclick="viewImage('{{ $evidence->file_path }}', '{{ $evidence->file_name }}')"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-semibold">
                                                        👁️ Ver Imagen
                                                </button>
                                            @else
                                                <a href="{{ route('sisgedi.colaborador.evidences.download', $evidence->id) }}" 
                                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-semibold">
                                                   📥 Descargar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 font-medium">Sin evidencias cargadas aún</p>
                        <p class="text-sm text-gray-400 mt-2">El colaborador debe cargar los archivos en su dashboard</p>
                    @endif
                </div>

                <!-- Lista de Verificación -->
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-700 uppercase mb-4">Lista de verificación</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-green-600 rounded mr-3" checked>
                            <label class="text-sm text-gray-700">El documento cumple con el formato requerido</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-green-600 rounded mr-3">
                            <label class="text-sm text-gray-700">Las actividades descritas corresponden a la unidad productiva</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-green-600 rounded mr-3">
                            <label class="text-sm text-gray-700">Las horas reportadas son consistentes con la bitácora</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-green-600 rounded mr-3">
                            <label class="text-sm text-gray-700">El contenido es completo y no tiene información faltante</label>
                        </div>
                    </div>
                </div>

                <!-- Decisión del Instructor -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Decisión del Instructor — {{ session('sisgedi_user')['nombre'] }}</h3>
                    
                    <!-- Botón Aprobar -->
                    <form action="{{ route('sisgedi.instructor.approvals.approve', $approval->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full text-white font-bold py-3 px-6 rounded flex items-center justify-center space-x-2" style="background-color: #075547; hover: #075547;" onclick="return confirm('¿Deseas aprobar este entregable y firmarlo?')">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Firmar y Aprobar</span>
                        </button>
                    </form>

                    <!-- Botón Rechazar con Feedback -->
                    <button type="button" onclick="toggleRejectForm()" class="w-full text-white font-bold py-3 px-6 rounded" style="background-color: #075547;">
                        ✕ Rechazar Entregable
                    </button>

                    <!-- Formulario de Rechazo (oculto) -->
                    <div id="rejectForm" class="hidden mt-4 p-4 border rounded" style="background-color: #f0f9f7; border-color: #075547;">
                        <form action="{{ route('sisgedi.instructor.approvals.reject', $approval->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Motivo del Rechazo:</label>
                                <textarea name="feedback" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-red-600" placeholder="Escribe el motivo por el cual rechazas este entregable. Ej: Faltan horas, formato incorrecto, información incompleta, etc." required></textarea>
                                <p class="text-xs text-gray-500 mt-1">Mínimo 10 caracteres, máximo 500.</p>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" class="flex-1 text-white font-bold py-2 px-4 rounded" style="background-color: #075547;">
                                    Confirmar Rechazo
                                </button>
                                <button type="button" onclick="toggleRejectForm()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>

                    <p class="text-xs text-gray-500 text-center mt-3">
                        Tu firma es individual y solo aplica al entregable que estés revisando.
                    </p>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600">Selecciona un entregable de la lista para revisar.</p>
                </div>
            @endif

            <script>
                function toggleRejectForm() {
                    const form = document.getElementById('rejectForm');
                    form.classList.toggle('hidden');
                }

                function selectApproval(id) {
                    // Aquí iría la lógica para cargar el entregable seleccionado
                    // Por ahora, simplemente recargamos la página
                    window.location.href = '?approval=' + id;
                }

                function viewImage(path, filename) {
                    const modal = document.getElementById('imageModal');
                    const img = document.getElementById('modalImage');
                    const title = document.getElementById('imageTitle');
                    
                    title.textContent = filename;
                    img.src = '{{ asset('') }}' + path;
                    modal.classList.remove('hidden');
                }

                function closeImageModal() {
                    const modal = document.getElementById('imageModal');
                    modal.classList.add('hidden');
                }
            </script>

            <!-- Modal para ver imágenes -->
            <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-4xl max-h-[90vh] overflow-auto">
                    <div class="sticky top-0 bg-gray-100 px-6 py-4 flex justify-between items-center border-b">
                        <h3 id="imageTitle" class="text-lg font-bold text-gray-800"></h3>
                        <button onclick="closeImageModal()" class="text-gray-600 hover:text-gray-800 text-2xl">✕</button>
                    </div>
                    <div class="p-6 text-center">
                        <img id="modalImage" src="" alt="Imagen" class="max-w-full max-h-[80vh] object-contain mx-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
