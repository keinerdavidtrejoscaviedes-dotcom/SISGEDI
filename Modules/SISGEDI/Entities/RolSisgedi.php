<?php

namespace Modules\SISGEDI\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de roles de `roles_sisgedi` (asignados de forma fija a cada
 * cuenta de `users_sisgedi` mediante la columna `id_rol`).
 */
class RolSisgedi extends Model
{
    protected $table = 'roles_sisgedi';

    protected $primaryKey = 'id_rol';

    public $timestamps = false;

    protected $fillable = ['nombre', 'descripcion'];
}
