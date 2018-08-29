ALTER TABLE `reservations` 
CHANGE COLUMN `user_id` `sender_user_id` INT(11) NOT NULL ,
ADD COLUMN `receiver_user_id` INT(11) NOT NULL AFTER `sender_user_id`;
