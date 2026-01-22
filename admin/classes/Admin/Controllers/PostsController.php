<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\View;
use Admin\Repositories\PostsRepository;

class PostsController
{
    private PostsRepository $postsRepository;
    private string $title = 'Posts';

    /**
     * __construct()
     *
     * Doel:
     * Ontvangt de repository en bewaart die.
     */
    public function __construct(PostsRepository $postsRepository)
    {
        $this->postsRepository = $postsRepository;
    }

    /**
     * index()
     *
     * Doel:
     * Toont overzicht van posts uit de database.
     */
    public function index(): void
    {
        $posts = $this->postsRepository->getAll();

        View::render('posts.php', [
            'title' => $this->title,
            'posts' => $posts,
        ]);
    }

    /**
     * show()
     *
     * Doel:
     * Toont één post via id.
     */
    public function show(int $id): void
    {
        $post = $this->postsRepository->find($id);

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
