<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['entrevista'];
foreach ($tables as $t) {
    if (Illuminate\Support\Facades\Schema::hasTable($t)) {
        $result = Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE $t");
        $create = json_decode(json_encode($result[0]), true);
        echo $create['Create Table'] . "\n\n";
    } else {
        echo "Table $t does NOT exist.\n";
    }
}
