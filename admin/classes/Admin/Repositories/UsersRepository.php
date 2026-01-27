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
     * findByEmail()
     *
     * Doel:
     * Haalt een user op via email.
     *
     * Werking:
     * 1) Prepared SELECT met :email
     * 2) execute() met parameter
     * 3) fetch() -> user array of null
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT id, email, password_hash, name, role
                FROM users
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user === false ?null : $user;
    }

    /**
     * make()
     *
     * Doel:
     * Factory method om repository te maken met standaard connectie.
     */
    public static function make(): self
    {
        return new self(Database::getConnection());
    }
}
