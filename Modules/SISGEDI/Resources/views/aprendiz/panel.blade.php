@extends('sisgedi::layouts.landing')

@section('content')

{{-- ══════════════════════════════════════════════════════════════════
     PANEL DEL APRENDIZ — Convocatorias disponibles
══════════════════════════════════════════════════════════════════ --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Cabecera de bienvenida --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#1B8C3E;">
                Bienvenido(a)
            </p>
            <h1 class="text-2xl font-extrabold text-gray-900">
                Hola, <span style="color:#1B8C3E;">{{ ucfirst(session('sisgedi_user.nombre')) }}</span> 👋
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Aquí puedes ver y postularte a las convocatorias abiertas.
                Recuerda que debes elegir <strong>3 cargos en orden de preferencia</strong>.
            </p>
        </div>
        <div class="hidden sm:flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
                 style="background:#1B8C3E;">
                {{ strtoupper(substr(session('sisgedi_user.nombre','AP'),0,2)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">
                    {{ ucfirst(session('sisgedi_user.nombre')) }}
                </p>
                <p class="text-xs text-gray-400">{{ session('sisgedi_user.rol') }}</p>
            </div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700
                text-sm px-5 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700
                text-sm px-5 py-3 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle text-red-500"></i>
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ── Convocatorias abiertas ── --}}
    @if($convocatorias->isEmpty())
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center"
             style="background:#E8F5ED;">
            <i class="fas fa-calendar-times text-2xl" style="color:#1B8C3E;"></i>
        </div>
        <h2 class="font-bold text-gray-700 text-lg mb-2">Sin convocatorias abiertas</h2>
        <p class="text-sm text-gray-500">
            Actualmente no hay convocatorias activas. Vuelve pronto.
        </p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($convocatorias as $conv)
        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden
                    {{ $conv->ya_postulado ? 'border-green-200' : 'border-gray-100' }}">

            {{-- Header de la tarjeta --}}
            <div class="px-5 py-4 border-b border-gray-100 flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-gray-900 text-base leading-tight">
                        {{ $conv->titulo }}
                    </h3>
                    @if(isset($conv->descripcion))
                    <p class="text-xs text-gray-500 mt-1">{{ $conv->descripcion }}</p>
                    @endif
                </div>
                @if($conv->ya_postulado)
                <span class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0"
                      style="background:#DCFCE7; color:#15803D;">
                    <i class="fas fa-check mr-1"></i>Postulado
                </span>
                @else
                <span class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0"
                      style="background:#EFF6FF; color:#1D4ED8;">
                    Abierta
                </span>
                @endif
            </div>

            {{-- Cargos disponibles --}}
            <div class="px-5 py-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                    Cargos disponibles ({{ $conv->cargos->count() }})
                </p>
                <ul class="space-y-1.5 mb-4">
                    @foreach($conv->cargos->take(4) as $cargo)
                    <li class="flex items-center gap-2 text-sm text-gray-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></span>
                        {{ $cargo->nombre_cargo }}
                        <span class="text-xs text-gray-400">({{ $cargo->cupos }} cupo{{ $cargo->cupos != 1 ? 's' : '' }})</span>
                    </li>
                    @endforeach
                    @if($conv->cargos->count() > 4)
                    <li class="text-xs text-gray-400 pl-3">
                        +{{ $conv->cargos->count() - 4 }} más...
                    </li>
                    @endif
                </ul>

                {{-- Botón de acción --}}
                @if($conv->ya_postulado)
                <div class="flex items-center gap-2 text-sm text-green-600 font-semibold">
                    <i class="fas fa-check-circle"></i>
                    Ya estás postulado/a a esta convocatoria
                </div>
                @else
                <a href="{{ route('sisgedi.aprendiz.convocatoria', $conv->convocatoria_id) }}"
                   class="inline-flex items-center gap-2 text-white text-sm font-bold
                          px-5 py-2.5 rounded-xl transition-all hover:opacity-90"
                   style="background:#1B8C3E; box-shadow:0 4px 12px rgba(27,140,62,.3);">
                    <i class="fas fa-paper-plane text-xs"></i>
                    Postularme a esta convocatoria
                </a>
                @endif
            </div>

        </div>
        @endforeach
    </div>
    @endif

    {{-- Cerrar sesión --}}
    <div class="mt-8 text-center">
        <form action="{{ route('sisgedi.logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit"
                    class="text-sm text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-sign-out-alt mr-1"></i> Cerrar sesión
            </button>
        </form>
    </div>

</section>

@endsection
