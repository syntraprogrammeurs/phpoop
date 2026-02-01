<?php
declare(strict_types=1);

namespace Admin\Core;

final class Flash
{
    private const KEY = '_flash';

    /**
     * Slaat een flash value op.
     * Voorbeelden:
     * Flash::set('success', 'Post succesvol aangemaakt.');
     * Flash::set('errors', ['Title is verplicht.']);
     * Flash::set('old', ['title' => '...', 'content' => '...']);
     */
    public static function set(string $key, mixed $value): void
    {
        if (!isset($_SESSION[self::KEY]) || !is_array($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }

        $_SESSION[self::KEY][$key] = $value;
    }

    /**
     * Haalt een flash value op en verwijdert hem meteen.
     * Als de key niet bestaat, return null.
     */
    public static function get(string $key): mixed
    {
        if (!isset($_SESSION[self::KEY]) || !is_array($_SESSION[self::KEY])) {
            return null;
        }

        if (!array_key_exists($key, $_SESSION[self::KEY])) {
            return null;
        }

        $value = $_SESSION[self::KEY][$key];
        unset($_SESSION[self::KEY][$key]);

        if (empty($_SESSION[self::KEY])) {
            unset($_SESSION[self::KEY]);
        }

        return $value;
    }

    /**
     * Checkt of een flash key bestaat (zonder te verwijderen).
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[self::KEY])
            && is_array($_SESSION[self::KEY])
            && array_key_exists($key, $_SESSION[self::KEY]);
    }
}
