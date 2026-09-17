@extends('sisgedi::layouts.colaborador')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <nav class="flex items-center space-x-2 text-sm mb-4">
            <span class="text-gray-600">Inicio</span>
            <span class="text-gray-400">›</span>
            <span class="text-gray-800 font-semibold">Colaborador (Aprendiz)</span>
            <span class="text-gray-400">›</span>
            <span class="text-gray-800 font-semibold">Convocatorias Abiertas</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-800">Convocatorias Abiertas</h1>
    </div>

    <!-- Convocatoria Disponible -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2">Líder Sector Lácteos — Sol T3 2026</h2>
                    <p class="text-green-100">Plantas de Lácteos · Campoalegre, Huila · Cierre: 05 Sep 2026</p>
                </div>
                <span class="inline-block bg-green-500 text-white px-4 py-2 rounded font-semibold">Abierta</span>
            </div>
        </div>

        <div class="p-8">
            <!-- Tabs de información -->
            <div class="flex space-x-8 border-b mb-8">
                <button class="pb-4 px-4 border-b-2 border-green-600 text-green-600 font-semibold">
                    <span class="inline-block w-6 h-6 bg-green-600 text-white rounded-full text-center text-sm mr-2">1</span>
                    Datos
                </button>
                <button class="pb-4 px-4 text-gray-600 font-semibold hover:text-gray-800">
                    <span class="inline-block w-6 h-6 bg-gray-300 text-gray-700 rounded-full text-center text-sm mr-2">2</span>
                    Documentos
                </button>
                <button class="pb-4 px-4 text-gray-600 font-semibold hover:text-gray-800">
                    <span class="inline-block w-6 h-6 bg-gray-300 text-gray-700 rounded-full text-center text-sm mr-2">3</span>
                    Preferencias
                </button>
            </div>

            <!-- Contenido Tab 1: Datos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombres Completos</label>
                        <p class="text-gray-800 font-medium">Juan Camilo Rodríguez Parra</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Ficha</label>
                        <p class="text-gray-800 font-medium">2893451</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Programa de Formación</label>
                        <p class="text-gray-800 font-medium">Técnico en Production Agropecuaria</p>
                    </div>
                </div>

                <div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <p class="text-gray-800 font-medium">juan.rodriguez@email.com</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Instructor Asignado</label>
                        <p class="text-gray-800 font-medium">Ana Torres</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                        <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded font-semibold text-sm">Elegible</span>
                    </div>
                </div>
            </div>

            <!-- Botón siguiente -->
            <div class="flex justify-end">
                <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded flex items-center">
                    Siguiente
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
