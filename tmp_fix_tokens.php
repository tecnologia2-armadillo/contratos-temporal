<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Personal;
use Illuminate\Support\Str;

echo "Asignando tokens...\n";
Personal::whereNull('signature_token')->chunk(100, function ($people) {
    foreach ($people as $p) {
        $p->signature_token = (string)Str::uuid();
        $p->save();
        echo ".";
    }
});
echo "\n¡Hecho!\n";
