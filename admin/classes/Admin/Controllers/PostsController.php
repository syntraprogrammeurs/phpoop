<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\View;
use Admin\Models\PostsModel;

class PostsController
{
    private PostsModel $postsModel;
    private string $title = 'Posts';

    /**
     * __construct()
     *
     * Doel:
     * De controller krijgt een PostsModel binnen en bewaart dit.
     * Daardoor kan index() en show() altijd posts ophalen via het model.
     */
    public function __construct(PostsModel $postsModel)
    {
        $this->postsModel = $postsModel;
    }

    /**
     * index()
     *
     * Doel:
     * Toont het overzicht van alle posts.
     *
     * Werking:
     * 1) Vraagt alle posts op via het model.
     * 2) Rendert de view posts.php via View::render().
     * 3) Geeft $title en $posts door aan de view.
     */
    public function index(): void
    {
        $posts = $this->postsModel->getAll();

        View::render('posts.php', [
            'title' => $this->title,
            'posts' => $posts,
        ]);
    }

    /**
     * show()
     *
     * Doel:
     * Toont één post op basis van het id uit de URL.
     *
     * Werking:
     * 1) Vraagt de post op via het model.
     * 2) Als de post niet bestaat: 404 en stoppen.
     * 3) Als de post bestaat: render post-show.php en geef $post door.
     */
    public function show(int $id): void
    {
        $post = $this->postsModel->find($id);

        if ($post === null) {
            http_response_code(404);
            echo '<h1>404 - Post niet gevonden</h1>';
            return;
        }

        View::render('post-show.php', [
            'title' => 'Post #' . $id,
            'post' => $post,
        ]);
    }
}
