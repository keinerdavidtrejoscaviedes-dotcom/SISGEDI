<?php

use Illuminate\Database\Migrations\Migration;

/**
 * No-op: el catálogo de roles se unificó con la tabla legacy `roles_sisgedi`
 * (asignados de forma fija en `users_sisgedi.id_rol`). Ver Entities/RolSisgedi.php.
 */
return new class extends Migration
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
