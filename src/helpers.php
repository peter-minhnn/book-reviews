<?php

/**
 * Escape HTML special characters for safe output.
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Get or set a session value.
 */
function session(?string $key = null, mixed $default = null): mixed
{
    if ($key === null) {
        return \App\Core\Session::class;
    }
    return \App\Core\Session::get($key, $default);
}

/**
 * Get the authenticated user.
 */
function auth(): ?array
{
    return \App\Core\Auth::user();
}

/**
 * Generate URL for a named route.
 */
function route(string $name, array $params = []): string
{
    return \App\Core\App::instance()->router()->route($name, $params);
}

/**
 * Generate a CSRF hidden input field.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(\App\Core\Session::token()) . '">';
}

/**
 * Generate a CSRF token value.
 */
function csrf_token(): string
{
    return \App\Core\Session::token();
}

/**
 * Get config value.
 */
function config(string $key, mixed $default = null): mixed
{
    $keys = explode('.', $key);
    $file = array_shift($keys);
    $config = require __DIR__ . "/../config/{$file}.php";
    foreach ($keys as $k) {
        if (!is_array($config) || !array_key_exists($k, $config)) {
            return $default;
        }
        $config = $config[$k];
    }
    return $config;
}

/**
 * Get the current URL or generate a URL.
 */
function url(string $path = ''): string
{
    $base = config('app.url', 'http://localhost');
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

/**
 * Resolve a Vite-built asset from the manifest.
 */
function vite_asset(string $entry): string
{
    static $manifest = null;

    if ($manifest === null) {
        $manifestPath = __DIR__ . '/../public/build/manifest.json';
        $manifest = [];

        if (is_file($manifestPath)) {
            $decoded = json_decode((string) file_get_contents($manifestPath), true);
            if (is_array($decoded)) {
                $manifest = $decoded;
            }
        }
    }

    $file = $manifest[$entry]['file'] ?? ltrim($entry, '/');

    return '/build/' . ltrim($file, '/');
}

/**
 * Render a view.
 */
function view(string $template, array $data = []): string
{
    return \App\Core\View::render($template, $data);
}

/**
 * Get old input value from flash session.
 */
function old(string $key, mixed $default = ''): mixed
{
    return \App\Core\Session::get('_old.' . $key, $default);
}

/**
 * Abort with HTTP status code.
 */
function abort(int $code, string $message = ''): void
{
    http_response_code($code);
    echo $message;
    exit;
}

/**
 * Redirect to a URL.
 */
function redirect(string $url = '/'): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Redirect back to the previous page.
 */
function back(): void
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    header('Location: ' . $referer);
    exit;
}

/**
 * Generate a method spoofing field for forms.
 */
function method_field(string $method): string
{
    return '<input type="hidden" name="_method" value="' . e($method) . '">';
}

/**
 * Get the current request path for active menu detection.
 */
function request(): \App\Core\Request
{
    return new \App\Core\Request();
}

/**
 * Convert a timestamp to a Vietnamese relative time string.
 */
function timeAgo(string $timestamp): string
{
    $now = new \DateTime();
    $then = new \DateTime($timestamp);
    $diff = $now->diff($then);

    if ($diff->invert === 0) return 'vừa xong';
    if ($diff->y > 0) return $diff->y . ' năm trước';
    if ($diff->m > 0) return $diff->m . ' tháng trước';
    if ($diff->d > 0) return $diff->d . ' ngày trước';
    if ($diff->h > 0) return $diff->h . ' giờ trước';
    if ($diff->i > 0) return $diff->i . ' phút trước';
    if ($diff->s > 0) return $diff->s . ' giây trước';
    return 'vừa xong';
}
