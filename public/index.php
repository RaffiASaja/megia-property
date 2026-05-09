<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
// Path diubah: dari __DIR__.'/../storage...' menjadi __DIR__.'/storage...'
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
// Path diubah: dari __DIR__.'/../vendor...' menjadi __DIR__.'/vendor...'
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
// Path diubah: dari __DIR__.'/../bootstrap...' menjadi __DIR__.'/bootstrap...'
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());