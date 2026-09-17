<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConvocatoriaController extends Controller
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

    // ── Guardia: requiere que existan fases en la BD ───────────────
    private function requireFases()
    {
        $hayFases = DB::table('fase')->exists();
        if (! $hayFases) {
            return redirect()->route('sisgedi.dashboard')
                ->with('error', 'No puedes gestionar convocatorias porque aún no se ha creado ninguna fase. Ve a Gestión de Fases y crea una fase primero.');
        }
        return null;
    }

    // ── 1. LISTADO DE CONVOCATORIAS ─────────────────────────────────
    public function index()
    {
        if ($r = $this->requireSession()) return $r;
        if ($r = $this->requireFases())   return $r;

        $convocatorias = DB::table('convocatoria')
            ->orderByDesc('convocatoria_id')
            ->get()
            ->map(function ($c) {
                $postulantes = DB::table('postulacion')
                    ->where('convocatoria_id', $c->convocatoria_id)->count();

                // Nombre real de la fase
                $fase = DB::table('fase')
                    ->where('fase_id', $c->fase_id)
                    ->value('nombre_fase');

                return (object)[
                    'id'          => $c->convocatoria_id,
                    'titulo'      => $c->titulo,
                    'fase'        => $fase ?? 'Fase ' . $c->fase_id,
                    'descripcion' => $c->descripcion ?? '',
                    'apertura'    => $c->fecha_apertura,
                    'cierre'      => $c->fecha_cierre,
                    'postulantes' => $postulantes,
                    'estado'      => ucfirst($c->estado),
                ];
            });

        return view('sisgedi::convocatorias.index', compact('convocatorias'));
    }

    // ── 2. FORMULARIO CREAR ─────────────────────────────────────────
    public function create()
    {
        if ($r = $this->requireSession()) return $r;
        if ($r = $this->requireFases())   return $r;

        $cargos = DB::table('cargo_sisgedi')->orderBy('nombre_cargo')->get();
        // Fases reales desde la BD para el select
        $fases  = DB::table('fase')->orderBy('fase_id')->get(['fase_id', 'nombre_fase']);
        return view('sisgedi::convocatorias.create', compact('cargos', 'fases'));
    }

    // ── 3. GUARDAR ──────────────────────────────────────────────────
    public function store(Request $request)
    {
        if ($r = $this->requireSession()) return $r;
        if ($r = $this->requireFases())   return $r;

        $request->validate([
            'titulo'   => 'required|string|max:255',
            'fase_id'  => 'required|integer|exists:fase,fase_id',
            'apertura' => 'required|date',
            'cierre'   => 'required|date|after:apertura',
            'cargos'   => 'required|array|min:1',
        ], [
            'titulo.required'      => 'El título es obligatorio.',
            'fase_id.required'     => 'Selecciona la fase destino.',
            'fase_id.exists'       => 'La fase seleccionada no existe en el sistema.',
            'apertura.required'    => 'La fecha de apertura es obligatoria.',
            'cierre.required'      => 'La fecha de cierre es obligatoria.',
            'cierre.after'         => 'El cierre debe ser posterior a la apertura.',
            'cargos.required'      => 'Agrega al menos un cargo.',
        ]);

        $sesion = session('sisgedi_user');
        $faseId = (int) $request->fase_id;

        // ── Validar límite: máximo 1 convocatoria activa por fase ────────
        $otraActiva = DB::table('convocatoria')
            ->where('fase_id', $faseId)
            ->where(function ($q) {
                $q->where('estado', 'abierta')->orWhere('estado', 'Abierta');
            })
            ->first();

        if ($otraActiva) {
            return back()
                ->withInput()
                ->withErrors([
                    'fase_id' => 'Ya existe una convocatoria activa para esta fase ("' . ($otraActiva->titulo ?? 'Convocatoria') . '"). Solo se permite 1 convocatoria activa por fase. Debes cerrar la actual antes de abrir una nueva.',
                ]);
        }

        // Insertar convocatoria
        // creado_por referencia users_sisgedi.id_users — usamos el id de la sesión SISGEDI
        $convId = DB::table('convocatoria')->insertGetId([
            'titulo'              => $request->titulo,
            'descripcion'         => $request->descripcion,
            'fase_id'             => $faseId,
            'fecha_apertura'      => $request->apertura,
            'fecha_cierre'        => $request->cierre,
            'umbral_aprobacion'   => $request->umbral ?? 70,
            'politica_sin_umbral' => $request->politica ?? 'mayor_puntaje',
            'estado'              => 'abierta',
            'creado_por'          => $sesion['id_sisgedi'] ?? null, // id_users de users_sisgedi
        ]);

        // Insertar cargos
        foreach ($request->cargos as $cargo) {
            $nombreCargo = $cargo['nombre'] ?? '';
            $cargoId = $cargo['cargo_id'] ?? null;
            if (!$cargoId && !empty($nombreCargo)) {
                $cargoDb = DB::table('cargo_sisgedi')->where('nombre_cargo', $nombreCargo)->first();
                if ($cargoDb) {
                    $cargoId = $cargoDb->cargo_id;
                } else {
                    $cargoId = DB::table('cargo_sisgedi')->insertGetId([
                        'nombre_cargo' => $nombreCargo,
                        'descripcion'  => 'Perfil asignado en convocatoria',
                    ]);
                }
            }
            if (! $cargoId) continue;

            DB::table('convocatoria_cargo')->insert([
                'convocatoria_id'        => $convId,
                'cargo_id'               => (int) $cargoId,
                'cupos'                  => (int) ($cargo['cupos'] ?? 1),
                'titulacion_requerida'   => implode(', ', $cargo['perfiles'] ?? []),
                'competencias_minimas'   => 'Competencias requeridas para el sector',
                'documentos_obligatorios'=> implode(', ', $cargo['documentos'] ?? []),
            ]);
        }

        return redirect()->route('sisgedi.convocatorias.index')
            ->with('success', '¡Convocatoria "' . $request->titulo . '" creada y publicada!');
    }

    // ── 3.B TOGGLE ESTADO (ACTIVAR / DESACTIVAR) ────────────────────
    public function toggleEstado($id)
    {
        if ($r = $this->requireSession()) return $r;

        $conv = DB::table('convocatoria')->where('convocatoria_id', $id)->first();
        if (! $conv) {
            return back()->with('info', 'Acción procesada.');
        }

        $estadoActual = strtolower($conv->estado ?? '');
        $nuevoEstado = ($estadoActual === 'abierta') ? 'cerrada' : 'abierta';

        // Si la intención es ACTIVAR la convocatoria, validar límite de 1 por fase
        if ($nuevoEstado === 'abierta') {
            $otraActiva = DB::table('convocatoria')
                ->where('fase_id', $conv->fase_id)
                ->where('convocatoria_id', '!=', $id)
                ->where(function ($q) {
                    $q->where('estado', 'abierta')->orWhere('estado', 'Abierta');
                })
                ->first();

            if ($otraActiva) {
                return back()->withErrors([
                    'general' => 'Ya existe la convocatoria activa "' . ($otraActiva->titulo ?? 'Convocatoria') . '" en la Fase ' . $conv->fase_id . '. Solo se permite 1 convocatoria activa por fase. Cierra la actual antes de activar esta.',
                ]);
            }
        }

        DB::table('convocatoria')
            ->where('convocatoria_id', $id)
            ->update(['estado' => $nuevoEstado]);

        $msg = ($nuevoEstado === 'abierta')
            ? '¡Convocatoria "' . $conv->titulo . '" activada correctamente!'
            : 'Convocatoria "' . $conv->titulo . '" desactivada / cerrada.';

        return back()->with('success', $msg);
    }

    // ── 4. VER DETALLE — cargos + postulantes con sus 3 opciones ──────
    public function show($id)
    {
        if ($r = $this->requireSession()) return $r;

        $conv = DB::table('convocatoria')->where('convocatoria_id', $id)->first();
        if (! $conv) {
            return redirect()->route('sisgedi.convocatorias.index')
                ->with('info', 'Convocatoria no encontrada.');
        }

        // Cargos asignados a esta convocatoria
        $cargos = DB::table('convocatoria_cargo as cc')
            ->join('cargo_sisgedi as cg', 'cc.cargo_id', '=', 'cg.cargo_id')
            ->where('cc.convocatoria_id', $id)
            ->select(
                'cc.convocatoria_cargo_id',
                'cg.nombre_cargo',
                'cc.cupos',
                'cc.titulacion_requerida',
                'cc.competencias_minimas',
                'cc.documentos_obligatorios'
            )
            ->get();

        // Postulantes con sus 3 opciones de cargo ordenadas por preferencia
        $postulaciones = DB::table('postulacion as p')
            ->where('p.convocatoria_id', $id)
            ->orderBy('p.postulacion_id')
            ->get(['p.postulacion_id', 'p.aprendiz_usuario_id', 'p.fecha_postulacion', 'p.estado']);

        // Para cada postulación, obtener el nombre del aprendiz y sus 3 opciones
        $postulaciones = $postulaciones->map(function ($post) {
            // Nombre del aprendiz desde users_sisgedi
            $aprendiz = DB::table('users_sisgedi')
                ->where('id_users', $post->aprendiz_usuario_id)
                ->first(['nombre', 'correo']);

            // Las 3 opciones de cargo en orden de preferencia
            $opciones = DB::table('postulacion_opcion_cargo as poc')
                ->join('convocatoria_cargo as cc', 'poc.convocatoria_cargo_id', '=', 'cc.convocatoria_cargo_id')
                ->join('cargo_sisgedi as cg', 'cc.cargo_id', '=', 'cg.cargo_id')
                ->where('poc.postulacion_id', $post->postulacion_id)
                ->orderBy('poc.orden_preferencia')
                ->select('poc.orden_preferencia', 'cg.nombre_cargo')
                ->get();

            $post->aprendiz_nombre = $aprendiz ? ucwords(str_replace('.', ' ', $aprendiz->nombre)) : 'Desconocido';
            $post->aprendiz_correo = $aprendiz->correo ?? '—';
            $post->opciones        = $opciones;
            return $post;
        });

        // Nombre de la fase
        $fase = DB::table('fase')->where('fase_id', $conv->fase_id)->first(['nombre_fase']);

        return view('sisgedi::convocatorias.show', compact('conv', 'cargos', 'postulaciones', 'fase'));
    }

    // ── 5. FORMULARIO EDITAR ────────────────────────────────────────
    public function edit($id)
    {
        if ($r = $this->requireSession()) return $r;
        return redirect()->route('sisgedi.convocatorias.index');
    }

    // ── 6. ACTUALIZAR ───────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        if ($r = $this->requireSession()) return $r;
        return redirect()->route('sisgedi.convocatorias.index')
            ->with('success', 'Convocatoria actualizada.');
    }

    // ── 7. ELIMINAR ─────────────────────────────────────────────────
    public function destroy($id)
    {
        if ($r = $this->requireSession()) return $r;

        $conv = DB::table('convocatoria')->where('convocatoria_id', $id)->first();
        if (! $conv) {
            return redirect()->route('sisgedi.convocatorias.index')
                ->with('info', 'La convocatoria no existe o ya fue eliminada.');
        }

        DB::transaction(function () use ($id) {
            // IDs de postulaciones de esta convocatoria (necesarios para limpiar sus hijos)
            $postulacionIds = DB::table('postulacion')
                ->where('convocatoria_id', $id)
                ->pluck('postulacion_id');

            if ($postulacionIds->isNotEmpty()) {
                // IDs de entrevistas de esas postulaciones (tienen su propia tabla hija)
                $entrevistaIds = DB::table('entrevista')
                    ->whereIn('postulacion_id', $postulacionIds)
                    ->pluck('entrevista_id');

                if ($entrevistaIds->isNotEmpty()) {
                    DB::table('calificacion_item_entrevista')
                        ->whereIn('entrevista_id', $entrevistaIds)->delete();
                }
                DB::table('entrevista')
                    ->whereIn('postulacion_id', $postulacionIds)->delete();

                // Tablas hijas de postulacion (todas las FK apuntan aquí con NO ACTION)
                DB::table('documento_postulacion')
                    ->whereIn('postulacion_id', $postulacionIds)->delete();
                DB::table('prueba_psicotecnica')
                    ->whereIn('postulacion_id', $postulacionIds)->delete();
                DB::table('resultado_seleccion')
                    ->whereIn('postulacion_id', $postulacionIds)->delete();
                DB::table('postulacion_opcion_cargo')
                    ->whereIn('postulacion_id', $postulacionIds)->delete();
            }

            // Eliminar en orden respetando las FK (NO ACTION = sin cascada automática)
            DB::table('postulacion')->where('convocatoria_id', $id)->delete();
            DB::table('convocatoria_cargo')->where('convocatoria_id', $id)->delete();
            DB::table('convocatoria')->where('convocatoria_id', $id)->delete();
        });

        return redirect()->route('sisgedi.convocatorias.index')
            ->with('success', 'Convocatoria "' . $conv->titulo . '" eliminada correctamente.');
    }

    // ── 8. RESULTADOS DE SELECCIÓN ──────────────────────────────────
    public function resultados()
    {
        if ($r = $this->requireSession()) return $r;

        $resultados = collect([
            (object)['cargo'=>'Líder de Producción',  'postulantes'=>5,  'seleccionado'=>'María Fernanda López',  'puntaje'=>92, 'estado'=>'Seleccionado'],
            (object)['cargo'=>'Aprendiz Avicultor',   'postulantes'=>20, 'seleccionado'=>'Brayan Stiven Muñoz',   'puntaje'=>88, 'estado'=>'Seleccionado'],
            (object)['cargo'=>'Operario de Empaque',  'postulantes'=>15, 'seleccionado'=>'Natalia Gómez Pineda',  'puntaje'=>85, 'estado'=>'Seleccionado'],
        ]);

        return view('sisgedi::convocatorias.resultados', compact('resultados'));
    }

    // ── 9. REASIGNACIÓN ─────────────────────────────────────────────
    public function reasignacion()
    {
        if ($r = $this->requireSession()) return $r;

        $reasignaciones = collect([
            (object)['cargo_original'=>'Líder Cultivo Maíz',   'colaborador_actual'=>'Laura Valentina Hernández', 'nuevo_asignado'=>null,                'estado'=>'Pendiente'],
            (object)['cargo_original'=>'Colaborador Lácteos',  'colaborador_actual'=>'David Santiago Martínez',  'nuevo_asignado'=>'Camila Rojas Vargas','estado'=>'Reasignado'],
            (object)['cargo_original'=>'Aprendiz Avicultura',  'colaborador_actual'=>'Brayan Stiven Muñoz',       'nuevo_asignado'=>null,                'estado'=>'Pendiente'],
        ]);

        return view('sisgedi::convocatorias.reasignacion', compact('reasignaciones'));
    }

    // ── 10. CHECKLIST ENTREVISTA ────────────────────────────────────
    public function checklist()
    {
        if ($r = $this->requireSession()) return $r;

        $criterios = collect([
            (object)['nombre'=>'Presentación personal y actitud', 'tipo'=>'Calificación 1-5'],
            (object)['nombre'=>'Conocimiento técnico del área',   'tipo'=>'Calificación 1-5'],
            (object)['nombre'=>'Experiencia previa relevante',    'tipo'=>'Calificación 1-5'],
            (object)['nombre'=>'Disponibilidad de horario',       'tipo'=>'Sí / No'],
            (object)['nombre'=>'Manejo de herramientas tecnológicas','tipo'=>'Calificación 1-5'],
            (object)['nombre'=>'Observaciones adicionales',       'tipo'=>'Texto libre'],
        ]);

        $version = 'v2.1';
        $entrevistasExistentes = 3;

        return view('sisgedi::convocatorias.checklist',
            compact('criterios', 'version', 'entrevistasExistentes'));
    }
}
