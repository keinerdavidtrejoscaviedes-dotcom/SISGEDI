<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $result = Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE entrevista");
    $create = json_decode(json_encode($result[0]), true);
    echo $create['Create Table'] . "\n\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
