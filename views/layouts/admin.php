<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - CDB Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#1e3a5f', secondary: '#2c5282', accent: '#d69e2e' } } } }</script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-primary text-white fixed h-full overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center"><i class="fas fa-building text-white"></i></div>
                    <div><h1 class="font-bold">CDB Admin</h1><p class="text-xs text-gray-300">Management Panel</p></div>
                </div>
                <nav class="space-y-2">
                    <?php $current = basename($_SERVER['PHP_SELF']); ?>
                    <a href="/admin/index.php" class="flex items-center space-x-3 px-4 py-3 <?= $current=='index.php'?'bg-secondary':'hover:bg-secondary' ?> rounded-lg transition"><i class="fas fa-home w-5"></i><span>Dashboard</span></a>
                    <a href="/admin/projects.php" class="flex items-center space-x-3 px-4 py-3 <?= $current=='projects.php'?'bg-secondary':'hover:bg-secondary' ?> rounded-lg transition"><i class="fas fa-project-diagram w-5"></i><span>Projects</span></a>
                    <a href="/admin/quotations.php" class="flex items-center space-x-3 px-4 py-3 <?= $current=='quotations.php'?'bg-secondary':'hover:bg-secondary' ?> rounded-lg transition"><i class="fas fa-file-invoice-dollar w-5"></i><span>Quotations</span></a>
                    <a href="/admin/invoices.php" class="flex items-center space-x-3 px-4 py-3 <?= $current=='invoices.php'?'bg-secondary':'hover:bg-secondary' ?> rounded-lg transition"><i class="fas fa-file-invoice w-5"></i><span>Invoices</span></a>
                    <a href="/admin/blogs.php" class="flex items-center space-x-3 px-4 py-3 <?= $current=='blogs.php'?'bg-secondary':'hover:bg-secondary' ?> rounded-lg transition"><i class="fas fa-newspaper w-5"></i><span>Blog</span></a>
                    <hr class="border-gray-600 my-4">
                    <a href="/logout.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-red-600 rounded-lg transition text-red-300"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a>
                </nav>
            </div>
        </aside>

        <main class="flex-1 ml-64">
            <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-primary"><?= $pageTitle ?? 'Dashboard' ?></h2>
                <div class="flex items-center space-x-4"><span class="text-gray-600"><i class="fas fa-user-circle mr-2"></i><?= Auth::user()['name'] ?? 'Admin' ?></span></div>
            </header>
            <div class="p-8"><?= $content ?? '' ?></div>
        </main>
    </div>
</body>
</html>