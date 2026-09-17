<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Adaptada a la tabla legacy `fase` (gestionada desde FaseController /
 * Gestión de Fases) en vez de una tabla `sisgedi_fases` independiente,
 * para que exista una única fuente de verdad de la fase vigente.
 */
class Fase extends Model
{
    protected $table = 'fase';

    protected $primaryKey = 'fase_id';

    protected $fillable = [
        'nombre_fase',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'descripcion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // Alias de compatibilidad con el código que ya esperaba el esquema
    // "sisgedi_fases" (id, nombre).
    public function getIdAttribute()
    {
        return $this->attributes['fase_id'] ?? null;
    }

    public function getNombreAttribute()
    {
        return $this->attributes['nombre_fase'] ?? null;
    }

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
        return static::where('estado', 'Activa')->first();
    }
}
