<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only act if convocatoria_cargo exists
        if (! Schema::hasTable('convocatoria_cargo')) {
            return;
        }

        // Step 1: Drop the bad FK (pointing to the generic `cargo` table)
        try {
            DB::statement('ALTER TABLE convocatoria_cargo DROP FOREIGN KEY fk_convocatoria_cargo_cargo_id');
        } catch (\Throwable $e) {
            // Already dropped or never existed – safe to continue
        }

        // Step 2: Change cargo_id to bigint UNSIGNED to match cargo_sisgedi.cargo_id
        DB::statement('ALTER TABLE convocatoria_cargo MODIFY cargo_id BIGINT UNSIGNED NOT NULL');

        // Step 3: Add the correct FK pointing to cargo_sisgedi
        DB::statement('ALTER TABLE convocatoria_cargo ADD CONSTRAINT fk_cc_cargo_sisgedi_id FOREIGN KEY (cargo_id) REFERENCES cargo_sisgedi(cargo_id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        if (! Schema::hasTable('convocatoria_cargo')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE convocatoria_cargo DROP FOREIGN KEY fk_cc_cargo_sisgedi_id');
        } catch (\Throwable $e) {
            // Ignore
        }

        // Revert cargo_id back to signed bigint
        DB::statement('ALTER TABLE convocatoria_cargo MODIFY cargo_id BIGINT NOT NULL');

        // Restore original FK (best-effort)
        try {
            DB::statement('ALTER TABLE convocatoria_cargo ADD CONSTRAINT fk_convocatoria_cargo_cargo_id FOREIGN KEY (cargo_id) REFERENCES cargo(cargo_id) ON DELETE CASCADE');
        } catch (\Throwable $e) {
            // Ignore if cargo table doesn't exist or types mismatch
        }
    }
};
