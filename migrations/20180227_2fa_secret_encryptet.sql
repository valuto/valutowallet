ALTER TABLE `users` 
ADD COLUMN `protected_key` VARCHAR(512) NULL DEFAULT NULL AFTER `authused`,
ADD COLUMN `secret_encrypted` VARCHAR(256) NULL DEFAULT NULL AFTER `protected_key`;
