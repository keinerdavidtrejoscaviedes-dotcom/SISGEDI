<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostulacionController extends Controller
{
    // ── Guardia: solo aprendices autenticados en SISGEDI ──────────────
    private function requireAprendiz()
    {
        $sesion = session('sisgedi_user');
        if (! $sesion) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }
        if (strtolower($sesion['rol'] ?? '') !== 'aprendiz') {
            return redirect()->route('sisgedi.dashboard')
                ->withErrors(['general' => 'Esta sección es solo para aprendices.']);
        }
        return null;
    }

    // ─────────────────────────────────────────────────────────────────
    // 1. PANEL DEL APRENDIZ — ver convocatorias abiertas y su estado
    // ─────────────────────────────────────────────────────────────────
    public function panel()
    {
        if ($r = $this->requireAprendiz()) return $r;
        $sesion = session('sisgedi_user');

        // Convocatorias abiertas con sus cargos reales
        $convocatorias = DB::table('convocatoria')
            ->where(function ($q) {
                $q->where('estado', 'abierta')->orWhere('estado', 'Abierta');
            })
            ->get();

        $convocatorias = $convocatorias->map(function ($conv) use ($sesion) {
            $cargos = DB::table('convocatoria_cargo as cc')
                ->join('cargo_sisgedi as cg', 'cc.cargo_id', '=', 'cg.cargo_id')
                ->where('cc.convocatoria_id', $conv->convocatoria_id)
                ->select('cc.convocatoria_cargo_id', 'cg.nombre_cargo', 'cc.cupos',
                         'cc.titulacion_requerida', 'cc.competencias_minimas')
                ->get();

            $yaPostulado = DB::table('postulacion')
                ->where('convocatoria_id', $conv->convocatoria_id)
                ->where('aprendiz_usuario_id', $sesion['id'])
                ->exists();

            $conv->cargos       = $cargos;
            $conv->ya_postulado = $yaPostulado;
            return $conv;
        });

        return view('sisgedi::aprendiz.panel', compact('convocatorias', 'sesion'));
    }

    // ─────────────────────────────────────────────────────────────────
    // 2. VER DETALLE DE UNA CONVOCATORIA Y FORMULARIO DE POSTULACIÓN
    // ─────────────────────────────────────────────────────────────────
    public function verConvocatoria($id)
    {
        if ($r = $this->requireAprendiz()) return $r;
        $sesion = session('sisgedi_user');

        $conv = DB::table('convocatoria')->where('convocatoria_id', $id)->first();

        if (! $conv) {
            return redirect()->route('sisgedi.aprendiz.panel')
                ->withErrors(['general' => 'La convocatoria no existe.']);
        }

        if (strtolower($conv->estado ?? '') !== 'abierta') {
            return redirect()->route('sisgedi.aprendiz.panel')
                ->withErrors(['general' => 'Esta convocatoria no está disponible actualmente.']);
        }

        // ¿Ya se postuló?
        $postulacion = DB::table('postulacion')
            ->where('convocatoria_id', $id)
            ->where('aprendiz_usuario_id', $sesion['id'])
            ->first();

        // Cargos reales asignados a esta convocatoria
        $cargos = DB::table('convocatoria_cargo as cc')
            ->join('cargo_sisgedi as cg', 'cc.cargo_id', '=', 'cg.cargo_id')
            ->where('cc.convocatoria_id', $id)
            ->select('cc.convocatoria_cargo_id', 'cg.nombre_cargo', 'cc.cupos',
                     'cc.titulacion_requerida', 'cc.competencias_minimas',
                     'cc.documentos_obligatorios')
            ->get();

        // Si ya se postuló, obtener sus opciones elegidas
        $opcionesElegidas = collect();
        if ($postulacion) {
            $opcionesElegidas = DB::table('postulacion_opcion_cargo as poc')
                ->join('convocatoria_cargo as cc', 'poc.convocatoria_cargo_id', '=', 'cc.convocatoria_cargo_id')
                ->join('cargo_sisgedi as cg', 'cc.cargo_id', '=', 'cg.cargo_id')
                ->where('poc.postulacion_id', $postulacion->postulacion_id)
                ->orderBy('poc.orden_preferencia')
                ->select('poc.orden_preferencia', 'cg.nombre_cargo')
                ->get();
        }

        return view('sisgedi::aprendiz.convocatoria',
            compact('conv', 'cargos', 'postulacion', 'opcionesElegidas', 'sesion'));
    }

    // ─────────────────────────────────────────────────────────────────
    // 3. GUARDAR POSTULACIÓN (3 opciones de cargo en orden)
    // ─────────────────────────────────────────────────────────────────
    public function postular(Request $request, $convocatoriaId)
    {
        if ($r = $this->requireAprendiz()) return $r;
        $sesion = session('sisgedi_user');

        // Validar
        $request->validate([
            'opciones'   => 'required|array|size:3',
            'opciones.*' => 'required|distinct|integer',
        ], [
            'opciones.size'     => 'Debes seleccionar exactamente 3 cargos en orden de preferencia.',
            'opciones.*.distinct' => 'No puedes elegir el mismo cargo más de una vez.',
        ]);

        // Verificar convocatoria
        $conv = DB::table('convocatoria')
            ->where('convocatoria_id', $convocatoriaId)
            ->where('estado', 'abierta')
            ->whereDate('fecha_cierre', '>=', now())
            ->first();

        if (! $conv) {
            return back()->withErrors(['general' => 'La convocatoria ya no está abierta.']);
        }

        // Verificar que no se haya postulado antes
        $yaExiste = DB::table('postulacion')
            ->where('convocatoria_id', $convocatoriaId)
            ->where('aprendiz_usuario_id', $sesion['id'])
            ->exists();

        if ($yaExiste) {
            return back()->withErrors(['general' => 'Ya te has postulado a esta convocatoria.']);
        }

        // Insertar postulación
        $postulacionId = DB::table('postulacion')->insertGetId([
            'convocatoria_id'     => $convocatoriaId,
            'aprendiz_usuario_id' => $sesion['id'],
            'fecha_postulacion'   => now(),
            'estado'              => 'postulado',
        ]);

        // Insertar las 3 opciones de cargo
        foreach ($request->opciones as $orden => $convocatoriaCargaId) {
            DB::table('postulacion_opcion_cargo')->insert([
                'postulacion_id'       => $postulacionId,
                'orden_preferencia'    => $orden + 1,
                'convocatoria_cargo_id'=> (int) $convocatoriaCargaId,
            ]);
        }

        return redirect()->route('sisgedi.aprendiz.panel')
            ->with('success', '¡Te has postulado exitosamente! Tus 3 opciones de cargo fueron registradas.');
    }
}
