<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-md mx-auto bg-white dark:bg-darkCard p-8 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">Anmelden</h2>
    <?php if(isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>
    <form action="/login" method="POST" class="space-y-4">
        <div>
            <label class="block mb-1 text-sm font-medium">Nutzername</label>
            <input type="text" name="username" required class="w-full p-2 rounded border dark:bg-gray-800 dark:border-gray-700">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium">Passwort</label>
            <input type="password" name="password" required class="w-full p-2 rounded border dark:bg-gray-800 dark:border-gray-700">
        </div>
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
            Einloggen
        </button>
    </form>
    <p class="mt-4 text-center text-sm">
        Noch kein Konto? <a href="/register" class="text-indigo-400 hover:underline">Registrieren</a>
    </p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>