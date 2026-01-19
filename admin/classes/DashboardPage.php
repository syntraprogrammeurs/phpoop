<?php
declare(strict_types=1);

class DashboardPage
{
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function render(): void
    {
        require __DIR__ . '/../pages/dashboard.php';
    }
}
