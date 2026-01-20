<?php
declare(strict_types=1);
?>

<section class="p-6">
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-4">Posts overzicht</h2>

        <table class="w-full text-sm">
            <thead>
            <tr class="text-left border-b">
                <th class="py-2">Titel</th>
                <th>Datum</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($posts as $post): ?>
                <tr class="border-b">
                    <td class="py-2"><?php echo htmlspecialchars($post['title'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($post['date'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($post['status'], ENT_QUOTES); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
