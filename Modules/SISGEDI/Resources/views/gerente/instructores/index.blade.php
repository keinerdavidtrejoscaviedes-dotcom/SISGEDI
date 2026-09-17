@extends('sisgedi::layouts.panel')

@section('titulo-topbar', 'Catálogo de Instructores')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold" style="color:#001A29;">Catálogo de Instructores</h1>
            <p class="text-gray-500 text-sm mt-1">
                Instructores cuyas firmas son requeridas en documentos oficiales (actas de empalme, paz y salvo).
            </p>
        </div>
        <a href="{{ route('sisgedi.gerente.instructores.create') }}"
           class="inline-flex items-center justify-center gap-2 text-white text-sm font-semibold
                  px-5 py-3 rounded-xl transition-all hover:scale-105"
           style="background:#39A900; box-shadow:0 4px 14px rgba(57,169,0,.4);">
            <i class="fas fa-plus"></i> Agregar instructor
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 uppercase border-b bg-gray-50">
                    <th class="py-3 px-4">Instructor</th>
                    <th class="py-3 px-4">Área / Especialidad</th>
                    <th class="py-3 px-4">Contacto</th>
                    <th class="py-3 px-4">Estado</th>
                    <th class="py-3 px-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($instructores as $instructor)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                @if($instructor->firma_digital_url)
                                    <img src="{{ asset('storage/'.$instructor->firma_digital_url) }}"
                                         class="w-9 h-9 rounded-full object-cover border border-gray-100" alt="Firma">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <span class="font-semibold text-gray-800">{{ $instructor->nombre_completo }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-500">{{ $instructor->area_especialidad }}</td>
                        <td class="py-3 px-4 text-gray-500">
                            <div>{{ $instructor->correo }}</div>
                            <div class="text-xs text-gray-400">{{ $instructor->telefono }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                  {{ $instructor->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ ucfirst($instructor->estado) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('sisgedi.gerente.instructores.edit', $instructor) }}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-sena-green"
                               title="Editar">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('sisgedi.gerente.instructores.destroy', $instructor) }}" method="POST" class="inline"
                                  onsubmit="return confirm('¿Eliminar a {{ $instructor->nombre_completo }} del catálogo?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 py-10">
                            Aún no has registrado instructores en el catálogo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $instructores->links() }}
    </div>
</div>
@endsection
