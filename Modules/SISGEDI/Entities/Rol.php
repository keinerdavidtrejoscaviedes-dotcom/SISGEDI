<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    public $timestamps = true;

    protected $table = 'sisgedi_roles';

    protected $fillable = [
        'nombre_rol',
        'es_dinamico',
        'descripcion',
    ];

    protected $casts = [
        'es_dinamico' => 'boolean',
    ];

    public function usuarioRoles()
    {
        return $this->hasMany(UsuarioRol::class, 'sisgedi_rol_id');
    }
}
