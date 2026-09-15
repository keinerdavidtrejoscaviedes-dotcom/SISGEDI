<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$firstUserId = Illuminate\Support\Facades\DB::table('users_sisgedi')->value('id_users');
if ($firstUserId) {
    echo "Found user ID: $firstUserId\n";
    $seederFile = 'Modules/SISGEDI/Database/Seeders/EntrevistaSeeder.php';
    $content = file_get_contents($seederFile);
    // Use regex to replace in case it was modified
    $content = preg_replace("/'creado_por'\s*=>\s*\d+,/", "'creado_por'     => $firstUserId,", $content);
    file_put_contents($seederFile, $content);
    echo "Updated seeder.\n";
} else {
    echo "No user found in users_sisgedi table.\n";
}
