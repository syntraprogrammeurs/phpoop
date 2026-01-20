<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\View;

class ErrorController
{
    /**
     * notFound()
     *
     * Doel:
     * Toont een nette 404 pagina binnen de vaste layout.
     *
     * Werking:
     * 1) Zet de HTTP status code naar 404.
     * 2) Rendert de 404 view via View::render().
     * 3) Geeft optioneel extra info door, zoals de opgevraagde URI.
     */
    public function notFound(string $requestedUri = ''): void
    {
        http_response_code(404);

        View::render('errors/404.php', [
            'title' => '404 - Pagina niet gevonden',
            'requestedUri' => $requestedUri,
        ]);
    }
}
