<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('postulacion')) {
            return;
        }

        // Step 1: Drop the bad FK that pointed to the generic `usuario` table
        try {
            DB::statement('ALTER TABLE postulacion DROP FOREIGN KEY fk_postulacion_aprendiz_usuario_id');
        } catch (\Throwable $e) {
            // Already dropped or never existed – safe to continue
        }

        // Step 2: Change aprendiz_usuario_id to INT (to match users_sisgedi.id_users which is int)
        // postulacion.aprendiz_usuario_id is bigint; users_sisgedi.id_users is int (signed)
        // MySQL allows FK from bigint -> int, but we must ensure signedness matches.
        // Both are signed so we just need to ensure compatibility – keep bigint is fine for values,
        // but MySQL 8 requires exact type match for FK. Convert to int unsigned to be safe.
        // Actually users_sisgedi.id_users is plain int (signed), so we match with int.
        DB::statement('ALTER TABLE postulacion MODIFY aprendiz_usuario_id INT NOT NULL');

        // Step 3: Add the correct FK pointing to users_sisgedi
        DB::statement('ALTER TABLE postulacion ADD CONSTRAINT fk_postulacion_aprendiz_sisgedi FOREIGN KEY (aprendiz_usuario_id) REFERENCES users_sisgedi(id_users) ON DELETE CASCADE');
    }

    public function down(): void
    {
        if (! Schema::hasTable('postulacion')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE postulacion DROP FOREIGN KEY fk_postulacion_aprendiz_sisgedi');
        } catch (\Throwable $e) {
            // Ignore
        }

        // Restore column to bigint
        DB::statement('ALTER TABLE postulacion MODIFY aprendiz_usuario_id BIGINT NOT NULL');

        // Restore original FK (best-effort)
        try {
            DB::statement('ALTER TABLE postulacion ADD CONSTRAINT fk_postulacion_aprendiz_usuario_id FOREIGN KEY (aprendiz_usuario_id) REFERENCES usuario(usuario_id) ON DELETE CASCADE');
        } catch (\Throwable $e) {
            // Ignore
        }
    }
};
