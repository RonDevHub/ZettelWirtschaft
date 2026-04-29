<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Meine Listen</h1>
        <a href="/lists/create" class="bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 transition">
            + Neue Liste
        </a>
    </div>

    <?php if (empty($lists)): ?>
        <div class="bg-white dark:bg-darkCard p-8 rounded-2xl shadow text-center">
            <p class="text-gray-500">Du hast noch keine Listen erstellt.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-4 md:grid-cols-2">
            <?php foreach ($lists as $list): ?>
                <div class="bg-white dark:bg-darkCard p-6 rounded-2xl shadow-sm border border-transparent hover:border-indigo-500 transition">
                    <h2 class="text-xl font-semibold mb-2"><?= htmlspecialchars($list['name']) ?></h2>
                    <p class="text-sm text-gray-500 mb-4">Erstellt am: <?= date('d.m.Y', strtotime($list['created_at'])) ?></p>
                    <a href="/lists/view?id=<?= $list['id'] ?>" class="text-indigo-600 font-medium hover:underline">Ansehen →</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>