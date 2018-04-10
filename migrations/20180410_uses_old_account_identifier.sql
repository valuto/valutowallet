ALTER TABLE `users` 
ADD COLUMN `uses_old_account_identifier` TINYINT(1) NULL DEFAULT '0' AFTER `secret_encrypted`;
