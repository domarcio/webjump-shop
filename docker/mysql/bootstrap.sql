DROP DATABASE IF EXISTS shop;
DROP DATABASE IF EXISTS shop_test;

CREATE DATABASE shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE shop_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE shop.product (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    public_id CHAR(36) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    sku CHAR(20) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    available_quantity SMALLINT(11) NOT NULL,
    `description` TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    UNIQUE (public_id),
    UNIQUE (sku)
) ENGINE=InnoDB;

CREATE TABLE shop.category (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    parent_id INT(11) DEFAULT NULL,
    INDEX parent_id_idx (parent_id),
    FOREIGN KEY (parent_id) REFERENCES category (id)
) ENGINE=InnoDB;

CREATE TABLE shop.related_category (
    category_id INT(11) NOT NULL,
    related_id INT(11) NOT NULL,
    PRIMARY KEY (category_id, related_id),
    FOREIGN KEY (category_id) REFERENCES category (id)
) ENGINE=InnoDB;