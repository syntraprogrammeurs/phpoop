<?php
declare(strict_types=1);

namespace Admin\Repositories;

use Admin\Core\Database;
use PDO;

class UsersRepository
{
    private PDO $pdo;

    /**
     * __construct()
     *
     * Doel:
     * Bewaart PDO zodat we user-queries kunnen doen.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * getAll()
     *
     * Doel:
     * Haalt alle users op voor het admin-overzicht.
     *
     * Werking:
     * - JOIN roles om role_name te tonen.
     * - Geen WHERE op email: dit is een lijst van alle users.
     */
    public function getAll(): array
    {
        $sql = "SELECT u.id, u.email, u.name, u.is_active, r.name AS role_name
                FROM users u
                JOIN roles r ON r.id = u.role_id
                ORDER BY u.id ASC";

        return $this->pdo->query($sql)->fetchAll();
    }

    /**
     * findByEmail()
     *
     * Doel:
     * Zoekt 1 actieve user op via email (login).
     *
     * Werking:
     * - prepared statement met :email
     * - AND u.is_active = 1 blokkeert gedeactiveerde users
     * - JOIN roles om role_name mee te geven
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.id, u.email, u.password_hash, u.name, u.is_active, r.name AS role_name
                FROM users u
                JOIN roles r ON r.id = u.role_id
                WHERE u.email = :email
                AND u.is_active = 1
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    /**
     * create()
     *
     * Doel:
     * Maakt een nieuwe user aan met gehasht wachtwoord.
     *
     * Werking:
     * - password_hash() maakt veilige hash
     * - INSERT in users tabel
     */
    public function create(string $email, string $name, string $plainPassword, int $roleId): void
    {
        $sql = "INSERT INTO users (email, name, password_hash, role_id, is_active)
                VALUES (:email, :name, :hash, :role_id, 1)";

        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'name' => $name,
            'hash' => $hash,
            'role_id' => $roleId,
        ]);
    }

    /**
     * disable()
     *
     * Doel:
     * Blokkeert een user door is_active op 0 te zetten.
     */
    public function disable(int $id): void
    {
        $sql = "UPDATE users
                SET is_active = 0
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
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
