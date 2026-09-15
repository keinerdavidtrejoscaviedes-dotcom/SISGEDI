<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('checklist_entrevista')) {
            try {
                DB::statement('ALTER TABLE checklist_entrevista DROP FOREIGN KEY fk_checklist_entrevista_creado_por');
            } catch (\Exception $e) {}

            DB::statement('ALTER TABLE checklist_entrevista MODIFY creado_por INT NOT NULL');
            DB::statement('ALTER TABLE checklist_entrevista ADD CONSTRAINT fk_checklist_entrevista_creado_sisgedi FOREIGN KEY (creado_por) REFERENCES users_sisgedi(id_users) ON DELETE CASCADE');
        }
    }

    public function down(): void
    {
    }
};
