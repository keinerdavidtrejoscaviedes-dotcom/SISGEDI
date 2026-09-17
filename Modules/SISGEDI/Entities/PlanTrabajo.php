<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class PlanTrabajo extends Model
{
    protected $table = 'sisgedi_planes_trabajo';

    protected $fillable = [
        'user_id',
        'fase_id',
        'nivel',
        'fecha_publicacion',
        'descripcion',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
    ];

    public function autor()
    {
        return $this->belongsTo(UsuarioSisgedi::class, 'user_id', 'id_users');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function hitos()
    {
        return $this->hasMany(HitoPlanTrabajo::class, 'plan_trabajo_id')->orderBy('fecha');
    }
}
