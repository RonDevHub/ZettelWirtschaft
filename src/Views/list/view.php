<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="flex flex-col gap-6" id="list-app" data-list-id="<?= $list['id'] ?>">
    <div class="flex justify-between items-center bg-white dark:bg-darkCard p-4 rounded-xl shadow-sm">
        <div>
            <h1 class="text-xl font-bold"><?= htmlspecialchars($list['name']) ?></h1>
            <p class="text-sm text-green-500 font-mono">Gesamt: <?= number_format($totalSum, 2, ',', '.') ?> €</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openModal('deposit-modal')" class="bg-yellow-600 text-white p-2 rounded-lg text-sm">Pfand</button>
            <button onclick="openModal('add-item-modal')" class="bg-indigo-600 text-white p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4v16m8-8H4"/></svg>
            </button>
        </div>
    </div>

    <?php foreach ($categories as $catId => $cat): ?>
        <section class="mb-4">
            <h3 class="text-xs font-bold text-gray-500 uppercase mb-2 ml-2"><?= htmlspecialchars($cat['name']) ?></h3>
            <div class="grid gap-2">
                <?php foreach ($cat['items'] as $item): ?>
                    <div class="flex items-center justify-between p-4 bg-white dark:bg-darkCard rounded-xl shadow-sm border-l-4 <?= $item['is_checked'] ? 'border-green-500 opacity-60' : 'border-indigo-500' ?>">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" <?= $item['is_checked'] ? 'checked' : '' ?> 
                                   onchange="<?= $item['is_checked'] ? "location.href='/item/toggle?id=".$item['id']."'" : "openCalcModal(".$item['id'].", '".$item['name']."')" ?>"
                                   class="w-6 h-6 rounded-full border-gray-300 text-indigo-600">
                            <span class="<?= $item['is_checked'] ? 'line-through text-gray-400' : 'font-medium' ?>">
                                <?= htmlspecialchars($item['name']) ?>
                                <?php if($item['amount']): ?><span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded ml-2"><?= htmlspecialchars($item['amount']) ?></span><?php endif; ?>
                            </span>
                        </div>
                        <div class="text-right font-mono text-sm">
                            <?= $item['total_price'] != 0 ? number_format($item['total_price'], 2, ',', '.') . ' €' : '' ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>

<!-- Modal: Taschenrechner -->
<div id="calc-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white dark:bg-darkCard w-full max-w-sm p-6 rounded-2xl shadow-2xl">
        <h2 id="calc-item-name" class="text-lg font-bold mb-4">Preis eingeben</h2>
        <form action="/item/update-price" method="POST">
            <input type="hidden" name="item_id" id="calc-item-id">
            <input type="hidden" name="list_id" value="<?= $list['id'] ?>">
            <div class="mb-4">
                <label class="block text-xs uppercase text-gray-500 mb-1">Preis oder Formel (z.B. 0,39+0,25)</label>
                <input type="text" name="price_formula" id="calc-input" autofocus class="w-full text-2xl p-3 rounded-xl border dark:bg-gray-800 dark:border-gray-700 font-mono" placeholder="0,00">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('calc-modal')" class="flex-1 py-3 bg-gray-100 dark:bg-gray-700 rounded-xl">Abbrechen</button>
                <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-bold">OK & Abhaken</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Pfand -->
<div id="deposit-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white dark:bg-darkCard w-full max-w-sm p-6 rounded-2xl shadow-2xl">
        <h2 class="text-lg font-bold mb-4">Pfandbon abziehen</h2>
        <form action="/item/add-deposit" method="POST">
            <input type="hidden" name="list_id" value="<?= $list['id'] ?>">
            <input type="text" name="deposit_amount" placeholder="Betrag (z.B. 2,50)" class="w-full text-2xl p-3 rounded-xl border dark:bg-gray-800 dark:border-gray-700 font-mono mb-4">
            <button type="submit" class="w-full py-3 bg-yellow-600 text-white rounded-xl font-bold">Abrechnen</button>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function openCalcModal(id, name) {
    document.getElementById('calc-item-id').value = id;
    document.getElementById('calc-item-name').innerText = name;
    openModal('calc-modal');
    setTimeout(() => document.getElementById('calc-input').focus(), 100);
}

// Mercure Integration
const listId = document.getElementById('list-app').dataset.listId;
const eventSource = new EventSource("<?= Config::get('MERCURE_PUBLIC_URL') ?>?topic=" + encodeURIComponent("http://zettelwirtschaft.local/list/" + listId));
eventSource.onmessage = () => location.reload();
</script>

// In der view.php ergänzen:
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const el = document.getElementById('shopping-list-container');
    new Sortable(el, {
        animation: 150,
        handle: '.category-header', // Wir fügen einen Griff hinzu
        onEnd: function() {
            let order = [];
            document.querySelectorAll('.category-section').forEach((section, index) => {
                order.push({ id: section.dataset.categoryId, pos: index });
            });
            // Hier könnte ein AJAX Call hin, um die Sortierung pro Liste zu speichern
            console.log("Neue Sortierung:", order);
        }
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>