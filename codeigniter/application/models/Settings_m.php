<?php

class Settings_m extends My_Model {
    
    protected $_table_name = 'sw_settings';
    protected $_order_by = 'idsettings';
    protected $_primary_key = 'idsettings';
    public $_own_columns = array();

    public $form_index = array();
    
	public function __construct(){
		parent::__construct();
        
        $args = array(
        	'sort_order' => 'asc',
        	'sort_column' => 'post_title',
        	'hierarchical' => 1,
        	'exclude' => '',
        	'include' => '',
        	'meta_key' => '',
        	'meta_value' => '',
        	'authors' => '',
        	'child_of' => 0,
        	'parent' => -1,
        	'exclude_tree' => '',
        	'number' => '',
        	'offset' => 0,
        	'post_type' => 'page',
            'post_status' => 'publish',
            'suppress_filters' => false
        ); 
        
        $current_lang = sw_current_language();
        do_action( 'wpml_switch_language', sw_default_language() );
        $pages = get_pages($args); 
        do_action( 'wpml_switch_language', $current_lang );
        
        $pages_values = array();
        $pages_values[''] = __('Not selected', 'sw_win');
        foreach($pages as $page)
        {
            $pages_values[$page->ID] = $page->post_title;
        }
        
        $this->load->model('currency_m');
        $currency_values = $this->currency_m->get_form_dropdown('currency_code');
        
        $this->form_index = array(
            'noreply' => array('field'=>'noreply', 'label'=>__('Noreply email', 'sw_win'), 'design'=>'input', 'rules'=>'trim|required|valid_email'),
            //'email_activation_enabled' => array('field'=>'email_activation_enabled', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Email user activation', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'use_walker' => array('field'=>'use_walker', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Neighborhood walker for map', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'listing_activation_required' => array('field'=>'listing_activation_required', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Listing activation required', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'multilanguage_required' => array('field'=>'multilanguage_required', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Multilanguage required', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'init_package_id' => array('field'=>'init_package_id', 'design'=>'input',  'label'=>__('Init package id', 'sw_win'), 'rules'=>'trim|integer|required'),
            'date_format_js' => array('field'=>'date_format_js', 'design'=>'input',  'label'=>__('Date format JS', 'sw_win'), 'rules'=>'trim|required'),
            'date_format_php' => array('field'=>'date_format_php', 'design'=>'input',  'label'=>__('Date format PHP', 'sw_win'), 'rules'=>'trim|required'),
            'maps_api_key' => array('field'=>'maps_api_key', 'design'=>'input',  'label'=>__('Maps API Key', 'sw_win'), 'rules'=>'trim'),
            'google_translate_api_key' => array('field'=>'google_translate_api_key', 'design'=>'input',  'label'=>__('Google Translate API Key', 'sw_win'), 'rules'=>'trim'),
            'limit_curl_calls' => array('field'=>'limit_curl_calls', 'design'=>'input',  'label'=>__('Limit CURL calls', 'sw_win'), 'rules'=>'trim'),
            'zoom_index' => array('field'=>'zoom_index', 'label'=>__('Zoom map index (default)', 'sw_win'), 'design'=>'input', 'rules'=>'trim|numeric'),
            'zoom_index_listing' => array('field'=>'zoom_index_listing', 'label'=>__('Zoom map index (listing preview)', 'sw_win'), 'design'=>'input', 'rules'=>'trim|numeric'),
            'auto_set_zoom_disabled' => array('field'=>'auto_set_zoom_disabled', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Disable automatically map zoom basic on lisings positions, on results map', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'auto_translate' => array('field'=>'auto_translate', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Auto translate', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'skip_numbers_copy' => array('field'=>'skip_numbers_copy', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Skip numbers copy to other languages', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'map_fixed_position' => array('field'=>'map_fixed_position', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Map fixed position', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'lat' => array('field'=>'lat', 'class'=>'col-xs-6 col-sm-6', 'design'=>'input_readonly', 'label'=>__('Latitude', 'sw_win'), 'rules'=>'trim|required|numeric'),
            'lng' => array('field'=>'lng', 'class'=>'col-xs-6 col-sm-6', 'design'=>'input_readonly', 'label'=>__('Longitude', 'sw_win'), 'rules'=>'trim|required|numeric'),
            'results_page' => array('field'=>'results_page', 'values'=> $pages_values, 'label'=>__('Results page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim|required'),
            'listing_preview_page' => array('field'=>'listing_preview_page', 'values'=> $pages_values, 'label'=>__('Listing preview page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim|required'),
            'user_profile_page' => array('field'=>'user_profile_page', 'values'=> $pages_values, 'label'=>__('User profile page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim|required'),
            'register_page' => array('field'=>'register_page', 'values'=> $pages_values, 'label'=>__('Register page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim|required'),
            'agents_page' => array('field'=>'agents_page', 'values'=> $pages_values, 'label'=>__('Agents page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim'),
            'agencies_page' => array('field'=>'agencies_page', 'values'=> $pages_values, 'label'=>__('Agencies page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim'),
            'tags_page' => array('field'=>'tags_page', 'values'=> $pages_values, 'label'=>__('Tags page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim'),
            'quick_submission' => array('field'=>'quick_submission', 'values'=> $pages_values, 'label'=>__('Quick submission', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim'),
            'compare_page' => array('field'=>'compare_page', 'values'=> $pages_values, 'label'=>__('Compare page', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim'),
            'per_page' => array('field'=>'per_page', 'label'=>__('Results per page', 'sw_win'), 'design'=>'input', 'rules'=>'trim|required|integer'),
            'recaptcha_site_key' => array('field'=>'recaptcha_site_key', 'label'=>__('Recaptcha site key', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'recaptcha_secret_key' => array('field'=>'recaptcha_secret_key', 'label'=>__('Recaptcha secret key', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'default_currency' => array('field'=>'default_currency', 'values'=> $currency_values, 'label'=>__('Default currency for payments', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim|required', 'hint'=>__('This is only for payments, for listings go to Listings->Fields->Edit price related field and change suffix/preffix', 'sw_win')),
            'default_vat' => array('field'=>'default_vat', 'label'=>__('Default VAT percentage', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'expire_days' => array('field'=>'expire_days', 'label'=>__('Days to expire', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'paypal_email' => array('field'=>'paypal_email', 'label'=>__('PayPal email', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'use_sandbox' => array('field'=>'use_sandbox', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Use sandbox (test payments)', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'bank_details' => array('field'=>'bank_details', 'label'=>__('Bank payment details', 'sw_win'), 'design'=>'textarea', 'rules'=>'trim'),
            'woocommerce_only_pay' => array('field'=>'woocommerce_only_pay', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('WooCommerce payments only enabled', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'facebook_login_enabled' => array('field'=>'facebook_login_enabled', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Enable facebook login', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'facebook_app_id' => array('field'=>'facebook_app_id', 'label'=>__('Facebook App ID', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'facebook_app_secret' => array('field'=>'facebook_app_secret', 'label'=>__('Facebook App Secret', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'sw_purchase_code' => array('field'=>'sw_purchase_code', 'label'=>__('Purchase code', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'terms_link' => array('field'=>'terms_link', 'label'=>__('Terms link', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'watermark_img' => array('field'=>'watermark_img', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Watermark', 'sw_win'), 'design'=>'image', 'rules'=>'trim'),
            'show_categories' => array('field'=>'show_categories', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Show categories', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'show_locations' => array('field'=>'show_locations', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Show locations', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'enable_multiple_results_page' => array('field'=>'enable_multiple_results_page', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Enable multiple results page (for templates with filename part results)', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'recursive_search' => array('field'=>'recursive_search', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Search in sub-categories/sub-locations', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim', 'hint'=>__('Will be disabled if multiple categories/location is enabled', 'sw_win')),
            'transform_user' => array('field'=>'transform_user', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Change subscriber to owner on quick submission', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'hide_map_listingpage' => array('field'=>'hide_map_listingpage', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Hide map on listing page', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'hide_fbcomments_listingpage' => array('field'=>'hide_fbcomments_listingpage', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Hide facebook comments on listing page', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'open_street_map_enabled' => array('field'=>'open_street_map_enabled', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Open street map (instead google map)', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'hide_menu_items_agent' => array('field'=>'hide_menu_items_agent', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Hide menu items for agent', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'hide_menu_items_owner' => array('field'=>'hide_menu_items_owner', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Hide menu items for owner', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'pdf_export_mpdf' => array('field'=>'pdf_export_mpdf', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Pdf export mpdf enable', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'number_format_i18n_enabled' => array('field'=>'number_format_i18n_enabled', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('use i18n wp number format', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'quicksubmission_no_registration' => array('field'=>'quicksubmission_no_registration', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Quicksubmission without registration', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'quicksubmission_gallery_on_top' => array('field'=>'quicksubmission_gallery_on_top', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Quicksubmission gallery on top', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'enable_multiple_treefield' => array('field'=>'enable_multiple_treefield', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Enable multiple category/locations', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim', 'hint'=>__('Experimental Testing feature in BETA', 'sw_win')),
            'disable_reviews_gallery' => array('field'=>'disable_reviews_gallery', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Disable reviews gallery', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'disable_woostore_navigation' => array('field'=>'disable_woostore_navigation', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Disable woo store link at wp top bar', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'private_listings' => array('field'=>'private_listings', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Private listings', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'notify_admin_rel_new_users' => array('field'=>'notify_admin_rel_new_users', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Notify admin on mail related new users', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
        );
        if(!function_exists('sw_win_load_ci_function_qs'))
        {
            unset($this->form_index['quick_submission']);
        }

        if(!function_exists('sw_win_load_ci_function_facebooklogin'))
        {
            unset($this->form_index['facebook_login_enabled']);
            unset($this->form_index['facebook_app_id']);
            unset($this->form_index['facebook_app_secret']);
        }

        if(!function_exists('sw_win_load_ci_function_rankpackages'))
        {
            unset($this->form_index['paypal_email']);
            unset($this->form_index['use_sandbox']);
            unset($this->form_index['bank_details']);
            unset($this->form_index['default_currency']);
            unset($this->form_index['default_vat']);
        }

        if(!function_exists('sw_win_pdf_export'))
        {
            unset($this->form_index['pdf_export_mpdf']);
        }
    }
    
    public function get_fields()
    {
        if(($fields_data = $this->cache_temp_load('fields_data')) === FALSE)
        {
            $query = $this->db->get($this->_table_name);
    
            $fields_data = array();
            
            if(is_object($query))
            foreach($query->result() as $key=>$setting)
            {
                $fields_data[$setting->field] = $setting->value;
            }
            
            $this->cache_temp_save($fields_data, 'fields_data');
        }

        return $fields_data;
    }
    
    public function save_settings($post_data)
    {
        $this->delete_fields($post_data);
        
        $data = array();
        
        if(isset($post_data['open_street_map_enabled']) && $post_data['open_street_map_enabled'] =='1') {
            $post_data['use_walker'] = 0;
        }
            
        foreach($post_data as $key=>$value)
        {
            $data[] = array(
               'field' => $key,
               'value' => $value
            );
        }
        
        $this->db->insert_batch($this->_table_name, $data); 
        
        unset($this->_cache_temp['fields_data']);
    }
    
    public function delete_fields($fields = array())
    {
        $this->db->where_in('field', array_keys($fields));
        $this->db->delete($this->_table_name);
    }
    
    public function get_field($field_name)
    {
        $fields = $this->get_fields();
        
        if(isset($fields[$field_name]))
            return $fields[$field_name];
            
        return NULL;
    }

}