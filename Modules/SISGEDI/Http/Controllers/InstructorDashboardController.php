<?php

namespace Modules\SISGEDI\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SISGEDI\Entities\Signature;
use Modules\SISGEDI\Entities\Approval;
use Modules\SISGEDI\Entities\Evidence;

class InstructorDashboardController extends Controller
{
    /**
     * Dashboard principal del instructor
     * HU-011: Consultar fichas asignadas
     */
    public function index()
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $instructor_id = $usuario['id'];

        // Obtener todas las aprobaciones del instructor
        $allApprovals = Approval::where('instructor_id', $instructor_id)->get();

        // Contar fichas únicas
        $fichasCount = $allApprovals->pluck('deliverable_id')->unique()->count();

        // Contar colaboradores únicos
        $colaboradoresCount = $allApprovals->pluck('colaborador_id')->unique()->count();

        // Contar entregables pendientes
        $pendingCount = $allApprovals->where('status', 'pendiente')->count();

        // Obtener entregables pendientes recientes (últimos 5)
        $recentPending = Approval::where('instructor_id', $instructor_id)
            ->where('status', 'pendiente')
            ->orderByDesc('submitted_at')
            ->limit(5)
            ->get();

        // Obtener firma activa
        $activeSignature = Signature::getActive($instructor_id);

        return view('sisgedi::instructor.dashboard', compact(
            'fichasCount',
            'colaboradoresCount',
            'pendingCount',
            'recentPending',
            'activeSignature'
        ));
    }

    /**
     * Alternador de contexto Instructor/Líder
     * HU-010: Cambiar entre contextos
     */
    public function cambiarContexto($contexto)
    {
        // Lógica para cambiar contexto
        return redirect()->route('sisgedi.instructor.dashboard');
    }

    /**
     * Fichas y colaboradores asignados
     * HU-011: Ver fichas con colaboradores y entregables
     */
    public function fichasAsignadas()
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $instructor_id = $usuario['id'];

        // Obtener todas las aprobaciones del instructor
        $allApprovals = Approval::where('instructor_id', $instructor_id)->get();

        // Agrupar por colaborador
        $colaboradoresByFicha = [];
        foreach ($allApprovals as $approval) {
            $ficha = $approval->deliverable_id; // Simulamos ficha con deliverable_id
            $colabId = $approval->colaborador_id;
            
            if (!isset($colaboradoresByFicha[$ficha])) {
                $colaboradoresByFicha[$ficha] = [];
            }
            
            if (!isset($colaboradoresByFicha[$ficha][$colabId])) {
                $colaboradoresByFicha[$ficha][$colabId] = [
                    'id' => $colabId,
                    'name' => $approval->colaborador_name,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                    'total' => 0
                ];
            }
            
            $colaboradoresByFicha[$ficha][$colabId]['total']++;
            
            if ($approval->status === 'pendiente') {
                $colaboradoresByFicha[$ficha][$colabId]['pending']++;
            } elseif ($approval->status === 'aprobado') {
                $colaboradoresByFicha[$ficha][$colabId]['approved']++;
            } elseif ($approval->status === 'rechazado') {
                $colaboradoresByFicha[$ficha][$colabId]['rejected']++;
            }
        }

        return view('sisgedi::instructor.fichas-asignadas', compact('colaboradoresByFicha'));
    }

    /**
     * Detalle de un colaborador específico
     * HU-011: Ver tareas y entregables de un colaborador
     */
    public function detalleColaborador($colaboradorId)
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        // Obtener TODAS las tareas del colaborador (sin filtro de instructor)
        // para que el instructor vea todo lo que el colaborador debe presentar
        $tasks = Approval::where('colaborador_id', $colaboradorId)
            ->orderByDesc('assigned_at')
            ->get();

        // Dividir por estado
        $pending = $tasks->where('status', 'pendiente');
        $approved = $tasks->where('status', 'aprobado');
        $rejected = $tasks->where('status', 'rechazado');

        // Obtener info del colaborador (del primer registro)
        $colaboradorInfo = $tasks->first();

        return view('sisgedi::instructor.detalle-colaborador', compact(
            'colaboradorId',
            'colaboradorInfo',
            'tasks',
            'pending',
            'approved',
            'rejected'
        ));
    }

    /**
     * Revisión y firma de entregables
     * HU-012: Firmar entregables verificados
     */
    public function revisarEntregables()
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $instructor_id = $usuario['id'];

        $pending = Approval::getPendingByInstructor($instructor_id);
        $approval = null;
        
        // Si hay un parámetro approval en la URL, obtener ese
        if (request()->has('approval')) {
            $approval = Approval::find(request()->get('approval'));
            // Verificar que sea una tarea del instructor
            if ($approval && $approval->instructor_id != $instructor_id) {
                $approval = null;
            }
        }
        
        // Si no hay approval en URL, obtener el primero
        if (!$approval && $pending->count() > 0) {
            $approval = $pending->first();
        }

        // Si hay approval, obtener sus evidencias
        $evidences = [];
        if ($approval) {
            $evidences = Evidence::getByApproval($approval->id);
        }

        return view('sisgedi::instructor.revisar-entregables', compact('pending', 'approval', 'evidences'));
    }

    /**
     * Gestión de firma digital del instructor
     * HU-013: Cargar y actualizar firma digital
     */
    public function gestionarFirma()
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return redirect()->route('sisgedi.index')->with('error', 'Por favor inicia sesión primero.');
        }

        $instructor_id = $usuario['id'];

        $activeSignature = Signature::getActive($instructor_id);
        $versions = Signature::getVersions($instructor_id);

        return view('sisgedi::instructor.gestionar-firma', compact('activeSignature', 'versions'));
    }

    /**
     * Obtener notificaciones de evidencias recientes no leídas
     */
    public function getNotifications()
    {
        $usuario = session('sisgedi_user');
        
        if (!$usuario || !isset($usuario['id'])) {
            return response()->json(['notifications' => []]);
        }

        $instructor_id = $usuario['id'];

        // Obtener evidencias recientes (últimas 24 horas) de colaboradores que entregan al instructor
        $evidences = Evidence::with('approval')
            ->whereHas('approval', function($query) use ($instructor_id) {
                $query->where('instructor_id', $instructor_id)
                      ->where('status', 'pendiente'); // Solo de entregables pendientes
            })
            ->where('created_at', '>=', now()->subDay())
            ->where('viewed', false) // No vistas
            ->orderByDesc('created_at')
            ->distinct()
            ->limit(10)
            ->get();

        $notifications = $evidences->map(function($evidence) {
            $approval = $evidence->approval;
            return [
                'id' => $evidence->id,
                'colaborador_name' => $approval?->colaborador_name ?? 'Colaborador',
                'time' => $evidence->created_at->diffForHumans()
            ];
        })->unique('colaborador_name')->values();

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Marcar notificación como leída
     */
    public function markNotificationRead(Request $request)
    {
        $evidenceId = $request->input('notification_id');

        $evidence = Evidence::find($evidenceId);
        if ($evidence) {
            $evidence->update(['viewed' => true]);
        }

        return response()->json(['success' => true]);
    }
}
