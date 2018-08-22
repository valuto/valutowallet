ALTER TABLE `users` 
ADD COLUMN `kyc_skipped` TINYINT(1) NULL DEFAULT '0' AFTER `bounty_received_at`;
