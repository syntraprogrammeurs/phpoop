<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/autoload.php';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';

use Admin\Services\StatsService;
use Admin\Controllers\DashboardController;

$statsService = new StatsService();
$controller = new DashboardController($statsService);

$title = $controller->getTitle();
?>

    <main class="flex-1">
        <?php require __DIR__ . '/includes/topbar.php'; ?>
        <?php $controller->index(); ?>
    </main>

<?php
require __DIR__ . '/includes/footer.php';
