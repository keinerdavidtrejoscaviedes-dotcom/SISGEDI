<?php

namespace Modules\SISGEDI\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instructor extends Model
{
    use SoftDeletes;

    protected $table = 'sisgedi_instructores';

    protected $fillable = [
        'usuario_id',
        'nombre_completo',
        'area_especialidad',
        'correo',
        'telefono',
        'estado',
        'firma_digital_url',
        'gestionado_por',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function gestionadoPor()
    {
        return $this->belongsTo(User::class, 'gestionado_por');
    }
}
