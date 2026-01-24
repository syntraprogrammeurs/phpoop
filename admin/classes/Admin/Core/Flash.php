<?php
declare(strict_types=1);

namespace Admin\Core;

class Flash
{
    /**
     * set()
     * Doel:
     * - Slaat een boodschap op in de session zodat je ze na een redirect kan tonen.
     */
    public static function set(string $message, string $type = 'success'): void
    {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    /**
     * get()
     * Doel:
     * - Haalt de boodschap op en verwijdert ze direct.
     * - Daardoor is een flash maar één keer zichtbaar.
     */
    public static function get(): ?array
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }
}
