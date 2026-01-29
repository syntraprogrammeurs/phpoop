<aside class="w-64 bg-gray-900 text-white p-6">
    <h2 class="text-xl font-bold mb-6">MiniCMS Pro</h2>

    <nav class="space-y-3">
        <a href="/admin" class="block text-gray-300 hover:text-white">
            Dashboard
        </a>
        <a href="/admin/posts" class="block text-gray-300 hover:text-white">
            Posts
        </a>
        <a href="/admin/posts/create" class="block text-gray-300 hover:text-white">
            Create Post
        </a>
        <form method="post" action="/admin/logout">
            <button type="submit" class="underline">
                Logout
            </button>
        </form>

    </nav>
</aside>
