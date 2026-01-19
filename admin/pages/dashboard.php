<?php
    $stats = $page->getStats();
?>
<section class="p-6">

    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <p class="text-gray-500 text-sm">Posts</p>
            <p class="text-2xl font-bold"><?php echo $stats['posts']; ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <p class="text-gray-500 text-sm">Users</p>
            <p class="text-2xl font-bold"><?php echo $stats['users']; ?></p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <p class="text-gray-500 text-sm">Views</p>
            <p class="text-2xl font-bold"><?php echo $stats['views']; ?></p>
        </div>

    </div>

    <div class="bg-white p-6 rounded shadow mb-8">
        <h3 class="font-bold mb-4">Bezoekers</h3>
        <div id="chart"></div>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h3 class="font-bold mb-4">Laatste posts</h3>
        <table class="w-full text-sm">
            <thead>
            <tr class="text-left border-b">
                <th class="py-2">Titel</th>
                <th>Datum</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr class="border-b">
                <td class="py-2">Welkom</td>
                <td>2026-01-01</td>
                <td>Published</td>
            </tr>
            <tr>
                <td class="py-2">Tweede post</td>
                <td>2026-01-05</td>
                <td>Draft</td>
            </tr>
            </tbody>
        </table>
    </div>

</section>

