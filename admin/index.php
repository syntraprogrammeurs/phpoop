<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/autoload.php';

use Admin\Core\Router;
use Admin\Controllers\DashboardController;
use Admin\Controllers\PostsController;
use Admin\Models\StatsModel;
use Admin\Models\PostsModel;

// URI ophalen
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Base path strippen (pas aan als je projectmap anders heet)
$basePath = '/minicms/admin';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

// Normaliseren
$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

// HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Router
$router = new Router();

/**
 * GET routes
 */
$router->get('/', function (): void {
    $controller = new DashboardController(new StatsModel());
    $title = $controller->getTitle();

    require __DIR__ . '/includes/header.php';
    require __DIR__ . '/includes/sidebar.php';

    echo '<main class="flex-1">';
    require __DIR__ . '/includes/topbar.php';
    $controller->index();
    echo '</main>';

    require __DIR__ . '/includes/footer.php';
});

$router->get('/posts', function (): void {
    $controller = new PostsController(new PostsModel());
    $title = $controller->getTitle();

    require __DIR__ . '/includes/header.php';
    require __DIR__ . '/includes/sidebar.php';

    echo '<main class="flex-1">';
    require __DIR__ . '/includes/topbar.php';
    $controller->index();
    echo '</main>';

    require __DIR__ . '/includes/footer.php';
});

/**
 * POST routes (placeholder)
 */
$router->post('/posts/store', function (): void {
    // later: controller store + redirect
    echo 'Post wordt opgeslagen (placeholder)';
});

// Dispatch
$router->dispatch($uri, $method);
