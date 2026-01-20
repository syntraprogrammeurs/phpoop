<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Models\PostsModel;

class PostsController
{
    private PostsModel $postsModel;
    private string $title = 'Posts';

    public function __construct(PostsModel $postsModel)
    {
        $this->postsModel = $postsModel;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function index(): void
    {
        $title = $this->getTitle();
        $posts = $this->postsModel->getAll();

        require __DIR__ . '/../../../views/posts.php';
    }
}
