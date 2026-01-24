<?php
declare(strict_types=1);

use Admin\Core\Flash;

/**
 * Haal een flash message op.
 * Doel:
 * - Toon success/error feedback na redirects (PRG pattern).
 * - De flash wordt automatisch gewist na het ophalen.
 */
$flash = Flash::get();
?>

<?php if ($flash !== null): ?>
    <div class="px-6 mt-4">
        <div class="p-4 rounded border
            <?php echo ($flash['type'] === 'error')
            ? 'border-red-200 bg-red-50 text-red-700'
            : 'border-green-200 bg-green-50 text-green-700'; ?>">
            <?php echo htmlspecialchars((string)$flash['message'], ENT_QUOTES); ?>
        </div>
    </div>
<?php endif; ?>
