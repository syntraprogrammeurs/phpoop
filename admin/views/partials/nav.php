<?php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$uri = rtrim($uri, '/') ?: '/';

function isActive(string $uri, string $target): bool
{
    // exact match
    if ($uri === $target) {
        return true;
    }

    // subroutes matchen: /admin/posts/..., /admin/users/...
    if ($target !== '/admin' && str_starts_with($uri, $target . '/')) {
        return true;
    }

    return false;
}

function navLinkClass(bool $active): string
{
    return $active
        ? 'bg-slate-800 text-white'
        : 'text-slate-300 hover:bg-slate-800 hover:text-white';
}
?>
