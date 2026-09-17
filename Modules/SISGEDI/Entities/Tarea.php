<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use SoftDeletes;

    protected $table = 'sisgedi_tareas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_limite',
        'tipo_evidencia_requerida',
        'estado',
        'clasificacion',
        'generador_usuario_id',
        'tarea_padre_id',
        'sector_id',
        'fase_id',
        'confirmada',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'confirmada' => 'boolean',
    ];

    public function generador()
    {
        return $this->belongsTo(UsuarioSisgedi::class, 'generador_usuario_id', 'id_users');
    }

    public function tareaPadre()
    {
        return $this->belongsTo(Tarea::class, 'tarea_padre_id');
    }

    public function tareasDerivadas()
    {
        return $this->hasMany(Tarea::class, 'tarea_padre_id');
    }

    public function sector()
    {
        return $this->belongsTo(SectorProductivo::class, 'sector_id');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function documentoGuia()
    {
        return $this->hasOne(DocumentoGuia::class, 'tarea_id');
    }
}
