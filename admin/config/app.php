<?php
declare(strict_types=1);

/**
 * admin/config/app.php
 *
 * Centrale app-config voor de admin (URLs, base path, etc.)
 * Zo vermijden we hardcoded "/admin
" in controllers en views.
 */

// Admin hangt onder dit pad op het domein
// Voor vhost:  /admin
// Voor subfolder: /admin

define('ADMIN_BASE_PATH', '/admin');
