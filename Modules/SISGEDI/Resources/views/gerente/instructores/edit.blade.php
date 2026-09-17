@extends('sisgedi::layouts.panel')

@section('titulo-topbar', 'Editar Instructor')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('sisgedi.gerente.instructores.index') }}"
           class="text-xs font-semibold text-gray-400 hover:text-sena-green">
            <i class="fas fa-arrow-left mr-1"></i> Volver al catálogo
        </a>
        <h1 class="text-2xl font-extrabold mt-2" style="color:#001A29;">
            Editar instructor — {{ $instructor->nombre_completo }}
        </h1>
    </div>

    <form action="{{ route('sisgedi.gerente.instructores.update', $instructor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('sisgedi::gerente.instructores._form')
    </form>
</div>
@endsection
