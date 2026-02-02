<?php
declare(strict_types=1);

$titleValue   = (string)($old['title'] ?? '');
$contentValue = (string)($old['content'] ?? '');
$statusValue  = (string)($old['status'] ?? 'draft');
?>

<section class="p-6">
    <div class="bg-white p-6 rounded shadow max-w-3xl">
        <h2 class="text-xl font-bold mb-6">Post bewerken</h2>

        <form method="post" action="/admin/posts/<?php echo (int)$postId; ?>/update" class="space-y-4">
            <div>
                <label class="block text-sm font-bold mb-1" for="title">Titel</label>
                <input
                        class="w-full border rounded p-2"
                        type="text"
                        id="title"
                        name="title"
                        value="<?php echo htmlspecialchars($titleValue, ENT_QUOTES); ?>"
                >
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="content">Inhoud</label>
                <textarea class="w-full border rounded p-2" id="content" name="content" rows="8"><?php
                    echo htmlspecialchars($contentValue, ENT_QUOTES);
                    ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="status">Status</label>
                <select class="w-full border rounded p-2" id="status" name="status">
                    <option value="draft" <?php echo ($statusValue === 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo ($statusValue === 'published') ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>

            <div class="flex gap-4">
                <button class="border rounded px-4 py-2" type="submit">Update</button>
                <a class="underline" href="/admin/posts">Terug</a>
            </div>
        </form>
    </div>
</section>
