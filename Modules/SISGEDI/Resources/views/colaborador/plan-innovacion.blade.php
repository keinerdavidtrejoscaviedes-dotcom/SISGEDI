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
            <span class="text-gray-800 font-semibold">Plan de Innovación y Mejora</span>
        </nav>
        <h1 class="text-3xl font-bold text-gray-800">Plan de Innovación y Mejora</h1>
    </div>

    <!-- Información General del Plan -->
    <div class="bg-white rounded-lg shadow p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-8">Información General del Plan</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Columna 1 -->
            <div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Idea de Innovación</label>
                    <p class="text-gray-800">Sistema de monitoreo automatizado de temperatura en galpón</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cargo Asignado</label>
                    <p class="text-gray-800">Técnico de Avicultura — Sol T3 2026</p>
                </div>
            </div>

            <!-- Columna 2 -->
            <div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Objetivo General</label>
                    <p class="text-gray-800">Reducir mortalidad aviar en un 15% mediante control térmico</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fase Vigente</label>
                    <p class="text-gray-800">Sol - Trimestre 3 - 2026</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividades del Plan (mínimo 3) -->
    <div class="bg-white rounded-lg shadow p-8 mb-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Actividades del Plan (mínimo 3)</h2>
            <button class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded">
                + Agregar Actividad
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actividad</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Indicador</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tiempo Est.</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Recursos</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Responsable</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">1</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Diagnóstico del sistema de ventilación actual</div>
                            <div class="text-sm text-gray-600">Evaluación técnica del equipamiento existente</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">Informe técnico</td>
                        <td class="px-6 py-4 text-sm text-gray-600">2 semanas</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Termómetros, planilla</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Juan Rodríguez</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">Editar</button>
                            <span class="text-gray-400">|</span>
                            <button class="text-red-600 hover:text-red-800">Eliminar</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">2</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Instalación de sensores IoT de temperatura</div>
                            <div class="text-sm text-gray-600">Instalación física de 5 sensores en puntos críticos</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600"># sensores instalados</td>
                        <td class="px-6 py-4 text-sm text-gray-600">1 semana</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Sensores, cableado</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Juan Rodríguez + Técnico</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">Editar</button>
                            <span class="text-gray-400">|</span>
                            <button class="text-red-600 hover:text-red-800">Eliminar</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">3</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Capacitación personal en manejo del sistema</div>
                            <div class="text-sm text-gray-600">Entrenamiento sobre lectura e interpretación de datos</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">% personal capacitado</td>
                        <td class="px-6 py-4 text-sm text-gray-600">3 días</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Manual, proyector</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Juan Rodríguez</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">Editar</button>
                            <span class="text-gray-400">|</span>
                            <button class="text-red-600 hover:text-red-800">Eliminar</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">4</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">Evaluación de impacto comparativo mortalidad</div>
                            <div class="text-sm text-gray-600">Análisis de datos previos vs posteriores a implementación</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">% reducción mortalidad</td>
                        <td class="px-6 py-4 text-sm text-gray-600">4 semanas</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Datos históricos</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Juan Rodríguez</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">Editar</button>
                            <span class="text-gray-400">|</span>
                            <button class="text-red-600 hover:text-red-800">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Verificación de Inventario -->
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Verificación de Inventario</h2>
            <p class="text-sm text-gray-600 mb-4">
                El inventario coincide con lo entregado en el empalme:
            </p>
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-green-50 rounded border border-green-200">
                    <input type="checkbox" checked disabled class="w-4 h-4 text-green-600 rounded mr-3">
                    <label class="text-sm font-medium text-gray-800">Equipos y herramientas</label>
                </div>
                <div class="flex items-center p-3 bg-green-50 rounded border border-green-200">
                    <input type="checkbox" checked disabled class="w-4 h-4 text-green-600 rounded mr-3">
                    <label class="text-sm font-medium text-gray-800">Materiales de consumo</label>
                </div>
                <div class="flex items-center p-3 bg-yellow-50 rounded border border-yellow-200">
                    <input type="checkbox" disabled class="w-4 h-4 text-yellow-600 rounded mr-3">
                    <label class="text-sm font-medium text-gray-800">Registros documentales</label>
                </div>
            </div>
            <button class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Cargar Verificación
            </button>
        </div>

        <!-- Diagnóstico Inicial -->
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Diagnóstico Inicial</h2>
            <p class="text-sm text-gray-600 mb-4">
                Descripción del diagnóstico del estado inicial de la unidad productiva.
            </p>
            
            <div class="bg-gray-50 rounded p-4 mb-4 border border-gray-200" style="min-height: 120px;">
                <p class="text-sm text-gray-700">
                    Estado actual del galpón de aves: Sistema de ventilación manual, sin controladores automáticos. Variabilidad de temperatura entre 18-28°C, mortalidad actual del 8%. Se requiere mejorar el control térmico para optimizar producción.
                </p>
            </div>

            <button class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Editar Diagnóstico
            </button>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="flex space-x-4">
        <button class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded">
            Guardar Plan
        </button>
        <button class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded">
            Cancelar
        </button>
    </div>
</div>
@endsection
