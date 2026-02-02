<?php
declare(strict_types=1);

namespace Admin\Controllers;

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

    public function index(): void
    {
        View::render('posts.php', [
            'title' => 'Posts',
            'posts' => $this->posts->getAll(),
        ]);
    }

    public function create(): void
    {
        $old = Flash::get('old');
        if (!is_array($old)) {
            $old = ['title' => '', 'content' => '', 'status' => 'draft'];
        }

        View::render('post-create.php', [
            'title' => 'Nieuwe post',
            'old' => $old,
        ]);
    }

    public function store(): void
    {
        $title   = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));
        $status  = (string)($_POST['status'] ?? 'draft');

        $errors = $this->validate($title, $content, $status);

        if (!empty($errors)) {
            Flash::set('warning', $errors);
            Flash::set('old', compact('title', 'content', 'status'));
            header('Location: /admin/posts/create');
            exit;
        }

        $this->posts->create($title, $content, $status);

        Flash::set('success', 'Post succesvol aangemaakt.');
        header('Location: /admin/posts');
        exit;
    }

    public function edit(int $id): void
    {
        $post = $this->posts->find($id);

        if (!$post) {
            Flash::set('error', 'Post niet gevonden.');
            header('Location: /admin/posts');
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
            'title'  => 'Post bewerken',
            'postId' => $id,
            'post'   => $post,
            'old'    => $old,
        ]);
    }

    public function update(int $id): void
    {
        $post = $this->posts->find($id);
        if (!$post) {
            Flash::set('error', 'Post niet gevonden.');
            header('Location: /admin/posts');
            exit;
        }

        $title   = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));
        $status  = (string)($_POST['status'] ?? 'draft');

        $errors = $this->validate($title, $content, $status);

        if (!empty($errors)) {
            Flash::set('warning', $errors);
            Flash::set('old', compact('title', 'content', 'status'));
            header('Location: /admin/posts/' . $id . '/edit');
            exit;
        }

        $this->posts->update($id, $title, $content, $status);

        Flash::set('success', 'Post succesvol aangepast.');
        header('Location: /admin/posts');
        exit;
    }

    public function deleteConfirm(int $id): void
    {
        $post = $this->posts->find($id);

        if (!$post) {
            Flash::set('error', 'Post niet gevonden.');
            header('Location: /admin/posts');
            exit;
        }

        View::render('post-delete.php', [
            'title' => 'Post verwijderen',
            'post'  => $post,
        ]);
    }

    private function validate(string $title, string $content, string $status): array
    {
        $errors = [];

        if ($title === '') {
            $errors[] = 'Titel is verplicht.';
        } elseif (mb_strlen($title) < 3) {
            $errors[] = 'Titel moet minstens 3 tekens bevatten.';
        }

        if ($content === '') {
            $errors[] = 'Inhoud is verplicht.';
        } elseif (mb_strlen($content) < 10) {
            $errors[] = 'Inhoud moet minstens 10 tekens bevatten.';
        }

        if (!in_array($status, ['draft', 'published'], true)) {
            $errors[] = 'Status moet draft of published zijn.';
        }

        return $errors;
    }
}
