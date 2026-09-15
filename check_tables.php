<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = [
    'fase', 'convocatoria', 'convocatoria_cargo', 'postulacion', 
    'postulacion_opcion_cargo', 'cargo_sisgedi', 'sectors', 
    'listado_maestro_documento', 'config_firma_rol_documento', 
    'users_sisgedi', 'roles_sisgedi'
];

foreach ($tables as $t) {
    echo str_pad($t, 30) . ': ' . (Illuminate\Support\Facades\Schema::hasTable($t) ? 'YES' : 'NO') . PHP_EOL;
}
