<?php

namespace Modules\SISGEDI\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\SISGEDI\Entities\DocumentoGuia;
use Modules\SISGEDI\Entities\EvidenciaSisgedi;
use Modules\SISGEDI\Entities\Tarea;

class SISGEDIController extends Controller
{
    public function dashboardLider()
    {
        $user = $this->liderUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $area = $this->gestorArea($user);
        $asignaciones = DB::table('tarea_asignaciones as asignaciones')
            ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
            ->where('asignaciones.colaborador_usuario_id', $user['id'])
            ->orderByDesc('asignaciones.created_at')
            ->get(['asignaciones.*', 'tareas.titulo', 'tareas.fecha_limite', 'tareas.evidencia_esperada']);
        $tareasPendientes = $asignaciones->whereIn('estado_individual', ['pendiente', 'en_desarrollo'])->count();
        $evidencias = DB::table('evidencias_sisgedi')->where('usuario_id', $user['id'])->get();
        $colaboradoresActivos = DB::table('tarea_asignaciones as asignaciones')
            ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
            ->where('tareas.gestor_usuario_id', $user['id'])
            ->distinct('asignaciones.colaborador_usuario_id')
            ->count('asignaciones.colaborador_usuario_id');
        $notificationCount = $tareasPendientes + $evidencias->whereIn('estado', ['en_revision', 'rechazada'])->count();

        return view('sisgedi::lider.dashboard', compact(
            'user', 'area', 'asignaciones', 'tareasPendientes', 'evidencias', 'colaboradoresActivos', 'notificationCount'
        ));
    }

    public function verDocumentoGuia(int $tareaId)
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $guide = $this->authorizedGuide($tareaId, $user);

        if (! $guide) {
            abort(404);
        }

        $extension = strtolower(pathinfo($guide->archivo_url, PATHINFO_EXTENSION));

        if (in_array($extension, ['xlsx', 'xls'], true)) {
            return view('sisgedi::spreadsheet', [
                'title' => $guide->titulo,
                'fileUrl' => route('sisgedi.documento_guia.file', $tareaId),
                'downloadUrl' => route('sisgedi.documento_guia.download', $tareaId),
            ]);
        }

        if (in_array($extension, ['docx', 'doc'], true)) {
            return view('sisgedi::word', [
                'title' => $guide->titulo,
                'fileUrl' => route('sisgedi.documento_guia.file', $tareaId),
                'downloadUrl' => route('sisgedi.documento_guia.download', $tareaId),
            ]);
        }

