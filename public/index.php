<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');


/*
|--------------------------------------------------------------------------
| Public Front Controller
|--------------------------------------------------------------------------
| Dankzij DocumentRoot naar /public en .htaccess komt elke publieke URL hier binnen.
| Hier bepalen we:
| - welke route is opgevraagd
| - welke data nodig is
| - welke view we tonen
*/


require_once __DIR__ . '/../admin/autoload.php';


use Admin\Core\Database;
use Admin\Repositories\PostsRepository;

// 1) Alleen het pad uit de URL halen (zonder querystring)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// 2) Trailing slash verwijderen ("/posts/" -> "/posts")
$uri = rtrim($uri, '/') ?: '/';

// 3) PDO connectie ophalen
$pdo = Database::getConnection();

// 4) Repository initialiseren
$postsRepository = new PostsRepository($pdo);

// 5) Routing
switch ($uri) {

    case '/':
        // Home: recente published posts
        $posts = $postsRepository->getPublishedLatest(5);

        require __DIR__ . '/views/posts/home.php';
        break;

    case '/posts':
        // Overzicht: alle published posts
        $posts = $postsRepository->getPublishedAll();

        require __DIR__ . '/views/posts/index.php';
        break;

    default:
        // Detail: /posts/{id}
        if (preg_match('#^/posts/([a-z0-9]+(?:-[a-z0-9]+)*)$#', $uri, $matches)) {
            $slug = (string)$matches[1];

            $post = $postsRepository->findPublishedBySlug($slug);

            if ($post === null) {
                http_response_code(404);
                echo '<h1>404 - Post niet gevonden</h1>';
                exit;
            }

            $title = (string)$post['title'];

            ob_start();
            require __DIR__ . '/views/posts/show.php';
            $content = ob_get_clean();

            require __DIR__ . '/views/layouts/public.php';
            exit;
        }



        http_response_code(404);
        echo '404 - Pagina niet gevonden';
}
