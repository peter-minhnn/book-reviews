<?php

namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        $userId = $_SESSION['user_id'];
        $db = App::instance()->db();
        return $db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
    }

    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function login(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        Session::regenerate();
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && ($user['role'] ?? '') === 'admin';
    }
}
