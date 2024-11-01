"dont run this file manually!"

ALTER TABLE `wp_sw_treefield` 
ADD COLUMN `marker_icon_id` INT(11) NULL DEFAULT NULL AFTER `image_repository`,
ADD COLUMN `featured_image_id` INT(11) NULL DEFAULT NULL AFTER `marker_icon_id`;

UPDATE `wp_field` SET `is_locked` = NULL, `is_hardlocked` = NULL, `is_required` = NULL WHERE `wp_field`.`idfield` = 14;

ALTER TABLE `wp_listing` 
ADD COLUMN `location_id` INT(11) NULL DEFAULT NULL AFTER `category_id`;

-- remove #78 and update keywords because of mobile api compatibility

DELETE FROM `wp_field` WHERE `wp_field`.`idfield` = 78;
DELETE FROM `wp_field_lang` WHERE `wp_field_lang`.`field_id` = 78;

UPDATE `wp_field` SET `idfield` = '78' WHERE `wp_field`.`idfield` = 11;
UPDATE `wp_field_lang` SET `field_id` = '78' WHERE `wp_field_lang`.`field_id` = 11;

DELETE FROM `wp_field` WHERE `wp_field`.`idfield` = 8;
DELETE FROM `wp_field_lang` WHERE `wp_field_lang`.`field_id` = 8;

UPDATE `wp_field` SET `idfield` = '8' WHERE `wp_field`.`idfield` = 12;
UPDATE `wp_field_lang` SET `field_id` = '8' WHERE `wp_field_lang`.`field_id` = 12;

ALTER TABLE `wp_field` auto_increment = 1000;








