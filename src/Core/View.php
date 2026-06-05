<?php

namespace App\Core;

class View
{
    private static array $sections = [];
    private static ?string $currentSection = null;
    private static array $stack = [];

    public static function render(string $template, array $data = []): string
    {
        $path = __DIR__ . '/../../views/' . str_replace('.', '/', $template) . '.php';

        if (!file_exists($path)) {
            throw new \RuntimeException("View [{$template}] not found at {$path}");
        }

        extract($data);
        ob_start();
        require $path;
        return ob_get_clean();
    }

    public static function layout(string $layout): void
    {
        // Just output nothing, layout handled by template composition
    }

    public static function section(string $name): void
    {
        self::$currentSection = $name;
        ob_start();
    }

    public static function endSection(): void
    {
        if (self::$currentSection) {
            self::$sections[self::$currentSection] = ob_get_clean();
            self::$currentSection = null;
        }
    }

    public static function yield(string $name, string $default = ''): string
    {
        return self::$sections[$name] ?? $default;
    }

    public static function push(string $name): void
    {
        self::$currentSection = $name;
        ob_start();
    }

    public static function endPush(): void
    {
        if (self::$currentSection) {
            $content = ob_get_clean();
            if (!isset(self::$stack[self::$currentSection])) {
                self::$stack[self::$currentSection] = '';
            }
            self::$stack[self::$currentSection] .= $content;
            self::$currentSection = null;
        }
    }

    public static function stack(string $name): string
    {
        return self::$stack[$name] ?? '';
    }

    public static function component(string $name, array $data = []): string
    {
        return self::render('components.' . $name, $data);
    }

    public static function include(string $name, array $data = []): string
    {
        return self::render($name, $data);
    }

    public static function clear(): void
    {
        self::$sections = [];
        self::$currentSection = null;
        self::$stack = [];
    }
}
