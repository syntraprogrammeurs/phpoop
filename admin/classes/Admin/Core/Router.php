<?php
declare(strict_types=1);

namespace Admin\Core;

class Router
{
    /**
     * @var array<string, callable>
     */
    private array $routes = [];

    /**
     * Registreert een route.
     *
     * Voorbeeld:
     * $router->add('/', fn() => ...);
     * $router->add('/posts', fn() => ...);
     */
    public function add(string $path, callable $handler): void
    {
        $path = $this->normalize($path);
        $this->routes[$path] = $handler;
    }

    /**
     * Dispatch: voert de juiste handler uit op basis van de URI.
     * Als de route niet bestaat, tonen we een 404.
     */
    public function dispatch(string $uri): void
    {
        $uri = $this->normalize($uri);

        if (!array_key_exists($uri, $this->routes)) {
            http_response_code(404);
            echo '<h1>404 - Pagina niet gevonden</h1>';
            return;
        }

        call_user_func($this->routes[$uri]);
    }

    /**
     * Normaliseert paden zodat:
     * - '/posts/' en '/posts' identiek worden
     * - '' wordt '/'
     */
    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}