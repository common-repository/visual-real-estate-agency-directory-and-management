"dont run this file manually!"

CREATE TABLE IF NOT EXISTS `wp_sw_savesearch` (
  `idsavesearch` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NULL DEFAULT NULL,
  `date_submit` DATETIME NULL DEFAULT NULL,
  `date_modified` DATETIME NULL DEFAULT NULL,
  `lang_id` INT(11) NULL DEFAULT NULL,
  `is_activated` TINYINT(1) NULL DEFAULT NULL,
  `parameters` TEXT NULL DEFAULT NULL,
  `date_last_informed` DATETIME NULL DEFAULT NULL,
  `delivery_frequency_h` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`idsavesearch`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `wp_sw_savesearch` 
ADD COLUMN `date_next_inform` DATETIME NULL DEFAULT NULL AFTER `delivery_frequency_h`;

ALTER TABLE `wp_favorite` 
ADD COLUMN `date_last_informed` DATETIME NULL DEFAULT NULL AFTER `date_modified`;
