<?php
require_once '../../includes/functions.php';
Auth::requireAuth();

$db = Database::getInstance()->getConnection();

$stats = [
    'projects' => $db->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
    'quotations' => $db->query("SELECT COUNT(*) FROM quotations")->fetchColumn(),
    'invoices' => $db->query("SELECT COUNT(*) FROM invoices")->fetchColumn(),
    'blogs' => $db->query("SELECT COUNT(*) FROM blogs WHERE status='published'")->fetchColumn(),
];

$recentProjects = $db->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentQuotations = $db->query("SELECT * FROM quotations ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentInvoices = $db->query("SELECT * FROM invoices ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CDB Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#1e3a5f', secondary: '#2c5282', accent: '#d69e2e' } } } }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary text-white fixed h-full overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-white"></i>
                    </div>
                    <div>
                        <h1 class="font-bold">CDB Admin</h1>
                        <p class="text-xs text-gray-300">Management Panel</p>
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="index.php" class="flex items-center space-x-3 px-4 py-3 bg-secondary rounded-lg">
                        <i class="fas fa-home w-5"></i><span>Dashboard</span>
                    </a>
                    <a href="projects.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-project-diagram w-5"></i><span>Projects</span>
                    </a>
                    <a href="quotations.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-file-invoice-dollar w-5"></i><span>Quotations</span>
                    </a>
                    <a href="invoices.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-file-invoice w-5"></i><span>Invoices</span>
                    </a>
                    <a href="blogs.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-secondary rounded-lg transition">
                        <i class="fas fa-newspaper w-5"></i><span>Blog</span>
                    </a>
                    <hr class="border-gray-600 my-4">
                    <a href="../logout.php" class="flex items-center space-x-3 px-4 py-3 hover:bg-red-600 rounded-lg transition text-red-300 hover:text-white">
                        <i class="fas fa-sign-out-alt w-5"></i><span>Logout</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-primary">Dashboard</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">
                        <i class="fas fa-user-circle mr-2"></i><?= Auth::user()['name'] ?>
                    </span>
                </div>
            </header>

            <div class="p-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Projects</p>
                                <p class="text-3xl font-bold text-primary"><?= $stats['projects'] ?></p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-project-diagram text-primary text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Quotations</p>
                                <p class="text-3xl font-bold text-primary"><?= $stats['quotations'] ?></p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-file-invoice-dollar text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Invoices</p>
                                <p class="text-3xl font-bold text-primary"><?= $stats['invoices'] ?></p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-lg">
                                <i class="fas fa-file-invoice text-accent text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Published Blogs</p>
                                <p class="text-3xl font-bold text-primary"><?= $stats['blogs'] ?></p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-newspaper text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Recent Projects -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-bold text-primary mb-4">Recent Projects</h3>
                        <?php if (count($recentProjects) > 0): ?>
                        <div class="space-y-3">
                            <?php foreach ($recentProjects as $p): ?>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate"><?= sanitize($p['company_name']) ?></p>
                                    <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i><?= sanitize($p['location']) ?></p>
                                </div>
                                <span class="text-xs text-gray-400"><?= formatDate($p['created_at']) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-gray-400 text-center py-4">Belum ada project</p>
                        <?php endif; ?>
                    </div>

                    <!-- Recent Quotations -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-bold text-primary mb-4">Recent Quotations</h3>
                        <?php if (count($recentQuotations) > 0): ?>
                        <div class="space-y-3">
                            <?php foreach ($recentQuotations as $q): ?>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-invoice-dollar text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate"><?= sanitize($q['quotation_number']) ?></p>
                                    <p class="text-sm text-gray-500"><?= sanitize($q['company_name']) ?></p>
                                </div>
                                <span class="text-xs text-gray-400"><?= formatDate($q['created_at']) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-gray-400 text-center py-4">Belum ada quotation</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>