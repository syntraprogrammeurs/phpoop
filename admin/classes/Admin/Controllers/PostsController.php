<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\Flash;
use Admin\Core\View;
use Admin\Repositories\MediaRepository;
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
            $old = ['title' => '', 'content' => '', 'status' => 'draft', 'featured_media_id' => ''];
        }

        View::render('post-create.php', [
            'title' => 'Nieuwe post',
            'old' => $old,
            'media' => MediaRepository::make()->getAllImages(),
        ]);
    }

    public function store(): void
    {
        $title = isset($_POST['title']) ? trim((string)$_POST['title']) : '';
        $content = isset($_POST['content']) ? trim((string)$_POST['content']) : '';
        $status = isset($_POST['status']) ? (string)$_POST['status'] : 'draft';

        $featuredId = null;
        if (isset($_POST['featured_media_id']) && $_POST['featured_media_id'] !== '') {
            $featuredId = (int)$_POST['featured_media_id'];
        }

        $errors = [];

        if ($title === '') {
            $errors[] = 'Titel is verplicht.';
        }

        if ($content === '') {
            $errors[] = 'Content is verplicht.';
        }

        if (!in_array($status, ['draft', 'published'], true)) {
            $errors[] = 'Status is ongeldig.';
        }

        if (!empty($errors)) {
            \Admin\Core\Flash::set('warning', $errors);
            \Admin\Core\Flash::set('old', [
                'title' => $title,
                'content' => $content,
                'status' => $status,
                'featured_media_id' => $featuredId,
            ]);

            header('Location: /admin/posts/create');
            exit;
        }

        // 1) Post aanmaken (slug nog NULL)
        $postId = $this->posts->create($title, $content, $status, $featuredId);

        // 2) Base slug genereren
        $baseSlug = \Admin\Services\SlugService::slugify($title);

        // 3) Uniek maken
        $slug = $baseSlug;
        $counter = 2;

        while ($this->posts->slugExists($slug, $postId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // 4) Slug opslaan
        $this->posts->updateSlug($postId, $slug);

        \Admin\Core\Flash::set('success', 'Post succesvol aangemaakt.');
        header('Location: /admin/posts');
        exit;
    }



    public function edit(int $id): void
    {
        $post = $this->posts->find($id);

        if (!$post) {
            Flash::set('error', 'Post niet gevonden.');
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        $old = Flash::get('old');
        if (!is_array($old)) {
            $old = [
                'title' => (string)$post['title'],
                'content' => (string)$post['content'],
                'status' => (string)$post['status'],
                'featured_media_id' => (string)($post['featured_media_id'] ?? ''),
            ];
        }

        View::render('post-edit.php', [
            'title' => 'Post bewerken',
            'postId' => $id,
            'post' => $post,
            'old' => $old,
            'media' => MediaRepository::make()->getAllImages(),
        ]);
    }

    public function update(int $id): void
    {
        $post = $this->posts->find($id);
        if (!$post) {
            Flash::set('error', 'Post niet gevonden.');
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        $title   = trim((string)($_POST['title'] ?? ''));
        $content = trim((string)($_POST['content'] ?? ''));
        $status  = (string)($_POST['status'] ?? 'draft');
        $featuredRaw = trim((string)($_POST['featured_media_id'] ?? ''));

        $featuredId = $this->normalizeFeaturedId($featuredRaw);

        $errors = $this->validate($title, $content, $status, $featuredId);

        if (!empty($errors)) {
            Flash::set('warning', $errors);
            Flash::set('old', compact('title', 'content', 'status') + ['featured_media_id' => $featuredRaw]);
            header('Location: ' . ADMIN_BASE_PATH . '/posts/' . $id . '/edit');
            exit;
        }

        $this->posts->update($id, $title, $content, $status, $featuredId);

        Flash::set('success', 'Post succesvol aangepast.');
        header('Location: ' . ADMIN_BASE_PATH . '/posts');
        exit;
    }

    public function deleteConfirm(int $id): void
    {
        $post = $this->posts->find($id);

        if (!$post) {
            Flash::set('error', 'Post niet gevonden.');
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        View::render('post-delete.php', [
            'title' => 'Post verwijderen',
            'post' => $post,
        ]);
    }

    public function show(int $id): void
    {
        $post = $this->posts->find($id);

        if (!$post) {
            Flash::set('error', 'Post niet gevonden.');
            header('Location: ' . ADMIN_BASE_PATH . '/posts');
            exit;
        }

        View::render('post-show.php', [
            'title' => 'Post bekijken',
            'post' => $post,
        ]);
    }

    private function normalizeFeaturedId(string $raw): ?int
    {
        if ($raw === '' || !ctype_digit($raw)) {
            return null;
        }
        $id = (int)$raw;
        return $id > 0 ? $id : null;
    }

    private function validate(string $title, string $content, string $status, ?int $featuredId): array
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

        if ($featuredId !== null && MediaRepository::make()->findImageById($featuredId) === null) {
            $errors[] = 'Featured image is ongeldig.';
        }

        return $errors;
    }
}
