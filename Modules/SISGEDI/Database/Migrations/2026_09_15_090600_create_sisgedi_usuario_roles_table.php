<?php

use Illuminate\Database\Migrations\Migration;

/**
 * No-op: la asignación de rol por usuario se unificó con
 * `users_sisgedi.id_rol` + `roles_sisgedi` (cada cuenta tiene un único rol
 * fijo, no roles dinámicos activables/desactivables). Ver
 * Http/Middleware/EnsureSisgediRole.php.
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
