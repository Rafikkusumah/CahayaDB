<?php
require_once __DIR__ . '/../config/database.php';

class Auth {
    private static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($email, $password) {
        self::init();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            return true;
        }
        return false;
    }

    public static function logout() {
        self::init();
        session_destroy();
        session_start();
    }

    public static function check() {
        self::init();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function user() {
        self::init();
        if (!self::check()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role']
        ];
    }

    public static function requireAuth() {
        if (!self::check()) {
            header('Location: /login.php');
            exit;
        }
    }
}