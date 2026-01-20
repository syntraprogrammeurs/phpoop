<?php
declare(strict_types=1);

namespace Admin\Core;

class Router
{
    /**
     * @var array<string, array<string, callable>>
     * [
     *   'GET' => [
     *      '/' => callable,
     *      '/posts' => callable
     *   ],
     *   'POST' => [
     *      '/posts/store' => callable
     *   ]
     * ]
     */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $path = $this->normalize($path);
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = $this->normalize($uri);

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo '<h1>404 - Pagina niet gevonden</h1>';
            return;
        }

        call_user_func($this->routes[$method][$uri]);
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}
