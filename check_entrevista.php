<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach($tables as $t) {
    $t = (array)$t;
    $val = array_values($t)[0];
    if(strpos($val, 'entrevista') !== false) {
        echo $val . PHP_EOL;
    }
}
