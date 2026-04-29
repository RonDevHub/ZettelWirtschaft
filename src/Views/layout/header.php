<!DOCTYPE html>
<html lang="de" class="<?= $_SESSION['theme'] ?? 'dark' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZettelWirtschaft</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        darkBg: '#1a1a1a',
                        darkCard: '#2d2d2d'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-darkBg text-gray-900 dark:text-gray-100 min-h-screen">
<nav class="bg-white dark:bg-darkCard shadow-md p-4 mb-6">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/dashboard" class="text-xl font-bold text-indigo-600 dark:text-indigo-400">ZettelWirtschaft</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="flex items-center gap-4">
                <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="/logout" class="text-sm bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded transition">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<main class="container mx-auto px-4">