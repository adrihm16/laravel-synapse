<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\Artisan::call('migrate:fresh');
} catch (\Exception $e) {
    file_put_contents('err_output.txt', $e->getMessage() . "\n" . $e->getTraceAsString());
}
