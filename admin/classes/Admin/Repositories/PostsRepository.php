<?php
declare(strict_types=1);

namespace Admin\Repositories;

use Admin\Core\Database;
use PDO;

class PostsRepository
{
    private PDO $pdo;

    /**
     * __construct()
     *
     * Doel:
     * Bewaart PDO zodat alle queries via dezelfde connectie lopen.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * getAll()
     *
     * Doel:
     * Haalt alle posts op voor het overzicht.
     */
    public function getAll(): array
    {
        $sql = "SELECT id, title, status, created_at
                FROM posts
                ORDER BY id DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * find()
     *
     * Doel:
     * Haalt één post op via id.
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT id, title, content, status, created_at
                FROM posts
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $post = $stmt->fetch();

        return $post === false ? null : $post;
    }

    /**
     * create()
     *
     * Doel:
     * Voegt een nieuwe post toe in de database.
     */
    public function create(string $title, string $content, string $status): int
    {
        $sql = "INSERT INTO posts (title, content, status)
                VALUES (:title, :content, :status)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'status' => $status,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * make()
     *
     * Doel:
     * Factory method om repository snel te maken met standaard connectie.
     */
    public static function make(): self
    {
        return new self(Database::getConnection());
    }
}
