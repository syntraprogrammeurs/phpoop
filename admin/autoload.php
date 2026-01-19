<?php
declare(strict_types=1);

spl_autoload_register(function (string $class) {

    // Basis map waar alle classes zitten
    $baseDir = __DIR__ . '/classes/';

    // Namespace omzetten naar mapstructuur
    $relativePath = str_replace('\\', '/', $class) . '.php';

    // Volledig pad opbouwen
    $file = $baseDir . $relativePath;

    // Bestand laden als het bestaat
    if (file_exists($file)) {
        require $file;
    }
});

