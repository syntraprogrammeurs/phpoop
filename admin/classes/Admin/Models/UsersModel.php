<?php
declare(strict_types=1);

namespace Admin\Models;

class UsersModel
{
    public function getAllUsers(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Tom Vanhoutte',
                'email' => 'tom@minicms.be',
                'role' => 'admin',
                'status' => 'active',
                'last_login' => '2026-01-18 09:42:00',
                'created_at' => '2025-11-10 14:22:00',
            ],
            [
                'id' => 2,
                'name' => 'Sofie De Smet',
                'email' => 'sofie@minicms.be',
                'role' => 'editor',
                'status' => 'active',
                'last_login' => '2026-01-17 20:11:00',
                'created_at' => '2025-11-20 10:05:00',
            ],
            [
                'id' => 3,
                'name' => 'Jens Peeters',
                'email' => 'jens@minicms.be',
                'role' => 'author',
                'status' => 'active',
                'last_login' => '2026-01-15 08:33:00',
                'created_at' => '2025-12-01 16:48:00',
            ],
            [
                'id' => 4,
                'name' => 'Laura Vermeulen',
                'email' => 'laura@minicms.be',
                'role' => 'author',
                'status' => 'inactive',
                'last_login' => null,
                'created_at' => '2025-12-12 11:19:00',
            ],
            [
                'id' => 5,
                'name' => 'Pieter Janssens',
                'email' => 'pieter@minicms.be',
                'role' => 'viewer',
                'status' => 'active',
                'last_login' => '2026-01-19 07:55:00',
                'created_at' => '2026-01-02 09:00:00',
            ],
        ];
    }

}
