@extends('sisgedi::layouts.colaborador')

@section('content')
<div class="p-8 bg-white">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio • Colaborador</p>
        <h1 class="text-4xl font-bold text-gray-800">Bitácoras de Actividades</h1>
    </div>

    <!-- Selector de Bitácora -->
    <div class="mb-8 flex space-x-4">
        <button class="px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded font-semibold hover:bg-gray-50">
            Bitácora 1 (Inducción)
        </button>
        <button class="px-6 py-3 bg-green-600 text-white rounded font-semibold hover:bg-green-700">
            Bitácora 2 (Formación)
        </button>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-white border-l-4 border-green-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Horas Registradas</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">142 h</p>
                </div>
                <svg class="w-12 h-12 text-green-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-.707.707a1 1 0 101.414 1.414L9 9.414V6z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white border-l-4 border-blue-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Días con Registro</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">24</p>
                </div>
                <svg class="w-12 h-12 text-blue-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v2h16V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a2 2 0 002 2h8a2 2 0 002-2H6z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white border-l-4 border-yellow-500 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Semanas sin Actividad</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">1</p>
                </div>
                <svg class="w-12 h-12 text-yellow-500 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white border-l-4 border-red-500 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Semanas</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">24</p>
                </div>
                <svg class="w-12 h-12 text-red-500 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Alerta -->
    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <div class="text-red-600 mr-4">⚠️</div>
            <div>
                <h3 class="font-bold text-red-800">Semana 32 sin actividades registradas</h3>
                <p class="text-sm text-red-700 mt-1">Por favor registre las actividades mínimas de esa semana para continuar.</p>
            </div>
        </div>
    </div>

    <!-- Resumen de horas por semana -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded p-6 text-center border-t-4 border-green-500">
            <p class="text-sm text-gray-700 font-medium">Semana 30</p>
            <p class="text-3xl font-bold text-green-700 mt-2">38 h</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded p-6 text-center border-t-4 border-green-500">
            <p class="text-sm text-gray-700 font-medium">Semana 31</p>
            <p class="text-3xl font-bold text-green-700 mt-2">40 h</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded p-6 text-center border-t-4 border-red-500">
            <p class="text-sm text-gray-700 font-medium">Semana 32</p>
            <p class="text-3xl font-bold text-red-700 mt-2">0 h</p>
            <p class="text-xs text-red-600 mt-1">Sin registro</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded p-6 text-center border-t-4 border-green-500">
            <p class="text-sm text-gray-700 font-medium">Semana 33</p>
            <p class="text-3xl font-bold text-green-700 mt-2">24 h</p>
        </div>
    </div>

    <!-- Registro de Actividades -->
    <div class="bg-white rounded shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Registro de Actividades</h2>
            <button class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded text-sm">
                + Nuevo Registro
            </button>
        </div>

        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Fecha</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actividad</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Horas</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Unidad</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Observaciones</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">25 Ago 2026</td>
                    <td class="px-6 py-4 text-sm">Visita técnica al galpón de aves, revisión de parámetros de bioseguridad</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">8 h</td>
                    <td class="px-6 py-4 text-sm"><span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs">Avicultura</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">Sin novedad</td>
                    <td class="px-6 py-4 text-sm space-x-3">
                        <button class="text-blue-600 hover:underline text-xs">Editar</button>
                        <span class="text-gray-400">|</span>
                        <button class="text-red-600 hover:underline text-xs">Eliminar</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">24 Ago 2026</td>
                    <td class="px-6 py-4 text-sm">Reunión de empalme con colaborador saliente, revisión de inventario</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">6 h</td>
                    <td class="px-6 py-4 text-sm"><span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs">Avicultura</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">Diferencia en inventario de equipos</td>
                    <td class="px-6 py-4 text-sm space-x-3">
                        <button class="text-blue-600 hover:underline text-xs">Editar</button>
                        <span class="text-gray-400">|</span>
                        <button class="text-red-600 hover:underline text-xs">Eliminar</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">23 Ago 2026</td>
                    <td class="px-6 py-4 text-sm">Registro y tabulación de datos de producción semana 33</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">8 h</td>
                    <td class="px-6 py-4 text-sm"><span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs">Avicultura</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">—</td>
                    <td class="px-6 py-4 text-sm space-x-3">
                        <button class="text-blue-600 hover:underline text-xs">Editar</button>
                        <span class="text-gray-400">|</span>
                        <button class="text-red-600 hover:underline text-xs">Eliminar</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">22 Ago 2026</td>
                    <td class="px-6 py-4 text-sm">Elaboración de informe semanal de gestión</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">7 h</td>
                    <td class="px-6 py-4 text-sm"><span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs">Avicultura</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">—</td>
                    <td class="px-6 py-4 text-sm space-x-3">
                        <button class="text-blue-600 hover:underline text-xs">Editar</button>
                        <span class="text-gray-400">|</span>
                        <button class="text-red-600 hover:underline text-xs">Eliminar</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">21 Ago 2026</td>
                    <td class="px-6 py-4 text-sm">Capacitación BPP (Buenas Prácticas Pecuarias) con técnico SENA</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-800">8 h</td>
                    <td class="px-6 py-4 text-sm"><span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs">Avicultura</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600">—</td>
                    <td class="px-6 py-4 text-sm space-x-3">
                        <button class="text-blue-600 hover:underline text-xs">Editar</button>
                        <span class="text-gray-400">|</span>
                        <button class="text-red-600 hover:underline text-xs">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
