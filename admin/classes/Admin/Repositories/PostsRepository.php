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

    // -------------------------
    // ADMIN
    // -------------------------

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

    // -------------------------
    // FRONTEND (exacte methodnames uit jouw public/index.php)
    // -------------------------

    /**
     * Laatste gepubliceerde posts voor homepage
     * Returnt ook:
     * - featured_url (of NULL)
     * - featured_alt (string)
     */
    public function getPublishedLatest(int $limit = 6): array
    {
        $limit = max(1, min(50, $limit));

        $sql = "SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.created_at,
                    p.featured_media_id,
                    CASE
                        WHEN m.id IS NULL THEN NULL
                        ELSE CONCAT('/', m.path, '/', m.filename)
                    END AS featured_url,
                    COALESCE(m.alt_text, m.original_name, p.title) AS featured_alt
                FROM posts p
                LEFT JOIN media m ON m.id = p.featured_media_id
                WHERE p.status = 'published'
                ORDER BY p.created_at DESC
                LIMIT " . (int)$limit;

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Alle gepubliceerde posts voor /posts
     */
    public function getPublishedAll(): array
    {
        $sql = "SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.created_at,
                    p.featured_media_id,
                    CASE
                        WHEN m.id IS NULL THEN NULL
                        ELSE CONCAT('/', m.path, '/', m.filename)
                    END AS featured_url,
                    COALESCE(m.alt_text, m.original_name, p.title) AS featured_alt
                FROM posts p
                LEFT JOIN media m ON m.id = p.featured_media_id
                WHERE p.status = 'published'
                ORDER BY p.created_at DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Detailpagina /posts/{id}
     */
    public function findPublishedById(int $id): ?array
    {
        $sql = "SELECT
                    p.id,
                    p.title,
                    p.content,
                    p.created_at,
                    p.featured_media_id,
                    CASE
                        WHEN m.id IS NULL THEN NULL
                        ELSE CONCAT('/', m.path, '/', m.filename)
                    END AS featured_url,
                    COALESCE(m.alt_text, m.original_name, p.title) AS featured_alt
                FROM posts p
                LEFT JOIN media m ON m.id = p.featured_media_id
                WHERE p.id = :id AND p.status = 'published'
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }
}
