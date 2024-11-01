"dont run this file manually!"

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
