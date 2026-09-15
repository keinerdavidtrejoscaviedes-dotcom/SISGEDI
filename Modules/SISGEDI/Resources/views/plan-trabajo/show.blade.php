@extends('sisgedi::layouts.master')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-extrabold" style="color:#001A29;">
            Cronograma de la fase — {{ $fase->nombre }}
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Plan de trabajo general publicado por el Gerente Administrativo, visible para todos los roles.
        </p>
    </div>

    @if(! $plan)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-400">
            <i class="fas fa-calendar-xmark text-3xl mb-3"></i>
            <p>El Gerente Administrativo aún no ha publicado el plan de trabajo de esta fase.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <p class="text-sm text-gray-700 leading-relaxed">{{ $plan->descripcion }}</p>
            <p class="text-xs text-gray-400 mt-3">
                Publicado por {{ $plan->autor->full_name }} el {{ $plan->fecha_publicacion->format('d/m/Y') }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-sm mb-5" style="color:#001A29;">
                <i class="fas fa-calendar-days text-sena-green mr-1"></i> Línea de tiempo
            </h2>

            <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                @forelse($plan->hitos as $hito)
                    @php
                        $estilo = match($hito->tipo) {
                            'entrega' => ['icon' => 'fa-file-export', 'color' => '#39A900', 'label' => 'Entrega'],
                            'reunion' => ['icon' => 'fa-people-group', 'color' => '#0EA5E9', 'label' => 'Reunión'],
                            default => ['icon' => 'fa-flag', 'color' => '#F59E0B', 'label' => 'Hito'],
                        };
                        $vencido = $hito->fecha->isPast();
                    @endphp
                    <div class="relative pl-6">
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white"
                              style="background: {{ $estilo['color'] }};"></span>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full"
                                  style="background: {{ $estilo['color'] }}1a; color: {{ $estilo['color'] }};">
                                <i class="fas {{ $estilo['icon'] }} mr-1"></i>{{ $estilo['label'] }}
                            </span>
                            <span class="text-xs text-gray-400 {{ $vencido ? 'line-through' : '' }}">
                                {{ $hito->fecha->format('d/m/Y') }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $hito->titulo }}</p>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm pl-6">El plan aún no tiene hitos registrados.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
@endsection
