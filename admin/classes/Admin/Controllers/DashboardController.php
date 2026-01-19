<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Services\StatsService;

class DashboardController
{
    private StatsService $statsService;
    private string $title = 'Dashboard';

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function index(): void
    {
        $title = $this->getTitle();
        $stats = $this->statsService->getStats();

        require __DIR__ . '/../../../views/dashboard.php';
    }
}
