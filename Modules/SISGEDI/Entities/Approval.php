<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'deliverable_id',
        'deliverable_name',
        'colaborador_id',
        'colaborador_name',
        'instructor_id',
        'instructor_name',
        'leader_id',
        'leader_name',
        'status',
        'feedback',
        'file_path',
        'file_type',
        'signature_id',
        'submitted_at',
        'assigned_at',
        'deadline',
        'reviewed_at',
        'approved_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'assigned_at' => 'datetime',
        'deadline' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_by' => 'array',
    ];

    /**
     * Relación con la firma digital
     */
    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    /**
     * Obtener entregables pendientes por revisar de un instructor
     */
    public static function getPendingByInstructor($instructor_id)
    {
        return self::where('instructor_id', $instructor_id)
            ->where('status', 'pendiente')
            ->orderByDesc('submitted_at')
            ->get();
    }

    /**
     * Obtener entregables aprobados de un instructor
     */
    public static function getApprovedByInstructor($instructor_id)
    {
        return self::where('instructor_id', $instructor_id)
            ->where('status', 'aprobado')
            ->orderByDesc('reviewed_at')
            ->get();
    }

    /**
     * Obtener entregables rechazados de un instructor
     */
    public static function getRejectedByInstructor($instructor_id)
    {
        return self::where('instructor_id', $instructor_id)
            ->where('status', 'rechazado')
            ->orderByDesc('reviewed_at')
            ->get();
    }

    /**
     * Obtener entregables de un colaborador
     */
    public static function getByColaborador($colaborador_id)
    {
        return self::where('colaborador_id', $colaborador_id)
            ->orderByDesc('submitted_at')
            ->get();
    }

    /**
     * Obtener estado general de un colaborador
     */
    public static function getColaboradorStats($colaborador_id)
    {
        $approvals = self::where('colaborador_id', $colaborador_id)->get();

        return [
            'pendiente' => $approvals->where('status', 'pendiente')->count(),
            'aprobado' => $approvals->where('status', 'aprobado')->count(),
            'rechazado' => $approvals->where('status', 'rechazado')->count(),
            'total' => $approvals->count(),
        ];
    }

    /**
     * Aprobar un entregable
     */
    public function approve($signature_id)
    {
        $this->status = 'aprobado';
        $this->signature_id = $signature_id;
        $this->reviewed_at = now();
        $this->save();

        return true;
    }

    /**
     * Rechazar un entregable con feedback
     */
    public function reject($feedback)
    {
        $this->status = 'rechazado';
        $this->feedback = $feedback;
        $this->reviewed_at = now();
        $this->save();

        return true;
    }

    /**
     * Agregar un aprobador adicional (para múltiples aprobadores)
     */
    public function addApprover($instructor_id, $instructor_name, $signature_id)
    {
        $approvers = $this->approved_by ?? [];
        
        $approvers[] = [
            'instructor_id' => $instructor_id,
            'instructor_name' => $instructor_name,
            'signature_id' => $signature_id,
            'approved_at' => now()->toDateTimeString(),
        ];
        
        $this->approved_by = $approvers;
        $this->reviewed_at = now();
        $this->save();

        return true;
    }

    /**
     * Obtener tareas asignadas por un líder
     */
    public static function getByLeader($leader_id)
    {
        return self::where('leader_id', $leader_id)
            ->orderByDesc('assigned_at')
            ->get();
    }

    /**
     * Obtener tareas de un colaborador (todas, sin filtro de estado)
     */
    public static function getColaboradorAllTasks($colaborador_id)
    {
        return self::where('colaborador_id', $colaborador_id)
            ->orderByDesc('assigned_at')
            ->get();
    }
}
