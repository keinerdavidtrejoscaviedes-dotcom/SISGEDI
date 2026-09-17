<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajo extends Model
{
    protected $table = 'planes_trabajo';
    protected $primaryKey = 'plan_trabajo_id';

    protected $fillable = [
        'usuario_id',
        'fase_id',
        'nivel',
        'fecha_publicacion',
        'descripcion',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
    ];

    public function hitos()
    {
        return $this->hasMany(HitoPlanTrabajo::class, 'plan_trabajo_id', 'plan_trabajo_id');
    }
}
