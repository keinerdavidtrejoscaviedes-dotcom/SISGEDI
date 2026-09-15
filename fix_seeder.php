<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$firstUserId = Illuminate\Support\Facades\DB::table('usuario')->value('usuario_id');
if ($firstUserId) {
    echo "Found user ID: $firstUserId\n";
    // Replace 1 with actual ID in seeder
    $seederFile = 'Modules/SISGEDI/Database/Seeders/EntrevistaSeeder.php';
    $content = file_get_contents($seederFile);
    $content = str_replace("'creado_por'     => 1,", "'creado_por'     => $firstUserId,", $content);
    file_put_contents($seederFile, $content);
    echo "Updated seeder.\n";
} else {
    echo "No user found in usuario table.\n";
}
