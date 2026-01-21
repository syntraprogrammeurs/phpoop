<?php
declare(strict_types=1);
?>

<section class="p-6">
    <div class="bg-white p-6 rounded shadow">
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
                    <td class="py-2">
                        <a class="underline" href="/minicms-pro/admin/posts/<?php echo (int)$post['id']; ?>">
                            <?php echo htmlspecialchars((string)$post['title'], ENT_QUOTES); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars((string)$post['date'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars((string)$post['status'], ENT_QUOTES); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</section>
