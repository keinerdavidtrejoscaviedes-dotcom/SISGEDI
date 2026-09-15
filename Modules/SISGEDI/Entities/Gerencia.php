<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gerencia extends Model
{
    use SoftDeletes;

    protected $table = 'sisgedi_gerencias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function sectores()
    {
        return $this->hasMany(SectorProductivo::class, 'gerencia_id');
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class, 'gerencia_id');
    }
}
