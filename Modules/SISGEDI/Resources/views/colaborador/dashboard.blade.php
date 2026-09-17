@extends('sisgedi::layouts.colaborador')

@section('content')
<div class="p-8 bg-white">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio</p>
        <h1 class="text-4xl font-bold text-gray-800">Mi Dashboard</h1>
    </div>

    <!-- 4 Tarjetas de Resumen -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <!-- Tareas Asignadas -->
        <div class="bg-white border-l-4 border-green-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Tareas Asignadas</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">12</p>
                </div>
                <svg class="w-12 h-12 text-green-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H3a1 1 0 00-1 1v12a1 1 0 001 1h10a1 1 0 001-1V4a1 1 0 00-1-1 1 1 0 000 2 2 2 0 012 2v12H4V5z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Tareas Pendientes -->
        <div class="bg-white border-l-4 border-yellow-500 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Tareas Pendientes</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">5</p>
                </div>
                <svg class="w-12 h-12 text-yellow-500 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Horas Registradas -->
        <div class="bg-white border-l-4 border-blue-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Horas Registradas</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">142 h</p>
                </div>
                <svg class="w-12 h-12 text-blue-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Paz y Salvo -->
        <div class="bg-white border-l-4 border-purple-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Paz y Salvo</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">68%</p>
                </div>
                <svg class="w-12 h-12 text-purple-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Dos Columnas: Tareas Próximas + Progreso Paz y Salvo -->
    <div class="grid grid-cols-3 gap-8 mb-8">
        <!-- Tareas Próximas a Vencer (2 columnas) -->
        <div class="col-span-2 bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Tareas Próximas a Vencer</h2>
            
            <div class="space-y-6">
                <!-- Tarea 1 -->
                <div class="pb-6 border-b">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-gray-800">Informe de Gestión Q3</h3>
                            <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 text-xs rounded font-semibold mt-1">Individual</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Informe semanal con resultados cuantitativos de la unidad productiva.</p>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-green-600 font-semibold">En Desarrollo</span>
                        <span class="text-gray-600">01 Sep 2026</span>
                    </div>
                </div>

                <!-- Tarea 2 -->
                <div class="pb-6 border-b">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-gray-800">Diagnóstico Inicial Sector</h3>
                            <span class="inline-block bg-yellow-100 text-yellow-700 px-2 py-1 text-xs rounded font-semibold mt-1">Cascada</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Análisis inicial del estado del sector.</p>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-yellow-600 font-semibold">Planificada</span>
                        <span class="text-gray-600">05 Sep 2026</span>
                    </div>
                </div>

                <!-- Tarea 3 -->
                <div>
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-bold text-gray-800">Bitácora Semana 32</h3>
                            <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 text-xs rounded font-semibold mt-1">General</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Registro de actividades de la semana 32.</p>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-red-600 font-semibold">Vencida</span>
                        <span class="text-gray-600">30 Ago 2026</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progreso Paz y Salvo -->
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Progreso Paz y Salvo</h2>
            
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-700">Sol - T3 2026</span>
                    <span class="text-sm font-bold text-gray-800">68%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: 68%"></div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="text-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-800">Ana Torres (Instructora)</span>
                        <span class="bg-green-100 text-green-700 px-2 py-1 text-xs rounded font-semibold">Firmado</span>
                    </div>
                    <p class="text-xs text-gray-500">20 Ago 2026</p>
                </div>

                <div class="text-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-800">Carlos Pérez (Líder)</span>
                        <span class="bg-green-100 text-green-700 px-2 py-1 text-xs rounded font-semibold">Firmado</span>
                    </div>
                    <p class="text-xs text-gray-500">22 Ago 2026</p>
                </div>

                <div class="text-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-800">María López (Gestora)</span>
                        <span class="bg-red-100 text-red-700 px-2 py-1 text-xs rounded font-semibold">Pendiente</span>
                    </div>
                    <p class="text-xs text-red-600">⚠️ 8 horas por justificar</p>
                </div>

                <div class="text-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-gray-800">Roberto Gómez (Gerente)</span>
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 text-xs rounded font-semibold">Pendiente</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Horas por Semana -->
    <div class="grid grid-cols-4 gap-4">
        <!-- Semana 30 -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded p-6 text-center border-t-4 border-green-500">
            <p class="text-sm text-gray-700 font-medium">Semana 30</p>
            <p class="text-3xl font-bold text-green-700 mt-2">38 h</p>
        </div>

        <!-- Semana 31 -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded p-6 text-center border-t-4 border-green-500">
            <p class="text-sm text-gray-700 font-medium">Semana 31</p>
            <p class="text-3xl font-bold text-green-700 mt-2">40 h</p>
        </div>

        <!-- Semana 32 -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded p-6 text-center border-t-4 border-red-500">
            <p class="text-sm text-gray-700 font-medium">Semana 32</p>
            <p class="text-3xl font-bold text-red-700 mt-2">0 h</p>
            <p class="text-xs text-red-600 mt-1">Sin registro</p>
        </div>

        <!-- Semana 33 -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded p-6 text-center border-t-4 border-green-500">
            <p class="text-sm text-gray-700 font-medium">Semana 33</p>
            <p class="text-3xl font-bold text-green-700 mt-2">24 h</p>
        </div>
    </div>
</div>
@endsection
