<?php

namespace Modules\SISGEDI\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SISGEDIDatabaseSeeder extends Seeder
{
    /**
     * Inserta el rol Administrador y el usuario yina en SISGEDI.
     * La tabla users_sisgedi almacena contraseñas en texto plano.
     */
    public function run(): void
    {
        // ── Rol Administrador ──────────────────────────────────────────
        $rolExiste = DB::table('roles_sisgedi')
            ->where('nombre', 'Administrador')
            ->exists();

        if (! $rolExiste) {
            DB::table('roles_sisgedi')->insert([
                'nombre'      => 'Administrador',
                'descripcion' => 'Acceso total al sistema. Gestión de usuarios, roles y configuración general.',
            ]);
        }

        $idRolAdmin = DB::table('roles_sisgedi')
            ->where('nombre', 'Administrador')
            ->value('id_rol');

        // ── Usuario yina ───────────────────────────────────────────────
        $usuarioExiste = DB::table('users_sisgedi')
            ->where('nombre', 'yina')
            ->exists();

        if (! $usuarioExiste) {
            DB::table('users_sisgedi')->insert([
                'nombre'     => 'yina',
                'contraseña' => '12345678',
                'correo'     => 'yina@sisgedi.sena.edu.co',
                'id_rol'     => $idRolAdmin,
            ]);
        }
    }
}
