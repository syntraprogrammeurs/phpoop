<?php
declare(strict_types=1);

namespace Admin\Pages;

use Admin\Services\StatsService;

class DashboardPage
{
    private string $title;
    private StatsService $statsService;
//injectie door de constructor
    public function __construct(string $title, StatsService $statsService) {
        $this->title = $title;
        $this->statsService = $statsService;
    }

    public function getStats(): array
    {
        return $this->statsService->getStats();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function render(): void
    {
        $page = $this; //maak page beschikbaar in de scope van de view
        require __DIR__ . '/../../../pages/dashboard.php';
    }
}
