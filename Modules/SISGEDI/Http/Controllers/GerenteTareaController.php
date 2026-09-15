<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\SISGEDI\Entities\DocumentoGuia;
use Modules\SISGEDI\Entities\SectorProductivo;
use Modules\SISGEDI\Entities\Tarea;

class GerenteTareaController extends Controller
{
    /**
     * Listado de tareas en cascada generadas por el Gerente Administrativo.
     */
    public function index(Request $request)
    {
        $fase = $request->attributes->get('sisgedi_fase');

        $tareas = Tarea::where('generador_usuario_id', Auth::id())
            ->where('fase_id', $fase->id)
            ->with(['sector', 'documentoGuia'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sisgedi::gerente.tareas.index', compact('tareas', 'fase'));
    }

    /**
     * Formulario para generar una nueva tarea en cascada con documento guia
     * dirigida a los Gestores del area (RF-019).
     */
    public function create(Request $request)
    {
        $fase = $request->attributes->get('sisgedi_fase');
        $usuarioRol = $request->attributes->get('sisgedi_usuario_rol');
        $gerenciaId = $usuarioRol->cargo->gerencia_id;

        // RN-014: solo los sectores activos en la fase vigente y de la gerencia del Gerente.
        $sectores = SectorProductivo::where('gerencia_id', $gerenciaId)
            ->activosEnFase($fase->id)
            ->orderBy('nombre')
            ->get();

        return view('sisgedi::gerente.tareas.create', compact('fase', 'sectores'));
    }

    /**
     * RN-013: toda tarea en cascada debe acompañarse de un documento guia
     * con instrucciones, entregables esperados y plazos.
     */
    public function store(Request $request)
    {
        $fase = $request->attributes->get('sisgedi_fase');
        $usuarioRol = $request->attributes->get('sisgedi_usuario_rol');
        $gerenciaId = $usuarioRol->cargo->gerencia_id;

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_limite' => 'required|date|after_or_equal:today',
            'sector_id' => [
                'required',
                'integer',
                Rule::exists('sisgedi_sectores_productivos', 'id')->where('gerencia_id', $gerenciaId),
            ],
            'instrucciones' => 'required|string',
            'entregables_esperados' => 'required|string',
            'plazos' => 'required|string',
            'archivo' => 'nullable|file|max:10240',
        ]);

        // Verifica que el sector siga activo en la fase vigente (RN-014/RN-017).
        $sectorValido = SectorProductivo::where('id', $validated['sector_id'])
            ->activosEnFase($fase->id)
            ->exists();

        if (! $sectorValido) {
            return back()->withInput()->withErrors([
                'sector_id' => 'El sector seleccionado no está activo en la fase vigente.',
            ]);
        }

        $archivoUrl = null;
        if ($request->hasFile('archivo')) {
            $archivoUrl = $request->file('archivo')->store('sisgedi/documentos_guia', 'public');
        }

        DB::transaction(function () use ($validated, $fase, $archivoUrl) {
            $tarea = Tarea::create([
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'],
                'fecha_limite' => $validated['fecha_limite'],
                // El entregable de un Gestor hacia el Gerente siempre es un documento;
                // foto/video-enlace solo aplican a las tareas de campo del Líder (RF-020).
                'tipo_evidencia_requerida' => 'documento',
                'estado' => 'asignada',
                'clasificacion' => 'cascada',
                'generador_usuario_id' => Auth::id(),
                'tarea_padre_id' => null,
                'sector_id' => $validated['sector_id'],
                'fase_id' => $fase->id,
                'confirmada' => false,
            ]);

            DocumentoGuia::create([
                'tarea_id' => $tarea->id,
                'instrucciones' => $validated['instrucciones'],
                'entregables_esperados' => $validated['entregables_esperados'],
                'plazos' => $validated['plazos'],
                'archivo_url' => $archivoUrl,
            ]);
        });

        return redirect()->route('sisgedi.gerente.tareas.index')
            ->with('success', 'Tarea generada y publicada para los Gestores del área.');
    }
}
