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
            <span class="text-gray-800 font-semibold">Proceso de Paz y Salvo</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-800">Proceso de Paz y Salvo</h1>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Firmas Completadas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">2 / 4</p>
                </div>
                <svg class="w-12 h-12 text-orange-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Fecha Límite</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">30 Sep 2026</p>
                </div>
                <svg class="w-12 h-12 text-yellow-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Estado</p>
                    <p class="text-lg font-bold text-gray-800 mt-2">
                        <span class="inline-block px-3 py-1 rounded text-sm" style="background-color: #e6f4f1; color: #075547;">En Proceso</span>
                    </p>
                </div>
                <svg class="w-12 h-12 text-blue-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Firmantes Requeridos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Firmantes Requeridos</h2>

            <!-- Ana Torres - Instructora -->
            <div class="mb-6 pb-6 border-b">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">Ana Torres</h3>
                        <p class="text-sm text-gray-600">Instructora</p>
                    </div>
                    <span class="inline-block px-3 py-1 rounded text-xs font-semibold" style="background-color: #e6f4f1; color: #075547;">Firmado</span>
                </div>
                <p class="text-sm text-gray-600 mb-2">
                    <strong>Fecha:</strong> 20 Ago 2026
                </p>
            </div>

            <!-- Carlos Pérez - Líder -->
            <div class="mb-6 pb-6 border-b">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">Carlos Pérez</h3>
                        <p class="text-sm text-gray-600">Líder de Sector</p>
                    </div>
                    <span class="inline-block px-3 py-1 rounded text-xs font-semibold" style="background-color: #e6f4f1; color: #075547;">Firmado</span>
                </div>
                <p class="text-sm text-gray-600 mb-2">
                    <strong>Fecha:</strong> 22 Ago 2026
                </p>
            </div>

            <!-- María López - Gestora -->
            <div class="mb-6 pb-6 border-b">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">María López</h3>
                        <p class="text-sm text-gray-600">Gestora</p>
                    </div>
                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded text-xs font-semibold">Pendiente</span>
                </div>
                <div class="bg-red-50 border border-red-200 rounded p-3 mb-2">
                    <p class="text-sm text-red-700">
                        <strong>⚠️ Motivo del rechazo:</strong> Identificó 8 horas por justificar en semana 32
                    </p>
                </div>
                <p class="text-sm text-gray-600">
                    Estado: En revisión
                </p>
            </div>

            <!-- Roberto Gómez - Gerente Admin -->
            <div>
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">Roberto Gómez</h3>
                        <p class="text-sm text-gray-600">Gerente Administrativo</p>
                    </div>
                    <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-semibold">Pendiente</span>
                </div>
                <p class="text-sm text-gray-600">
                    Estado: Pendiente de revisión
                </p>
            </div>
        </div>

        <!-- Documentos de Cierre de Fase -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Documentos de Cierre de Fase</h2>
            <p class="text-sm text-gray-600 mb-6">
                Todos estos documentos deben estar cargados antes de solicitar la firma del paz y salvo.
            </p>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded border" style="background-color: #f0f9f7; border-color: #075547;">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" style="color: #075547;">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-800">Bitácora 1 completa</span>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                </div>

                <div class="flex items-center justify-between p-3 rounded border" style="background-color: #f0f9f7; border-color: #075547;">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" style="color: #075547;">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-800">Bitácora 2 completa</span>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                </div>

                <div class="flex items-center justify-between p-3 rounded border" style="background-color: #f0f9f7; border-color: #075547;">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" style="color: #075547;">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-800">Plan de Innovación</span>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                </div>

                <div class="flex items-center justify-between p-3 rounded border" style="background-color: #f0f9f7; border-color: #075547;">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" style="color: #075547;">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-800">Diagnóstico Inicial</span>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Ver</button>
                </div>

                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded border border-yellow-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-800">Informe de Gestión</span>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Cargar</button>
                </div>

                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded border border-yellow-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-800">Paz y Salvo Firmado</span>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Cargar</button>
                </div>
            </div>

            <!-- Botón para iniciar firma -->
            <button class="w-full mt-8 text-white font-bold py-3 px-6 rounded" style="background-color: #075547;">
                Iniciar Proceso de Firma
            </button>
        </div>
    </div>
</div>
@endsection
