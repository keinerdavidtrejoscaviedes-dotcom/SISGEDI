<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fase extends Model
{
    use SoftDeletes;

    protected $table = 'sisgedi_fases';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function sectores()
    {
        return $this->belongsToMany(SectorProductivo::class, 'sisgedi_fase_sector', 'fase_id', 'sector_id')
            ->withPivot('estado_activo')
            ->withTimestamps();
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'fase_id');
    }

    /**
     * RN-010: solo puede existir una fase activa a la vez.
     */
    public static function vigente(): ?self
    {
        return static::where('estado', 'activa')->first();
    }
}
