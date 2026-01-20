<?php declare(strict_types=1); ?>

<section class="p-6">
    <h1 class="text-2xl font-bold mb-6">Users overzicht</h1>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
            <tr>
                <th class="p-4">Naam</th>
                <th class="p-4">Email</th>
                <th class="p-4">Rol</th>
                <th class="p-4">Status</th>
                <th class="p-4">Laatste login</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr class="border-t">
                    <td class="p-4"><?= htmlspecialchars($user['name'], ENT_QUOTES) ?></td>
                    <td class="p-4"><?= htmlspecialchars($user['email'], ENT_QUOTES) ?></td>
                    <td class="p-4"><?= htmlspecialchars($user['role'], ENT_QUOTES) ?></td>
                    <td class="p-4"><?= htmlspecialchars($user['status'], ENT_QUOTES) ?></td>
                    <td class="p-4">
                        <?= $user['last_login'] ? htmlspecialchars($user['last_login'], ENT_QUOTES) : '-' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
