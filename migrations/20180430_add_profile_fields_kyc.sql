ALTER TABLE `users` 
ADD COLUMN `first_name` VARCHAR(100) NULL DEFAULT '' AFTER `deleted_at`,
ADD COLUMN `last_name` VARCHAR(250) NULL DEFAULT '' AFTER `first_name`,
ADD COLUMN `address_1` VARCHAR(254) NULL DEFAULT '' AFTER `last_name`,
ADD COLUMN `address_2` VARCHAR(254) NULL DEFAULT '' AFTER `address_1`,
ADD COLUMN `zip_code` VARCHAR(15) NULL DEFAULT '' AFTER `address_2`,
ADD COLUMN `city` VARCHAR(200) NULL DEFAULT '' AFTER `zip_code`,
ADD COLUMN `country_code` VARCHAR(2) NULL DEFAULT '' AFTER `city`,
ADD COLUMN `state` VARCHAR(200) NULL DEFAULT '' AFTER `country_code`,
ADD COLUMN `email` VARCHAR(254) NULL DEFAULT '' AFTER `state`;
