<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class EvidenciaSisgedi extends Model
{
    protected $table = 'evidencias_sisgedi';
    protected $primaryKey = 'evidencia_id';

    protected $fillable = [
        'tarea_asignacion_id',
        'usuario_id',
        'codigo',
        'archivo',
        'version',
        'estado',
        'motivo_rechazo',
        'fecha_carga',
    ];

    protected $casts = [
        'fecha_carga' => 'datetime',
    ];

    public function asignacion()
    {
        return $this->belongsTo(TareaAsignacion::class, 'tarea_asignacion_id', 'tarea_asignacion_id');
    }
}
