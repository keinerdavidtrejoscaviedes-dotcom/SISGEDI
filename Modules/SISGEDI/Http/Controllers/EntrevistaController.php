<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntrevistaController extends Controller
{
    private function requireSession()
    {
        if (!session('sisgedi_user')) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }
        return null;
    }

    // ── 1. LISTAR POSTULANTES PARA ENTREVISTA ────────────────────────
    public function index(Request $request)
    {
        if ($r = $this->requireSession()) return $r;

        // Mostrar postulaciones pendientes o evaluadas.
        // Seleccionamos las postulaciones de convocatorias abiertas (o todas si se quiere historial).
        $postulaciones = DB::table('postulacion as p')
            ->join('convocatoria as c', 'p.convocatoria_id', '=', 'c.convocatoria_id')
            ->join('users_sisgedi as u', 'p.aprendiz_usuario_id', '=', 'u.id_users')
            ->leftJoin('entrevista as e', 'p.postulacion_id', '=', 'e.postulacion_id')
            ->select(
                'p.postulacion_id',
                'p.estado as estado_postulacion',
                'p.fecha_postulacion',
                'u.nombre as aprendiz_nombre',
                'u.correo as aprendiz_correo',
                'c.titulo as convocatoria_titulo',
                'e.entrevista_id'
            )
            ->orderByDesc('p.fecha_postulacion')
            ->get();

        return view('sisgedi::entrevistas.index', compact('postulaciones'));
    }

    // ── 2. FORMULARIO DE ENTREVISTA ──────────────────────────────────
    public function create($postulacion_id)
    {
        if ($r = $this->requireSession()) return $r;

        $postulacion = DB::table('postulacion as p')
            ->join('users_sisgedi as u', 'p.aprendiz_usuario_id', '=', 'u.id_users')
            ->where('p.postulacion_id', $postulacion_id)
            ->select('p.*', 'u.nombre as aprendiz_nombre')
            ->first();

        if (!$postulacion) {
            return redirect()->route('sisgedi.entrevistas.index')->withErrors(['general' => 'Postulación no encontrada.']);
        }

        // Obtener el checklist vigente
        $checklist = DB::table('checklist_entrevista')
            ->where('vigente', 1)
            ->orderByDesc('checklist_id')
            ->first();

        if (!$checklist) {
            return redirect()->route('sisgedi.entrevistas.index')->withErrors(['general' => 'No hay un checklist de entrevista configurado o vigente.']);
        }

        // Obtener los ítems
        $items = DB::table('item_checklist')
            ->where('checklist_id', $checklist->checklist_id)
            ->orderBy('orden')
            ->get();

        // Opciones a las que aplicó (para mostrarlas al evaluador)
        $opciones = DB::table('postulacion_opcion_cargo as poc')
            ->join('convocatoria_cargo as cc', 'poc.convocatoria_cargo_id', '=', 'cc.convocatoria_cargo_id')
            ->join('cargo_sisgedi as cg', 'cc.cargo_id', '=', 'cg.cargo_id')
            ->where('poc.postulacion_id', $postulacion_id)
            ->orderBy('poc.orden_preferencia')
            ->select('poc.orden_preferencia', 'cg.nombre_cargo')
            ->get();

        return view('sisgedi::entrevistas.create', compact('postulacion', 'checklist', 'items', 'opciones'));
    }

    // ── 3. GUARDAR ENTREVISTA ────────────────────────────────────────
    public function store(Request $request, $id)
    {
        if ($r = $this->requireSession()) return $r;
        $sesion = session('sisgedi_user');

        $request->validate([
            'observaciones' => 'nullable|string|max:2000',
        ]);

        $postulacion = DB::table('postulacion')->where('postulacion_id', $id)->first();
        if (!$postulacion) {
            return redirect()->route('sisgedi.entrevistas.index')
                ->withErrors(['general' => 'Postulación no encontrada.']);
        }

        // Verificar si ya tiene entrevista
        $existe = DB::table('entrevista')->where('postulacion_id', $id)->exists();
        if ($existe) {
            return redirect()->route('sisgedi.entrevistas.index')
                ->withErrors(['general' => 'Este postulante ya ha sido entrevistado.']);
        }

        DB::beginTransaction();
        try {
            $evaluadorId = $sesion['id'] ?? null;

            // Verificar que el ID de sesión existe en users_sisgedi
            if (!$evaluadorId || !DB::table('users_sisgedi')->where('id_users', $evaluadorId)->exists()) {
                // Fallback: usar el primer usuario con rol Administrador
                $adminRol = DB::table('roles_sisgedi')->where('nombre', 'Administrador')->value('id_rol');
                $evaluadorId = DB::table('users_sisgedi')->where('id_rol', $adminRol)->value('id_users');
            }

            $entrevistaId = DB::table('entrevista')->insertGetId([
                'postulacion_id'   => $id,
                'evaluador_id'     => $evaluadorId,
                'fecha_entrevista' => now(),
                'observaciones'    => $request->observaciones,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Solo guardar ítems de tipo calificacion (numéricos)
            $calificaciones = $request->input('calificaciones', []);
            foreach ($calificaciones as $itemId => $valor) {
                // Verificar que sea un item de tipo calificacion (valor numérico 1-5)
                $item = DB::table('item_checklist')
                    ->where('item_id', $itemId)
                    ->where('tipo_respuesta', 'calificacion')
                    ->first();

                if ($item && is_numeric($valor) && $valor >= 1 && $valor <= 5) {
                    DB::table('calificacion_item_entrevista')->insert([
                        'entrevista_id'      => $entrevistaId,
                        'item_id'            => $itemId,
                        'valor_calificacion' => (float) $valor,
                    ]);
                }
            }

            // Actualizar estado de la postulación a 'seleccionado'
            DB::table('postulacion')
                ->where('postulacion_id', $id)
                ->update(['estado' => 'seleccionado']);

            DB::commit();

            return redirect()->route('sisgedi.entrevistas.index')
                ->with('success', 'Entrevista registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['general' => 'Error al guardar la entrevista: ' . $e->getMessage()]);
        }
    }
}
