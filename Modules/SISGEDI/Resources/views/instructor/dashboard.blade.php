@extends('sisgedi::layouts.instructor')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <!-- Encabezado -->
    <div class="mb-8">
        <p class="text-sm text-gray-500 mb-2">Inicio > Instructor</p>
        <h1 class="text-3xl font-bold text-gray-800">Dashboard — Instructor</h1>
    </div>

    <!-- 4 Tarjetas de Resumen -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <!-- Fichas a Cargo -->
        <div class="bg-white border-l-4 border-blue-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Fichas a Cargo</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">{{ $fichasCount }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
            </div>
        </div>

        <!-- Colaboradores -->
        <div class="bg-white border-l-4 border-purple-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Colaboradores</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">{{ $colaboradoresCount }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 016 6H3a6 6 0 016-6zM13 5a1 1 0 100 2h4a1 1 0 100-2h-4zm0 4a1 1 0 100 2h4a1 1 0 100-2h-4z"></path>
                </svg>
            </div>
        </div>

        <!-- Entregables Pendientes -->
        <div class="bg-white border-l-4 border-orange-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Entregables Pendientes</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">{{ $pendingCount }}</p>
                </div>
                <svg class="w-12 h-12 text-orange-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 002 2h8a2 2 0 002-2H6z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <!-- Firma Digital Activa -->
        <div class="bg-white border-l-4 border-green-600 rounded p-6 shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Firma Digital</p>
                    <p class="text-xl font-bold text-gray-800 mt-2">
                        @if($activeSignature)
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded text-sm">Activa</span>
                        @else
                            <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm">Inactiva</span>
                        @endif
                    </p>
                </div>
                <svg class="w-12 h-12 text-green-600 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Entregables Recientes por Revisar -->
    @if($recentPending && $recentPending->count() > 0)
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Entregables Recientes por Revisar</h2>
            
            <div class="space-y-4">
                @foreach($recentPending as $approval)
                    @php
                        $isLast = $loop->last;
                    @endphp
                    <div class="{{ $isLast ? '' : 'pb-4 border-b' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $approval->colaborador_name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $approval->deliverable_name }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 mb-1">
                                    @if($approval->submitted_at)
                                        {{ $approval->submitted_at->format('d M Y - H:i a') }}
                                    @else
                                        —
                                    @endif
                                </p>
                                <a href="{{ route('sisgedi.instructor.revisar-entregables', ['approval' => $approval->id]) }}" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1 rounded font-semibold inline-block">
                                    Revisar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-lg font-semibold text-gray-600 mb-2">No hay entregables pendientes</p>
            <p class="text-gray-500">Todos los entregables han sido revisados y firmados</p>
        </div>
    @endif
</div>
@endsection
