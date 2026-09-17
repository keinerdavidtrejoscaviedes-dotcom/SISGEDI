<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class TareaAsignacion extends Model
{
    protected $table = 'tarea_asignaciones';
    protected $primaryKey = 'tarea_asignacion_id';

    protected $fillable = [
        'tarea_id',
        'colaborador_usuario_id',
        'estado_individual',
        'fecha_asignacion',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
    ];
}
