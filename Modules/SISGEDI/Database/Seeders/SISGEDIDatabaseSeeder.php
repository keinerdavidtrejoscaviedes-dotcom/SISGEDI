<?php

namespace Modules\SISGEDI\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Inserta el rol Administrador y el usuario yina en SISGEDI.
 * La tabla users_sisgedi almacena contraseñas en texto plano.
 *
 * NOTA (resuelto en merge): la rama origin/gerente-administrativo tenía aquí
 * un seeder alterno que poblaba un organigrama completo (Gerencia/Cargo/Rol/
 * UsuarioRol/Fase vía Eloquent, tablas sisgedi_gerencias/sisgedi_cargos/
 * sisgedi_roles/sisgedi_usuario_roles/sisgedi_fases). Esas tablas se
 * unificaron con las legacy `roles_sisgedi`/`users_sisgedi`/`fase` (ver
 * Entities/Fase.php y Http/Middleware/EnsureSisgediRole.php), por lo que ese
 * seeder quedó incompatible y se retiró. Los 27 usuarios de users_sisgedi ya
 * existen en la base de datos real; este seeder solo cubre el caso de una
 * base de datos nueva/vacía.
 */
class SISGEDIDatabaseSeeder extends Seeder
{
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
