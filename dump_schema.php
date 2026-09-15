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

$output = "";
foreach ($tables as $t) {
    $output .= "--- TABLE: $t ---\n";
    if (Illuminate\Support\Facades\Schema::hasTable($t)) {
        $result = Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE $t");
        $create = json_decode(json_encode($result[0]), true);
        $output .= $create['Create Table'] . "\n\n";
    }
}
file_put_contents('schema_dump.txt', $output);
