"dont run this file manually!"

ALTER TABLE `wp_sw_field` ADD `columns_number` INT(11) NULL DEFAULT NULL AFTER `image_gallery`;

ALTER TABLE `wp_sw_field_lang` ADD `placeholder` VARCHAR(45) NULL DEFAULT NULL AFTER `hint`;

ALTER TABLE `wp_sw_treefield` ADD `font_icon_code` VARCHAR(64) NULL DEFAULT NULL AFTER `featured_image_id`;

ALTER TABLE `wp_sw_field` ADD `image_id` INT(11) NULL DEFAULT NULL AFTER `image_gallery`;

UPDATE `wp_sw_field` SET `columns_number`=3 WHERE `type` = 'CHECKBOX';

