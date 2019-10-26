DROP DATABASE IF EXISTS shop;
CREATE DATABASE shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE shop.product (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    public_id CHAR(36) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    sku CHAR(12) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    available_quantity SMALLINT(11) NOT NULL,
    `description` TEXT,
    UNIQUE (public_id),
    UNIQUE (sku)
) ENGINE=InnoDB;

CREATE TABLE shop.category (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    parent_id INT(11) DEFAULT 0,
    INDEX parent_id_idx (parent_id)
) ENGINE=InnoDB;

CREATE TABLE shop.related_category (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_id INT(11) NOT NULL,
    related_id INT(11) NOT NULL,
    INDEX category_id_idx (category_id),
    INDEX related_id_idx (related_id),
    FOREIGN KEY (category_id) REFERENCES category (id)
) ENGINE=InnoDB;