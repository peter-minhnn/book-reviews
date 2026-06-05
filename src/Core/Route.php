<?php

namespace App\Core;

class Route
{
    public string $method;
    public string $uri;
    public mixed $handler;
    public array $middleware = [];
    public string $name = '';
    public string $namePrefix = '';

    public function __construct(string $method, string $uri, callable|array $handler)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->handler = $handler;
    }

    /**
     * Set the route name (applies accumulated group prefix).
     */
    public function name(string $name): self
    {
        $this->name = $this->namePrefix . $name;
        return $this;
    }

    /**
     * Add middleware to this route (fluent chaining).
     */
    public function middleware(string|array $middleware): self
    {
        $mw = is_array($middleware) ? $middleware : [$middleware];
        $this->middleware = array_merge($this->middleware, $mw);
        return $this;
    }

    public function matches(string $requestUri): array|false
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $this->uri);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return false;
    }
}
