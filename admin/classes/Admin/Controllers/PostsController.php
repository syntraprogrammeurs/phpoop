<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\View;
use Admin\Core\Flash;
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
        Flash::set('Post succesvol aangemaakt.');

        header('Location: /minicms/admin/posts');
        exit;
    }

    /**
     * edit()
     *
     * Doel:
     * Toont het edit-formulier met bestaande data.
     *
     * Werking:
     * 1) Haalt de post op via find($id).
     * 2) Bestaat de post niet? Toon 404.
     * 3) Bestaat de post wel? Vul old waarden met de bestaande data.
     */
    public function edit(int $id): void
    {
        $post = $this->postsRepository->find($id);

        if ($post === null) {
            (new ErrorController())->notFound('/posts/' . $id . '/edit');
            return;
        }

        View::render('post-edit.php', [
            'title' => 'Post bewerken',
            'errors' => [],
            'postId' => $id,
            'old' => [
                'title' => (string)$post['title'],
                'content' => (string)$post['content'],
                'status' => (string)$post['status'],
            ],
        ]);
    }

    /**
     * update()
     *
     * Doel:
     * Verwerkt het edit-formulier (POST) en past de post aan.
     *
     * Werking:
     * 1) Lees input + trim.
     * 2) Valideer (zelfde regels als store()).
     * 3) Bij errors: render post-edit.php opnieuw met errors + old input.
     * 4) Bij succes: repository->update(...)
     * 5) Redirect naar overzicht.
     */
    public function update(int $id): void
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
            View::render('post-edit.php', [
                'title' => 'Post bewerken',
                'errors' => $errors,
                'postId' => $id,
                'old' => [
                    'title' => $title,
                    'content' => $content,
                    'status' => $status,
                ],
            ]);
            return;
        }

        $this->postsRepository->update($id, $title, $content, $status);
        Flash::set('Post succesvol bijgewerkt.');

        header('Location: /minicms/admin/posts');
        exit;
    }
    /**
     * deleteConfirm()
     *
     * Doel:
     * Toont bevestigingspagina voor verwijderen.
     *
     * Werking:
     * 1) Haal post op via id.
     * 2) Bestaat die niet? Toon 404.
     * 3) Bestaat die wel? Render confirm view.
     */
    public function deleteConfirm(int $id): void
    {
        $post = $this->postsRepository->find($id);

        if ($post === null) {
            (new ErrorController())->notFound('/posts/' . $id . '/delete');
            return;
        }

        View::render('post-delete.php', [
            'title' => 'Post verwijderen',
            'post' => $post,
        ]);
    }

    /**
     * delete()
     *
     * Doel:
     * Verwijdert de post na bevestiging.
     *
     * Werking:
     * 1) Controleer of post bestaat.
     * 2) delete() via repository.
     * 3) Redirect naar overzicht.
     */
    public function delete(int $id): void
    {
        $post = $this->postsRepository->find($id);

        if ($post === null) {
            (new ErrorController())->notFound('/posts/' . $id . '/delete');
            return;
        }

        $this->postsRepository->delete($id);


        Flash::set('Post succesvol verwijderd.');

        header('Location: /minicms/admin/posts');
        exit;

    }

}
