<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';

require __DIR__ . '/classes/Admin/Services/StatsService.php';
require __DIR__ . '/classes/Admin/Pages/DashboardPage.php';

use Admin\Services\StatsService;
use Admin\Pages\DashboardPage;

$statsService = new StatsService();
$page = new DashboardPage('PPPP Dashboard',$statsService);

?>

    <main class="flex-1">
        <?php require __DIR__ . '/includes/topbar.php'; ?>
        <?php $page->render(); ?>
    </main>

<?php
require __DIR__ . '/includes/footer.php';
