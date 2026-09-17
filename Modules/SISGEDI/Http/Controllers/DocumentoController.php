<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SISGEDI\Entities\Documento;

class DocumentoController extends Controller
{
    // 1. PÁGINA PRINCIPAL — landing institucional
    public function index()
    {
        $totalDocumentos   = Documento::count();
        $documentosActivos = Documento::where('estado', 'Activo')->count();

        return view('sisgedi::index', compact(
            'totalDocumentos',
            'documentosActivos'
        ));
    }

    // 2. DASHBOARD (requiere sesión sisgedi_user)
    public function dashboard()
    {
        if (! session('sisgedi_user')) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }

        // Obtener el rol del usuario desde la sesión
        $usuario = session('sisgedi_user');
        $rol = strtolower(trim($usuario['rol'] ?? ''));

        // Redireccionar según el rol - mapeo según roles de base de datos
        if (empty($rol)) {
            // Si no hay rol definido, mostrar mensaje de error
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Tu cuenta no tiene un rol asignado.']);
        }

        // Colaborador → Dashboard de Colaborador
        if ($rol === 'colaborador') {
            return redirect()->route('sisgedi.colaborador.dashboard');
        }

        // Instructor, Gestor, Líder, Gerente → Dashboard de Instructor/Supervisor
        if ($rol === 'instructor' || str_contains($rol, 'gestor') || str_contains($rol, 'líder') || str_contains($rol, 'gerente')) {
            return redirect()->route('sisgedi.instructor.dashboard');
        }

        // Si no coincide con ningún rol esperado, mostrar error
        return redirect()->route('sisgedi.index')
            ->withErrors(['nickname' => 'Tu rol ("' . $usuario['rol'] . '") no tiene acceso a dashboards aún.']);
    }

    // 3. FORMULARIO DE CREACIÓN
    public function create()
    {
        return view('sisgedi::create');
    }

    // 3. GUARDAR EN BASE DE DATOS
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:documentos,codigo|max:50',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Activo,Inactivo'
        ]);

        Documento::create($validated);

        return redirect()->route('sisgedi.index')->with('success', '¡Documento registrado con éxito!');
    }

    // 4. FORMULARIO DE EDICIÓN
    public function edit($id)
    {
        $elemento = Documento::findOrFail($id);
        return view('sisgedi::edit', compact('elemento'));
    }

    // 5. ACTUALIZAR REGISTRO
    public function update(Request $request, $id)
    {
        $elemento = Documento::findOrFail($id);

        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:documentos,codigo,' . $elemento->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:Activo,Inactivo'
        ]);

        $elemento->update($validated);

        return redirect()->route('sisgedi.index')->with('success', '¡Documento actualizado con éxito!');
    }

    // 6. ELIMINAR REGISTRO
    public function destroy($id)
    {
        $elemento = Documento::findOrFail($id);
        $elemento->delete();

        return redirect()->route('sisgedi.index')->with('success', '¡Documento eliminado correctamente!');
    }
}