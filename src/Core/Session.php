<?php

namespace App\Core;

class Session
{
    private static bool $started = false;

    public static function start(): void
    {
        if (self::$started) {
            return;
        }

        $config = require __DIR__ . '/../../config/session.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_name($config['name']);
            session_start();
        }

        self::$started = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]) || isset($_SESSION[$key]);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key], $_SESSION['_flash'][$key]);
    }

    public static function ageFlashData(): void
    {
        if (isset($_SESSION['_flash'])) {
            foreach ($_SESSION['_flash'] as $key => $value) {
                unset($_SESSION['_flash'][$key]);
            }
        }
    }

    public static function invalidate(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function token(): string
    {
        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_token'];
    }
}
