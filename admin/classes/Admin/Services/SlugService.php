<?php
declare(strict_types=1);

namespace Admin\Services;

final class SlugService
{
    public static function slugify(string $title): string
    {
        $title = trim($title);

        // 1) lowercase (multibyte)
        $title = mb_strtolower($title);

        // 2) accents verwijderen (best effort)
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title);
        if ($converted !== false) {
            $title = $converted;
        }

        // 3) alles wat geen letter/cijfer is -> "-"
        $replaced = preg_replace('/[^a-z0-9]+/i', '-', $title);
        if ($replaced !== null) {
            $title = $replaced;
        } else {
            $title = '';
        }

        // 4) trims "-" aan begin/einde
        $title = trim($title, '-');

        // 5) fallback
        if ($title === '') {
            return 'post';
        }

        return $title;
    }
}
