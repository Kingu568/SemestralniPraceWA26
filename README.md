For this to work you have to create the SQL database
```
CREATE DATABASE IF NOT EXISTS ffxiv_market_helper
CHARACTER SET utf8mb4
COLLATE utf8mb4_czech_ci;

USE ffxiv_market_helper;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nickname VARCHAR(50) DEFAULT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category ENUM('Material', 'Gear', 'Consumable', 'Housing', 'Other') NOT NULL DEFAULT 'Other',
    description TEXT DEFAULT NULL,
    created_by INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (created_by) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quality ENUM('NQ', 'HQ') NOT NULL DEFAULT 'NQ',
    quantity INT NOT NULL,
    price_per_unit DECIMAL(12,0) NOT NULL,
    status ENUM('active', 'sold', 'cancelled') DEFAULT 'active',
    created_by INT NULL,
    updated_by INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (item_id) REFERENCES items(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (created_by) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (updated_by) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NULL,
    item_id INT NOT NULL,
    quality ENUM('NQ', 'HQ') NOT NULL DEFAULT 'NQ',
    quantity_sold INT NOT NULL,
    price_per_unit DECIMAL(12,0) NOT NULL,
    total_price DECIMAL(12,0) NOT NULL,
    sold_by INT NULL,
    sold_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (listing_id) REFERENCES listings(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (item_id) REFERENCES items(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (sold_by) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (item_id) REFERENCES items(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
```
