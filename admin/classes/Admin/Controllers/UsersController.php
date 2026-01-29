<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\Auth;
use Admin\Core\Flash;
use Admin\Core\View;
use Admin\Repositories\RolesRepository;
use Admin\Repositories\UsersRepository;

class UsersController
{
    private UsersRepository $users;
    private RolesRepository $roles;

    /**
     * __construct()
     *
     * Doel:
     * Injecteert repositories voor users en roles.
     */
    public function __construct(UsersRepository $users, RolesRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    /**
     * index()
     *
     * Doel:
     * Toont het overzicht van users (admin-only).
     */
    public function index(): void
    {
        if (!Auth::isAdmin()) {
            header('Location: /minicms/admin');
            exit;
        }

        View::render('users.php', [
            'title' => 'Gebruikers',
            'users' => $this->users->getAll(),
        ]);
    }

    /**
     * create()
     *
     * Doel:
     * Toont het formulier om een user aan te maken (admin-only).
     */
    public function create(): void
    {
        if (!Auth::isAdmin()) {
            header('Location: /minicms/admin');
            exit;
        }

        View::render('user-create.php', [
            'title' => 'Nieuwe gebruiker',
            'roles' => $this->roles->getAll(),
            'errors' => [],
            'old' => [
                'email' => '',
                'name' => '',
                'role_id' => '',
            ],
        ]);
    }

    /**
     * store()
     *
     * Doel:
     * Verwerkt het create user formulier en maakt de user aan (admin-only).
     */
    public function store(): void
    {
        if (!Auth::isAdmin()) {
            header('Location: /minicms/admin');
            exit;
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $name = trim((string)($_POST['name'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $roleId = (int)($_POST['role_id'] ?? 0);

        $errors = [];

        if ($email === '') {
            $errors[] = 'Email is verplicht.';
        }

        if ($name === '') {
            $errors[] = 'Naam is verplicht.';
        }

        if ($password === '') {
            $errors[] = 'Wachtwoord is verplicht.';
        }

        if ($roleId <= 0) {
            $errors[] = 'Kies een rol.';
        }

        if (!empty($errors)) {
            View::render('user-create.php', [
                'title' => 'Nieuwe gebruiker',
                'roles' => $this->roles->getAll(),
                'errors' => $errors,
                'old' => [
                    'email' => $email,
                    'name' => $name,
                    'role_id' => (string)$roleId,
                ],
            ]);
            return;
        }

        $this->users->create($email, $name, $password, $roleId);

        Flash::set('Gebruiker aangemaakt.');

        header('Location: /minicms/admin/users');
        exit;
    }

    /**
     * disable()
     *
     * Doel:
     * Blokkeert een user (admin-only).
     */
    public function disable(int $id): void
    {
        if (!Auth::isAdmin()) {
            header('Location: /minicms/admin');
            exit;
        }

        $this->users->disable($id);

        Flash::set('Gebruiker geblokkeerd.', 'success');

        header('Location: /minicms/admin/users');
        exit;
    }
}
