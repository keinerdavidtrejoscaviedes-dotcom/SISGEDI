<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SISGEDI\Entities\Documento;

class DocumentoController extends Controller
{
    // ── 1. PÁGINA PRINCIPAL — landing institucional ───────────────────
    public function index()
    {
        $totalDocumentos   = Documento::count();
        $documentosActivos = Documento::where('estado', 'Activo')->count();

        // Verificar si existe al menos una convocatoria abierta
        $hayConvocatoriaActiva = DB::table('convocatoria')
            ->where(function ($q) {
                $q->where('estado', 'abierta')->orWhere('estado', 'Abierta');
            })
            ->whereDate('fecha_cierre', '>=', now())
            ->exists();

        return view('sisgedi::index', compact(
            'totalDocumentos',
            'documentosActivos',
            'hayConvocatoriaActiva'
        ));
    }

    // 2. DASHBOARD (requiere sesión sisgedi_user)
    public function dashboard()
    {
        $usuario = session('sisgedi_user');

        if (!$usuario) {
            return redirect()->route('sisgedi.login');
        }

        // Retornar vista específica o redireccionar basado en el id_rol
        if ($usuario['id_rol'] == 4) {
            return redirect()->route('sisgedi.comercial.dashboard');
        }

        $totalDocumentos   = Documento::count();
        $documentosActivos = Documento::where('estado', 'Activo')->count();
        $documentosRecientes = Documento::where('created_at', '>=', now()->subDays(30))->count();
        $ultimosDocumentos = Documento::orderBy('created_at', 'desc')->limit(8)->get();

        return view('sisgedi::dashboard', compact(
            'usuario',
            'totalUsuarios',
            'totalSectores',
            'convocatoriasAbiertas',
            'evidenciasPendientes',
            'pazYSalvoCompletos',
            'pazYSalvoTotal',
            'faseVigente',
            'hayFases',
            'progresoFase',
            'actividadReciente',
            'documentosPorSector'
        ));
    }

    // ── 3. FORMULARIO DE CREACIÓN ─────────────────────────────────────
    public function create()
    {
        return view('sisgedi::create');
    }

    // ── 4. GUARDAR EN BASE DE DATOS ───────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'      => 'required|string|unique:documentos,codigo|max:50',
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado'      => 'required|in:Activo,Inactivo',
        ]);

        Documento::create($validated);

        return redirect()->route('sisgedi.index')
            ->with('success', '¡Documento registrado con éxito!');
    }

    // ── 5. FORMULARIO DE EDICIÓN ──────────────────────────────────────
    public function edit($id)
    {
        $elemento = Documento::findOrFail($id);
        return view('sisgedi::edit', compact('elemento'));
    }

    // ── 6. ACTUALIZAR REGISTRO ────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $elemento  = Documento::findOrFail($id);
        $validated = $request->validate([
            'codigo'      => 'required|string|max:50|unique:documentos,codigo,' . $elemento->id,
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado'      => 'required|in:Activo,Inactivo',
        ]);

        $elemento->update($validated);

        return redirect()->route('sisgedi.index')
            ->with('success', '¡Documento actualizado con éxito!');
    }

    // ── 7. ELIMINAR REGISTRO ──────────────────────────────────────────
    public function destroy($id)
    {
        $elemento = Documento::findOrFail($id);
        $elemento->delete();

        return redirect()->route('sisgedi.index')
            ->with('success', '¡Documento eliminado correctamente!');
    }
}
