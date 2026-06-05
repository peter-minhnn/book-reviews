<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $groupStack = [];

    public function get(string $uri, callable|array $handler): Route
    {
        return $this->addRoute('GET', $uri, $handler);
    }

    public function post(string $uri, callable|array $handler): Route
    {
        return $this->addRoute('POST', $uri, $handler);
    }

    public function put(string $uri, callable|array $handler): Route
    {
        return $this->addRoute('PUT', $uri, $handler);
    }

    public function patch(string $uri, callable|array $handler): Route
    {
        return $this->addRoute('PATCH', $uri, $handler);
    }

    public function delete(string $uri, callable|array $handler): Route
    {
        return $this->addRoute('DELETE', $uri, $handler);
    }

    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = [
            'prefix' => $attributes['prefix'] ?? '',
            'name' => $attributes['name'] ?? '',
            'middleware' => $attributes['middleware'] ?? [],
        ];

        $callback($this);

        array_pop($this->groupStack);
    }

    private function addRoute(string $method, string $uri, callable|array $handler): Route
    {
        $prefix = '';
        $namePrefix = '';
        $groupMiddleware = [];

        foreach ($this->groupStack as $group) {
            $prefix .= $group['prefix'] ?? '';
            $namePrefix .= $group['name'] ?? '';
            $groupMiddleware = array_merge($groupMiddleware, $group['middleware'] ?? []);
        }

        $fullUri = $prefix . $uri;
        $fullUri = preg_replace('#/+#', '/', $fullUri);
        // Strip trailing slash unless the result is root "/"
        if ($fullUri !== '/' && str_ends_with($fullUri, '/')) {
            $fullUri = rtrim($fullUri, '/');
        }

        $route = new Route($method, $fullUri, $handler);
        $route->namePrefix = $namePrefix;
        $route->middleware = $groupMiddleware;

        $this->routes[] = $route;

        return $route;
    }

    public function route(string $name, array $params = []): string
    {
        foreach ($this->routes as $route) {
            if ($route->name === $name) {
                $uri = $route->uri;
                foreach ($params as $key => $value) {
                    $uri = str_replace("{{$key}}", $value, $uri);
                }
                return $uri;
            }
        }
        throw new \RuntimeException("Route [{$name}] not found.");
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') {
            $uri = '/';
        }

        // Method spoofing
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofed = strtoupper($_POST['_method']);
            if (in_array($spoofed, ['PUT', 'PATCH', 'DELETE'])) {
                $method = $spoofed;
            }
        }

        // CSRF check for mutating requests (skip SSE endpoints)
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $isSseEndpoint = str_contains($uri, '/events/') || str_contains($uri, '/reviews/stream');
            if (!$isSseEndpoint) {
                $token = $_POST['_token'] ?? '';
                if (!$token || !hash_equals(Session::token(), $token)) {
                    http_response_code(419);
                    echo "CSRF token mismatch.";
                    return;
                }
            }
        }

        foreach ($this->routes as $route) {
            if ($route->method !== $method) {
                continue;
            }

            $params = $route->matches($uri);
            if ($params === false) {
                continue;
            }

            // Run middleware
            foreach ($route->middleware as $middlewareClass) {
                $middleware = new $middlewareClass();
                $result = $middleware->handle();
                if ($result !== null) {
                    return;
                }
            }

            // Call handler with positional args (associative $params → values)
            // Must use array_values() because PHP 8 treats string keys as named arguments,
            // e.g. ...['book' => '2'] would call $fn(book: 2) instead of $fn('2')
            $handler = $route->handler;
            if (is_array($handler)) {
                [$class, $method] = $handler;
                $controller = new $class();
                echo $controller->$method(...array_values($params));
            } else {
                echo $handler(...array_values($params));
            }

            return;
        }

        http_response_code(404);
        echo View::render('errors.404', ['title' => '404 - Not Found']);
    }
}
