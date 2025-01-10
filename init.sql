SET NAMES utf8mb4;

CREATE TABLE item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    amount INT,
    count INT,
    created_at DATETIME,
    updated_at DATETIME
);

INSERT INTO laravel.item (item_name, amount, count, created_at, updated_at) VALUES
('商品001', 100, 10, NOW() + INTERVAL 9 HOUR, NOW() + INTERVAL 9 HOUR);
INSERT INTO laravel.item (item_name, amount, count, created_at, updated_at) VALUES
('商品002', 200, 20, NOW() + INTERVAL 9 HOUR, NOW() + INTERVAL 9 HOUR);
INSERT INTO laravel.item (item_name, amount, count, created_at, updated_at) VALUES
('商品003', 300, 30, NOW() + INTERVAL 9 HOUR, NOW() + INTERVAL 9 HOUR);
