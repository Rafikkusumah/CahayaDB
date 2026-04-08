<?php
require_once __DIR__ . '/auth.php';

class Middleware {
    // Guard: Wajib login
    public static function requireAuth() {
        if (!Auth::check()) {
            if (self::isJson()) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            header('Location: ' . APP_URL . '/login.php');
            exit;
        }
    }

    // Guard: Hanya untuk guest (login page)
    public static function requireGuest() {
        if (Auth::check()) {
            header('Location: ' . APP_URL . '/admin/index.php');
            exit;
        }
    }

    // CSRF Protection
    public static function csrfVerify() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            $session = $_SESSION['csrf_token'] ?? '';
            if (!hash_equals($session, $token)) {
                http_response_code(403);
                die('CSRF validation failed');
            }
        }
    }

    public static function csrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private static function isJson() {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }
}

// Load config ke constants
$config = require __DIR__ . '/../config/app.php';
foreach ($config as $k => $v) {
    define('APP_' . strtoupper($k), $v);
}