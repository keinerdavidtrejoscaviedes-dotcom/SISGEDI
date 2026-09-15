<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabla de cargos SISGEDI ───────────────────────────────────
        if (! Schema::hasTable('cargo_sisgedi')) {
            Schema::create('cargo_sisgedi', function (Blueprint $table) {
                $table->bigIncrements('cargo_id');
                $table->string('nombre_cargo');
                $table->string('descripcion')->nullable();
                $table->timestamps();
            });

            // Cargos iniciales de demostración
            DB::table('cargo_sisgedi')->insert([
                ['nombre_cargo' => 'Líder de Producción Agrícola',     'descripcion' => 'Coordina el sector agrícola'],
                ['nombre_cargo' => 'Líder de Planta de Lácteos',       'descripcion' => 'Coordina la planta de lácteos'],
                ['nombre_cargo' => 'Colaborador Cultivo de Maíz',      'descripcion' => 'Apoya el cultivo de maíz'],
                ['nombre_cargo' => 'Colaborador Avicultura',           'descripcion' => 'Apoya el sector avícola'],
                ['nombre_cargo' => 'Colaborador Porcicultura',         'descripcion' => 'Apoya el sector porcícola'],
                ['nombre_cargo' => 'Operario de Empaque',              'descripcion' => 'Maneja la línea de empaque'],
                ['nombre_cargo' => 'Aprendiz Gestión Ambiental',       'descripcion' => 'Apoyo en gestión ambiental'],
                ['nombre_cargo' => 'Aprendiz Innovación y Prototipado','descripcion' => 'Apoyo en área de innovación'],
            ]);
        }

        // ── Agregar columna titulo a convocatoria si no existe ────────
        if (Schema::hasTable('convocatoria') && ! Schema::hasColumn('convocatoria', 'titulo')) {
            Schema::table('convocatoria', function (Blueprint $table) {
                $table->string('titulo')->after('convocatoria_id')->default('Sin título');
            });
        }

        // ── Agregar columna descripcion a convocatoria si no existe ───
        if (Schema::hasTable('convocatoria') && ! Schema::hasColumn('convocatoria', 'descripcion')) {
            Schema::table('convocatoria', function (Blueprint $table) {
                $table->text('descripcion')->nullable()->after('titulo');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo_sisgedi');
    }
};
