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
     * update()
     *
     * Doel:
     * Past een bestaande post aan in de database.
     *
     * Werking:
     * 1) Prepared UPDATE met placeholders.
     * 2) updated_at wordt gezet op NOW().
     * 3) execute() vult placeholders veilig in.
     * 4) rowCount() toont hoeveel rijen gewijzigd zijn.
     */
    public function update(int $id, string $title, string $content, string $status): int
    {
        $sql = "UPDATE posts
                SET title = :title,
                    content = :content,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'status' => $status,
        ]);

        return (int)$stmt->rowCount();
    }
    /**
     * delete()
     *
     * Doel:
     * Verwijdert een post uit de database.
     *
     * Werking:
     * 1) Prepared DELETE met :id.
     * 2) execute() voert delete uit.
     * 3) rowCount() toont of er effectief iets verwijderd is.
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM posts
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return (int)$stmt->rowCount();
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


    /**FRONTEND*/
    /*
|--------------------------------------------------------------------------
| Public queries: enkel published
|--------------------------------------------------------------------------
| Deze methodes worden gebruikt door de publieke frontend.
| Ze filteren altijd op status = 'published'.
*/

    public function getPublishedLatest(int $limit = 5): array
    {
        // SQL: enkel published, nieuwste eerst, beperkt via LIMIT
        $sql = "
        SELECT id, title, content, created_at
        FROM posts
        WHERE status = 'published'
        ORDER BY created_at DESC
        LIMIT :limit
    ";

        // prepare op de bestaande PDO property
        $stmt = $this->pdo->prepare($sql);

        // LIMIT moet integer zijn
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        // uitvoeren
        $stmt->execute();

        // fetchAll kan false geven -> we willen altijd array
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getPublishedAll(): array
    {
        // SQL: alle published posts, nieuwste eerst
        $sql = "
        SELECT id, title, content, created_at
        FROM posts
        WHERE status = 'published'
        ORDER BY created_at DESC
    ";

        // query() kan omdat er geen parameters zijn
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findPublishedById(int $id): ?array
    {
        // SQL: één published post
        $sql = "
        SELECT id, title, content, created_at
        FROM posts
        WHERE id = :id
          AND status = 'published'
        LIMIT 1
    ";

        $stmt = $this->pdo->prepare($sql);

        // execute met array bindt :id
        $stmt->execute([
            'id' => $id,
        ]);

        // fetch geeft array of false
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        return $post ?: null;
    }
}
