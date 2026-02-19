<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

echo "App class: " . get_class($app) . PHP_EOL;
echo "Has make method: " . (method_exists($app, 'make') ? 'yes' : 'no') . PHP_EOL;

try {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    echo "Kernel class: " . get_class($kernel) . PHP_EOL;
    echo "Kernel has bootstrap method: " . (method_exists($kernel, 'bootstrap') ? 'yes' : 'no') . PHP_EOL;
    
    // Try to bootstrap
    $kernel->bootstrap();
    echo "Bootstrap successful!" . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}
