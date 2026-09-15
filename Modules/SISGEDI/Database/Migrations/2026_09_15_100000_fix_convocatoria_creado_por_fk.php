<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // La FK inválida (-> usuario.usuario_id) ya fue dropeada en la ejecución
        // anterior fallida. Solo aplicamos los pasos restantes.

        // 1. Cambiar creado_por de bigint a int(11) nullable para ser compatible
        //    con users_sisgedi.id_users (int, NOT NULL)
        DB::statement('ALTER TABLE convocatoria MODIFY creado_por INT(11) NULL');

        // 2. Crear la FK correcta apuntando a users_sisgedi.id_users
        DB::statement('
            ALTER TABLE convocatoria
            ADD CONSTRAINT fk_convocatoria_creado_por_sisgedi
            FOREIGN KEY (creado_por) REFERENCES users_sisgedi(id_users)
            ON DELETE SET NULL
        ');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE convocatoria DROP FOREIGN KEY fk_convocatoria_creado_por_sisgedi');
        DB::statement('ALTER TABLE convocatoria MODIFY creado_por BIGINT(20) NULL');
    }
};
