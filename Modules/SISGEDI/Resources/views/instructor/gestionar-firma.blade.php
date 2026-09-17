@extends('sisgedi::layouts.instructor')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio > Instructor</p>
        <h1 class="text-3xl font-bold text-gray-800">Mi Firma Digital</h1>
    </div>

    <!-- Dos Columnas: Cargar Firma + Historial -->
    <div class="grid grid-cols-2 gap-8">
        <!-- Cargar Firma -->
        <div class="bg-white rounded shadow p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Cargar Firma Digital</h2>
            
            <form action="{{ route('sisgedi.instructor.signatures.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div class="border-2 border-dashed border-gray-300 rounded p-8 text-center cursor-pointer hover:border-green-600 transition" onclick="document.getElementById('signatureFile').click()">
                    <input type="file" name="signature_file" id="signatureFile" accept="image/*" class="hidden" required onchange="updateFileName(this)">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <p class="text-gray-700 font-semibold" id="fileLabel">Haz clic para seleccionar tu firma</p>
                    <p class="text-sm text-gray-600 mt-2">PNG, JPG, JPEG, GIF (máx. 2MB)</p>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Cargar Firma
                    </button>
                    <button type="button" onclick="document.getElementById('signatureFile').value=''; document.getElementById('fileLabel').textContent='Haz clic para seleccionar tu firma'" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Limpiar
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-4">
                    <strong>Instrucciones:</strong>
                </p>
                <ul class="text-sm text-gray-600 space-y-2 list-disc list-inside">
                    <li>Selecciona una imagen de tu firma (PNG, JPG, GIF)</li>
                    <li>El archivo no debe superar 2MB</li>
                    <li>Una vez cargada, podrás activarla desde el historial</li>
                    <li>Solo una firma puede estar activa a la vez</li>
                </ul>
            </div>

            <script>
                function updateFileName(input) {
                    if (input.files && input.files[0]) {
                        document.getElementById('fileLabel').textContent = input.files[0].name;
                    }
                }
            </script>
        </div>

        <!-- Historial de Firmas -->
        <div class="bg-white rounded shadow p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Historial de Firmas</h2>
            <p class="text-sm text-gray-600 mb-6">
                Las firmas anteriores se conservan y no invalidan documentos ya firmados.
            </p>

            <div class="space-y-4">
                @forelse($versions as $signature)
                    <div class="border border-gray-200 rounded p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $signature->version }}</h3>
                                <p class="text-sm text-gray-600">Cargada {{ $signature->uploaded_at->format('d M Y') }}</p>
                                <p class="text-sm text-gray-700 font-medium mt-1">{{ $signature->getSignedDocumentsCount() ?? 0 }} docs firmados</p>
                            </div>
                            @if($signature->is_active)
                                <span class="bg-green-100 text-green-700 px-2 py-1 text-xs rounded font-semibold whitespace-nowrap">
                                    Activa
                                </span>
                            @endif
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex space-x-2 mt-4">
                            <button onclick="viewSignature('{{ asset($signature->file_path) }}', '{{ $signature->version }}')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 px-3 rounded">
                                Ver Firma
                            </button>
                            @if(!$signature->is_active)
                                <form action="{{ route('sisgedi.instructor.signatures.replace') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="signature_id" value="{{ $signature->id }}">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-2 px-3 rounded">
                                        Activar
                                    </button>
                                </form>
                            @else
                                <button disabled class="flex-1 bg-gray-300 text-gray-600 text-xs font-semibold py-2 px-3 rounded cursor-not-allowed">
                                    Activada
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 bg-gray-50 border border-gray-200 rounded text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 text-sm">No hay firmas en el historial.</p>
                        <p class="text-gray-500 text-xs mt-1">Carga una firma usando el formulario de la izquierda.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal para Ver Firma -->
    <div id="signatureModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded shadow-lg max-w-2xl w-full max-h-96 overflow-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Ver Firma</h3>
                <button onclick="closeSignatureModal()" class="text-gray-600 hover:text-gray-800 text-2xl">×</button>
            </div>
            <div class="p-6 text-center">
                <img id="signatureImage" src="" alt="Firma" class="max-h-64 mx-auto">
            </div>
            <div class="p-6 border-t border-gray-200 flex justify-end">
                <button onclick="closeSignatureModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        function viewSignature(imagePath, version) {
            document.getElementById('modalTitle').textContent = 'Firma: ' + version;
            document.getElementById('signatureImage').src = imagePath;
            document.getElementById('signatureModal').classList.remove('hidden');
        }

        function closeSignatureModal() {
            document.getElementById('signatureModal').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('signatureModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSignatureModal();
            }
        });
    </script>
</div>
@endsection
