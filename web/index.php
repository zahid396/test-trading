<?php

/**
 * Front controller for shared-hosting (public_html) deployment.
 *
 * This lets you upload the ENTIRE project folder as your website root
 * (e.g. everything inside `public_html`) and the site just works.
 * The assets that used to live in `public/` are mapped in the root
 * `.htaccess` (see /build, /storage, /favicon.ico, /robots.txt).
 *
 * `public/index.php` is kept as well, so both layouts keep working.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());