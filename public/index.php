<?php

/**
 * Book Review - Pure PHP Front Controller
 */

// Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Run: resolve route, execute middleware, dispatch controller
$app->run();
