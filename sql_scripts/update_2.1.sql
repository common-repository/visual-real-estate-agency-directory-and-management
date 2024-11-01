"dont run this file manually!"

ALTER TABLE `wp_sw_profile` 
ADD COLUMN `instagram` VARCHAR(160) NULL DEFAULT NULL AFTER `googleplus`;

-- for multiselect location/category

CREATE TABLE IF NOT EXISTS `wp_sw_treefield_listing` (
`idtreefield_listing` int(11) NOT NULL,
  `treefield_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Support for multiple categories / locations';

ALTER TABLE `wp_sw_treefield_listing`
 ADD PRIMARY KEY (`idtreefield_listing`);

ALTER TABLE `wp_sw_treefield_listing`
MODIFY `idtreefield_listing` int(11) NOT NULL AUTO_INCREMENT;

