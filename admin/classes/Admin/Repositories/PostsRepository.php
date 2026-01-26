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
     * Bewaart de PDO connectie zodat alle queries via dezelfde connectie lopen.
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
     *
     * Werking:
     * 1) Voert SELECT query uit.
     * 2) Sorteert nieuwste eerst.
     * 3) Geeft array van posts terug.
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
     *
     * Werking:
     * 1) Prepared statement met :id.
     * 2) Execute met parameter.
     * 3) Fetch 1 record.
     * 4) Return array of null.
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
