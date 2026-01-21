<?php
declare(strict_types=1);

namespace Admin\Core;

class Router
{
    /**
     * $routes
     *
     * Doel:
     * Hierin bewaren we alle geregistreerde routes.
     *
     * Structuur:
     * - Eerst per HTTP method (GET/POST)
     * - Dan per route:
     *   - pattern (regex)
     *   - handler (callable)
     */
    private array $routes = [];

    /**
     * get()
     *
     * Doel:
     * Registreert een GET-route.
     * Voorbeeld: /posts, /posts/{id}, /
     */
    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * post()
     *
     * Doel:
     * Registreert een POST-route.
     */
    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * addRoute()
     *
     * Doel:
     * Slaat een route op in $routes.
     *
     * Werking:
     * 1) Compileert het pad naar een regex pattern.
     * 2) Slaat pattern + handler op in $routes.
     */
    private function addRoute(string $method, string $path, callable $handler): void
    {
        $pattern = $this->compilePattern($path);

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * dispatch()
     *
     * Doel:
     * Kiest de juiste route voor de huidige request.
     *
     * Werking:
     * 1) Normaliseert de URI.
     * 2) Loopt door alle routes voor de huidige HTTP method.
     * 3) Matcht de URI met preg_match().
     * 4) Haalt parameters uit de URL.
     * 5) Roept de handler aan met de parameters.
     * 6) Als geen match: 404.
     */
    public function dispatch(string $uri, string $method): void
    {
        $uri = $this->normalize($uri);

        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($route['handler'], $matches);
                return;
            }
        }

        $this->notFound();
    }

    /**
     * compilePattern()
     *
     * Doel:
     * Zet routes met {id} om naar een regex pattern.
     *
     * Voorbeeld:
     * - /posts/{id} wordt #^/posts/(\d+)$#
     *
     * Belangrijk:
     * In deze les matcht {id} enkel cijfers (1,2,3,...).
     */
    private function compilePattern(string $path): string
    {
        $path = $this->normalize($path);

        $pattern = preg_replace('/\{(\w+)\}/', '(\d+)', $path);

        return '#^' . $pattern . '$#';
    }

    /**
     * normalize()
     *
     * Doel:
     * Zorgt dat paden consistent zijn zodat:
     * - /posts en /posts/ hetzelfde worden
     * - altijd een leading slash bestaat
     */
    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }

    /**
     * notFound()
     *
     * Doel:
     * Stuurt een simpele 404 response.
     * (In LES 5.3 vervangen we dit door een ErrorController + 404 view.)
     */
    private function notFound(): void
    {
        http_response_code(404);
        echo '<h1>404 - Pagina niet gevonden</h1>';
    }
}
