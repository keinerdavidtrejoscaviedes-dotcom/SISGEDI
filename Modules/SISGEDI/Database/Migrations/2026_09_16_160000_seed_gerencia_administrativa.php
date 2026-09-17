<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Siembra la Gerencia y el Cargo de "Gerente Administrativo" necesarios
 * para el panel de cascada de tareas (RF-019), ya que hoy solo existe una
 * cuenta con ese rol (`gerente.administrativo` en users_sisgedi).
 */
return new class extends Migration
{
    public function up(): void
    {
        $gerenciaId = DB::table('sisgedi_gerencias')->where('nombre', 'Gerencia Administrativa')->value('id');

        if (! $gerenciaId) {
            $gerenciaId = DB::table('sisgedi_gerencias')->insertGetId([
                'nombre' => 'Gerencia Administrativa',
                'descripcion' => 'Procesos administrativos, financieros y de talento humano.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $existeCargo = DB::table('sisgedi_cargos')
            ->where('tipo_cargo', 'GERENTE_ADMINISTRATIVO')
            ->exists();

        if (! $existeCargo) {
            DB::table('sisgedi_cargos')->insert([
                'nombre' => 'Gerente Administrativo',
                'tipo_cargo' => 'GERENTE_ADMINISTRATIVO',
                'gerencia_id' => $gerenciaId,
                'cargo_superior_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('sisgedi_cargos')->where('tipo_cargo', 'GERENTE_ADMINISTRATIVO')->delete();
        DB::table('sisgedi_gerencias')->where('nombre', 'Gerencia Administrativa')->delete();
    }
};
