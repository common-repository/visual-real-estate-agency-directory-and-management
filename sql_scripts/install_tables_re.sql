"dont run this file manually!"

CREATE TABLE IF NOT EXISTS `wp_cacher` (
`idcacher` int(11) NOT NULL,
  `index_hash` varchar(45) DEFAULT NULL,
  `index_real` varchar(100) DEFAULT NULL,
  `value` text,
  `expire_date` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_currency` (
`idcurrency` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `rate_index` decimal(9,2) DEFAULT NULL,
  `currency_code` varchar(45) DEFAULT NULL,
  `currency_symbol` varchar(45) DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;


INSERT INTO `wp_currency` (`idcurrency`, `date_submit`, `date_modified`, `rate_index`, `currency_code`, `currency_symbol`, `is_activated`, `is_default`) VALUES
(1, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1.07', 'USD', '$', 1, NULL),
(2, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '121.49', 'JPY', NULL, NULL, NULL),
(3, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1.96', 'BGN', NULL, NULL, NULL),
(4, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '27.02', 'CZK', NULL, NULL, NULL),
(5, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '7.44', 'DKK', NULL, NULL, NULL),
(6, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '0.86', 'GBP', NULL, NULL, NULL),
(7, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '309.46', 'HUF', NULL, NULL, NULL),
(8, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '4.30', 'PLN', NULL, NULL, NULL),
(9, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '4.52', 'RON', NULL, NULL, NULL),
(10, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '9.44', 'SEK', NULL, NULL, NULL),
(11, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1.07', 'CHF', NULL, NULL, NULL),
(12, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '8.86', 'NOK', NULL, NULL, NULL),
(13, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '7.43', 'HRK', 'kn', 1, NULL),
(14, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '63.61', 'RUB', NULL, NULL, NULL),
(15, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '4.01', 'TRY', NULL, NULL, NULL),
(16, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1.41', 'AUD', NULL, NULL, NULL),
(17, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '3.36', 'BRL', NULL, NULL, NULL),
(18, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1.40', 'CAD', NULL, NULL, NULL),
(19, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '7.38', 'CNY', NULL, NULL, NULL),
(20, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '8.33', 'HKD', NULL, NULL, NULL),
(21, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '14333.86', 'IDR', NULL, NULL, NULL),
(22, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '4.03', 'ILS', NULL, NULL, NULL),
(23, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '72.31', 'INR', NULL, NULL, NULL),
(24, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1232.25', 'KRW', NULL, NULL, NULL),
(25, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '22.02', 'MXN', NULL, NULL, NULL),
(26, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '4.76', 'MYR', NULL, NULL, NULL),
(27, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1.48', 'NZD', NULL, NULL, NULL),
(28, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '53.46', 'PHP', NULL, NULL, NULL),
(29, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '1.52', 'SGD', NULL, NULL, NULL),
(30, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '37.65', 'THB', NULL, NULL, NULL),
(31, '2017-02-04 00:52:11', '2017-02-04 09:46:49', '14.42', 'ZAR', NULL, NULL, NULL),
(32, '2017-02-04 00:53:09', '2017-02-04 00:53:09', '1.00', 'EUR', '€', 1, NULL);

CREATE TABLE IF NOT EXISTS `wp_favorite` (
`idfavorite` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `note` text
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `wp_field` (
`idfield` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT NULL,
  `is_table_visible` tinyint(1) DEFAULT NULL,
  `is_preview_visible` tinyint(1) DEFAULT NULL,
  `is_submission_visible` tinyint(1) DEFAULT NULL,
  `is_hardlocked` tinyint(1) DEFAULT NULL,
  `is_required` tinyint(1) DEFAULT NULL,
  `is_translatable` tinyint(1) DEFAULT NULL,
  `is_quickvisible` tinyint(1) DEFAULT NULL,
  `max_length` int(11) DEFAULT NULL,
  `repository_id` int(11) DEFAULT NULL,
  `image_filename` int(11) DEFAULT NULL,
  `image_gallery` text
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;



INSERT INTO `wp_field` (`idfield`, `parent_id`, `order`, `type`, `is_locked`, `is_table_visible`, `is_preview_visible`, `is_submission_visible`, `is_hardlocked`, `is_required`, `is_translatable`, `is_quickvisible`, `max_length`, `repository_id`, `image_filename`, `image_gallery`) VALUES
(1, 0, 6, 'CATEGORY', NULL, NULL, 1, 1, 1, NULL, 1, 1, NULL, 52, NULL, ''),
(4, 1, 7, 'DROPDOWN_MULTIPLE', 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, 245, NULL, ''),
(5, 1, 11, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 345, NULL, ''),
(7, 1, 14, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 7, NULL, ''),
(8, 0, 3, 'TEXTAREA', 1, NULL, 1, 1, 1, NULL, 1, 1, NULL, 12, NULL, ''),
(9, 1, 16, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 9, NULL, ''),
(10, 0, 1, 'INPUTBOX', 1, 1, 1, 1, 1, 1, 1, 1, 20, 10, NULL, ''),
(11, 52, 25, 'CHECKBOX', NULL, NULL, 1, 1, 1, NULL, NULL, 1, NULL, 11, NULL, ''),
(13, 0, 4, 'TEXTAREA', 1, NULL, 1, 1, 1, NULL, 1, 1, NULL, 13, NULL, ''),
(14, 0, 5, 'DROPDOWN', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 14, NULL, ''),
(18, 0, 36, 'CATEGORY', 1, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 18, NULL, NULL),
(19, 1, 13, 'INTEGER', NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 20, NULL, ''),
(20, 1, 12, 'INTEGER', NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 21, NULL, ''),
(21, 0, 17, 'CATEGORY', 1, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 15, NULL, NULL),
(22, 21, 18, 'CHECKBOX', NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 23, NULL, ''),
(23, 21, 20, 'CHECKBOX', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 177, NULL, NULL),
(27, 52, 26, 'CHECKBOX', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 28, NULL, ''),
(29, 21, 21, 'CHECKBOX', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 179, NULL, NULL),
(30, 52, 24, 'CHECKBOX', NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 31, NULL, ''),
(31, 21, 22, 'CHECKBOX', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 182, NULL, NULL),
(32, 52, 23, 'CHECKBOX', NULL, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 33, NULL, ''),
(33, 52, 27, 'CHECKBOX', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 26, NULL, ''),
(35, 18, 37, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, 36, NULL, ''),
(36, 1, 8, 'INTEGER', 1, NULL, 1, 1, 1, NULL, 1, 1, NULL, 37, NULL, ''),
(37, 1, 9, 'INTEGER', 1, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 38, NULL, NULL),
(38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 599, NULL, NULL),
(43, 0, 28, 'CATEGORY', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 17, NULL, ''),
(44, 43, 29, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 35, NULL, ''),
(45, 43, 30, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 34, NULL, ''),
(47, 43, 35, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 183, NULL, NULL),
(48, 43, 31, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 32, NULL, ''),
(49, 43, 34, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 180, NULL, ''),
(50, 43, 33, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 29, NULL, ''),
(51, 43, 32, 'INPUTBOX', NULL, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 30, NULL, ''),
(52, 0, 22, 'CATEGORY', 1, NULL, 1, 1, NULL, NULL, 1, NULL, NULL, 16, NULL, ''),
(57, 1, 10, 'INPUTBOX', NULL, NULL, 1, 1, 1, NULL, 1, NULL, NULL, 455, NULL, ''),
(78, 0, 2, 'INPUTBOX', NULL, NULL, NULL, 1, 1, NULL, 1, NULL, NULL, 178, NULL, NULL);


CREATE TABLE IF NOT EXISTS `wp_field_lang` (
`idfield_lang` int(11) NOT NULL,
  `field_id` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `values` text,
  `prefix` varchar(45) DEFAULT NULL,
  `suffix` varchar(45) DEFAULT NULL,
  `hint` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8;

INSERT INTO `wp_field_lang` (`idfield_lang`, `field_id`, `lang_id`, `field_name`, `values`, `prefix`, `suffix`, `hint`) VALUES
(61, 21, 1, 'Indoor amenities', NULL, NULL, NULL, NULL),
(67, 18, 1, 'Multimedia', NULL, NULL, NULL, NULL),
(69, 52, 1, 'Outdoor amenities', NULL, NULL, NULL, NULL),
(125, 7, 1, 'Country', NULL, NULL, NULL, NULL),
(131, 37, 1, 'Rent price', NULL, '$', NULL, NULL),
(135, 57, 1, 'Land area', NULL, NULL, 'ft2', NULL),
(137, 5, 1, 'Living area', NULL, NULL, 'ft2', NULL),
(203, 35, 1, 'Youtube link', NULL, NULL, NULL, NULL),
(209, 33, 1, 'Pool', NULL, NULL, NULL, NULL),
(213, 27, 1, 'Grill', NULL, NULL, NULL, NULL),
(225, 14, 1, 'Map pin icon', 'empty,commercial,house,land,apartment', NULL, NULL, 'Pin/Marker/Icon for location on map'),
(241, 1, 1, 'Overview', NULL, NULL, NULL, NULL),
(245, 10, 1, 'Title', NULL, NULL, NULL, NULL),
(249, 8, 1, 'Description', NULL, NULL, NULL, 'Short description for result listing and SEO'),
(254, 43, 1, 'Distances', NULL, NULL, NULL, NULL),
(256, 13, 1, 'Content', NULL, NULL, NULL, NULL),
(319, 4, 1, 'Purpose', ',For Sale,For Rent', NULL, NULL, NULL),
(325, 36, 1, 'Sale price', ',10000,20000,30000,40000,50000,100000', '$', NULL, NULL),
(331, 20, 1, 'Bedrooms', NULL, NULL, NULL, NULL),
(333, 19, 1, 'Bathrooms', NULL, NULL, NULL, NULL),
(339, 22, 1, 'Air conditioning', NULL, NULL, NULL, NULL),
(343, 32, 1, 'Parking', NULL, NULL, NULL, NULL),
(345, 9, 1, 'Zip code', NULL, NULL, NULL, NULL),
(347, 23, 1, 'Cable TV', NULL, NULL, NULL, NULL),
(351, 11, 1, 'Balcony', NULL, NULL, NULL, NULL),
(353, 78, 1, 'Keywords', NULL, NULL, NULL, 'Keywords for SEO, separate with comma'),
(355, 29, 1, 'Internet', NULL, NULL, NULL, NULL),
(367, 30, 1, 'Elevator', NULL, NULL, NULL, NULL),
(371, 48, 1, 'Pharmacies', NULL, NULL, 'ft', NULL),
(373, 51, 1, 'Coffee shop', NULL, NULL, 'ft', NULL),
(375, 50, 1, 'Restourant', NULL, NULL, 'ft', NULL),
(377, 49, 1, 'Bakery', NULL, NULL, 'ft', NULL),
(389, 44, 1, 'Beach', NULL, NULL, 'ft', NULL),
(391, 45, 1, 'Train', NULL, NULL, 'ft', NULL),
(393, 31, 1, 'Microwave', NULL, NULL, NULL, NULL),
(395, 47, 1, 'Bus', NULL, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `wp_file` (
`idfile` int(11) NOT NULL,
  `repository_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `filetype` varchar(45) DEFAULT NULL,
  `alt` varchar(45) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_inquiry` (
`idinquiry` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  `user_id_sender` int(11) DEFAULT NULL,
  `user_id_receiver` int(11) DEFAULT NULL,
  `json_object` text,
  `email_receiver` varchar(100) DEFAULT NULL,
  `email_sender` varchar(100) DEFAULT NULL,
  `message` text,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL,
  `is_readed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_invoice` (
`idinvoice` int(11) NOT NULL,
  `invoicenum` varchar(100) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_paid` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT NULL,
  `vat_percentage` int(11) DEFAULT NULL,
  `company_details` text,
  `price` decimal(10,2) DEFAULT NULL,
  `currency_code` varchar(45) DEFAULT NULL,
  `paid_via` varchar(45) DEFAULT NULL,
  `note` text,
  `data_json` text
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_listing` (
`idlisting` int(11) NOT NULL,
  `transition_id` varchar(100) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT NULL,
  `related_id` int(11) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT NULL,
  `gps` varchar(100) DEFAULT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `lng` decimal(9,6) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `date_activated` datetime DEFAULT NULL,
  `date_rank_expire` datetime DEFAULT NULL,
  `date_alert` datetime DEFAULT NULL,
  `date_notify` datetime DEFAULT NULL,
  `date_repost` datetime DEFAULT NULL,
  `date_renew` datetime DEFAULT NULL,
  `date_activation_paid` datetime DEFAULT NULL,
  `date_featured_paid` datetime DEFAULT NULL,
  `date_status` datetime DEFAULT NULL,
  `date_expire` datetime DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `last_edit_ip` varchar(45) DEFAULT NULL,
  `counter_views` int(11) DEFAULT NULL,
  `image_filename` varchar(200) DEFAULT NULL,
  `image_repository` text,
  `repository_id` int(11) DEFAULT NULL,
  `affilate_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_listing_agent` (
`idlisting_agent` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_listing_field` (
`idlisting_field` int(11) NOT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `value` text,
  `value_num` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_listing_lang` (
`idlisting_lang` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `json_object` text,
  `field_3_int` int(11) DEFAULT NULL,
  `field_5_int` int(11) DEFAULT NULL,
  `field_19_int` int(11) DEFAULT NULL,
  `field_20_int` int(11) DEFAULT NULL,
  `field_36_int` int(11) DEFAULT NULL,
  `field_37_int` int(11) DEFAULT NULL,
  `field_57_int` int(11) DEFAULT NULL,
  `field_2` varchar(200) DEFAULT NULL,
  `field_4` varchar(200) DEFAULT NULL,
  `field_7` varchar(200) DEFAULT NULL,
  `field_10` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_packagerank` (
`idpackagerank` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `package_name` varchar(60) DEFAULT NULL,
  `package_price` decimal(10,2) DEFAULT NULL,
  `currency_code` varchar(45) DEFAULT NULL,
  `package_days` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


INSERT INTO `wp_packagerank` (`idpackagerank`, `date_submit`, `date_modified`, `rank`, `package_name`, `package_price`, `currency_code`, `package_days`) VALUES
(1, '2017-03-08 00:00:00', '2017-03-08 00:00:00', 0, 'Regular', '0.00', 'USD', 0),
(2, '2017-03-01 17:38:57', '2017-03-01 17:38:57', 1, 'Featured', '5.00', 'USD', 60),
(3, '2017-03-01 17:39:19', '2017-03-02 09:55:34', 2, 'Premium', '10.00', 'USD', 120);


CREATE TABLE IF NOT EXISTS `wp_repository` (
`idrepository` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `model_name` varchar(45) DEFAULT NULL,
  `is_activated` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `wp_search_form` (
`idsearch_form` int(11) NOT NULL,
  `form_name` varchar(100) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `fields_order` text
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO `wp_search_form` (`idsearch_form`, `form_name`, `type`, `fields_order`) VALUES
(1, 'Primary form', 'MAIN', '{  "PRIMARY": {  "CATEGORY":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"CATEGORY"} ,"WHAT_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"WHAT_SEARCH"} ,"WHERE_SEARCH":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"WHERE_SEARCH"} ,"DROPDOWN_MULTIPLE_4":{"direction":"NONE", "style":"", "class":"", "id":"4", "type":"DROPDOWN_MULTIPLE"} ,"INPUTBOX_7":{"direction":"NONE", "style":"", "class":"", "id":"7", "type":"INPUTBOX"} ,"INTEGER_36_FROM":{"direction":"FROM", "style":"", "class":"", "id":"36", "type":"INTEGER"} ,"INTEGER_36_TO":{"direction":"TO", "style":"", "class":"", "id":"36", "type":"INTEGER"} }, "SECONDARY": {  "CHECKBOX_26":{"direction":"NONE", "style":"", "class":"", "id":"26", "type":"CHECKBOX"} ,"CHECKBOX_27":{"direction":"NONE", "style":"", "class":"", "id":"27", "type":"CHECKBOX"} ,"CHECKBOX_25":{"direction":"NONE", "style":"", "class":"", "id":"25", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_19":{"direction":"NONE", "style":"", "class":"", "id":"19", "type":"CHECKBOX"} } }'),
(2, 'Result item', 'RESULT_ITEM', '{  "PRIMARY": {  "INTEGER_36":{"direction":"NONE", "style":"", "class":"", "id":"36", "type":"INTEGER"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} }, "SECONDARY": { } }');


CREATE TABLE IF NOT EXISTS `wp_settings` (
`idsettings` int(11) NOT NULL,
  `field` varchar(100) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB AUTO_INCREMENT=869 DEFAULT CHARSET=utf8;


INSERT INTO `wp_settings` (`idsettings`, `field`, `value`) VALUES
(434, 'email_activation_enabled', '1'),
(841, 'noreply', 'noreply@my-website.com'),
(842, 'use_walker', '1'),
(843, 'listing_activation_required', '1'),
(844, 'multilanguage_required', NULL),
(845, 'init_package_id', '1'),
(846, 'date_format_js', 'DD.MM.YYYY HH:mm'),
(847, 'date_format_php', 'd.m.Y H:i'),
(848, 'maps_api_key', 'AIzaSyB0lxCRSHcNPBu5hq3wsmY1KhcBq5Tlwi8'),
(849, 'lat', '41.90227704096372'),
(850, 'lng', '12.744140625'),
(851, 'results_page', '40'),
(852, 'listing_preview_page', '43'),
(853, 'user_profile_page', '45'),
(854, 'register_page', '47'),
(855, 'agents_page', '6'),
(856, 'quick_submission', '66'),
(857, 'per_page', '9'),
(858, 'recaptcha_site_key', '6Lc_UgcTAAAAAB9OXOrIbu0REpoJPhQCcTkWylyl'),
(859, 'recaptcha_secret_key', '6Lc_UgcTAAAAAGM4NKnehTVmDYxDljl3LV3LBOwd'),
(860, 'default_currency', 'USD'),
(861, 'default_vat', '25'),
(862, 'expire_days', NULL),
(863, 'paypal_email', 'sandi.winter@gmail.com'),
(864, 'use_sandbox', '1'),
(865, 'bank_details', 'IBAN: HR43 2340009 3207462177<br/>\r\nSWIFT: PBZGHR2X'),
(866, 'facebook_login_enabled', '1'),
(867, 'facebook_app_id', '1386561621405561'),
(868, 'facebook_app_secret', '3df1336045a5f1e342b4dd662a881457');


CREATE TABLE IF NOT EXISTS `wp_slug` (
`idslug` int(11) NOT NULL,
  `table` varchar(45) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `lang_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `lang_code` varchar(16) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `wp_review` (
`idreview` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `agentprofile_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_mail` varchar(100) DEFAULT NULL,
  `stars` int(11) DEFAULT NULL,
  `message` text,
  `is_visible` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `wp_cacher`
 ADD PRIMARY KEY (`idcacher`);

ALTER TABLE `wp_currency`
 ADD PRIMARY KEY (`idcurrency`);

ALTER TABLE `wp_favorite`
 ADD PRIMARY KEY (`idfavorite`);

ALTER TABLE `wp_field`
 ADD PRIMARY KEY (`idfield`);

ALTER TABLE `wp_field_lang`
 ADD PRIMARY KEY (`idfield_lang`);

ALTER TABLE `wp_file`
 ADD PRIMARY KEY (`idfile`);

ALTER TABLE `wp_inquiry`
 ADD PRIMARY KEY (`idinquiry`);

ALTER TABLE `wp_invoice`
 ADD PRIMARY KEY (`idinvoice`);

ALTER TABLE `wp_listing`
 ADD PRIMARY KEY (`idlisting`);

ALTER TABLE `wp_listing_agent`
 ADD PRIMARY KEY (`idlisting_agent`);

ALTER TABLE `wp_listing_field`
 ADD PRIMARY KEY (`idlisting_field`);

ALTER TABLE `wp_listing_lang`
 ADD PRIMARY KEY (`idlisting_lang`);

ALTER TABLE `wp_packagerank`
 ADD PRIMARY KEY (`idpackagerank`);

ALTER TABLE `wp_repository`
 ADD PRIMARY KEY (`idrepository`);

ALTER TABLE `wp_search_form`
 ADD PRIMARY KEY (`idsearch_form`);

ALTER TABLE `wp_settings`
 ADD PRIMARY KEY (`idsettings`);

ALTER TABLE `wp_slug`
 ADD PRIMARY KEY (`idslug`);
 
ALTER TABLE `wp_review`
 ADD PRIMARY KEY (`idreview`);
 
 
ALTER TABLE `wp_cacher`
MODIFY `idcacher` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_currency`
--
ALTER TABLE `wp_currency`
MODIFY `idcurrency` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `wp_favorite`
--
ALTER TABLE `wp_favorite`
MODIFY `idfavorite` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_field`
--
ALTER TABLE `wp_field`
MODIFY `idfield` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `wp_field_lang`
--
ALTER TABLE `wp_field_lang`
MODIFY `idfield_lang` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=253;
--
-- AUTO_INCREMENT for table `wp_file`
--
ALTER TABLE `wp_file`
MODIFY `idfile` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_inquiry`
--
ALTER TABLE `wp_inquiry`
MODIFY `idinquiry` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_invoice`
--
ALTER TABLE `wp_invoice`
MODIFY `idinvoice` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_listing`
--
ALTER TABLE `wp_listing`
MODIFY `idlisting` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_listing_agent`
--
ALTER TABLE `wp_listing_agent`
MODIFY `idlisting_agent` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_listing_field`
--
ALTER TABLE `wp_listing_field`
MODIFY `idlisting_field` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_listing_lang`
--
ALTER TABLE `wp_listing_lang`
MODIFY `idlisting_lang` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_packagerank`
--
ALTER TABLE `wp_packagerank`
MODIFY `idpackagerank` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `wp_repository`
--
ALTER TABLE `wp_repository`
MODIFY `idrepository` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `wp_search_form`
--
ALTER TABLE `wp_search_form`
MODIFY `idsearch_form` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `wp_settings`
--
ALTER TABLE `wp_settings`
MODIFY `idsettings` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=869;
--
-- AUTO_INCREMENT for table `wp_slug`
--
ALTER TABLE `wp_slug`
MODIFY `idslug` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `wp_review`
MODIFY `idreview` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

-- V1.1

CREATE TABLE IF NOT EXISTS `wp_sw_report` (
`idreport` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `date_submit` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text,
  `allow_contact` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `wp_sw_report`
 ADD PRIMARY KEY (`idreport`);


ALTER TABLE `wp_sw_report`
MODIFY `idreport` int(11) NOT NULL AUTO_INCREMENT;

-- V1.2

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

-- V1.3

ALTER TABLE `wp_sw_treefield` 
ADD COLUMN `marker_icon_id` INT(11) NULL DEFAULT NULL AFTER `image_repository`,
ADD COLUMN `featured_image_id` INT(11) NULL DEFAULT NULL AFTER `marker_icon_id`;

UPDATE `wp_field` SET `is_locked` = NULL, `is_hardlocked` = NULL, `is_required` = NULL WHERE `wp_field`.`idfield` = 14;

ALTER TABLE `wp_listing` 
ADD COLUMN `location_id` INT(11) NULL DEFAULT NULL AFTER `category_id`;

DELETE FROM `wp_field` WHERE `wp_field`.`idfield` = 14;
DELETE FROM `wp_field_lang` WHERE `field_id` = 14;

ALTER TABLE `wp_field` auto_increment = 100;

-- Demo data for Locations

INSERT INTO `wp_sw_treefield` (`idtreefield`, `field_id`, `parent_id`, `parent_path`, `order`, `level`, `template`, `wp_sw_treefieldcol`, `repository_id`, `image_filename`, `image_repository`, `marker_icon_id`, `featured_image_id`) VALUES
(8, 2, 0, NULL, 5, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 2, 8, NULL, 6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 2, 8, NULL, 7, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 2, 8, NULL, 8, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 2, 8, NULL, 9, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 2, 8, NULL, 10, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, 8, NULL, 11, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 2, 8, NULL, 12, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 2, 8, NULL, 13, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 2, 11, NULL, 9, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 2, 11, NULL, 10, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 2, 11, NULL, 11, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 2, 11, NULL, 12, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 2, 10, NULL, 8, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 2, 10, NULL, 9, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO `wp_sw_treefield_lang` (`idtreefield_lang`, `treefield_id`, `lang_id`, `value`, `value_path`, `title`, `keywords`, `description`, `body`, `slug`, `json_object`) VALUES
(9, 8, 1, 'Croatia', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 9, 1, 'Čakovec', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 10, 1, 'Zagreb', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 11, 1, 'Varaždin', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 12, 1, 'Split', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 13, 1, 'Rijeka', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 15, 1, 'Pula', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 16, 1, 'Dubrovnik', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 17, 1, 'Cestica', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 18, 1, 'Babinec', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 19, 1, 'Sračinec', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 14, 1, 'Bjelovar', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 20, 1, 'Petrijanec', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 21, 1, 'Zaprešić', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- V1.4

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

-- V1.5

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

-- V1.6

ALTER TABLE `wp_sw_profile` ADD `profile_image` INT NULL ;

ALTER TABLE `wp_review` ADD `repository_id` INT(11) NULL DEFAULT NULL AFTER `message`;

ALTER TABLE `wp_review` ADD `counter_like` INT(11) NULL DEFAULT '0' AFTER `is_visible`, 
ADD `counter_love` INT(11) NULL DEFAULT '0' AFTER `counter_like`, 
ADD `counter_wow` INT(11) NULL DEFAULT '0' AFTER `counter_love`,
ADD `counter_angry` INT(11) NULL DEFAULT '0' AFTER `counter_wow`;

DELETE FROM `wp_settings` WHERE `field` = 'open_street_map_enabled';

INSERT INTO `wp_settings` (`idsettings`, `field`, `value`) VALUES
(NULL, 'open_street_map_enabled', '1');

UPDATE `wp_settings` SET `value`='' WHERE `field` = 'maps_api_key';

-- V1.7

ALTER TABLE `wp_field` ADD `columns_number` INT(11) NULL DEFAULT NULL AFTER `image_gallery`;

ALTER TABLE `wp_field_lang` ADD `placeholder` VARCHAR(45) NULL DEFAULT NULL AFTER `hint`;

ALTER TABLE `wp_sw_treefield` ADD `font_icon_code` VARCHAR(64) NULL DEFAULT NULL AFTER `featured_image_id`;

ALTER TABLE `wp_field` ADD `image_id` INT(11) NULL DEFAULT NULL AFTER `image_gallery`;

UPDATE `wp_field` SET `columns_number`=3 WHERE `type` = 'CHECKBOX';

-- v1.8

CREATE TABLE IF NOT EXISTS `wp_sw_messages` 
( `idmessages` INT(11) NOT NULL AUTO_INCREMENT , 
 `related_key` VARCHAR(32) NULL DEFAULT NULL , 
 `date_sent` DATETIME NOT NULL ,
 `user_id_sender` INT(11) NULL DEFAULT NULL ,
 `user_id_receiver` INT(11) NULL DEFAULT NULL , 
 `email_sender` VARCHAR(100) NULL DEFAULT NULL ,
 `email_receiver` VARCHAR(100) NULL DEFAULT NULL , 
 `message` TEXT NOT NULL ,
 `is_readed` TINYINT(1) NOT NULL DEFAULT '0' ,
 PRIMARY KEY (`idmessages`)) 
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

-- v1.9

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

UPDATE `wp_field` SET `is_preview_visible` = '0' WHERE `wp_field`.`idfield` = 8;

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

ALTER TABLE `wp_invoice` ADD `subscription_id` INT NULL AFTER `listing_id`;



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

-- v2.0

ALTER TABLE `wp_sw_calendar` ADD `user_id` INT NOT NULL AFTER `date_modified`, ADD `listing_id` INT NULL AFTER `user_id`;

ALTER TABLE `wp_sw_reservation` CHANGE `currency_code` `currency_code` VARCHAR(11) NULL DEFAULT NULL;

ALTER TABLE `wp_sw_reservation` ADD `is_payment_completed` BOOLEAN NULL ;

ALTER TABLE `wp_sw_reservation` ADD `guests_number` INT NULL ;

-- v2.1

ALTER TABLE `wp_sw_profile` 
ADD COLUMN `instagram` VARCHAR(160) NULL DEFAULT NULL AFTER `googleplus`;

CREATE TABLE IF NOT EXISTS `wp_sw_treefield_listing` (
`idtreefield_listing` int(11) NOT NULL,
  `treefield_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Support for multiple categories / locations';

ALTER TABLE `wp_sw_treefield_listing`
 ADD PRIMARY KEY (`idtreefield_listing`);

ALTER TABLE `wp_sw_treefield_listing`
MODIFY `idtreefield_listing` int(11) NOT NULL AUTO_INCREMENT;


