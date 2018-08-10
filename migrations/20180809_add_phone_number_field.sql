ALTER TABLE `users` 
ADD COLUMN `phone_number` VARCHAR(40) NULL DEFAULT '' AFTER `email`;
