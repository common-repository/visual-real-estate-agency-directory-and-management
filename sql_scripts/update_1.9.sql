"dont run this file manually!"

CREATE TABLE IF NOT EXISTS `wp_sw_tokenapi` (
`idtokenapi` INT(11) NOT NULL AUTO_INCREMENT ,
  `date_last_access` datetime NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `other` text COLLATE utf8_unicode_ci NOT NULL,
  `json` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`idtokenapi`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `wp_sw_subscriptions` (
`idsubscriptions` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `listing_limit` int(11) DEFAULT NULL,
  `subscription_price` decimal(10,2) DEFAULT NULL,
  `currency_code` varchar(45) DEFAULT NULL,
  `days_limit` int(11) DEFAULT NULL,
  `featured_limit` int(11) DEFAULT NULL,
  `set_activated` tinyint(1) DEFAULT NULL,
  `set_private` tinyint(1) DEFAULT NULL,
  `user_type` varchar(45) DEFAULT NULL,
  `woo_item_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `wp_sw_subscriptions`
 ADD PRIMARY KEY (`idsubscriptions`);

ALTER TABLE `wp_sw_subscriptions`
MODIFY `idsubscriptions` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wp_sw_subscriptions` ADD `subscription_name` VARCHAR(60) NULL AFTER `date_modified`;

ALTER TABLE `wp_sw_subscriptions` ADD `is_default` BOOLEAN NULL AFTER `subscription_name`;

ALTER TABLE `wp_sw_profile` ADD `package_expire` DATETIME NULL AFTER `profile_image`, ADD `package_id` INT NULL AFTER `package_expire`;

ALTER TABLE `wp_sw_invoice` ADD `subscription_id` INT NULL AFTER `listing_id`;




CREATE TABLE IF NOT EXISTS `wp_sw_calendar` (
`idcalendar` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `calendar_title` varchar(160) DEFAULT NULL,
  `calendar_type` varchar(20) DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT NULL,
  `payment_details` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Calendars for listings';

CREATE TABLE IF NOT EXISTS `wp_sw_rates` (
`idrates` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `calendar_id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `rate_hour` decimal(9,2) DEFAULT NULL,
  `rate_night` decimal(9,2) DEFAULT NULL,
  `rate_week` decimal(9,2) DEFAULT NULL,
  `rate_month` decimal(9,2) DEFAULT NULL,
  `currency_code` varchar(20) DEFAULT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `min_stay_days` int(11) DEFAULT NULL,
  `changeover_day` int(11) DEFAULT NULL COMMENT '0-monday, 6-sunday'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `wp_sw_reservation` (
`idreservation` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `calendar_id` int(11) NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `total_price` double(9,2) DEFAULT NULL,
  `currency_code` int(11) DEFAULT NULL,
  `date_paid_advance` datetime DEFAULT NULL,
  `date_paid_total` datetime DEFAULT NULL,
  `total_paid` decimal(9,2) DEFAULT NULL,
  `is_confirmed` tinyint(1) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `is_payment_informed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `wp_sw_calendar`
 ADD PRIMARY KEY (`idcalendar`);

ALTER TABLE `wp_sw_rates`
 ADD PRIMARY KEY (`idrates`);

ALTER TABLE `wp_sw_reservation`
 ADD PRIMARY KEY (`idreservation`);

ALTER TABLE `wp_sw_calendar`
MODIFY `idcalendar` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wp_sw_rates`
MODIFY `idrates` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wp_sw_reservation`
MODIFY `idreservation` int(11) NOT NULL AUTO_INCREMENT;







