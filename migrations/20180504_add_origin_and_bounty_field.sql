ALTER TABLE `users` 
ADD COLUMN `origin` VARCHAR(45) NULL DEFAULT NULL AFTER `set_password_before`;

ALTER TABLE `users` 
ADD COLUMN `bounty_signup` TINYINT(1) NULL DEFAULT '0' AFTER `origin`;

ALTER TABLE `users` 
ADD COLUMN `bounty_received_at` DATETIME NULL DEFAULT NULL AFTER `bounty_signup`;


