CREATE TABLE `reservations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `amount` DECIMAL(65,8) NOT NULL,
  `user_id` INT NOT NULL,
  `origin` VARCHAR(45) NULL DEFAULT NULL,
  `reference_id` INT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `reservations` 
ADD COLUMN `state` VARCHAR(45) NOT NULL AFTER `user_id`;

CREATE TABLE `reservation_transactions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reservation_id` INT NOT NULL,
  `transaction_id` INT NOT NULL,
  `action` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `transactions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `valuto_transaction_id` VARCHAR(64) NULL DEFAULT NULL,
  `comment` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `amount` DECIMAL(65,8) NOT NULL,
  PRIMARY KEY (`id`));
