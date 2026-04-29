<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="flex flex-col gap-6" id="shopping-list-container" data-list-id="<?= $list['id'] ?>">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold"><?= htmlspecialchars($list['name']) ?></h1>
        <button onclick="document.getElementById('add-item-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-full shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>

    <?php foreach ($categories as $catId => $cat): ?>
        <div class="category-section mb-4" data-category-id="<?= $catId ?>">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                <?= htmlspecialchars($cat['name']) ?>
            </h3>
            <div class="space-y-2">
                <?php foreach ($cat['items'] as $item): ?>
                    <div id="item-<?= $item['id'] ?>" class="flex items-center justify-between p-4 bg-white dark:bg-darkCard rounded-lg shadow-sm border-l-4 <?= $item['is_checked'] ? 'border-green-500 opacity-50' : 'border-indigo-500' ?>">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" 
                                   <?= $item['is_checked'] ? 'checked' : '' ?> 
                                   onchange="toggleItem(<?= $item['id'] ?>)"
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="<?= $item['is_checked'] ? 'line-through' : '' ?> font-medium">
                                <?= htmlspecialchars($item['name']) ?> 
                                <span class="text-sm text-gray-400 ml-2"><?= htmlspecialchars($item['amount']) ?></span>
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400"><?= $item['total_price'] > 0 ? number_format($item['total_price'], 2, ',', '.') . ' €' : '' ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal: Produkt hinzufügen -->
<div id="add-item-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white dark:bg-darkCard w-full max-w-md p-6 rounded-xl shadow-2xl">
        <h2 class="text-xl font-bold mb-4">Produkt hinzufügen</h2>
        <form action="/item/add" method="POST" class="space-y-4">
            <input type="hidden" name="list_id" value="<?= $list['id'] ?>">
            <div>
                <label class="block text-sm mb-1">Produktname</label>
                <input type="text" name="name" required autofocus class="w-full p-2 rounded border dark:bg-gray-800 dark:border-gray-700">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1">Menge</label>
                    <input type="text" name="amount" placeholder="z.B. 2er Pack" class="w-full p-2 rounded border dark:bg-gray-800 dark:border-gray-700">
                </div>
                <div>
                    <label class="block text-sm mb-1">Kategorie</label>
                    <select name="category_id" class="w-full p-2 rounded border dark:bg-gray-800 dark:border-gray-700">
                        <?php foreach($allCategories as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="document.getElementById('add-item-modal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded">Abbrechen</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded font-bold">Speichern</button>
            </div>
        </form>
    </div>
</div>

<script>
// Mercure Echtzeit-Anbindung
const listId = document.getElementById('shopping-list-container').dataset.listId;
const eventSource = new EventSource("<?= Config::get('MERCURE_PUBLIC_URL') ?>?topic=" + encodeURIComponent("http://zettelwirtschaft.local/list/" + listId));

eventSource.onmessage = event => {
    const data = JSON.parse(event.data);
    if (data.action === 'item_toggled' || data.action === 'item_added') {
        location.reload(); // Einfachste Lösung für konsistente Daten ohne komplexes DOM-JS
    }
};

function toggleItem(id) {
    fetch('/item/toggle?ajax=1&id=' + id)
        .then(response => response.json())
        .then(data => {
            if(!data.success) alert('Fehler beim Speichern');
        });
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>