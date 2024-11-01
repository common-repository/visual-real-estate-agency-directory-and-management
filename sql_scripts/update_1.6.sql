"dont run this file manually!"

ALTER TABLE `wp_sw_profile` ADD `profile_image` INT NULL ;

ALTER TABLE `wp_sw_review` ADD `repository_id` INT(11) NULL DEFAULT NULL AFTER `message`;


ALTER TABLE `wp_sw_review` ADD `counter_like` INT(11) NULL DEFAULT '0' AFTER `is_visible`, 
ADD `counter_love` INT(11) NULL DEFAULT '0' AFTER `counter_like`, 
ADD `counter_wow` INT(11) NULL DEFAULT '0' AFTER `counter_love`,
ADD `counter_angry` INT(11) NULL DEFAULT '0' AFTER `counter_wow`;

DELETE FROM `wp_sw_settings` WHERE `field` = 'open_street_map_enabled';

INSERT INTO `wp_sw_settings` (`idsettings`, `field`, `value`) VALUES
(NULL, 'open_street_map_enabled', '1');

UPDATE `wp_sw_settings` SET `value`='' WHERE `field` = 'maps_api_key';
