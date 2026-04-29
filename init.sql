CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    theme ENUM('light', 'dark') DEFAULT 'light',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    default_order INT DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS shopping_lists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    share_token VARCHAR(64) UNIQUE,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS list_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    list_id INT,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    amount VARCHAR(50),
    price_formula VARCHAR(100),
    total_price DECIMAL(10, 2) DEFAULT 0.00,
    is_checked BOOLEAN DEFAULT FALSE,
    checked_at DATETIME,
    FOREIGN KEY (list_id) REFERENCES shopping_lists(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Standardkategorien einfuegen
INSERT INTO categories (name, default_order) VALUES 
('Obst & Gemüse', 1), ('Molkerei', 2), ('Fleisch & Wurst', 3), 
('Gewürze & Vorrat', 4), ('Getränke', 5), ('Haushalt', 6);