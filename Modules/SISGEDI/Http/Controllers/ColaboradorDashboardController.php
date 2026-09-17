<?php

namespace Modules\SISGEDI\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SISGEDI\Entities\Approval;
use Modules\SISGEDI\Entities\Evidence;

class ColaboradorDashboardController extends Controller
{
    /**
     * Dashboard principal del colaborador
     * HU-001: Visualizar tareas asignadas
     */
    public function index()
    {
        return view('sisgedi::colaborador.dashboard');
    }

    /**
     * Mis tareas asignadas
     * HU-001: Visualizar y filtrar tareas
     */
    public function misTareas()
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $colaborador_id = $usuario['id'];

        // Obtener todas las tareas asignadas al colaborador
        $tasks = Approval::getByColaborador($colaborador_id);

        // Para cada tarea, obtener el contador de evidencias
        $tasksWithEvidence = $tasks->map(function ($task) {
            $task->evidences_count = Evidence::countByApproval($task->id);
            $task->evidences = Evidence::getByApproval($task->id);
            return $task;
        });

        return view('sisgedi::colaborador.mis-tareas', compact('tasksWithEvidence', 'tasks'));
    }

    /**
     * Bitácoras de actividades
     * HU-002: Registro de bitácoras 1 y 2
     */
    public function bitacoras()
    {
        return view('sisgedi::colaborador.bitacoras');
    }

    /**
     * Proceso de paz y salvo
     * HU-003: Iniciar y hacer seguimiento de firma
     */
    public function pazYSalvo()
    {
        return view('sisgedi::colaborador.paz-y-salvo');
    }

    /**
     * Plan de innovación y mejora
     * HU-004: Registrar plan de innovación
     */
    public function planInnovacion()
    {
        return view('sisgedi::colaborador.plan-innovacion');
    }

    /**
     * Convocatorias abiertas (para aprendices)
     * HU-005: Postularse a convocatoria
     */
    public function convocatorias()
    {
        return view('sisgedi::colaborador.convocatorias');
    }

    /**
     * Mis evidencias
     * HU-007: Cargar y ver estado de evidencias
     */
    public function misEvidencias()
    {
        return view('sisgedi::colaborador.mis-evidencias');
    }

    /**
     * Documentación final de fase
     * HU-009: Cargar documentación final
     */
    public function docFinalFase()
    {
        return view('sisgedi::colaborador.doc-final-fase');
    }
}

