<?php
/**
 * Fallback redirect ke public/index.php
 * File ini akan dieksekusi jika .htaccess tidak berfungsi
 */

// Pastikan tidak ada akses langsung ke file sensitif
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Jika request mengarah ke file/folder yang diizinkan di public, forward
if (preg_match('#^/(assets|uploads)/#', $uri)) {
    // Biarkan web server menangani file statis
    return false;
}

// Redirect semua request ke public/index.php
require_once __DIR__ . '/public/index.php';