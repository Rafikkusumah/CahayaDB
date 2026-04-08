-- Database Schema for Cahaya Dimensi Bumi
-- Database: cahaya_dimensi_bumi

CREATE DATABASE IF NOT EXISTS cahaya_dimensi_bumi 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE cahaya_dimensi_bumi;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: projects
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    main_photo VARCHAR(255),
    other_media TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: blogs
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    status VARCHAR(50) DEFAULT 'draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: quotations
CREATE TABLE IF NOT EXISTS quotations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quotation_number VARCHAR(100) UNIQUE NOT NULL,
    quotation_date DATE NOT NULL,
    valid_until DATE NOT NULL,
    salesperson VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    address TEXT,
    city VARCHAR(100),
    zip_code VARCHAR(20),
    project_description TEXT,
    line_items TEXT NOT NULL,
    vat_applied TINYINT DEFAULT 0,
    vat_percentage DECIMAL(5,2) DEFAULT 11.00,
    notes TEXT,
    terms TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: invoices
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(100) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE NOT NULL,
    salesperson VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    address TEXT,
    city VARCHAR(100),
    zip_code VARCHAR(20),
    project_description TEXT,
    line_items TEXT NOT NULL,
    vat_applied TINYINT DEFAULT 0,
    vat_percentage DECIMAL(5,2) DEFAULT 11.00,
    notes TEXT,
    terms TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
INSERT INTO users (email, password, name, role) 
SELECT 'admin@cahayadimensibumi.com', '$2y$10$placeholder_hash_ganti_dengan_password_asli', 'Administrator', 'admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@cahayadimensibumi.com');

-- Note: Password hash di atas adalah placeholder. 
-- Gunakan password_hash('admin123', PASSWORD_DEFAULT) di PHP untuk menghasilkan hash yang sebenarnya.
