<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/autoload.php';

use Admin\Controllers\DashboardController;
use Admin\Models\StatsModel;

// 1) Controller bouwen (met dependency)
$controller = new DashboardController(new StatsModel());

// 2) Titel klaarzetten voor topbar include
$title = $controller->getTitle();

// 3) Layout laden
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';
?>
    <main class="flex-1">
        <?php require __DIR__ . '/includes/topbar.php'; ?>
        <?php $controller->index(); ?>
    </main>
<?php
require __DIR__ . '/includes/footer.php';
