<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * Start de PHP session.
 * Zonder dit werkt $_SESSION niet.
 */
session_start();

require __DIR__ . '/autoload.php';

use Admin\Core\Router;
use Admin\Core\Auth;
use Admin\Controllers\DashboardController;
use Admin\Controllers\PostsController;
use Admin\Controllers\ErrorController;
use Admin\Models\StatsModel;
use Admin\Repositories\PostsRepository;
use Admin\Controllers\AuthController;
use Admin\Repositories\UsersRepository;



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/minicms/admin';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;

/**
 * Beveilig admin-routes:
 * als je niet ingelogd bent, ga naar /login
 */
$publicRoutes = ['/login'];

if (!Auth::check() && !in_array($uri, $publicRoutes, true)) {
    header('Location: /minicms/admin/login');
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

/**
 * setNotFoundHandler()
 *
 * Doel:
 * Zorgt dat elke onbekende URL een nette 404 pagina krijgt via ErrorController.
 */
$errorController = new ErrorController();
$router->setNotFoundHandler(function (string $requestedUri) use ($errorController): void {
    $errorController->notFound($requestedUri);
});

/**
 * Dashboard
 */
$router->get('/', function (): void {
    (new DashboardController(new StatsModel()))->index();
});
/**
 * Auth
 */
$router->get('/login', function (): void {
    (new AuthController(UsersRepository::make()))->showLogin();
});

$router->post('/login', function (): void {
    (new AuthController(UsersRepository::make()))->login();
});

/**
 * Posts
 */
$router->get('/posts', function (): void {
    (new PostsController(PostsRepository::make()))->index();
});

/**
 * Create
 */
$router->get('/posts/create', function (): void {
    (new PostsController(PostsRepository::make()))->create();
});

$router->post('/posts/store', function (): void {
    (new PostsController(PostsRepository::make()))->store();
});

/**
 * Edit + Update
 * Deze routes zijn specifieker dan /posts/{id}, dus ze moeten erboven staan.
 */
$router->get('/posts/{id}/edit', function (int $id): void {
    (new PostsController(PostsRepository::make()))->edit($id);
});

$router->post('/posts/{id}/update', function (int $id): void {
    (new PostsController(PostsRepository::make()))->update($id);
});


$router->get('/posts/{id}/delete', function (int $id): void {
    if (!Auth::isAdmin()) {
        header('Location: /minicms/admin/posts');
        exit;
    }

    (new PostsController(PostsRepository::make()))->deleteConfirm($id);
});

$router->post('/posts/{id}/delete', function (int $id): void {
    if (!Auth::isAdmin()) {
        header('Location: /minicms/admin/posts');
        exit;
    }

    (new PostsController(PostsRepository::make()))->delete($id);
});



/**
 * Show
 * Deze moet onder edit/update staan.
 */
$router->get('/posts/{id}', function (int $id): void {
    (new PostsController(PostsRepository::make()))->show($id);
});

$router->post('/logout', function (): void {
    (new AuthController(UsersRepository::make()))->logout();
});




$router->dispatch($uri, $method);
