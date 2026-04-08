<?php
require_once '../includes/functions.php';

if (Auth::check()) {
    header('Location: admin/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    if (Auth::login($email, $password)) {
        header('Location: admin/index.php');
        exit;
    } else {
        $error = 'Email atau password salah';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cahaya Dimensi Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#1e3a5f', accent: '#d69e2e' } } } }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-building text-accent text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-primary">CDB Admin</h1>
                <p class="text-gray-500">Masuk ke dashboard admin</p>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i><?= $error ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                        <input type="email" name="email" required 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="admin@cahayadimensibumi.com">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                        <input type="password" name="password" required 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Masukkan password">
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-secondary transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/" class="text-primary hover:text-accent">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>