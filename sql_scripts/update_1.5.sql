"dont run this file manually!"

CREATE TABLE IF NOT EXISTS `wp_sw_profile` (
  `idprofile` INT(11) NOT NULL AUTO_INCREMENT,
  `address` VARCHAR(160) NULL DEFAULT NULL,
  `country` VARCHAR(45) NULL DEFAULT NULL,
  `city` VARCHAR(45) NULL DEFAULT NULL,
  `zip_code` VARCHAR(20) NULL DEFAULT NULL,
  `phone_number` VARCHAR(45) NULL DEFAULT NULL,
  `facebook` VARCHAR(160) NULL DEFAULT NULL,
  `youtube` VARCHAR(160) NULL DEFAULT NULL,
  `linkedin` VARCHAR(160) NULL DEFAULT NULL,
  `twitter` VARCHAR(160) NULL DEFAULT NULL,
  `googleplus` VARCHAR(160) NULL DEFAULT NULL,
  `is_email_alerts_enabled` TINYINT(1) NULL DEFAULT NULL,
  `json_object` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idprofile`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `wp_sw_profile` 
ADD COLUMN `gps` VARCHAR(100) NULL DEFAULT NULL AFTER `idprofile`,
ADD COLUMN `lat` DECIMAL(9,6) NULL DEFAULT NULL AFTER `gps`,
ADD COLUMN `lng` DECIMAL(9,6) NULL DEFAULT NULL AFTER `lat`;

ALTER TABLE `wp_sw_profile` 
ADD COLUMN `user_id` INT(11) NULL DEFAULT NULL AFTER `idprofile`,
ADD UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC);

ALTER TABLE `wp_sw_profile` 
ADD COLUMN `agency_id` INT(11) NULL DEFAULT NULL AFTER `user_id`;

ALTER TABLE `wp_sw_profile` 
ADD COLUMN `is_agency_verified` TINYINT(1) NULL DEFAULT NULL AFTER `json_object`;

ALTER TABLE `wp_sw_profile` 
ADD COLUMN `position_title` VARCHAR(160) NULL DEFAULT NULL AFTER `city`;


