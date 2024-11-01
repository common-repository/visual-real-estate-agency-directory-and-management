"dont run this file manually!"

ALTER TABLE  `wp_listing` ADD  `category_id` INT( 11 ) NULL DEFAULT NULL AFTER  `transition_id` ;

CREATE TABLE IF NOT EXISTS `wp_sw_treefield` (
  `idtreefield` INT(11) NOT NULL AUTO_INCREMENT,
  `field_id` INT(11) NULL DEFAULT NULL,
  `parent_id` INT(11) NULL DEFAULT NULL,
  `parent_path` TEXT NULL DEFAULT NULL,
  `order` INT(11) NULL DEFAULT NULL,
  `level` INT(11) NULL DEFAULT NULL,
  `template` VARCHAR(100) NULL DEFAULT NULL,
  `wp_sw_treefieldcol` VARCHAR(45) NULL DEFAULT NULL,
  `repository_id` INT(11) NULL DEFAULT NULL,
  `image_filename` TEXT NULL DEFAULT NULL,
  `image_repository` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idtreefield`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `wp_sw_treefield_lang` (
  `idtreefield_lang` INT(11) NOT NULL AUTO_INCREMENT,
  `lang_id` INT(11) NULL DEFAULT NULL,
  `value` TEXT NULL DEFAULT NULL,
  `value_path` TEXT NULL DEFAULT NULL,
  `title` TEXT NULL DEFAULT NULL,
  `keywords` TEXT NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `body` TEXT NULL DEFAULT NULL,
  `slug` VARCHAR(100) NULL DEFAULT NULL,
  `json_object` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`idtreefield_lang`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `wp_sw_treefield_lang` 
ADD COLUMN `treefield_id` INT(11) NULL DEFAULT NULL AFTER `idtreefield_lang`;

CREATE TABLE IF NOT EXISTS `wp_sw_dependentfields` (
  `iddependentfields` INT(11) NOT NULL AUTO_INCREMENT,
  `field_id` INT(11) NULL DEFAULT NULL,
  `treefield_id` INT(11) NULL DEFAULT NULL,
  `hidden_fields_list` TEXT NULL DEFAULT NULL COMMENT 'saparated with comma \"1,2,3\"',
  PRIMARY KEY (`iddependentfields`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

INSERT INTO `wp_sw_dependentfields` (`iddependentfields`, `field_id`, `treefield_id`, `hidden_fields_list`) VALUES
(2, 1, 1, ''),
(3, 1, 2, ''),
(4, 1, 3, '15,22,21,20,19,16,27,26,25,24,23'),
(5, 1, 4, ''),
(6, 1, 5, '15,22,21,20,19,16,27'),
(7, 1, 6, '15,22,21,20,19,16,27'),
(8, 1, 7, '15,22,21,20,19,16,27');

INSERT INTO `wp_sw_treefield` (`idtreefield`, `field_id`, `parent_id`, `parent_path`, `order`, `level`, `template`, `wp_sw_treefieldcol`, `repository_id`, `image_filename`, `image_repository`) VALUES
(1, 1, 0, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL),
(2, 1, 0, NULL, 2, 0, NULL, NULL, NULL, NULL, NULL),
(3, 1, 0, NULL, 3, 0, NULL, NULL, NULL, NULL, NULL),
(4, 1, 0, NULL, 4, 0, NULL, NULL, NULL, NULL, NULL),
(5, 1, 4, NULL, 5, 1, NULL, NULL, NULL, NULL, NULL),
(6, 1, 4, NULL, 6, 1, NULL, NULL, NULL, NULL, NULL),
(7, 1, 4, NULL, 7, 1, NULL, NULL, NULL, NULL, NULL);

INSERT INTO `wp_sw_treefield_lang` (`idtreefield_lang`, `treefield_id`, `lang_id`, `value`, `value_path`, `title`, `keywords`, `description`, `body`, `slug`, `json_object`) VALUES
(1, 1, 1, 'Apartment', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 1, 'House', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 1, 'Commercial property', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, 1, 'Restaurant', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, 1, 'Bakery', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, 1, 'Shop', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 3, 1, 'Land', NULL, NULL, NULL, NULL, NULL, NULL, NULL);


