CREATE DATABASE shop;

USE shop;

CREATE TABLE goods_category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    alias VARCHAR(255) NOT NULL,
    INDEX (alias)
);

CREATE TABLE goods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    alias VARCHAR(255) NOT NULL,
    article VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES goods_category(id),
    INDEX (alias),
    INDEX (price),
    INDEX (quantity)
);

CREATE TABLE goods_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    goods_id INT,
    photo_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (goods_id) REFERENCES goods(id)
);
