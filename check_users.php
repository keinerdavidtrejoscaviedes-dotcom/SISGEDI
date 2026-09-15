<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ver todos los usuarios sisgedi
$users = Illuminate\Support\Facades\DB::table('users_sisgedi')->get();
foreach ($users as $u) {
    echo "ID: {$u->id_users} | Nombre: {$u->nombre} | Rol: {$u->id_rol} | Correo: {$u->correo}\n";
}

echo "\n--- ROLES ---\n";
$roles = Illuminate\Support\Facades\DB::table('roles_sisgedi')->get();
foreach ($roles as $r) {
    echo "ID: {$r->id_rol} | Nombre: {$r->nombre}\n";
}
