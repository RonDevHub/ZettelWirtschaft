<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">
    <h1 class="text-2xl font-bold">Admin-Bereich</h1>

    <!-- Kategorien Verwaltung -->
    <section class="bg-white dark:bg-darkCard p-6 rounded-xl shadow-sm">
        <h2 class="text-lg font-bold mb-4">Kategorien verwalten</h2>
        <form action="/admin/category/add" method="POST" class="flex gap-2 mb-6">
            <input type="text" name="name" placeholder="Kategoriename" required class="flex-1 p-2 rounded border dark:bg-gray-800 dark:border-gray-700">
            <input type="number" name="order" placeholder="Sortierung" class="w-24 p-2 rounded border dark:bg-gray-800 dark:border-gray-700">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Hinzufügen</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b dark:border-gray-700">
                        <th class="py-2">Name</th>
                        <th class="py-2">Reihenfolge</th>
                        <th class="py-2 text-right">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $cat): ?>
                    <tr class="border-b dark:border-gray-700">
                        <td class="py-2"><?= htmlspecialchars($cat['name']) ?></td>
                        <td class="py-2"><?= $cat['default_order'] ?></td>
                        <td class="py-2 text-right">
                            <a href="/admin/category/delete?id=<?= $cat['id'] ?>" class="text-red-500 hover:underline">Löschen</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>