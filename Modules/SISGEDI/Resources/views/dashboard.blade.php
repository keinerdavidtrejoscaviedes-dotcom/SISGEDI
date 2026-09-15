@extends('sisgedi::layouts.master')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-extrabold" style="color:#001A29;">
            Bienvenido(a), {{ session('sisgedi_user.nombre') }}
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            Rol: <strong>{{ session('sisgedi_user.rol') }}</strong>
        </p>
    </div>

    {{-- ── Aviso: este panel de documentos usa un login independiente ── --}}
    <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-xl p-4 mb-8">
        <p class="font-semibold mb-1">
            <i class="fas fa-triangle-exclamation mr-1"></i> Este es el panel general de documentos.
        </p>
        <p>
            El panel de cascada de tareas del Gerente Administrativo vive en una sección aparte y usa
            el <a href="{{ route('login') }}" class="underline font-semibold">inicio de sesión principal del sistema</a>
            (no este formulario). Ingresa ahí con tu correo <code>@sisgedi.sena.edu.co</code> y la misma
            contraseña para acceder a
            <a href="{{ route('sisgedi.gerente.dashboard') }}" class="underline font-semibold">/sisgedi/gerente/dashboard</a>.
        </p>
    </div>

    {{-- ── KPI cards genéricas de documentos ── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $kpis = [
                ['label' => 'Documentos totales', 'valor' => $totalDocumentos, 'icon' => 'fa-file-lines'],
                ['label' => 'Activos', 'valor' => $documentosActivos, 'icon' => 'fa-circle-check'],
                ['label' => 'Últimos 30 días', 'valor' => $documentosRecientes, 'icon' => 'fa-clock'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">{{ $kpi['label'] }}</p>
            <p class="text-3xl font-extrabold mt-1" style="color:#001A29;">
                <i class="fas {{ $kpi['icon'] }} text-sena-green text-xl mr-2"></i>{{ $kpi['valor'] }}
            </p>
        </div>
        @endforeach
    </div>

    <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 uppercase border-b bg-gray-50">
                    <th class="py-3 px-4">Código</th>
                    <th class="py-3 px-4">Nombre</th>
                    <th class="py-3 px-4">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimosDocumentos as $doc)
                    <tr class="border-b border-gray-50">
                        <td class="py-3 px-4 font-mono text-gray-500">{{ $doc->codigo }}</td>
                        <td class="py-3 px-4 font-semibold text-gray-800">{{ $doc->nombre }}</td>
                        <td class="py-3 px-4">{{ $doc->estado }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-400 py-8">Sin documentos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
