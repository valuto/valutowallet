ALTER TABLE `users` 
ADD COLUMN `email_confirmed_at` DATETIME NULL DEFAULT NULL AFTER `email`;

ALTER TABLE `users` 
ADD COLUMN `phone_number_confirmed_at` DATETIME NULL DEFAULT NULL AFTER `email_confirmed_at`;
