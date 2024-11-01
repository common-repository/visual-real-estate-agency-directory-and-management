"dont run this file manually!"

# Remove all plugin tables

DROP TABLE `wp_sw_cacher`, `wp_sw_currency`, `wp_sw_favorite`, `wp_sw_field`, `wp_sw_field_lang`, `wp_sw_file`, 
           `wp_sw_inquiry`, `wp_sw_invoice`, `wp_sw_listing`, `wp_sw_listing_agent`, `wp_sw_listing_field`, `wp_sw_listing_lang`, 
           `wp_sw_packagerank`, `wp_sw_repository`, `wp_sw_review`, `wp_sw_search_form`, `wp_sw_settings`, `wp_sw_slug`, `wp_sw_report`;
           
DROP TABLE `wp_sw_dependentfields`, `wp_sw_treefield`, `wp_sw_treefield_lang`;

DROP TABLE `wp_sw_profile`, `wp_sw_savesearch`;

# Old version remove

DROP TABLE `wp_cacher`, `wp_currency`, `wp_favorite`, `wp_field`, `wp_field_lang`, `wp_file`, 
           `wp_inquiry`, `wp_invoice`, `wp_listing`, `wp_listing_agent`, `wp_listing_field`, `wp_listing_lang`, 
           `wp_packagerank`, `wp_repository`, `wp_review`, `wp_search_form`, `wp_settings`, `wp_slug`, `wp_report`;
           
DROP TABLE `wp_dependentfields`, `wp_treefield`, `wp_treefield_lang`;

DROP TABLE `wp_profile`, `wp_savesearch`;


# Unlock fields

UPDATE `wp_sw_field` SET `is_hardlocked`=0;

# fix table update

ALTER TABLE `wp_file`
  RENAME TO `wp_sw_file`;

ALTER TABLE `wp_cacher`
  RENAME TO `wp_sw_cacher`;

ALTER TABLE `wp_currency`
  RENAME TO `wp_sw_currency`;

ALTER TABLE `wp_favorite`
  RENAME TO `wp_sw_favorite`;

ALTER TABLE `wp_field`
  RENAME TO `wp_sw_field`;

ALTER TABLE `wp_field_lang`
  RENAME TO `wp_sw_field_lang`;

ALTER TABLE `wp_inquiry`
  RENAME TO `wp_sw_inquiry`;

ALTER TABLE `wp_invoice`
  RENAME TO `wp_sw_invoice`;

ALTER TABLE `wp_listing`
  RENAME TO `wp_sw_listing`;

ALTER TABLE `wp_listing_agent`
  RENAME TO `wp_sw_listing_agent`;

ALTER TABLE `wp_listing_field`
  RENAME TO `wp_sw_listing_field`;

ALTER TABLE `wp_listing_lang`
  RENAME TO `wp_sw_listing_lang`;

ALTER TABLE `wp_packagerank`
  RENAME TO `wp_sw_packagerank`;

ALTER TABLE `wp_repository`
  RENAME TO `wp_sw_repository`;

ALTER TABLE `wp_review`
  RENAME TO `wp_sw_review`;

ALTER TABLE `wp_search_form`
  RENAME TO `wp_sw_search_form`;

ALTER TABLE `wp_settings`
  RENAME TO `wp_sw_settings`;

ALTER TABLE `wp_slug`
  RENAME TO `wp_sw_slug`;
