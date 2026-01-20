<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Models\StatsModel;

class DashboardController
{
    private StatsModel $statsModel;
    private string $title = 'Dashboard';

    public function __construct(StatsModel $statsModel)
    {
        $this->statsModel = $statsModel;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function index(): void
    {
        $title = $this->getTitle();
        $stats = $this->statsModel->getStats();

        require __DIR__ . '/../../../views/dashboard.php';
    }
}
