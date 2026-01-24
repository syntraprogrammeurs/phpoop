<?php
declare(strict_types=1);

namespace Admin\Core;

class Auth
{
    /**
     * check()
     *
     * Doel:
     * Controleert of er een user is ingelogd.
     *
     * Werking:
     * - Checkt of $_SESSION['user_id'] bestaat.
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * userId()
     *
     * Doel:
     * Geeft het id van de ingelogde gebruiker terug.
     */
    public static function userId(): ?int
    {
        return isset($_SESSION['user_id'])
            ? (int)$_SESSION['user_id']
            : null;
    }

    /**
     * logout()
     *
     * Doel:
     * Logt de gebruiker uit.
     *
     * Werking:
     * - Verwijdert user_id uit session.
     * - Vernietigt sessie.
     */
    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_destroy();
    }
}
