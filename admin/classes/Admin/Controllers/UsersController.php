<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Models\UsersModel;

class UsersController
{
    private UsersModel $usersModel;
    private string $title = 'Users';

    public function __construct(UsersModel $usersModel)
    {
        $this->usersModel = $usersModel;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function index(): void
    {
        $title = $this->getTitle();
        $users = $this->usersModel->getAllUsers();

        require __DIR__ . '/../../../views/users.php';
    }
}
