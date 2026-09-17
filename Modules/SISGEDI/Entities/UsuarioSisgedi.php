<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Representa un registro de `users_sisgedi` (la identidad usada por la
 * sesión `sisgedi_user`). Sirve de destino para relaciones Eloquent que
 * antes apuntaban a `App\Models\User` (tabla principal), la cual no
 * corresponde al espacio de IDs usado por el login de SISGEDI.
 */
class UsuarioSisgedi extends Model
{
    protected $table = 'users_sisgedi';

    protected $primaryKey = 'id_users';

    public $timestamps = false;

    protected $fillable = ['nombre', 'contraseña', 'correo', 'id_rol'];

    public function getFullNameAttribute()
    {
        return $this->nombre;
    }

    public function rol()
    {
        return $this->belongsTo(RolSisgedi::class, 'id_rol', 'id_rol');
    }
}
