<?php
require_once '../../includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: /#blog');
    exit;
}

$db = Database::getInstance()->getConnection();
$blog = $db->prepare("SELECT * FROM blogs WHERE id = ? AND status = 'published'");
$blog->execute([$_GET['id']]);
$blog = $blog->fetch();

if (!$blog) {
    header('Location: /#blog');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($blog['title']) ?> - Cahaya Dimensi Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#DC2626',
                        secondary: '#991B1B',
                        accent: '#FCA5A5',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-primary fixed w-full z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <img src="/assets/images/logo.svg" alt="CDB Logo" class="w-10 h-10 object-contain">
                        <span class="text-white font-bold text-xl">Cahaya Dimensi Bumi</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-white hover:text-accent transition">Home</a>
                    <a href="/#about" class="text-white hover:text-accent transition">About</a>
                    <a href="/#projects" class="text-white hover:text-accent transition">Our Project</a>
                    <a href="/#blog" class="text-white hover:text-accent transition">Blog</a>
                    <a href="/#contact" class="text-white hover:text-accent transition">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Blog Detail -->
    <div class="pt-24 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="/#blog" class="inline-flex items-center text-primary hover:text-secondary mb-6">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Blog
            </a>
            
            <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <?php if ($blog['image']): ?>
                <img src="<?= $blog['image'] ?>" alt="<?= sanitize($blog['title']) ?>" class="w-full h-96 object-cover">
                <?php else: ?>
                <div class="w-full h-96 bg-primary flex items-center justify-center">
                    <i class="fas fa-newspaper text-8xl text-white opacity-50"></i>
                </div>
                <?php endif; ?>
                
                <div class="p-8">
                    <div class="flex items-center text-gray-500 mb-4">
                        <i class="far fa-calendar mr-2"></i>
                        <span><?= formatDate($blog['created_at']) ?></span>
                    </div>
                    
                    <h1 class="text-4xl font-bold text-primary mb-6"><?= sanitize($blog['title']) ?></h1>
                    
                    <div class="prose max-w-none text-gray-600">
                        <?= nl2br(sanitize($blog['content'])) ?>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> PT Cahaya Dimensi Bumi. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
