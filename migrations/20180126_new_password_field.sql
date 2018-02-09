ALTER TABLE `users` 
CHANGE COLUMN `password` `password_old_md5` VARCHAR(300) NOT NULL ;

ALTER TABLE `users` 
ADD COLUMN `password` VARCHAR(300) NULL DEFAULT NULL AFTER `password_old_md5`;

ALTER TABLE `users` 
CHANGE COLUMN `password_old_md5` `password_old_md5` VARCHAR(300) NULL DEFAULT NULL ;
