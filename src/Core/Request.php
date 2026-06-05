<?php

namespace App\Core;

class Request
{
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function uri(): string
    {
        return '/' . trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public static function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public static function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public static function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    public static function only(array $keys): array
    {
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = self::input($key);
        }
        return $values;
    }

    public static function has(string $key): bool
    {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }

    public static function filled(string $key): bool
    {
        $value = self::input($key);
        return $value !== null && $value !== '';
    }

    public static function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public static function hasFile(string $key): bool
    {
        $file = self::file($key);
        return $file && $file['error'] === UPLOAD_ERR_OK;
    }

    public static function isAjax(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    public static function header(string $key, mixed $default = null): mixed
    {
        $header = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$header] ?? $default;
    }

    public static function routeIs(string $pattern): bool
    {
        // Simple match for active route detection
        $uri = self::uri();
        $pattern = str_replace('*', '.*', $pattern);
        return (bool) preg_match('#^' . $pattern . '$#', $uri);
    }
}