        return response()->file(
            Storage::disk('public')->path($guide->archivo_url),
            ['Content-Disposition' => 'inline; filename="' . basename($guide->archivo_url) . '"']
        );
    }

    public function archivoDocumentoGuia(int $tareaId)
    {
        $user = $this->sisgediUser();
        $guide = $user instanceof \Illuminate\Http\RedirectResponse ? null : $this->authorizedGuide($tareaId, $user);

        if (! $guide || ! $guide->archivo_url || ! Storage::disk('public')->exists($guide->archivo_url)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($guide->archivo_url));
    }

    public function descargarDocumentoGuia(int $tareaId)
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $guide = $this->authorizedGuide($tareaId, $user);

        if (! $guide || ! $guide->archivo_url || ! Storage::disk('public')->exists($guide->archivo_url)) {
            abort(404);
        }

        return Storage::disk('public')->download($guide->archivo_url);
    }

    private function authorizedGuide(int $tareaId, array $user)
    {
        $rol = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));
        $query = DB::table('documentos_guia as guias')
            ->join('tareas', 'tareas.tarea_id', '=', 'guias.tarea_id')
            ->where('guias.tarea_id', $tareaId);

        if (Str::startsWith($rol, 'lider ')) {
            $query->join('tarea_asignaciones as asignaciones', 'asignaciones.tarea_id', '=', 'tareas.tarea_id')
                ->where('asignaciones.colaborador_usuario_id', $user['id']);
        } else {
            $query->where('tareas.gestor_usuario_id', $user['id']);
        }

        return $query->first(['guias.*', 'tareas.titulo', 'tareas.fecha_limite', 'tareas.sector']);
    }

    public function dashboardGestor()
    {
        $user = session('sisgedi_user');

        if (! $user) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }

        $rolNormalizado = Str::lower(trim((string) ($user['rol'] ?? '')));

        if (! Str::startsWith($rolNormalizado, 'gestor.') && ! Str::startsWith($rolNormalizado, 'gestor ')) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Tu rol no tiene acceso al dashboard del Gestor.']);
        }

        $area = $this->gestorArea($user);

        $totalTareas = DB::table('tarea_asignaciones')->count();
        $tareasPendientes = DB::table('tarea_asignaciones')
            ->whereIn('estado_individual', ['pendiente', 'en_desarrollo', 'enviada'])
            ->count();
        $evidenciasRevision = DB::table('tarea_asignaciones')
            ->where('estado_individual', 'enviada')
            ->count();
        $planesTrabajo = DB::table('planes_trabajo')
            ->where('usuario_id', $user['id'])
            ->count();
        $proximosHitos = DB::table('hitos_plan_trabajo')
            ->join('planes_trabajo', 'planes_trabajo.plan_trabajo_id', '=', 'hitos_plan_trabajo.plan_trabajo_id')
            ->where('planes_trabajo.usuario_id', $user['id'])
            ->whereDate('hitos_plan_trabajo.fecha', '>=', today())
            ->orderBy('hitos_plan_trabajo.fecha')
            ->limit(5)
            ->get(['hitos_plan_trabajo.titulo', 'hitos_plan_trabajo.fecha', 'hitos_plan_trabajo.tipo']);
        $notificationCount = $tareasPendientes + $evidenciasRevision;

        return view('sisgedi::gestor.dashboard', compact(
            'user',
            'totalTareas',
            'tareasPendientes',
            'evidenciasRevision',
            'planesTrabajo',
            'notificationCount',
            'area',
            'proximosHitos'
        ));
    }

    public function tareasRecibidas()
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $rol = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));
        $esLider = Str::startsWith($rol, 'lider ');

        return $this->gestorModuleView(
            'Tareas Recibidas',
            $esLider ? 'Tareas asignadas por el Gestor de mi sector' : 'Tareas asignadas por el Gerente Administrativo',
            'received',
            DB::table('tarea_asignaciones as asignaciones')
                ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
                ->join('users_sisgedi as usuarios', 'usuarios.id_users', '=', 'asignaciones.colaborador_usuario_id')
                ->where('asignaciones.colaborador_usuario_id', $user['id'])
                ->orderByDesc('asignaciones.created_at')
                ->leftJoin('documentos_guia as guias', 'guias.tarea_id', '=', 'tareas.tarea_id')
                ->get(['asignaciones.*', 'tareas.titulo', 'tareas.fecha_limite', 'tareas.sector', 'tareas.evidencia_esperada', 'guias.archivo_url'])
        );
    }

    public function tareasLideres()
    {
        return $this->gestorModuleView(
            'Tareas para Líderes',
            'Tareas en cascada generadas para los Líderes de tu sector',
            'leaders',
            DB::table('tarea_asignaciones as asignaciones')
                ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
                ->where('tareas.gestor_usuario_id', session('sisgedi_user.id'))
                ->orderByDesc('asignaciones.created_at')
                ->leftJoin('documentos_guia as guias', 'guias.tarea_id', '=', 'tareas.tarea_id')
                ->get(['asignaciones.*', 'tareas.titulo', 'tareas.fecha_limite', 'tareas.sector', 'tareas.evidencia_esperada', 'guias.archivo_url'])
        );
    }

    public function revisionEvidencias()
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $rol = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));
        $esLider = Str::startsWith($rol, 'lider ');

        return $this->gestorModuleView(
            'Revisión de Evidencias',
            $esLider ? 'Evidencias de los Colaboradores pendientes de aprobación' : 'Evidencias de los Líderes pendientes de aprobación',
            'review',
            DB::table('evidencias_sisgedi as evidencias')
                ->join('tarea_asignaciones as asignaciones', 'asignaciones.tarea_asignacion_id', '=', 'evidencias.tarea_asignacion_id')
                ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
                ->join('users_sisgedi as usuarios', 'usuarios.id_users', '=', 'asignaciones.colaborador_usuario_id')
                ->whereIn('evidencias.estado', ['enviada', 'en_revision'])
                ->when($esLider, function ($query) use ($user) {
                    return $query->where('tareas.gestor_usuario_id', $user['id']);
                }, function ($query) use ($user) {
                    return $query->where('tareas.gestor_usuario_id', $user['id']);
                })
                ->orderByDesc('evidencias.created_at')
                ->get([
                    'evidencias.*',
                    'evidencias.estado as estado_individual',
                    'asignaciones.tarea_id',
                    'asignaciones.tarea_asignacion_id',
                    'usuarios.nombre as usuario_nombre',
                    'usuarios.correo as usuario_correo',
                    'tareas.titulo',
                ])
                ->map(function ($evidence) {
                    $evidence->created_at = \Illuminate\Support\Carbon::parse($evidence->created_at);
                    return $evidence;
                })
        );
    }

    public function liderMisEvidencias()
    {
        return $this->gestorModuleView(
            'Mis Evidencias',
            'Evidencias de cumplimiento de mis tareas asignadas',
            'evidence',
            DB::table('evidencias_sisgedi as evidencias')
                ->join('tarea_asignaciones as asignaciones', 'asignaciones.tarea_asignacion_id', '=', 'evidencias.tarea_asignacion_id')
                ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
                ->where('evidencias.usuario_id', session('sisgedi_user.id'))
                ->orderByDesc('evidencias.created_at')
                ->get([
                    'evidencias.*',
                    'evidencias.estado as estado_individual',
                    'asignaciones.tarea_id',
                    'asignaciones.tarea_asignacion_id',
                ])
        );
    }

    public function liderTareasColaboradores()
    {
        $user = $this->liderUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        return $this->gestorModuleView(
            'Tareas para Colaboradores',
            'Tareas generadas para los colaboradores de mi equipo',
            'collaborators',
            DB::table('tarea_asignaciones as asignaciones')
                ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
                ->leftJoin('documentos_guia as guias', 'guias.tarea_id', '=', 'tareas.tarea_id')
                ->where('tareas.gestor_usuario_id', $user['id'])
                ->orderByDesc('asignaciones.created_at')
                ->get([
                    'asignaciones.*',
                    'tareas.titulo',
                    'tareas.fecha_limite',
                    'tareas.evidencia_esperada',
                    'guias.archivo_url',
                ])
        );
    }

    public function liderStoreTareaColaborador(Request $request)
    {
        $user = $this->liderUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:2000',
            'fecha_limite' => 'required|date',
            'colaborador_usuario_id' => 'required|integer',
            'evidencia_esperada' => 'nullable|string|max:150',
            'instrucciones' => 'required|string|max:5000',
            'entregables_esperados' => 'required|string|max:5000',
            'plazos' => 'required|string|max:1000',
            'documento_guia' => 'required|file|max:10240',
        ]);

        if (! $this->colaboradoresDelArea($user)->firstWhere('id_users', (int) $validated['colaborador_usuario_id'])) {
            return back()->withInput()->withErrors(['colaborador_usuario_id' => 'Selecciona un colaborador válido.']);
        }

        $area = $this->gestorArea($user);
        $tarea = Tarea::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'fecha_limite' => $validated['fecha_limite'],
            'estado' => 'asignada',
            'gestor_usuario_id' => $user['id'],
            'sector' => $area,
            'evidencia_esperada' => $validated['evidencia_esperada'] ?? null,
        ]);

        DocumentoGuia::create([
            'tarea_id' => $tarea->tarea_id,
            'instrucciones' => $validated['instrucciones'],
            'entregables_esperados' => $validated['entregables_esperados'],
            'plazos' => $validated['plazos'],
            'archivo_url' => $request->file('documento_guia')->store('sisgedi/documentos-guia/' . $user['id'], 'public'),
        ]);

        DB::table('tarea_asignaciones')->insert([
            'tarea_id' => $tarea->tarea_id,
            'colaborador_usuario_id' => $validated['colaborador_usuario_id'],
            'estado_individual' => 'pendiente',
            'fecha_asignacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sisgedi.lider.tareas_colaboradores')
            ->with('success', 'Tarea creada para el colaborador correctamente.');
    }

    public function verEvidencia(int $evidenciaId)
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $evidencia = $this->authorizedEvidence($evidenciaId, $user);

        if (! $evidencia || ! $evidencia->archivo || ! Storage::disk('public')->exists($evidencia->archivo)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($evidencia->archivo, PATHINFO_EXTENSION));

        if (in_array($extension, ['xlsx', 'xls'], true)) {
            return view('sisgedi::spreadsheet', [
                'title' => $evidencia->titulo ?? $evidencia->codigo,
                'fileUrl' => route('sisgedi.evidencia.file', $evidenciaId),
                'downloadUrl' => route('sisgedi.evidencia.download', $evidenciaId),
            ]);
        }

        if (in_array($extension, ['docx', 'doc'], true)) {
            return view('sisgedi::word', [
                'title' => $evidencia->titulo ?? $evidencia->codigo,
                'fileUrl' => route('sisgedi.evidencia.file', $evidenciaId),
                'downloadUrl' => route('sisgedi.evidencia.download', $evidenciaId),
            ]);
        }

        return response()->file(Storage::disk('public')->path($evidencia->archivo));
    }

    public function archivoEvidencia(int $evidenciaId)
    {
        $user = $this->sisgediUser();
        $evidencia = $user instanceof \Illuminate\Http\RedirectResponse ? null : $this->authorizedEvidence($evidenciaId, $user);

        if (! $evidencia || ! $evidencia->archivo || ! Storage::disk('public')->exists($evidencia->archivo)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($evidencia->archivo));
    }

    public function descargarEvidencia(int $evidenciaId)
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $evidencia = $this->authorizedEvidence($evidenciaId, $user);

        if (! $evidencia || ! $evidencia->archivo || ! Storage::disk('public')->exists($evidencia->archivo)) {
            abort(404);
        }

        return Storage::disk('public')->download($evidencia->archivo);
    }

    public function aprobarEvidencia(Request $request)
    {
        return $this->resolverEvidencia($request, 'aprobada');
    }

    public function rechazarEvidencia(Request $request)
    {
        return $this->resolverEvidencia($request, 'rechazada');
    }

    private function resolverEvidencia(Request $request, string $estado)
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'evidencia_id' => 'required|integer',
            'motivo_rechazo' => $estado === 'rechazada' ? 'required|string|max:2000' : 'nullable|string|max:2000',
        ]);
        $evidencia = $this->authorizedEvidence((int) $validated['evidencia_id'], $user);

        if (! $evidencia) {
            abort(403);
        }

        DB::table('evidencias_sisgedi')
            ->where('evidencia_id', $evidencia->evidencia_id)
            ->update([
                'estado' => $estado,
                'motivo_rechazo' => $estado === 'rechazada' ? $validated['motivo_rechazo'] : null,
                'updated_at' => now(),
            ]);
        DB::table('tarea_asignaciones')
            ->where('tarea_asignacion_id', $evidencia->tarea_asignacion_id)
            ->update(['estado_individual' => $estado, 'updated_at' => now()]);

        return back()->with('success', $estado === 'aprobada' ? 'Evidencia aprobada correctamente.' : 'Evidencia rechazada con motivo.');
    }

    private function authorizedEvidence(int $evidenciaId, array $user)
    {
        $rol = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));

        $query = DB::table('evidencias_sisgedi as evidencias')
            ->join('tarea_asignaciones as asignaciones', 'asignaciones.tarea_asignacion_id', '=', 'evidencias.tarea_asignacion_id')
            ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
            ->where('evidencias.evidencia_id', $evidenciaId);

        if (Str::startsWith($rol, 'lider ')) {
            $query->where(function ($scope) use ($user) {
                $scope->where('evidencias.usuario_id', $user['id'])
                    ->orWhere('tareas.gestor_usuario_id', $user['id']);
            });
        } else {
            $query->where('tareas.gestor_usuario_id', $user['id']);
        }

        return $query->first(['evidencias.*', 'tareas.titulo']);
    }

    public function storeTareaLider(Request $request)
    {
        $user = $this->gestorUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:2000',
            'fecha_limite' => 'required|date',
            'colaborador_usuario_id' => 'required|integer',
            'sector' => 'nullable|string|max:150',
            'evidencia_esperada' => 'nullable|string|max:150',
            'instrucciones' => 'required|string|max:5000',
            'entregables_esperados' => 'required|string|max:5000',
            'plazos' => 'required|string|max:1000',
            'documento_guia' => 'required|file|max:10240',
        ]);

        $area = $this->gestorArea($user);

        $lider = $this->lideresDelArea($user)->firstWhere('id_users', (int) $validated['colaborador_usuario_id']);

        if (! $lider) {
            return back()->withInput()->withErrors([
                'colaborador_usuario_id' => 'Selecciona un líder perteneciente a tu área.',
            ]);
        }

        $tarea = Tarea::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'fecha_limite' => $validated['fecha_limite'],
            'estado' => 'asignada',
            'gestor_usuario_id' => $user['id'],
            'sector' => $area,
            'evidencia_esperada' => $validated['evidencia_esperada'] ?? null,
        ]);

        $archivoGuia = $request->file('documento_guia')->store(
            'sisgedi/documentos-guia/' . $user['id'],
            'public'
        );

        DocumentoGuia::create([
            'tarea_id' => $tarea->tarea_id,
            'instrucciones' => $validated['instrucciones'],
            'entregables_esperados' => $validated['entregables_esperados'],
            'plazos' => $validated['plazos'],
            'archivo_url' => $archivoGuia,
        ]);

        DB::table('tarea_asignaciones')->insert([
            'tarea_id' => $tarea->tarea_id,
            'colaborador_usuario_id' => $validated['colaborador_usuario_id'],
            'estado_individual' => 'pendiente',
            'fecha_asignacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sisgedi.gestor.tareas_lideres')
            ->with('success', 'Tarea creada y asignada correctamente.');
    }

    public function liderStorEvidencia(Request $request)
    {
        $user = $this->liderUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $validated = $request->validate([
            'tarea_asignacion_id' => 'required|integer|exists:tarea_asignaciones,tarea_asignacion_id',
            'archivo' => 'required|file|max:10240',
        ]);

        $asignacion = DB::table('tarea_asignaciones')
            ->where('tarea_asignacion_id', $validated['tarea_asignacion_id'])
            ->where('colaborador_usuario_id', $user['id'])
            ->first();

        if (! $asignacion) {
            return back()->withErrors(['tarea_asignacion_id' => 'La asignación no pertenece a este Líder.']);
        }

        $archivo = $request->file('archivo');
        $ruta = $archivo->store('sisgedi/evidencias/' . $user['id'], 'public');
        $codigo = 'EVD-GES-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        EvidenciaSisgedi::create([
            'tarea_asignacion_id' => $validated['tarea_asignacion_id'],
            'usuario_id' => $user['id'],
            'codigo' => $codigo,
            'archivo' => $ruta,
            'fecha_carga' => now(),
            'estado' => 'enviada',
        ]);

        DB::table('tarea_asignaciones')
            ->where('tarea_asignacion_id', $validated['tarea_asignacion_id'])
            ->update(['estado_individual' => 'enviada', 'updated_at' => now()]);

        return redirect()->route('sisgedi.lider.mis_evidencias')
            ->with('success', 'Evidencia cargada correctamente.');
    }

    private function gestorModuleView(string $title, string $subtitle, string $type, $items)
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $notificationCount = DB::table('tarea_asignaciones')
            ->whereIn('estado_individual', ['pendiente', 'en_desarrollo', 'enviada'])
            ->count();
        $rolNormalizado = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));
        $esGestor = Str::startsWith($rolNormalizado, 'gestor.') || Str::startsWith($rolNormalizado, 'gestor ');
        $esLider = Str::startsWith($rolNormalizado, 'lider ');
        $lideres = $this->lideresDelArea($user);
        $colaboradores = $this->colaboradoresDelArea($user);
        $area = $this->gestorArea($user);
        $asignacionesDisponibles = DB::table('tarea_asignaciones as asignaciones')
            ->join('tareas', 'tareas.tarea_id', '=', 'asignaciones.tarea_id')
            ->when($esGestor, function ($query) use ($user, $area) {
                return $query->where('tareas.gestor_usuario_id', $user['id'])->where('tareas.sector', $area);
            }, function ($query) use ($user) {
                return $query->where('asignaciones.colaborador_usuario_id', $user['id']);
            })
            ->orderByDesc('asignaciones.created_at')
            ->get([
                'asignaciones.tarea_asignacion_id',
                'asignaciones.tarea_id',
                'tareas.titulo',
            ]);

        if ($type === 'review') {
            $selectedEvidence = $items->first();

            return view('sisgedi::gestor.review', compact('user', 'title', 'subtitle', 'type', 'items', 'selectedEvidence', 'notificationCount', 'area', 'esLider'));
        }

        return view('sisgedi::gestor.module', compact('user', 'title', 'subtitle', 'type', 'items', 'notificationCount', 'lideres', 'colaboradores', 'area', 'asignacionesDisponibles', 'esLider'));
    }

    private function colaboradoresDelArea(array $user)
    {
        return DB::table('users_sisgedi as usuarios')
            ->join('roles_sisgedi as roles', 'roles.id_rol', '=', 'usuarios.id_rol')
            ->where('roles.nombre', 'like', 'Colaborador%')
            ->get(['usuarios.id_users', 'usuarios.nombre', 'usuarios.correo', 'roles.nombre as rol']);
    }

    private function gestorArea(array $user): string
    {
        $rol = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));
        $areas = [
            'talento' => 'Talento Humano',
            'contabilidad' => 'Contabilidad y Finanzas',
            'asig' => 'ASIG',
            'investigacion' => 'Investigación',
            'innovacion' => 'Innovación y Prototipado',
            'fabrica' => 'Fábrica de Software',
            'agricola' => 'Área Agrícola',
            'pecuaria' => 'Área Pecuaria',
            'agroindustrial' => 'Área Agroindustrial',
            'ambiental' => 'Área Ambiental',
            'mercadeo' => 'Mercadeo',
            'bilingue' => 'Educación Bilingüe',
        ];

        foreach ($areas as $key => $label) {
            if (Str::contains($rol, $key)) {
                return $label;
            }
        }

        return 'Área no definida';
    }

    private function lideresDelArea(array $user)
    {
        $rolGestor = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));
        $areas = [
            'talento' => ['talento'],
            'contabilidad' => ['contabilidad'],
            'asig' => ['sig'],
            'investigacion' => ['investigacion'],
            'innovacion' => ['innovacion'],
            'fabrica' => ['fabrica', 'software'],
            'agricola' => ['agricola'],
            'pecuaria' => ['pecuaria'],
            'agroindustrial' => ['agroindustrial'],
            'ambiental' => ['ambiental'],
            'mercadeo' => ['mercadeo'],
            'bilingue' => ['bilingue'],
        ];

        $palabrasArea = [];
        foreach ($areas as $area => $palabras) {
            if (Str::contains($rolGestor, $area)) {
                $palabrasArea = $palabras;
                break;
            }
        }

        if (! $palabrasArea) {
            return collect();
        }

        return DB::table('users_sisgedi as usuarios')
            ->join('roles_sisgedi as roles', 'roles.id_rol', '=', 'usuarios.id_rol')
            ->where('roles.nombre', 'like', 'Líder%')
            ->get(['usuarios.id_users', 'usuarios.nombre', 'usuarios.correo', 'roles.nombre as rol'])
            ->filter(function ($lider) use ($palabrasArea) {
                $rolLider = Str::ascii(Str::lower($lider->rol));

                foreach ($palabrasArea as $palabra) {
                    if (Str::contains($rolLider, $palabra)) {
                        return true;
                    }
                }

                return false;
            })
            ->values();
    }

    private function gestorUser()
    {
        $user = $this->sisgediUser();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        $rolNormalizado = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));
        $esGestor = Str::startsWith($rolNormalizado, 'gestor.') || Str::startsWith($rolNormalizado, 'gestor ');

        if (! $esGestor) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Tu rol no tiene acceso al módulo del Gestor.']);
        }

        return $user;
    }

    private function sisgediUser()
    {
        $user = session('sisgedi_user');

        if (! $user) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }

        return $user;
    }

    private function liderUser()
    {
        $user = session('sisgedi_user');

        if (! $user) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Debes iniciar sesión para acceder.']);
        }

        $rolNormalizado = Str::ascii(Str::lower(trim((string) ($user['rol'] ?? ''))));

        if (! Str::startsWith($rolNormalizado, 'lider ')) {
            return redirect()->route('sisgedi.index')
                ->withErrors(['nickname' => 'Tu rol no tiene acceso al dashboard del Líder.']);
        }

        return $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sisgedi::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sisgedi::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('sisgedi::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('sisgedi::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
