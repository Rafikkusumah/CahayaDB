<?php
require_once '../includes/functions.php';
$db = Database::getInstance()->getConnection();

// Get recent projects
$projects = $db->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Get recent blogs
$blogs = $db->query("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cahaya Dimensi Bumi - General Construction & Automatic Doors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a5f',
                        secondary: '#2c5282',
                        accent: '#d69e2e',
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .hero-bg {
            background: linear-gradient(rgba(30, 58, 95, 0.85), rgba(30, 58, 95, 0.7)), url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1920');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-primary fixed w-full z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-white text-lg"></i>
                        </div>
                        <span class="text-white font-bold text-xl">CDB</span>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-white hover:text-accent transition">Home</a>
                    <a href="#about" class="text-white hover:text-accent transition">About</a>
                    <a href="#projects" class="text-white hover:text-accent transition">Our Project</a>
                    <a href="#blog" class="text-white hover:text-accent transition">Blog</a>
                    <a href="#contact" class="text-white hover:text-accent transition">Contact Us</a>
                    <a href="login.php" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-lock mr-2"></i>Login
                    </a>
                </div>

                <button id="mobile-menu-btn" class="md:hidden text-white">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-secondary">
            <div class="px-4 py-3 space-y-2">
                <a href="#home" class="block text-white py-2 hover:text-accent">Home</a>
                <a href="#about" class="block text-white py-2 hover:text-accent">About</a>
                <a href="#projects" class="block text-white py-2 hover:text-accent">Our Project</a>
                <a href="#blog" class="block text-white py-2 hover:text-accent">Blog</a>
                <a href="#contact" class="block text-white py-2 hover:text-accent">Contact Us</a>
                <a href="login.php" class="block bg-accent text-white px-4 py-2 rounded-lg text-center">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-bg min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    Cahaya Dimensi Bumi
                </h1>
                <p class="text-xl md:text-2xl text-gray-200 mb-4">
                    General Construction & Automatic Door Specialist
                </p>
                <p class="text-lg text-gray-300 mb-8">
                    Partner terpercaya Anda dalam konstruksi umum dan solusi pintu otomatis berkualitas tinggi. 
                    Kami menghadirkan inovasi Dormakaba untuk keamanan dan kenyamanan bangunan Anda.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#projects" class="bg-accent text-white px-8 py-4 rounded-lg font-semibold hover:bg-yellow-600 transition text-center">
                        Lihat Project Kami
                    </a>
                    <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition text-center">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-primary mb-6">Tentang Kami</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        <strong>PT Cahaya Dimensi Bumi</strong> adalah perusahaan yang berfokus pada bidang 
                        <strong>General Construction</strong> dan spesialisasi dalam instalasi serta pemeliharaan 
                        <strong>pintu otomatis Dormakaba</strong>.
                    </p>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Dengan pengalaman bertahun-tahun dan tim profesional, kami berkomitmen memberikan 
                        solusi terbaik untuk kebutuhan konstruksi dan akses kontrol bangunan Anda.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <i class="fas fa-hard-hat text-3xl text-primary mb-2"></i>
                            <h3 class="font-bold text-primary">General Construction</h3>
                            <p class="text-sm text-gray-600">Konstruksi bangunan komersial & residensial</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <i class="fas fa-door-open text-3xl text-primary mb-2"></i>
                            <h3 class="font-bold text-primary">Automatic Doors</h3>
                            <p class="text-sm text-gray-600">Spesialis Dormakaba authorized dealer</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=600" alt="Construction" class="rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-6 -left-6 bg-accent text-white p-6 rounded-xl shadow-lg">
                        <p class="text-3xl font-bold">10+</p>
                        <p class="text-sm">Tahun Pengalaman</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Projects Section -->
    <section id="projects" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Recent Projects</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Beberapa project terbaru yang telah kami selesaikan dengan profesional</p>
            </div>

            <?php if (count($projects) > 0): ?>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach ($projects as $project): ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group">
                    <div class="relative overflow-hidden h-64">
                        <?php if ($project['main_photo']): ?>
                        <img src="<?= $project['main_photo'] ?>" alt="<?= sanitize($project['company_name']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <?php else: ?>
                        <div class="w-full h-full bg-primary flex items-center justify-center">
                            <i class="fas fa-building text-6xl text-white opacity-50"></i>
                        </div>
                        <?php endif; ?>
                        <div class="absolute top-4 right-4 bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Completed
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-primary mb-2"><?= sanitize($project['company_name']) ?></h3>
                        <p class="text-gray-500 mb-3"><i class="fas fa-map-marker-alt mr-2"></i><?= sanitize($project['location']) ?></p>
                        <p class="text-gray-600 text-sm line-clamp-3"><?= sanitize($project['description']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada project yang ditampilkan</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Blog Section -->
    <section id="blog" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Blog & Informasi</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Artikel terbaru seputar konstruksi dan pintu otomatis</p>
            </div>

            <?php if (count($blogs) > 0): ?>
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach ($blogs as $blog): ?>
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition">
                    <?php if ($blog['image']): ?>
                    <img src="<?= $blog['image'] ?>" alt="<?= sanitize($blog['title']) ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                    <div class="w-full h-48 bg-primary flex items-center justify-center">
                        <i class="fas fa-newspaper text-5xl text-white opacity-50"></i>
                    </div>
                    <?php endif; ?>
                    <div class="p-6">
                        <div class="text-sm text-gray-500 mb-2">
                            <i class="far fa-calendar mr-1"></i> <?= formatDate($blog['created_at']) ?>
                        </div>
                        <h3 class="text-lg font-bold text-primary mb-2"><?= sanitize($blog['title']) ?></h3>
                        <p class="text-gray-600 text-sm line-clamp-3"><?= substr(strip_tags($blog['content']), 0, 150) ?>...</p>
                        <a href="#" class="inline-block mt-4 text-accent font-semibold hover:text-yellow-600">Baca Selengkapnya →</a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada artikel yang dipublikasikan</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Hubungi Kami</h2>
                <p class="text-gray-300 max-w-2xl mx-auto">Konsultasikan kebutuhan konstruksi dan pintu otomatis Anda</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="bg-accent p-3 rounded-lg">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Alamat</h3>
                            <p class="text-gray-300">Jl. Contoh Alamat No. 123, Jakarta, Indonesia</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-accent p-3 rounded-lg">
                            <i class="fas fa-phone text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Telepon</h3>
                            <p class="text-gray-300">+62 21 1234 5678</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-accent p-3 rounded-lg">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold text-lg">Email</h3>
                            <p class="text-gray-300">info@cahayadimensibumi.com</p>
                        </div>
                    </div>
                </div>

                <form class="bg-white rounded-2xl p-8 shadow-2xl">
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nama</label>
                            <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="email@contoh.com">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Subjek</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Subjek pesan">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Pesan</label>
                        <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Tulis pesan Anda..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-accent text-white py-3 rounded-lg font-semibold hover:bg-yellow-600 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <span class="font-bold text-xl">CDB</span>
                    </div>
                    <p class="text-gray-400 text-sm">General Construction & Automatic Door Specialist. Partner terpercaya untuk kebutuhan konstruksi Anda.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#home" class="hover:text-accent">Home</a></li>
                        <li><a href="#about" class="hover:text-accent">About</a></li>
                        <li><a href="#projects" class="hover:text-accent">Projects</a></li>
                        <li><a href="#blog" class="hover:text-accent">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>General Construction</li>
                        <li>Automatic Doors</li>
                        <li>Dormakaba Products</li>
                        <li>Maintenance</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-accent transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-accent transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-accent transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?= date('Y') ?> PT Cahaya Dimensi Bumi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>