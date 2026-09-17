@extends('sisgedi::layouts.master')

@section('content')
<div class="min-h-screen bg-[#f0f4f8] p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-12 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Dashboard SISGEDI</h1>
            <p class="text-gray-600 text-lg">Panel principal de SISGEDI</p>
        </div>

        {{-- Loading spinner --}}
        <div class="flex justify-center items-center py-16">
            <div class="relative w-16 h-16">
                <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-green-600 animate-spin"></div>
            </div>
        </div>

        {{-- Información del usuario --}}
        <div class="mt-12 bg-white rounded-lg shadow p-8 max-w-md mx-auto">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <p class="text-gray-900 font-semibold">{{ $user['nombre'] ?? 'Usuario' }}</p>
                <p class="text-gray-500 text-sm mt-1">{{ $user['correo'] ?? 'correo@example.com' }}</p>
                <p class="text-gray-600 text-sm mt-3 font-medium">Rol: <span class="text-green-600">{{ $user['rol'] ?? 'Sin rol' }}</span></p>
            </div>
        </div>

    </div>
</div>
@endsection
