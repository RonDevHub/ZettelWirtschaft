<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-md mx-auto bg-white dark:bg-darkCard p-8 rounded-2xl shadow-xl">
    <h1 class="text-2xl font-bold mb-6 text-center">Konto erstellen</h1>
    
    <form action="/register" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Benutzername</label>
            <input type="text" name="username" required class="w-full p-3 rounded-xl border dark:bg-gray-800 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Passwort</label>
            <input type="password" name="password" required class="w-full p-3 rounded-xl border dark:bg-gray-800 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition">
            Registrieren
        </button>
    </form>
    
    <p class="mt-6 text-center text-sm text-gray-500">
        Bereits ein Konto? <a href="/login" class="text-indigo-600 hover:underline">Hier anmelden</a>
    </p>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>