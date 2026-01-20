<?php

namespace Admin\Models;

class StatsModel
{
    public function getStats(): array
    {
        return [
            'posts' => 12,
            'users' => 3,
            'views' => 1245, 'comments' => 55,
        ];
    }
}