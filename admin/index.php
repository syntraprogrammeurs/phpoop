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

// 1) URI ophalen (zonder querystring)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2) Base path verwijderen: project draait in /minicms-pro en admin in /admin
$basePath = '/minicms/admin';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

// 3) Normaliseren: '' -> '/', '/posts/' -> '/posts'
$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

// 4) Router maken
$router = new Router();

// Route: dashboard
$router->add('/', function (): void {
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

// Route: posts
$router->add('/posts', function (): void {
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

// 5) Dispatch
$router->dispatch($uri);