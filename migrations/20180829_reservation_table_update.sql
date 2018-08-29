ALTER TABLE `reservations` 
ADD COLUMN `state` VARCHAR(45) NOT NULL AFTER `reference_id`;
