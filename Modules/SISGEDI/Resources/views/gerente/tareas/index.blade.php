@extends('sisgedi::layouts.master')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('sisgedi.gerente.dashboard') }}"
               class="text-xs font-semibold text-gray-400 hover:text-sena-green">
                <i class="fas fa-arrow-left mr-1"></i> Volver al panel
            </a>
            <h1 class="text-2xl font-extrabold mt-2" style="color:#001A29;">
                Tareas generadas — {{ $fase->nombre }}
            </h1>
        </div>
        <a href="{{ route('sisgedi.gerente.tareas.create') }}"
           class="inline-flex items-center justify-center gap-2 text-white text-sm font-semibold
                  px-5 py-3 rounded-xl transition-all hover:scale-105"
           style="background:#39A900; box-shadow:0 4px 14px rgba(57,169,0,.4);">
            <i class="fas fa-plus"></i> Generar nueva tarea
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 uppercase border-b bg-gray-50">
                    <th class="py-3 px-4">Título</th>
                    <th class="py-3 px-4">Sector</th>
                    <th class="py-3 px-4">Fecha límite</th>
                    <th class="py-3 px-4">Evidencia</th>
                    <th class="py-3 px-4">Confirmada</th>
                    <th class="py-3 px-4">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tareas as $tarea)
                    @php
                        $badgeColor = match($tarea->estado) {
                            'aprobada' => 'bg-green-100 text-green-700',
                            'rechazada', 'vencida' => 'bg-red-100 text-red-700',
                            'en_revision', 'enviada' => 'bg-amber-100 text-amber-700',
                            default => 'bg-sky-100 text-sky-700',
                        };
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4 font-semibold text-gray-800">{{ $tarea->titulo }}</td>
                        <td class="py-3 px-4 text-gray-500">{{ $tarea->sector->nombre }}</td>
                        <td class="py-3 px-4 text-gray-500">{{ $tarea->fecha_limite->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-gray-500 capitalize">{{ str_replace('-', ' ', $tarea->tipo_evidencia_requerida) }}</td>
                        <td class="py-3 px-4">
                            @if($tarea->confirmada)
                                <span class="text-green-600 text-xs font-semibold"><i class="fas fa-check"></i> Sí</span>
                            @else
                                <span class="text-gray-400 text-xs font-semibold"><i class="fas fa-clock"></i> Pendiente</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                {{ str_replace('_', ' ', $tarea->estado) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-10">
                            Aún no has generado tareas en esta fase.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tareas->links() }}
    </div>
</div>
@endsection
