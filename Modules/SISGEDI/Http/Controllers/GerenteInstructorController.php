<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SISGEDI\Entities\Instructor;

/**
 * RF-066/RF-067: catalogo de instructores cuyas firmas son requeridas en
 * documentos oficiales del sistema. Gestion exclusiva del Gerente
 * Administrativo (Matriz de Responsabilidades del SRS v8.0).
 */
class GerenteInstructorController extends Controller
{
    public function index()
    {
        $instructores = Instructor::orderBy('nombre_completo')->paginate(10);

        return view('sisgedi::gerente.instructores.index', compact('instructores'));
    }

    public function create()
    {
        return view('sisgedi::gerente.instructores.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validarDatos($request);

        if ($request->hasFile('firma_digital')) {
            $validated['firma_digital_url'] = $request->file('firma_digital')
                ->store('sisgedi/firmas_instructores', 'public');
        }

        $validated['gestionado_por'] = Auth::id();

        Instructor::create($validated);

        return redirect()->route('sisgedi.gerente.instructores.index')
            ->with('success', 'Instructor registrado en el catálogo.');
    }

    public function edit(Instructor $instructor)
    {
        return view('sisgedi::gerente.instructores.edit', compact('instructor'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $this->validarDatos($request, $instructor->id);

        if ($request->hasFile('firma_digital')) {
            $validated['firma_digital_url'] = $request->file('firma_digital')
                ->store('sisgedi/firmas_instructores', 'public');
        }

        $instructor->update($validated);

        return redirect()->route('sisgedi.gerente.instructores.index')
            ->with('success', 'Datos del instructor actualizados.');
    }

    public function destroy(Instructor $instructor)
    {
        // RF-066: un instructor no puede eliminarse si tiene firmas pendientes
        // en documentos vigentes. El modulo de firmas de documentos aun no
        // existe en SISGEDI, asi que por ahora no hay nada que lo bloquee;
        // esta validacion debe conectarse aqui cuando se construya ese modulo.
        $instructor->delete();

        return redirect()->route('sisgedi.gerente.instructores.index')
            ->with('success', 'Instructor eliminado del catálogo.');
    }

    private function validarDatos(Request $request, ?int $ignorarId = null): array
    {
        return $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'area_especialidad' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:50',
            'estado' => 'required|in:activo,inactivo',
            'firma_digital' => 'nullable|image|max:2048',
        ]);
    }
}
