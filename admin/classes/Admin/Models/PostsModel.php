<?php
declare(strict_types=1);

namespace Admin\Models;

class PostsModel
{
    /**
     * $posts
     *
     * Doel:
     * Dummy data die we later vervangen door database.
     * De keys blijven consistent zodat de views nooit warnings krijgen.
     */
    private array $posts = [
        [
            'id' => 1,
            'title' => 'Welkom',
            'content' => 'Welkom bij MiniCMS',
            'status' => 'published',
            'date' => '2026-01-01',
        ],
        [
            'id' => 2,
            'title' => 'Tweede post',
            'content' => 'Dit is de tweede post',
            'status' => 'draft',
            'date' => '2026-01-05',
        ],
    ];

    /**
     * getAll()
     *
     * Doel:
     * Geeft alle posts terug voor het overzicht.
     */
    public function getAll(): array
    {
        return $this->posts;
    }

    /**
     * find()
     *
     * Doel:
     * Zoekt één post op basis van ID.
     *
     * Return:
     * - array als de post gevonden is
     * - null als de post niet bestaat
     */
    public function find(int $id): ?array
    {
        foreach ($this->posts as $post) {
            if ($post['id'] === $id) {
                return $post;
            }
        }

        return null;
    }
}
