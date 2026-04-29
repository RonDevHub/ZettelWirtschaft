<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-md mx-auto bg-white dark:bg-darkCard p-8 rounded-2xl shadow-xl">
    <h1 class="text-2xl font-bold mb-6 text-center">Anmelden</h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <form action="/login" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Benutzername</label>
            <input type="text" name="username" required class="w-full p-3 rounded-xl border dark:bg-gray-800 dark:border-gray-700 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Passwort</label>
            <input type="password" name="password" required class="w-full p-3 rounded-xl border dark:bg-gray-800 dark:border-gray-700 outline-none">
        </div>
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition">
            Einloggen
        </button>
    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>