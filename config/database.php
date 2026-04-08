<?php
class Database {
    private static $instance = null;
    private $db;

    private function __construct() {
        $dbPath = __DIR__ . '/../database.sqlite';
        try {
            $this->db = new PDO('sqlite:' . $dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->createTables();
        } catch(PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->db;
    }

    private function createTables() {
        $db = $this->db;

        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            name TEXT NOT NULL,
            role TEXT DEFAULT 'admin',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS projects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_name TEXT NOT NULL,
            location TEXT NOT NULL,
            description TEXT,
            main_photo TEXT,
            other_media TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS blogs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            content TEXT NOT NULL,
            image TEXT,
            status TEXT DEFAULT 'draft',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS quotations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            quotation_number TEXT UNIQUE NOT NULL,
            quotation_date TEXT NOT NULL,
            valid_until TEXT NOT NULL,
            salesperson TEXT NOT NULL,
            company_name TEXT NOT NULL,
            address TEXT,
            city TEXT,
            zip_code TEXT,
            project_description TEXT,
            line_items TEXT NOT NULL,
            vat_applied INTEGER DEFAULT 0,
            vat_percentage REAL DEFAULT 11,
            notes TEXT,
            terms TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $db->exec("CREATE TABLE IF NOT EXISTS invoices (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            invoice_number TEXT UNIQUE NOT NULL,
            invoice_date TEXT NOT NULL,
            due_date TEXT NOT NULL,
            salesperson TEXT NOT NULL,
            company_name TEXT NOT NULL,
            address TEXT,
            city TEXT,
            zip_code TEXT,
            project_description TEXT,
            line_items TEXT NOT NULL,
            vat_applied INTEGER DEFAULT 0,
            vat_percentage REAL DEFAULT 11,
            notes TEXT,
            terms TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Create default admin if not exists
        $stmt = $db->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) {
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $db->exec("INSERT INTO users (email, password, name, role) VALUES ('admin@cahayadimensibumi.com', '$hashedPassword', 'Administrator', 'admin')");
        }
    }
}