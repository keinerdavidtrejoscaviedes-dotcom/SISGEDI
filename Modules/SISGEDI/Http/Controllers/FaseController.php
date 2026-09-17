<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
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

    // ── Sincroniza fases vencidas (RN-010) ──────────────────────────
    // Una fase "Activa" cuya fecha_fin ya pasó se marca automáticamente
    // como "Finalizada", liberando el sistema para crear la siguiente.
    public static function sincronizarEstados(): void
    {
        $schema = DB::getSchemaBuilder();
        if (! $schema->hasColumn('fase', 'estado') || ! $schema->hasColumn('fase', 'fecha_fin')) {
            return;
        }

        DB::table('fase')
            ->where('estado', 'Activa')
            ->whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '<', now()->toDateString())
            ->update(['estado' => 'Finalizada']);
    }

    // ── Fase activa actual (RN-010: solo una fase activa a la vez) ──
    // Una fase queda "Activa" desde el momento en que se crea (sin importar
    // su fecha_inicio) y lo sigue estando hasta que se sincronice como
    // Finalizada por fecha o se cierre manualmente.
    private function faseActiva()
    {
        self::sincronizarEstados();

        if (! DB::getSchemaBuilder()->hasColumn('fase', 'estado')) {
            return null;
        }

        return DB::table('fase')->where('estado', 'Activa')->first();
    }

    // ── 1. LISTADO DE FASES ─────────────────────────────────────────
    public function index()
    {
        if ($r = $this->requireSession()) return $r;

        $faseActiva = $this->faseActiva();

        $fases = DB::table('fase')
            ->orderBy('fase_id')
            ->get();

        // Enriquecer con conteo de convocatorias
        $fases = $fases->map(function ($f) {
            $convocatorias = DB::table('convocatoria')
                ->where('fase_id', $f->fase_id)
                ->count();

            return (object) [
                'fase_id'         => $f->fase_id,
                'nombre_fase'     => $f->nombre_fase,
                'tipo'            => $f->tipo ?? null,
                'fecha_inicio'    => $f->fecha_inicio ?? null,
                'fecha_fin'       => $f->fecha_fin ?? null,
                'estado'          => $f->estado ?? 'Pendiente',
                'convocatorias'   => $convocatorias,
            ];
        });

        return view('sisgedi::fases.index', compact('fases', 'faseActiva'));
    }

    // ── 2. FORMULARIO CREAR ─────────────────────────────────────────
    public function create()
    {
        if ($r = $this->requireSession()) return $r;

        if ($faseActiva = $this->faseActiva()) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'Ya existe una fase activa ("' . $faseActiva->nombre_fase . '"). No puedes crear una nueva fase hasta que esta finalice.']);
        }

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

        if ($faseActiva = $this->faseActiva()) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'Ya existe una fase activa ("' . $faseActiva->nombre_fase . '"). No puedes crear una nueva fase hasta que esta finalice.']);
        }

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
            // La fase queda Activa de inmediato (RN-010: solo una fase activa a la vez).
            $data['estado'] = 'Activa';
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

    // ── 5. ACTUALIZAR FASE ───────────────────────────────────────────
    public function update(Request $request, $id)
    {
        if ($r = $this->requireSession()) return $r;

        $fase = DB::table('fase')->where('fase_id', $id)->first();
        if (! $fase) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'Fase no encontrada.']);
        }

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
        if (DB::getSchemaBuilder()->hasColumn('fase', 'descripcion')) {
            $data['descripcion'] = $request->descripcion;
        }

        DB::table('fase')->where('fase_id', $id)->update($data);

        return redirect()->route('sisgedi.fases.index')
            ->with('success', 'Fase "' . $request->nombre_fase . '" actualizada exitosamente.');
    }

    // ── 6. DESACTIVAR FASE ────────────────────────────────────────────
    // Cierra manualmente una fase activa antes de su fecha_fin, liberando
    // de inmediato la creación de una fase nueva.
    public function desactivar($id)
    {
        if ($r = $this->requireSession()) return $r;

        $fase = DB::table('fase')->where('fase_id', $id)->first();
        if (! $fase) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'Fase no encontrada.']);
        }

        if (! DB::getSchemaBuilder()->hasColumn('fase', 'estado')) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'No se puede desactivar: la tabla de fases no tiene columna de estado.']);
        }

        if (strtolower($fase->estado ?? '') !== 'activa') {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'Esta fase no está activa.']);
        }

        DB::table('fase')->where('fase_id', $id)->update(['estado' => 'Cerrada']);

        return redirect()->route('sisgedi.fases.index')
            ->with('success', 'Fase "' . $fase->nombre_fase . '" desactivada. Ya puedes crear una nueva fase.');
    }

    // ── 7. ELIMINAR FASE ─────────────────────────────────────────────
    public function destroy($id)
    {
        if ($r = $this->requireSession()) return $r;

        $fase = DB::table('fase')->where('fase_id', $id)->first();
        if (! $fase) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'Fase no encontrada.']);
        }

        try {
            DB::table('fase')->where('fase_id', $id)->delete();
        } catch (QueryException $e) {
            return redirect()->route('sisgedi.fases.index')
                ->withErrors(['msg' => 'No se puede eliminar la fase "' . $fase->nombre_fase . '" porque tiene información asociada (convocatorias, documentos u otros registros). Elimina primero esos datos.']);
        }

        return redirect()->route('sisgedi.fases.index')
            ->with('success', 'Fase "' . $fase->nombre_fase . '" eliminada exitosamente.');
    }
}
