<?php
$pageTitle = 'Home';
ob_start();
?>
<section id="home" class="min-h-screen flex items-center" style="background: linear-gradient(rgba(30,58,95,0.85), rgba(30,58,95,0.7)), url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920'); background-size: cover; background-position: center;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight"><?= APP_NAME ?></h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-4">General Construction & Automatic Door Specialist</p>
            <p class="text-lg text-gray-300 mb-8">Partner terpercaya Anda dalam konstruksi umum dan solusi pintu otomatis berkualitas tinggi. Kami menghadirkan inovasi Dormakaba untuk keamanan dan kenyamanan bangunan Anda.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#projects" class="bg-accent text-white px-8 py-4 rounded-lg font-semibold hover:bg-yellow-600 transition text-center">Lihat Project Kami</a>
                <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-center">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/about.php'; ?>
<?php require_once __DIR__ . '/projects.php'; ?>
<?php require_once __DIR__ . '/blog.php'; ?>
<?php require_once __DIR__ . '/contact.php'; ?>

<?php $content = ob_get_clean(); include __DIR__ . '/../layouts/landing.php'; ?>