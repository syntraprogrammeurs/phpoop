<?php
declare(strict_types=1);

require_once __DIR__ . '/../autoload.php';

use Admin\Core\Database;
use Admin\Repositories\PostsRepository;
use Admin\Services\SlugService;

$pdo = Database::getConnection();
$posts = new PostsRepository($pdo);

$all = $posts->getAll();

echo "Backfill slugs: start\n";

foreach ($all as $row) {
    $id = (int)$row['id'];
    $currentSlug = isset($row['slug']) ? (string)$row['slug'] : '';

    if ($currentSlug !== '') {
        continue;
    }

    $title = isset($row['title']) ? (string)$row['title'] : '';
    $base = SlugService::slugify($title);

    $slug = $base;
    $counter = 2;

    while ($posts->slugExists($slug, $id)) {
        $slug = $base . '-' . $counter;
        $counter++;
    }

    $posts->updateSlug($id, $slug);

    echo "Post {$id} -> {$slug}\n";
}

echo "Backfill slugs: klaar\n";
