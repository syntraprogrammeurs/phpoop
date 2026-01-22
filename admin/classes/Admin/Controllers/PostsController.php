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
     * Bewaart PostsModel zodat we posts kunnen ophalen.
     */
    public function __construct(PostsModel $postsModel)
    {
        $this->postsModel = $postsModel;
    }

    /**
     * index()
     *
     * Doel:
     * Toont overzicht van alle posts.
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
     * Toont één post op basis van id uit de URL.
     *
     * Nieuw in LES 5.3:
     * - Als post niet bestaat, tonen we de centrale 404 pagina via ErrorController.
     */
    public function show(int $id): void
    {
        $post = $this->postsModel->find($id);

        if ($post === null) {
            (new ErrorController())->notFound('/posts/' . $id);
            return;
        }

        View::render('post-show.php', [
            'title' => 'Post #' . $id,
            'post' => $post,
        ]);
    }
}
