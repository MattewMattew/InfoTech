CREATE DATABASE IF NOT EXISTS yii2_books CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON yii2_books.* TO 'yii2_user'@'localhost' IDENTIFIED BY 'password123';
FLUSH PRIVILEGES;

USE yii2_books;