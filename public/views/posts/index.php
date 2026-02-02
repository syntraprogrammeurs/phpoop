<?php
declare(strict_types=1);

/**
 * Verwacht variabelen:
 * - $posts (array) van getPublishedAll()
 */

// fallback afbeelding als er geen featured image is
$defaultImage = 'https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=1200&q=60';
?>

<section class="max-w-5xl mx-auto px-4 py-10">
    <header class="mb-8">
        <h1 class="text-3xl font-semibold">Alle posts</h1>
        <p class="text-gray-600 mt-2">Overzicht van alle gepubliceerde artikels.</p>
    </header>

    <?php if (empty($posts)): ?>
        <div class="bg-white border rounded p-6">
            <p class="text-gray-700">Er zijn nog geen gepubliceerde posts.</p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($posts as $post): ?>
                <?php
                $slug = htmlspecialchars((string)$post['slug'], ENT_QUOTES);
                $title = htmlspecialchars((string)$post['title'], ENT_QUOTES);

                $imageUrl = $post['featured_url'] !== null && $post['featured_url'] !== ''
                        ? (string)$post['featured_url']
                        : $defaultImage;

                $imageAlt = htmlspecialchars((string)($post['featured_alt'] ?? $post['title'] ?? 'Afbeelding'), ENT_QUOTES);
                ?>

                <article class="bg-white border rounded overflow-hidden flex flex-col md:flex-row">
                    <a href="/posts/<?php echo $slug; ?>" class="block md:w-64">
                        <img
                                src="<?php echo htmlspecialchars($imageUrl, ENT_QUOTES); ?>"
                                alt="<?php echo $imageAlt; ?>"
                                class="w-full h-48 md:h-full object-cover"
                                loading="lazy"
                        />
                    </a>

                    <div class="p-5 flex-1">
                        <h2 class="text-xl font-semibold mb-2">
                            <a href="/posts/<?php echo $slug; ?>" class="hover:underline">
                                <?php echo $title; ?>
                            </a>
                        </h2>

                        <p class="text-sm text-gray-500 mb-3">
                            <?php echo htmlspecialchars((string)($post['created_at'] ?? ''), ENT_QUOTES); ?>
                        </p>

                        <div>
                            <a href="/posts/<?php echo $slug; ?>" class="text-blue-700 hover:underline">
                                Lees meer
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
