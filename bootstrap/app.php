<?php

// Load environment variables
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, '"\'');
            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}

// Load helpers
require_once __DIR__ . '/../src/helpers.php';

// Set timezone
date_default_timezone_set('UTC');

// Error reporting
$isLocal = ($_ENV['APP_ENV'] ?? 'production') === 'local';
if ($isLocal) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Global exception handler for nicer errors in local mode
set_exception_handler(function (\Throwable $e) use ($isLocal) {
    http_response_code(500);
    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) { mkdir($logDir, 0755, true); }
    $msg = '[' . date('Y-m-d H:i:s') . '] ' . get_class($e) . ': ' . $e->getMessage()
         . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString() . "\n";
    file_put_contents($logDir . '/app.log', $msg, FILE_APPEND);
    if ($isLocal) {
        echo "<h1>500 — Internal Server Error</h1>";
        echo "<p><strong>" . htmlspecialchars(get_class($e)) . "</strong>: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>File: " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</pre>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "<h1>500 — Internal Server Error</h1>";
    }
});

// Catch fatal errors (parse errors, memory exhaustion, etc.) that set_exception_handler misses
register_shutdown_function(function () use ($isLocal) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $logDir = __DIR__ . '/../storage/logs';
        if (!is_dir($logDir)) { mkdir($logDir, 0755, true); }
        $msg = '[' . date('Y-m-d H:i:s') . '] FATAL: ' . $error['message']
             . ' in ' . $error['file'] . ':' . $error['line'] . "\n";
        file_put_contents($logDir . '/app.log', $msg, FILE_APPEND);
        if ($isLocal) {
            if (!headers_sent()) { http_response_code(500); }
            echo "<h1>500 — Fatal Error</h1>";
            echo "<p><strong>" . htmlspecialchars($error['message']) . "</strong></p>";
            echo "<pre>File: " . htmlspecialchars($error['file']) . ":" . $error['line'] . "</pre>";
        }
    }
});

return new \App\Core\App();
