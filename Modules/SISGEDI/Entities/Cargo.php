<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use SoftDeletes;

    protected $table = 'sisgedi_cargos';

    protected $fillable = [
        'nombre',
        'tipo_cargo',
        'gerencia_id',
        'cargo_superior_id',
    ];

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_id');
    }

    public function cargoSuperior()
    {
        return $this->belongsTo(Cargo::class, 'cargo_superior_id');
    }

    public function cargosSubordinados()
    {
        return $this->hasMany(Cargo::class, 'cargo_superior_id');
    }
}
