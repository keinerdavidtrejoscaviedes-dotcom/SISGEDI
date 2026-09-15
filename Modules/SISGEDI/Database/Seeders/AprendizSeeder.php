<?php

namespace Modules\SISGEDI\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AprendizSeeder extends Seeder
{
    public function run(): void
    {
        // ── Rol Aprendiz ──────────────────────────────────────────────
        if (! DB::table('roles_sisgedi')->where('nombre', 'Aprendiz')->exists()) {
            DB::table('roles_sisgedi')->insert([
                'nombre'      => 'Aprendiz',
                'descripcion' => 'Aprendiz SENA. Puede postularse a convocatorias activas seleccionando hasta 3 cargos.',
            ]);
        }

        $idRolAprendiz = DB::table('roles_sisgedi')
            ->where('nombre', 'Aprendiz')
            ->value('id_rol');

        // ── Usuario aprendiz ──────────────────────────────────────────
        if (! DB::table('users_sisgedi')->where('nombre', 'aprendiz')->exists()) {
            DB::table('users_sisgedi')->insert([
                'nombre'     => 'aprendiz',
                'contraseña' => '12345678',
                'correo'     => 'aprendiz@sisgedi.sena.edu.co',
                'id_rol'     => $idRolAprendiz,
            ]);
        }
    }
}
