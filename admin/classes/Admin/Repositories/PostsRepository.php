<?php
declare(strict_types=1);

namespace Admin\Repositories;

use Admin\Core\Database;
use PDO;

final class PostsRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public static function make(): self
    {
        return new self(Database::getConnection());
    }

    // Admin
    public function getAll(): array
    {
        $sql = "SELECT id, title, content, status, featured_media_id, created_at
                FROM posts
                ORDER BY id DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT id, title, content, status, featured_media_id, created_at
                FROM posts
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    // Frontend: homepage
    public function getPublishedLatest(int $limit = 6): array
    {
        $limit = max(1, min(50, $limit));

        $sql = "SELECT id, title, content, status, featured_media_id, created_at
                FROM posts
                WHERE status = 'published'
                ORDER BY created_at DESC
                LIMIT " . (int)$limit;

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // Frontend: detailpagina /posts/{id}
    public function findPublishedById(int $id): ?array
    {
        $sql = "SELECT id, title, content, status, featured_media_id, created_at
                FROM posts
                WHERE id = :id AND status = 'published'
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    // Admin: create/update/delete
    public function create(string $title, string $content, string $status, ?int $featuredMediaId = null): int
    {
        $sql = "INSERT INTO posts (title, content, status, featured_media_id, created_at)
                VALUES (:title, :content, :status, :featured_media_id, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'featured_media_id' => $featuredMediaId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $title, string $content, string $status, ?int $featuredMediaId = null): void
    {
        $sql = "UPDATE posts
                SET title = :title,
                    content = :content,
                    status = :status,
                    featured_media_id = :featured_media_id
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'featured_media_id' => $featuredMediaId,
        ]);
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
