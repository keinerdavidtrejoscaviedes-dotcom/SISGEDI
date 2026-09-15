<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class HitoPlanTrabajo extends Model
{
    protected $table = 'sisgedi_hitos_plan_trabajo';

    protected $fillable = [
        'plan_trabajo_id',
        'titulo',
        'fecha',
        'tipo',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function planTrabajo()
    {
        return $this->belongsTo(PlanTrabajo::class, 'plan_trabajo_id');
    }
}
