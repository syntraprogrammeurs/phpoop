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
     * Toont het overzicht van posts.
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
     * Toont één post.
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

    /**
     * create()
     *
     * Doel:
     * Toont het formulier om een nieuwe post aan te maken.
     */
    public function create(): void
    {
        View::render('post-create.php', [
            'title' => 'Nieuwe post',
            'errors' => [],
            'old' => [
                'title' => '',
                'content' => '',
                'status' => 'draft',
            ],
        ]);
    }

    /**
     * store()
     *
     * Doel:
     * Verwerkt het formulier (POST) en slaat de post op.
     */
    public function store(): void
    {
        $title = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));
        $status = (string)($_POST['status'] ?? 'draft');

        $errors = [];

        if ($title === '') {
            $errors[] = 'Titel is verplicht.';
        }

        if ($content === '') {
            $errors[] = 'Inhoud is verplicht.';
        }

        if (!in_array($status, ['draft', 'published'], true)) {
            $errors[] = 'Status moet draft of published zijn.';
        }

        if (!empty($errors)) {
            View::render('post-create.php', [
                'title' => 'Nieuwe post',
                'errors' => $errors,
                'old' => [
                    'title' => $title,
                    'content' => $content,
                    'status' => $status,
                ],
            ]);
            return;
        }

        $this->postsRepository->create($title, $content, $status);

        header('Location: /minicms/admin/posts');
        exit;
    }
}
