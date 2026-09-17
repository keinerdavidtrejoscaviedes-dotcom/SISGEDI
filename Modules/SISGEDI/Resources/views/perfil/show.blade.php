@extends('sisgedi::layouts.dashboard')
@section('title', 'Mi Perfil')

@section('content')
<div class="flex" style="min-height:calc(100vh - 59px);">

    @include('sisgedi::layouts.partials.sidebar-dashboard')

    <div class="flex-1 flex flex-col min-w-0" style="margin-left:240px;">

        @include('sisgedi::convocatorias._topbar', [
            'breadcrumb' => [
                ['label' => 'Inicio', 'url' => route('sisgedi.dashboard')],
                ['label' => 'Mi Perfil', 'url' => null],
            ],
            'titulo' => 'Mi Perfil',
        ])

        <main class="flex-1 p-6" style="background:#f3f4f6;">

            @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-sm text-green-800 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            <div class="max-w-2xl bg-white rounded-xl border border-gray-100 shadow-sm p-6">

                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0"
                         style="background:linear-gradient(135deg,#39A900,#002336);">
                        {{ strtoupper(substr($sesion['nombre'] ?? 'US', 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ ucwords(str_replace('.', ' ', $sesion['nombre'] ?? 'Usuario')) }}
                        </h2>
                        <span class="inline-block mt-1 text-xs font-bold px-2.5 py-1 rounded-full"
                              style="background:#EBF9EB; color:#39A900;">
                            {{ $sesion['rol'] ?? 'Sin rol' }}
                        </span>
                    </div>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Usuario</dt>
                        <dd class="text-gray-800 font-medium">{{ $sesion['nombre'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Correo</dt>
                        <dd class="text-gray-800 font-medium">{{ $sesion['correo'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Rol</dt>
                        <dd class="text-gray-800 font-medium">{{ $sesion['rol'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">ID de usuario (SISGEDI)</dt>
                        <dd class="text-gray-800 font-medium">#{{ $sesion['id'] ?? '—' }}</dd>
                    </div>
                </dl>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        Para cambiar tu contraseña u otros datos, contacta al Administrador del sistema.
                    </p>
                    <form action="{{ route('sisgedi.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold
                                       text-red-600 bg-red-50 border border-red-100 hover:bg-red-100 transition-colors">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection
