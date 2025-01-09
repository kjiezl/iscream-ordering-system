CREATE TABLE `user_info` (
  `id` INT(100) PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100),
  `email` VARCHAR(100) UNIQUE,
  `password` VARCHAR(100),
  `is_admin` TINYINT(1) DEFAULT 0
);

CREATE TABLE `products` (
  `id` INT(100) PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100),
  `price` VARCHAR(100),
  `image` VARCHAR(100)
);

CREATE TABLE `cart` (
  `id` INT(100) PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT(100),
  `name` VARCHAR(100),
  `price` VARCHAR(100),
  `image` VARCHAR(100),
  `quantity` INT(100)
);

CREATE TABLE `orders` (
  `id` INT(100) PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT(100),
  `cart_id` INT(100),
  `total_price` DECIMAL(10,2),
  `status` ENUM(Pending,Shipped,Delivered) DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT 'current_timestamp'
);

CREATE TABLE `order_items` (
  `id` INT(100) PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT(100),
  `product_name` VARCHAR(255),
  `price` DECIMAL(10,2),
  `quantity` INT(11),
  `total_price` DECIMAL(10,2)
);

ALTER TABLE `cart` ADD FOREIGN KEY (`user_id`) REFERENCES `user_info` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`user_id`) REFERENCES `user_info` (`id`);

ALTER TABLE `orders` ADD FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`);

ALTER TABLE `order_items` ADD FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

ALTER TABLE `order_items` ADD FOREIGN KEY (`product_name`) REFERENCES `products` (`name`);
