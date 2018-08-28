CREATE TABLE `roles` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `role_id` MEDIUMTEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `user_roles` (
  `relation_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) NOT NULL,
  `role_id` BIGINT(20) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`relation_id`));

INSERT INTO `roles` (`role_id`, `created_at`) VALUES ('vlumarketadmin', '2018-07-06 01:00:00');
INSERT INTO `roles` (`role_id`, `created_at`) VALUES ('usercheck', '2018-07-06 01:00:00');
INSERT INTO `roles` (`role_id`, `created_at`) VALUES ('usercreate', '2018-07-06 01:00:00');
