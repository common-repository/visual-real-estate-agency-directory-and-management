"dont run this file manually!"

ALTER TABLE `wp_sw_calendar` ADD `user_id` INT NOT NULL AFTER `date_modified`, ADD `listing_id` INT NULL AFTER `user_id`;

ALTER TABLE `wp_sw_reservation` CHANGE `currency_code` `currency_code` VARCHAR(11) NULL DEFAULT NULL;

ALTER TABLE `wp_sw_reservation` ADD `is_payment_completed` BOOLEAN NULL ;

ALTER TABLE `wp_sw_reservation` ADD `guests_number` INT NULL ;

-- v2.0 end

