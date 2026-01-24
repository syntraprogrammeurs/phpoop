<?php
use Admin\Core\Flash;

$flash = Flash::get();
?>

<?php if ($flash !== null): ?>
    <div class="mb-6 p-4 border rounded
        <?php echo $flash['type'] === 'error'
        ? 'border-red-200 bg-red-50 text-red-700'
        : 'border-green-200 bg-green-50 text-green-700'; ?>">
        <?php echo htmlspecialchars((string)$flash['message'], ENT_QUOTES); ?>
    </div>
<?php endif; ?>
