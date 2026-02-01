<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\Auth;
use Admin\Core\Flash;
use Admin\Core\View;
use Admin\Repositories\PostsRepository;

final class PostsController
{
    private PostsRepository $posts;

    public function __construct(PostsRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * GET /admin/posts
     */
    public function index(): void
    {
        $posts = $this->posts->getAll();

        View::render('posts.php', [
            'title' => 'Posts',
            'posts' => $posts,
        ]);
    }

    /**
     * GET /admin/posts/create
     */
    public function create(): void
    {
        $old = Flash::get('old');
        if (!is_array($old)) {
            $old = [
                'title' => '',
                'content' => '',
                'status' => 'draft',
            ];
        }

        View::render('post-create.php', [
            'title' => 'Nieuwe post',
            'old' => $old,
        ]);
    }

    /**
     * POST /admin/posts/store
     */
    public function store(): void
    {
        $title = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));
        $status = (string)($_POST['status'] ?? 'draft');

        $errors = $this->validate($title, $content, $status);

        if (!empty($errors)) {
            Flash::set('errors', $errors);
            Flash::set('old', [
                'title' => $title,
                'content' => $content,
                'status' => $status,
            ]);

            header('Location: ' . ADMIN_BASE_PATH . '/posts/create');
            exit;
        }

        $this->posts->create($title, $content, $status);

        Flash::set('success', 'Post succesvol aangemaakt.');
        header('Location: ' . ADMIN_BASE_PATH . '/posts');
        exit;
    }

    /**
     * GET /admin/posts/{id}/edit
     */
    public function edit(int $id): void
    {
        $post = $this->posts->find($id);

        if ($post === null) {
            Flash::set('errors', ['Post niet gevonden.']);
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        $old = Flash::get('old');
        if (!is_array($old)) {
            $old = [
                'title' => (string)$post['title'],
                'content' => (string)$post['content'],
                'status' => (string)$post['status'],
            ];
        }

        View::render('post-edit.php', [
            'title' => 'Post bewerken',
            'postId' => $id,
            'old' => $old,
        ]);
    }

    /**
     * POST /admin/posts/{id}/update
     */
    public function update(int $id): void
    {
        $post = $this->posts->find($id);

        if ($post === null) {
            Flash::set('errors', ['Post niet gevonden.']);
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        $title = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));
        $status = (string)($_POST['status'] ?? 'draft');

        $errors = $this->validate($title, $content, $status);

        if (!empty($errors)) {
            Flash::set('errors', $errors);
            Flash::set('old', [
                'title' => $title,
                'content' => $content,
                'status' => $status,
            ]);

            header('Location: ' . ADMIN_BASE_PATH . '/posts/' . $id . '/edit');
            exit;
        }

        $this->posts->update($id, $title, $content, $status);

        Flash::set('success', 'Post succesvol aangepast.');
        header('Location: ' . ADMIN_BASE_PATH . '/posts');
        exit;
    }

    /**
     * POST /admin/posts/{id}/delete
     *
     * Gedrag:
     * - admin: success "Post verwijderd."
     * - niet-admin: errors "Je hebt geen rechten om dit te doen."
     */
    public function delete(int $id): void
    {
        if (!Auth::isAdmin()) {
            Flash::set('errors', ['Je hebt geen rechten om dit te doen.']);
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        $post = $this->posts->find($id);
        if ($post === null) {
            Flash::set('errors', ['Post niet gevonden.']);
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        $this->posts->delete($id);

        Flash::set('success', 'Post verwijderd.');
        header('Location: ' . ADMIN_BASE_PATH . '/posts');
        exit;
    }

    /**
     * Validatie regels volgens de opdracht.
     */
    private function validate(string $title, string $content, string $status): array
    {
        $errors = [];

        if ($title === '') {
            $errors[] = 'Title is verplicht.';
        } elseif (mb_strlen($title) < 3) {
            $errors[] = 'Title moet minstens 3 tekens zijn.';
        }

        if ($content === '') {
            $errors[] = 'Content is verplicht.';
        } elseif (mb_strlen($content) < 10) {
            $errors[] = 'Content moet minstens 10 tekens zijn.';
        }

        if (!in_array($status, ['draft', 'published'], true)) {
            $errors[] = 'Status moet draft of published zijn.';
        }

        return $errors;
    }
}
