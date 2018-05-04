ALTER TABLE `users` 
ADD COLUMN `set_password_token` VARCHAR(40) NULL DEFAULT NULL AFTER `tier_level`,
ADD COLUMN `set_password_before` DATETIME NULL DEFAULT NULL AFTER `set_password_token`;
