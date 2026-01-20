<?php
declare(strict_types=1);

namespace Admin\Models;

class PostsModel
{
    public function getAll(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Welkom',
                'status' => 'published',
                'date' => '2026-01-01',
            ],
            [
                'id' => 2,
                'title' => 'Tweede post',
                'status' => 'draft',
                'date' => '2026-01-05',
            ],
        ];
    }
}
