<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaseController extends Controller
{
    // ── Guardia de sesión reutilizable ──────────────────────────────
    private function requireSession()
    {
        if (! session('sisgedi_user')) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }
        return null;
    }

    // ── 1. LISTADO DE FASES ─────────────────────────────────────────
    public function index()
    {
        if ($r = $this->requireSession()) return $r;

        $fases = DB::table('fase')
            ->orderBy('fase_id')
            ->get();

        // Enriquecer con conteo de sectores y convocatorias por fase
        $fases = $fases->map(function ($f) {
            // Intentar obtener sectores asociados a la fase (si existe tabla pivot)
            // Por ahora retornamos conteo de convocatorias de esa fase como referencia
            $convocatorias = DB::table('convocatoria')
                ->where('fase_id', $f->fase_id)
                ->count();

            return (object) [
                'fase_id'         => $f->fase_id,
                'nombre_fase'     => $f->nombre_fase,
                'tipo'            => $f->tipo ?? null,
                'fecha_inicio'    => $f->fecha_inicio ?? null,
                'fecha_fin'       => $f->fecha_fin ?? null,
                'estado'          => $f->estado ?? 'En Curso',
                'convocatorias'   => $convocatorias,
            ];
        });

        return view('sisgedi::fases.index', compact('fases'));
    }

    // ── 2. FORMULARIO CREAR ─────────────────────────────────────────
    public function create()
    {
        if ($r = $this->requireSession()) return $r;

        // Obtener sectores disponibles
        $sectores = DB::table('sectors')
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return view('sisgedi::fases.create', compact('sectores'));
    }

    // ── 3. GUARDAR NUEVA FASE ───────────────────────────────────────
    public function store(Request $request)
    {
        if ($r = $this->requireSession()) return $r;

        $request->validate([
            'nombre_fase'  => 'required|string|max:255',
            'tipo'         => 'required|in:Sol,Luna',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
        ], [
            'nombre_fase.required'  => 'El nombre de la fase es obligatorio.',
            'tipo.required'         => 'El tipo de fase es obligatorio.',
            'tipo.in'               => 'El tipo debe ser Sol o Luna.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'    => 'La fecha de fin es obligatoria.',
            'fecha_fin.after'       => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ]);

        // Verificar columnas disponibles en la tabla fase
        $data = ['nombre_fase' => $request->nombre_fase];

        if (DB::getSchemaBuilder()->hasColumn('fase', 'tipo')) {
            $data['tipo'] = $request->tipo;
        }
        if (DB::getSchemaBuilder()->hasColumn('fase', 'fecha_inicio')) {
            $data['fecha_inicio'] = $request->fecha_inicio;
        }
        if (DB::getSchemaBuilder()->hasColumn('fase', 'fecha_fin')) {
            $data['fecha_fin'] = $request->fecha_fin;
        }
        if (DB::getSchemaBuilder()->hasColumn('fase', 'estado')) {
            $data['estado'] = 'Pendiente';
        }
        if (DB::getSchemaBuilder()->hasColumn('fase', 'descripcion')) {
            $data['descripcion'] = $request->descripcion;
        }

        DB::table('fase')->insert($data);

        return redirect()->route('sisgedi.fases.index')
            ->with('success', 'Fase "' . $request->nombre_fase . '" creada exitosamente.');
    }

    // ── 4. EDITAR FASE ──────────────────────────────────────────────
    public function edit($id)
    {
        if ($r = $this->requireSession()) return $r;

        $fase = DB::table('fase')->where('fase_id', $id)->first();
        if (! $fase) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'Fase no encontrada.']);
        }

        $sectores = DB::table('sectors')
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return view('sisgedi::fases.create', compact('fase', 'sectores'));
    }
}
