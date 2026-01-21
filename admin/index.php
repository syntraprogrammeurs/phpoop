<?php
declare(strict_types=1);

require __DIR__ . '/autoload.php';

use Admin\Core\Router;
use Admin\Controllers\DashboardController;
use Admin\Controllers\PostsController;
use Admin\Models\StatsModel;
use Admin\Models\PostsModel;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/minicms/admin';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

/**
 * Dashboard
 */
$router->get('/', function (): void {
    (new DashboardController(new StatsModel()))->index();
});

/**
 * Posts
 */
$router->get('/posts', function (): void {
    (new PostsController(new PostsModel()))->index();
});

$router->get('/posts/{id}', function (int $id): void {
    (new PostsController(new PostsModel()))->show($id);
});

$router->dispatch($uri, $method);
