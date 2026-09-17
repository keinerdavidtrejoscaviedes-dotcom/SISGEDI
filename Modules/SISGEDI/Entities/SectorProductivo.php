<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectorProductivo extends Model
{
    use SoftDeletes;

    protected $table = 'sisgedi_sectores_productivos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'gerencia_id',
        'estado_catalogo',
    ];

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_id');
    }

    public function fases()
    {
        return $this->belongsToMany(Fase::class, 'sisgedi_fase_sector', 'sector_id', 'fase_id')
            ->withPivot('estado_activo')
            ->withTimestamps();
    }

    /**
     * Sectores activos dentro de una fase especifica (RN-014).
     */
    public function scopeActivosEnFase($query, int $faseId)
    {
        return $query->whereHas('fases', function ($q) use ($faseId) {
            $q->where('fase.fase_id', $faseId)
                ->where('sisgedi_fase_sector.estado_activo', true);
        });
    }
}
