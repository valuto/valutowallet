ALTER TABLE `users` 
ADD COLUMN `tier_level` TINYINT(1) NULL DEFAULT 0 AFTER `email`;
