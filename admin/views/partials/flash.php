<?php
declare(strict_types=1);

use Admin\Core\Flash;

$success = Flash::get('success'); // string|null
$errors  = Flash::get('errors');  // array|null
?>

<?php if (is_string($success) && $success !== ''): ?>
    <div class="mx-6 mt-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-green-800">
        <?php echo htmlspecialchars($success, ENT_QUOTES); ?>
    </div>
<?php endif; ?>

<?php if (is_array($errors) && !empty($errors)): ?>
    <div class="mx-6 mt-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-red-800">
        <p class="font-bold mb-2">Controleer je invoer:</p>
        <ul class="list-disc pl-6">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars((string)$error, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
