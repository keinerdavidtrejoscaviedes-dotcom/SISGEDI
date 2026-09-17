@extends('sisgedi::layouts.colaborador')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <nav class="flex items-center space-x-2 text-sm mb-4">
            <span class="text-gray-600">Inicio</span>
            <span class="text-gray-400">›</span>
            <span class="text-gray-800 font-semibold">Colaborador</span>
            <span class="text-gray-400">›</span>
            <span class="text-gray-800 font-semibold">Documentación Final de Fase</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-800">Documentación Final de Fase</h1>
    </div>

    <!-- Alerta de fecha límite -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded p-4 mb-8 flex items-start">
        <svg class="w-6 h-6 text-yellow-600 mr-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <div>
            <h3 class="font-bold text-yellow-800">Fecha límite de entrega: 30 Sep 2026</h3>
            <p class="text-sm text-yellow-700 mt-1">Quedan 35 días. 4 documentos pendientes.</p>
        </div>
    </div>

    <!-- Checklist de Documentos Requeridos -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Documento</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Formato</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Fecha Carga</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <!-- Póster Final -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Póster Final</div>
                            <div class="text-xs text-gray-500">Presentación visual del proyecto</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF/Imagen</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">Cargado</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">15 Ago 2026</td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                        </td>
                    </tr>

                    <!-- Informe de Resultados de Gestión -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Informe de Resultados de Gestión</div>
                            <div class="text-xs text-gray-500">Análisis de resultados de la fase</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">Cargado</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">15 Ago 2026</td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                        </td>
                    </tr>

                    <!-- Evidencias Fotográficas -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Evidencias Fotográficas</div>
                            <div class="text-xs text-gray-500">Registro visual de actividades</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">ZIP (Imágenes)</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">Cargado</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">20 Ago 2026</td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                        </td>
                    </tr>

                    <!-- Informe Gerencial -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Informe Gerencial</div>
                            <div class="text-xs text-gray-500">Informe de gestión gerencial</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-semibold">Pendiente</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">—</td>
                        <td class="px-6 py-4">
                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded">Cargar</button>
                        </td>
                    </tr>

                    <!-- Página Web si aplica -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Página Web si aplica</div>
                            <div class="text-xs text-gray-500">URL de página web del proyecto</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">URL</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs font-semibold">No aplica</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">—</td>
                        <td class="px-6 py-4">
                            <button class="text-gray-600 text-xs font-semibold cursor-not-allowed" disabled>—</button>
                        </td>
                    </tr>

                    <!-- Diagnóstico Inicial -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Diagnóstico Inicial</div>
                            <div class="text-xs text-gray-500">Diagnóstico inicial del sector/unidad</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">Cargado</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">10 Ago 2026</td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                        </td>
                    </tr>

                    <!-- Plan de Innovación -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Plan de Innovación</div>
                            <div class="text-xs text-gray-500">Plan de innovación y mejora</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">Cargado</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">12 Ago 2026</td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                        </td>
                    </tr>

                    <!-- Plan de Trabajo -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Plan de Trabajo</div>
                            <div class="text-xs text-gray-500">Plan de trabajo de la fase</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-semibold">Pendiente</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">—</td>
                        <td class="px-6 py-4">
                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded">Cargar</button>
                        </td>
                    </tr>

                    <!-- Bitácora 1 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Bitácora 1</div>
                            <div class="text-xs text-gray-500">Bitácora 1 (Inducción)</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-semibold">Cargado</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">01 Ago 2026</td>
                        <td class="px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                        </td>
                    </tr>

                    <!-- Bitácora 2 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Bitácora 2</div>
                            <div class="text-xs text-gray-500">Bitácora 2 (Formación)</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-semibold">Pendiente</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">—</td>
                        <td class="px-6 py-4">
                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded">Cargar</button>
                        </td>
                    </tr>

                    <!-- Paz y Salvo Firmado -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Paz y Salvo Firmado</div>
                            <div class="text-xs text-gray-500">Documento de paz y salvo firmado</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">PDF</td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-semibold">Pendiente</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">—</td>
                        <td class="px-6 py-4">
                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-4 py-2 rounded">Cargar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botón de descarga de checklist -->
    <div class="mt-8 text-center">
        <button class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Descargar Checklist de Entrega
        </button>
    </div>
</div>
@endsection
