<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?> - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#c53030', secondary: '#9b2c2c', accent: '#d69e2e' } } } }
    </script>
    <style>html { scroll-behavior: smooth; }</style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-primary fixed w-full z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center space-x-3">
                    <img src="/assets/images/logo.png" alt="Logo" class="h-10 w-auto">
                    <span class="text-white font-bold text-xl">CDB</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/#home" class="text-white hover:text-accent transition">Home</a>
                    <a href="/#about" class="text-white hover:text-accent transition">About</a>
                    <a href="/#projects" class="text-white hover:text-accent transition">Our Project</a>
                    <a href="/#blog" class="text-white hover:text-accent transition">Blog</a>
                    <a href="/#contact" class="text-white hover:text-accent transition">Contact Us</a>
                    <a href="/login.php" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition"><i class="fas fa-lock mr-2"></i>Login</a>
                </div>
                <button id="mobile-menu-btn" class="md:hidden text-white"><i class="fas fa-bars text-2xl"></i></button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-secondary">
            <div class="px-4 py-3 space-y-2">
                <a href="/#home" class="block text-white py-2">Home</a>
                <a href="/#about" class="block text-white py-2">About</a>
                <a href="/#projects" class="block text-white py-2">Our Project</a>
                <a href="/#blog" class="block text-white py-2">Blog</a>
                <a href="/#contact" class="block text-white py-2">Contact Us</a>
                <a href="/login.php" class="block bg-accent text-white px-4 py-2 rounded-lg text-center">Login</a>
            </div>
        </div>
    </nav>

    <main class="pt-16">
        <?= $content ?? '' ?>
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
        </div>
    </footer>

    <script>document.getElementById('mobile-menu-btn').addEventListener('click', () => document.getElementById('mobile-menu').classList.toggle('hidden'));</script>
</body>
</html>