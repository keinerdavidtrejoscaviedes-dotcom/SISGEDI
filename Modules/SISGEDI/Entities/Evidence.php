<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    protected $table = 'evidences';

    protected $fillable = [
        'approval_id',
        'colaborador_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
        'uploaded_at',
        'viewed',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'viewed' => 'boolean',
    ];

    /**
     * Relación con Approval
     */
    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }

    /**
     * Obtener todas las evidencias de un entregable
     */
    public static function getByApproval($approval_id)
    {
        return self::where('approval_id', $approval_id)
            ->orderByDesc('uploaded_at')
            ->get();
    }

    /**
     * Obtener evidencias de un colaborador
     */
    public static function getByColaborador($colaborador_id)
    {
        return self::where('colaborador_id', $colaborador_id)
            ->orderByDesc('uploaded_at')
            ->get();
    }

    /**
     * Contar evidencias de un entregable
     */
    public static function countByApproval($approval_id)
    {
        return self::where('approval_id', $approval_id)->count();
    }
}
