<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'tarea_id';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_limite',
        'estado',
        'gestor_usuario_id',
        'fase_id',
        'sector',
        'evidencia_esperada',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    public function asignaciones()
    {
        return $this->hasMany(TareaAsignacion::class, 'tarea_id', 'tarea_id');
    }

    public function documentosGuia()
    {
        return $this->hasOne(DocumentoGuia::class, 'tarea_id', 'tarea_id');
    }
}
