@extends('sisgedi::layouts.instructor')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio > Instructor</p>
        <h1 class="text-3xl font-bold text-gray-800">Mis Fichas y Colaboradores</h1>
    </div>

    @if(count($colaboradoresByFicha) > 0)
        <!-- Tabs de Fichas -->
        <div class="mb-8 flex space-x-2 border-b border-gray-200 overflow-x-auto">
            @foreach($colaboradoresByFicha as $ficha => $colaboradores)
                <button onclick="switchFicha({{ $ficha }})" class="ficha-tab px-6 py-3 border-b-2 border-transparent text-gray-600 font-semibold hover:text-gray-800 whitespace-nowrap" data-ficha="{{ $ficha }}">
                    Ficha {{ $ficha }}
                </button>
            @endforeach
        </div>

        <!-- Contenido de Fichas -->
        @foreach($colaboradoresByFicha as $ficha => $colaboradores)
            <div class="ficha-content hidden" data-ficha="{{ $ficha }}">
                <!-- Título de la sección de colaboradores -->
                <h2 class="text-xl font-bold text-gray-800 mb-6">Colaboradores — Ficha {{ $ficha }}</h2>

                <!-- Filtros de Estado -->
                <div class="mb-6 flex space-x-2">
                    <button onclick="filterColaboradores(this, 'all')" class="filter-btn px-4 py-2 text-white rounded font-semibold text-sm active" style="background-color: #075547;" data-filter="all">
                        Todos
                    </button>
                    <button onclick="filterColaboradores(this, 'pending')" class="filter-btn px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm" data-filter="pending">
                        Con Pendientes
                    </button>
                    <button onclick="filterColaboradores(this, 'approved')" class="filter-btn px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm" data-filter="approved">
                        Todos Aprobados
                    </button>
                    <button onclick="filterColaboradores(this, 'rejected')" class="filter-btn px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded font-semibold text-sm" data-filter="rejected">
                        Con Rechazos
                    </button>
                </div>

                <!-- Tabla de Colaboradores -->
                <div class="bg-white rounded shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Colaborador</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Pendientes</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aprobados</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Rechazados</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Total</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($colaboradores as $colab)
                                <tr class="hover:bg-gray-50 colaborador-row" data-filter-pending="{{ $colab['pending'] }}" data-filter-approved="{{ $colab['approved'] }}" data-filter-rejected="{{ $colab['rejected'] }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($colab['name'], 0, 2)) }}
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $colab['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($colab['pending'] > 0)
                                            <span class="inline-block bg-orange-100 text-orange-700 px-3 py-1 rounded text-sm font-semibold">{{ $colab['pending'] }}</span>
                                        @else
                                            <span class="inline-block bg-gray-100 text-gray-500 px-3 py-1 rounded text-sm font-semibold">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($colab['approved'] > 0)
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-sm font-semibold">{{ $colab['approved'] }}</span>
                                        @else
                                            <span class="inline-block bg-gray-100 text-gray-500 px-3 py-1 rounded text-sm font-semibold">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($colab['rejected'] > 0)
                                            <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded text-sm font-semibold">{{ $colab['rejected'] }}</span>
                                        @else
                                            <span class="inline-block bg-gray-100 text-gray-500 px-3 py-1 rounded text-sm font-semibold">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm font-semibold">{{ $colab['total'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('sisgedi.instructor.detalle-colaborador', $colab['id']) }}" class="text-white text-xs px-4 py-2 rounded font-semibold inline-block" style="background-color: #075547;">
                                            Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <p class="text-gray-500">No hay colaboradores en esta ficha</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <script>
            // Mostrar primera ficha por defecto
            document.addEventListener('DOMContentLoaded', function() {
                const firstFichaBtn = document.querySelector('.ficha-tab');
                const firstFichaCont = document.querySelector('.ficha-content');
                
                if (firstFichaBtn && firstFichaCont) {
                    firstFichaBtn.classList.add('border-b-2', 'text-gray-800', 'font-bold');
                    firstFichaBtn.classList.remove('border-transparent', 'text-gray-600');
                    firstFichaBtn.style.borderBottomColor = '#075547';
                    firstFichaBtn.style.color = '#075547';
                    firstFichaCont.classList.remove('hidden');
                }
            });

            function switchFicha(ficha) {
                // Ocultar todos
                document.querySelectorAll('.ficha-content').forEach(el => {
                    el.classList.add('hidden');
                });
                document.querySelectorAll('.ficha-tab').forEach(el => {
                    el.classList.add('border-transparent', 'text-gray-600');
                    el.classList.remove('border-green-600', 'text-green-600');
                });

                // Mostrar seleccionado
                const content = document.querySelector(`.ficha-content[data-ficha="${ficha}"]`);
                const tab = document.querySelector(`.ficha-tab[data-ficha="${ficha}"]`);
                
                if (content && tab) {
                    content.classList.remove('hidden');
                    tab.classList.remove('border-transparent', 'text-gray-600');
                    tab.classList.add('border-b-2', 'text-gray-800', 'font-bold');
                    tab.style.borderBottomColor = '#075547';
                    tab.style.color = '#075547';
                }
            }

            function filterColaboradores(button, filter) {
                const rows = document.querySelectorAll('.colaborador-row');
                const buttons = document.querySelectorAll('.filter-btn');

                // Actualizar botones
                buttons.forEach(btn => {
                    btn.classList.remove('text-white');
                    btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
                    btn.style.backgroundColor = '';
                });
                button.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
                button.classList.add('text-white');
                button.style.backgroundColor = '#075547';

                // Filtrar filas
                rows.forEach(row => {
                    let show = true;
                    
                    if (filter === 'all') {
                        show = true;
                    } else if (filter === 'pending') {
                        show = parseInt(row.dataset.filterPending) > 0;
                    } else if (filter === 'approved') {
                        show = parseInt(row.dataset.filterRejected) === 0 && parseInt(row.dataset.filterPending) === 0;
                    } else if (filter === 'rejected') {
                        show = parseInt(row.dataset.filterRejected) > 0;
                    }
                    
                    row.style.display = show ? '' : 'none';
                });
            }
        </script>

    @else
        <!-- Sin datos -->
        <div class="bg-white rounded shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-lg font-semibold text-gray-600 mb-2">Sin fichas asignadas</p>
            <p class="text-gray-500">No tienes colaboradores asignados en este momento</p>
        </div>
    @endif
</div>
@endsection
