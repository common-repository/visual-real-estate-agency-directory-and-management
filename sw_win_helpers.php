<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$sw_win_head_meta_loaded = false;
function sw_win_generate_head_meta(&$output, $atts)
{
    global $sw_win_head_meta_loaded, $wp_scripts;
    if($sw_win_head_meta_loaded == true)return;
    extract($atts);
    
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    
    $o = get_option('sw_neighborhood_options');

    if(empty($o['sw_map_api']))$o['sw_map_api'] = '';

    $api_key_part='';
    if(sw_settings('maps_api_key')) {
        $api_key_part = "&amp;key=".sw_settings('maps_api_key');
    }
    elseif(!empty($o['sw_map_api']))
    {
        $api_key_part = "&amp;key=".$o['sw_map_api'];
    }
    else 
    {
        $api_key_part = "&amp;key=AIzaSyB0lxCRSHcNPBu5hq3wsmY1KhcBq5Tlwi8";
    }

    sw_win_load_basic_cssjs();

    if( !is_admin()){
        if(sw_settings('open_street_map_enabled')){
            global $config;
            wp_deregister_script('leaflet-maps-api');
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_register_script('leaflet-maps-api',  plugins_url('assets/js/leaflet/leaflet.js', __FILE__), array('jquery'));
            else
                wp_register_script('leaflet-maps-api', $protocol."://unpkg.com/leaflet@1.3.3/dist/leaflet.js", array('jquery'));
                
            wp_enqueue_script('leaflet-maps-api');

            wp_deregister_script('leaflet-maps-api-cluster');
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_register_script('leaflet-maps-api-cluster',  plugins_url('assets/js/leaflet/leaflet.markercluster.js', __FILE__), array('jquery'));
            else
                wp_register_script('leaflet-maps-api-cluster', $protocol."://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js", array('jquery'));
            
            wp_enqueue_script('leaflet-maps-api-cluster');

            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_enqueue_style( 'leaflet-maps-api',  plugins_url('assets/js/leaflet/leaflet.css', __FILE__));
            else
                wp_enqueue_style( 'leaflet-maps-api', $protocol.'://unpkg.com/leaflet@1.3.3/dist/leaflet.css' );
            
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_enqueue_style( 'leaflet-maps-api-cluster-def',  plugins_url('assets/js/leaflet/markercluster.default.css', __FILE__) );
            else
                wp_enqueue_style( 'leaflet-maps-api-cluster-def', $protocol.'://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css' );
                
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_enqueue_style( 'leaflet-maps-api-cluster',  plugins_url('assets/js/leaflet/markercluster.css', __FILE__));
            else
                wp_enqueue_style( 'leaflet-maps-api-cluster', $protocol.'://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css' );
            
        } else {
            wp_deregister_script('google-maps-api-w');
            wp_register_script('google-maps-api-w', $protocol."://maps.google.com/maps/api/js?libraries=places,geometry".$api_key_part."", array('jquery'));
            wp_enqueue_script('google-maps-api-w');
        }
        
    }
    else
    {
        
    }
    
    
    if(sw_settings('open_street_map_enabled')){
        wp_enqueue_script('sw_win_swmap_script', plugins_url('assets/js/sw_win_open_map_obj.js', __FILE__), array('jquery'));
    } else {
        wp_enqueue_script('sw_win_swmap_script', plugins_url('assets/js/sw_win_map_obj.js', __FILE__), array('jquery'));
    }
    
    wp_enqueue_script('sw_my_script', plugins_url('assets/js/script.js', __FILE__), array('jquery'));
    $sw_win_head_meta_loaded = true;
}

function sw_register_admin_resources() {
    
            // Register

            wp_deregister_script('leaflet-maps-api');
            
            wp_enqueue_script('leaflet-maps-api');

            wp_deregister_script('leaflet-maps-api-cluster');
           
            wp_enqueue_script('leaflet-maps-api-cluster');
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        if(sw_settings('open_street_map_enabled')){
            global $config;
            wp_deregister_script('leaflet-maps-api');
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_register_script('leaflet-maps-api',  plugins_url('assets/js/leaflet/leaflet.js', __FILE__), array('jquery'));
            else
                wp_register_script('leaflet-maps-api', $protocol."://unpkg.com/leaflet@1.3.3/dist/leaflet.js", array('jquery'));
                
            wp_deregister_script('leaflet-maps-api-cluster');
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_register_script('leaflet-maps-api-cluster',  plugins_url('assets/js/leaflet/leaflet.markercluster.js', __FILE__), array('jquery'));
            else
                wp_register_script('leaflet-maps-api-cluster', $protocol."://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js", array('jquery'));
            
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_enqueue_style( 'leaflet-maps-api',  plugins_url('assets/js/leaflet/leaflet.css', __FILE__));
            else
                wp_enqueue_style( 'leaflet-maps-api', $protocol.'://unpkg.com/leaflet@1.3.3/dist/leaflet.css' );
            
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_enqueue_style( 'leaflet-maps-api-cluster-def',  plugins_url('assets/js/leaflet/markercluster.default.css', __FILE__) );
            else
                wp_enqueue_style( 'leaflet-maps-api-cluster-def', $protocol.'://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css' );
                
            if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true')
                wp_enqueue_style( 'leaflet-maps-api-cluster',  plugins_url('assets/js/leaflet/markercluster.css', __FILE__));
            else
                wp_enqueue_style( 'leaflet-maps-api-cluster', $protocol.'://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css' );
                
            
        } else {
            wp_register_script('google-maps-api-w', "https://maps.google.com/maps/api/js?key=".sw_settings('maps_api_key'), array('jquery'));
        }   
            
            
            
        wp_enqueue_script( 'editable_table', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/editable_table/jquery.tabledit.min.js', false, false, false );
        wp_register_script( 'blueimp-gallery', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/blueimp-gallery.min.js', false, false, false );
        wp_register_script( 'jquery.iframe-transport', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.iframe-transport.js', false, false, false );
        wp_register_script( 'jquery.fileupload', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.fileupload.js', false, false, false );
        wp_register_script( 'jquery.fileupload-fp', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.fileupload-fp.js', false, false, false );
        wp_register_script( 'jquery.fileupload-ui', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.fileupload-ui.js', false, false, false );
        wp_register_script( 'zebra_dialog', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/zebra/javascript/zebra_dialog.src.js', false, false, false );
        wp_register_script( 'datetime-picker-moment', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/js/moment-with-locales.js', false, false, false );
        wp_register_script( 'datetime-picker-bootstrap', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/js/bootstrap-datetimepicker.min.js', false, false, false );

        wp_register_script( 'datatables', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datatables.js', false, false, false );
        wp_register_script( 'dataTables-responsive', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/dataTables.responsive.js', false, false, false );
        wp_register_script( 'jquery-cropit', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/cropit/jquery.cropit.js', false, false, false );
        wp_register_script( 'jquery-nestedSortable', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/jquery.mjs.nestedSortable.js', false, false, false );
        wp_register_script( 'jquery-magnific-popup', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/magnific-popup/jquery.magnific-popup.js', false, false, false );
        wp_register_script( 'bootstrap3', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/bootstrap.min.js', false, false, false );

        wp_register_style( 'blueimp-gallery', plugins_url( SW_WIN_SLUG.'/assets' ) . '/css/blueimp-gallery.min.css' );
        wp_register_style( 'jquery.fileupload-ui', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/style/jquery.fileupload-ui.css' );
        wp_register_style( 'zebra_dialog', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/zebra/css/flat/zebra_dialog.css' );
        wp_register_style( 'datetime-picker-css', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/css/bootstrap-datetimepicker.css' );
        wp_register_style( 'sw_win_bootstrap_wrapper', plugins_url('assets/css/bootstrap-wrapper.css', __FILE__), false, '1.0.0' );
        wp_register_style( 'bootstrap3', plugins_url('assets/css/bootstrap.css', __FILE__), false, '1.0.0' );
        wp_register_style( 'jquery-magnific-popup', plugins_url('assets/js/magnific-popup/magnific-popup.css', __FILE__), false, '1.0.0' );
        wp_register_style( 'font-awesome', plugins_url('assets//css/font-awesome.min.css', __FILE__), false, '1.0.0' );
        
        if(is_rtl()){
           wp_enqueue_style( 'sw_win-rtl',  plugins_url( SW_WIN_SLUG.'/assets' ) . '/css/style_rtl.css');
        }
}

/**
 * Enqueue scripts and styles.
 */
function sw_scripts() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'sw_scripts' );

function sw_win_load_basic_cssjs()
{
    static $multiple_instance=false;

    if($multiple_instance) return;

    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    wp_register_script('google-maps-api-w', "https://maps.google.com/maps/api/js?key=".sw_settings('maps_api_key'), array('jquery'));
    wp_register_script( 'blueimp-gallery', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/blueimp-gallery.min.js', false, false, false );
    wp_register_script( 'jquery.iframe-transport', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.iframe-transport.js', false, false, false );
    wp_register_script( 'jquery.fileupload', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.fileupload.js', false, false, false );
    wp_register_script( 'jquery.fileupload-fp', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.fileupload-fp.js', false, false, false );
    wp_register_script( 'jquery.fileupload-ui', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/jquery.fileupload-ui.js', false, false, false );
    wp_register_script( 'zebra_dialog', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/zebra/javascript/zebra_dialog.src.js', false, false, false );
    wp_register_script( 'datetime-picker-moment', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/js/moment-with-locales.js', false, false, false );
    wp_register_script( 'datetime-picker-bootstrap', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/js/bootstrap-datetimepicker.min.js', false, false, false );

    wp_register_style( 'blueimp-gallery', plugins_url( SW_WIN_SLUG.'/assets' ) . '/css/blueimp-gallery.min.css' );
    wp_register_style( 'jquery.fileupload-ui', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/fileupload/style/jquery.fileupload-ui.css' );
    wp_register_style( 'zebra_dialog', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/zebra/css/flat/zebra_dialog.css' );
    wp_register_style( 'datetime-picker-css', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/css/bootstrap-datetimepicker.css' );
             
    wp_enqueue_script( 'editable_table', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/editable_table/jquery.tabledit.min.js', false, false, false );
    if( !is_admin()){

        // Bootstrap load
        wp_enqueue_script('sw_win_bootstrap_carousel', plugins_url('assets/js/bootstrap_carousel.min.js', __FILE__), array('jquery'));

        wp_register_style('sw_win_basic_bootstrap', plugins_url('assets/css/basic-bootstrap-wrapper.css', __FILE__), false, '1.0.0' );
        wp_enqueue_style('sw_win_basic_bootstrap', plugins_url('assets/css/basic-bootstrap-wrapper.css', __FILE__));
        
        wp_register_style('sw_win_font_awesome', plugins_url('assets/css/font-awesome.min.css', __FILE__), false, '1.0.0' );
        wp_enqueue_style('sw_win_font_awesome', plugins_url('assets/css/font-awesome.min.css', __FILE__));
        
        wp_enqueue_script('admin_js_helpers', plugins_url('assets/js/jquery.helpers.js', __FILE__), false, '1.0.0', false);

		// dynamic loaded files based on page/template file
		if(function_exists('sw_is_page'))
		{
			if(sw_is_page(sw_settings('quick_submission')))
			{
                wp_enqueue_script( 'jquery' );
                wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, false);
                wp_enqueue_script('jquery-ui-widget', false, array('jquery'), false, false);
                wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, false);
                wp_enqueue_script( 'ui-widget' );
				wp_enqueue_script( 'blueimp-gallery' );
				wp_enqueue_script( 'jquery.iframe-transport' );
				wp_enqueue_script( 'jquery.fileupload' );
				wp_enqueue_script( 'jquery.fileupload-fp' );
				wp_enqueue_script( 'jquery.fileupload-ui' );
				wp_enqueue_script( 'zebra_dialog' );

                wp_enqueue_style( 'blueimp-gallery');
                wp_enqueue_style( 'jquery.fileupload-ui');
                wp_enqueue_style( 'zebra_dialog');
			}
			else if(sw_is_page(sw_settings('listing_preview_page')))
			{
				wp_register_script( 'bootstrap-rating-input', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/ratings/bootstrap-rating-input.js', false, false, false );
				wp_enqueue_script( 'bootstrap-rating-input' );
			}

		}
    }
    else
    {
        //wp_enqueue_media(); -- causing blog post featured issues
    }
    
    wp_enqueue_script('sw_win_b3_typeahead', plugins_url('assets/js/typeahead/bootstrap3-typeahead.js', __FILE__), array('jquery'));

    $multiple_instance = true;
}

function sw_dash_icons() {
    wp_enqueue_style( 'font-awesome', plugins_url('assets/css/font-awesome.min.css', __FILE__), false, '1.0.0' );
}
add_action('admin_enqueue_scripts', 'sw_dash_icons' );

// With this function, only models, helpers, libraries can be used, but not controllers or view files

function sw_win_load_ci_frontend()
{
    if(defined('SW_FROM_WORDPRESS'))return;
    
    // [Load ci main files]
    define('SW_FROM_WORDPRESS', 'yes');
    
    require_once(dirname(__FILE__).'/codeigniter/wordpress_basicindex.php');
    
    // [/Load ci main files]
        
    // [Load some controller for test]

    $class = 'Wordpressfrontend';
    $method = 'index';
    $params = array();
    
    require_once(APPPATH.'controllers/'.$class.'.php');
    
    // Only one controller can be runned in CI, it's designed this way
    $CI = new $class();
    call_user_func_array(array(&$CI, $method), $params);
    //$OUT->_display();
    
//    $query = $CI->db->get('wp_options');
//    foreach ($query->result() as $row)
//    {
//        dump($row);
//    }
    
    // [/Load some controller for test]
    
    return $CI;
}

// With this, specific designed controllers can be executed

function sw_win_load_ci_function($class, $method, $params)
{
    sw_win_load_ci_frontend();
    
    if(sw_settings('loadfest') == '1')
    {
        echo '';
        return;
    }
    
//    $class = 'Widgets';
//    $method = 'index';
//    $params = array(&$output);

    if(!file_exists(APPPATH.'controllers/'.$class.'.php'))
    {
        if(isset($params[0]))
        {
            $params[0].='<p class="error-ci">Controller file not exists: '.$class.'</p>';
        }
        else
        {
            echo '<p class="error-ci">Controller file not exists: '.$class.'</p>';
        }
        
        return;
    }
    
    require_once(APPPATH.'controllers/'.$class.'.php');

    $CIn = new $class();
    
    if(!method_exists($CIn, $method))
    {
        if(isset($params[0]))
        {
            $params[0].='<p class="error-ci">Method not exists: '.$method.'</p>';
        }
        else
        {
            echo '<p class="error-ci">Method not exists: '.$method.'</p>';
        }
    }
    else
    {
        call_user_func_array(array(&$CIn, $method), $params);
    }
}

function sw_settings($item = NULL, $reset=false)
{
    global $wpdb;
    static $sw_settings;

    if(!sw_classified_installed())return NULL;
    
    // reset
    if($reset)
        $sw_settings = NULL;
    
    if(!is_array($sw_settings))
    {
        $myrows = $wpdb->get_results( "SELECT * FROM ".$GLOBALS['table_prefix']."sw_settings" );

        $sw_settings = array();
        foreach($myrows as $row)
        {
            $sw_settings[$row->field] = $row->value;
        }
    }
    
    /* enabled open_street_map_enabled if google map key is empty */
    if(empty($sw_settings['maps_api_key'])) {
        $sw_settings['open_street_map_enabled'] = 1;
    }
    
    if(is_null($item))
    {
        return $sw_settings;
    }
    elseif(isset($sw_settings[$item]))
    {
        return $sw_settings[$item];
    }
    
    return NULL;
}

function sw_widget_options($widget_id)
{
    // example $widget_id: sw_win_contactform_widget-2
    $exp = explode('-', $widget_id);
    
    if(sw_count($exp) < 2)return FALSE;
    
    $widget_name = $exp[0];
    $widget_id = $exp[1];
    
    $options = get_option('widget_'.$widget_name);
    
    if(is_array($options) && isset($options[$widget_id]))
    {
        return $options[$widget_id];
    }
    
    return FALSE;
}

$sw_win_map_id = 0;

function sw_show_google_map(&$output, $atts) {
    global $wpdb, $sw_win_map_id;
    extract($atts);
    
    $sw_win_map_id++;

    $output .= "<div class='sw_win_wrapper'>";
    $output .= "<div class='sw_win_map' id='sw_map_id_$sw_win_map_id'>";
    //add options to html
    foreach($atts as $key=>$value){
        $output .= "<div class='sw-hide $key'>$value</div>";
    }

    
    $output .= "<div class='show_sw_win_map'> </div>";
    $output .= "</div>";
    $output .= "</div>";
}

function sw_win_custom_login()
{
    global $wpdb;
    
    if($_GET['custom_login'] == 'facebook')
    {
        if(sw_settings('facebook_login_enabled') != '1')
            exit('Facebook login disabled');
        
        if (!session_id()) {
            session_start();
        }
        
        sw_win_load_ci_frontend();
        $CI = &get_instance();
        $CI->load->library('MY_Composer');
        
        $callback = get_site_url().'/?custom_login=facebook';

        $fb = new \Facebook\Facebook([
          'app_id' => sw_settings('facebook_app_id'),
          'app_secret' => sw_settings('facebook_app_secret'),
          'default_graph_version' => 'v3.2',
          'redirect_uri' => $callback
          //'default_access_token' => '{access-token}', // optional
        ]);
        
        $helper = $fb->getRedirectLoginHelper();
        
        $permissions = ['email']; // optional
        
        $facebook_login_url = $helper->getLoginUrl($callback, $permissions);
        
        if(isset($_GET['state']))
            $_SESSION['FBRLH_state']=$_GET['state'];
        
        try {
          $accessToken = $helper->getAccessToken($callback);
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // There was an error communicating with Graph
          echo $e->getMessage();
          exit;
        }
        
        try {
          // Get the \Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=name,email,link', $accessToken);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
        
        $me = $response->getGraphUser();
        
        $email_address = $me->getEmail();
        
        if(empty($email_address))
            exit('Email address not provided');
        
//        echo 'Logged in as ' . $me->getName().'<br />';
//        echo 'Logged in as ' . $me->getEmail().'<br />';
//        echo 'Logged in as ' . $me->getLink().'<br />';
//        echo 'Logged in as ' . $me->getId().'<br />';
        
//        dump($me);
//      Dump => object(Facebook\GraphNodes\GraphUser)#474 (1) {
//          ["items":protected] => array(4) {
//            ["name"] => string(12) "Sandi Winter"
//            ["email"] => string(15) "sandi@winter.hr"
//            ["link"] => string(62) "https://www.facebook.com/app_scoped_user_id/10208049941622489/"
//            ["id"] => string(17) "10208049941622489"
//          }
//        }
        
        // If user not exists
        
        if( null == username_exists( $email_address ) )
        {
            // Register user
            
            $user_id = wp_create_user( $email_address, md5($me->getId().LOGGED_IN_KEY), $email_address );

            if ( is_wp_error( $user_id ) ) {
                echo $user_id->get_error_message();
                exit();
            }
            
            // Set the nickname
            wp_update_user(
                array(
                    'ID'          =>    $user_id,
                    'nickname'    =>    $me->getName(),
                    'user_url'    =>    $me->getLink()
                )
            );
            
            $user = new WP_User( $user_id );
            $user->set_role('OWNER');
        }

        // Login user
        
        /**
         * Perform automatic login.
         */
        $creds = array(
            'user_login'    => $email_address,
            'user_password' => md5($me->getId().LOGGED_IN_KEY),
            'remember'      => true
        );
     
        $user = wp_signon( $creds, '' );
        //var_dump($user);
        //var_dump($creds);
     
        if ( is_wp_error( $user ) ) {
            echo $user->get_error_message();
        }
        else
        {
            wp_set_current_user($user->ID);
            wp_redirect(admin_url("")); exit;
            //wp_redirect(get_site_url()); exit;
        }
        
        exit();
    }
    
    echo 'custom login method not found';
    exit();
}

function sw_win_export_controller($method)
{
    sw_win_load_ci_function('Frontendexport', $method, array());

    exit();
}

function sw_win_payment_notify($provider_name = 'Unknown')
{
    global $wpdb;
    
    $payment_query = explode('_', $_GET['payment']);
    $parameters = $_POST;

    if(isset($_GET['payment_user']))
        $payment_user = $_GET['payment_user'];
    
    // Check md5
    if(isset($payment_query[1]))
    {
        if(md5(SECURE_AUTH_KEY.$payment_query[0]) != $payment_query[1])
        {
//            http_response_code(404);
//            die();
            
            exit('Notify failed, wrong query: '.$_GET['payment']);
        }
    }
    else
    {
        exit('Wrong query');
    }

    $invoice_id = $payment_query[0];
    
    sw_win_load_ci_frontend();
    
    $CI = &get_instance();

    if(isset($payment_user)) // if subscriptio
    {
        // get package id
        $product_id = $invoice_id;

        $CI->load->model('listing_m');
        $CI->load->model('user_m');
        $CI->load->model('profile_m');
        $CI->load->model('subscriptions_m');

        $package_details = $CI->subscriptions_m->get_by(array('woo_item_id'=>$product_id), true);

        if(is_object($package_details))
        {
            $package_id = $package_details->idsubscriptions;

            $user = get_userdata( $payment_user );
            $user_package_id = profile_data($user, 'package_id');
            $user_package_expire = profile_data($user, 'package_expire');
    
            $user_id = $payment_user;
    
            $profile = $CI->profile_m->get_by(array('user_id'=>$user_id), TRUE);
            
            $days_expire = intval((strtotime($user_package_expire)-time())/86400);
    
            if($days_expire < 0) $days_expire = 0;
    
            // extend/change package
            $data_user_new = array();
    
            $days_expire_new = date('Y-m-d H:i:s', time()+$days_expire*86400+intval($package_details->days_limit)*86400);
    
            // Activate services to related listing
            $data_update = array();
            $data_update['package_id'] = $package_id;
            $data_update['package_expire'] = $days_expire_new;
            $data_update['user_id'] = $user_id;

            $CI->profile_m->save($data_update, $profile->idprofile);

            // change all listing date_modified to current, like edited now because of days expired problem

            $CI->listing_m->update_user_listings($user_id);
        }
        else
        {
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.sw_settings('noreply');
            
            $subject = __('IMPORTANT, issue with package reactivation', 'sw_win');
            $message = __('Package not found for product ID', 'sw_win').': #'.$product_id.'<br />';
            $message.= __('User ID related', 'sw_win').': #'.$payment_user;
            
            $ret1 = wp_mail( get_bloginfo( 'admin_email' ), $subject, $message, $headers );
        }

        return;
    }


    $dir = APPPATH.'libraries/payment_providers/';

    // Open a directory, and read its contents
    if (is_dir($dir) && $provider_name == 'Unknown'){
      if ($dh = opendir($dir)){
        while (($file = readdir($dh)) !== false){
            if ($file != "." && $file != "..") {
                include(APPPATH.'libraries/payment_providers/'.$file);
                
                $class = substr($file, 0, strpos($file, '.php'));
                $object = new $class();
                
                if($object->check_notify($parameters))
                {
                    $provider_name = $object->get_name();
                }
            }
        }
        closedir($dh);
      }
    }
    
    // Save transaction details
    
    $CI->load->model('invoice_m');
    
    $invoice = $CI->invoice_m->get($invoice_id);
    
    if(!empty($invoice->date_paid) || !empty($invoice->is_activated))
    {
        if($provider_name == 'WooCommerce')
        {
            error_log( "Already paid" );
            return;
        }
        
        exit('Already paid');
    }

    $data_json = json_decode($invoice->data_json);
    $data_json->transaction = $parameters;
    
    $data_update = array();
    $data_update['data_json'] = json_encode($data_json);
    
    $data_update['paid_via'] = $provider_name;
    $data_update['date_paid'] = date('Y-m-d H:i:s', time());
    $data_update['is_activated'] = 1;
    
    $CI->invoice_m->save($data_update, $invoice_id);
    
    // Activate service rank packages
    if(empty($invoice->is_activated) && !empty($invoice->listing_id))
    {
        //Get package details
        if(isset($data_json->item->package_price) && $data_json->item->package_days > 0)
        {
            $CI->load->model('listing_m');
            $CI->load->model('user_m');
            
            // Activate services to related listing
            $listing_id = $invoice->listing_id;
            $listing = $CI->listing_m->get($listing_id);
            $package_days = $data_json->item->package_days;
            
            $data_update = array();
            $data_update['rank'] = $data_json->item->rank;
            $data_update['date_rank_expire'] = date('Y-m-d H:i:s', time() + $package_days*24*60*60);
            
            if(empty($listing->is_activated) && 
               empty($listing->is_disalbed))
            {
                $data_update['is_activated'] = 1;
                $data_update['date_activated'] = date('Y-m-d H:i:s', time());
            }
            
            $CI->listing_m->save($data_update, $listing_id);
            
            // Inform user that invoice services are activated
            
            // send email to client
            $client = $CI->user_m->get($invoice->user_id);
            $email_address = $client->user_email;
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.sw_settings('noreply');
            
            $subject = __('Thanks on your payment', 'sw_win');
            $message = __('Activated services related to invoice', 'sw_win').': #'.$invoice->invoicenum;
            
            $ret1 = wp_mail( $email_address, $subject, $message, $headers );
        }
    }

    // Activate service subscription package
    if(empty($invoice->is_activated) && !empty($invoice->subscription_id))
    {

        //dump($data_json);
        //exit();

        //Get package details
        if(isset($data_json->item->subscription_price) && $data_json->item->days_limit > 0)
        {
            $CI->load->model('listing_m');
            $CI->load->model('user_m');
            $CI->load->model('profile_m');
            $CI->load->model('subscriptions_m');


            $user = get_userdata( $invoice->user_id );
            $user_package_id = profile_data($user, 'package_id');
            $user_package_expire = profile_data($user, 'package_expire');
    
            $user_id = $invoice->user_id;

            $profile = (object) $CI->profile_m->get_by(array('user_id'=>$user_id), TRUE);
            
            $days_expire = intval((strtotime($user_package_expire)-time())/86400);

            if($days_expire < 0) $days_expire = 0;

            // extend/change package
            $data_user_new = array();
    
            $days_expire_new = date('Y-m-d H:i:s', time()+$days_expire*86400+intval($data_json->item->days_limit)*86400);

            
            // Activate services to related listing
            $data_update = array();
            $data_update['package_id'] = $invoice->subscription_id;
            $data_update['package_expire'] = $days_expire_new;
            
            $CI->profile_m->save($data_update, $profile->idprofile);

            // change all listing date_modified to current, like edited now because of days expired problem

            $CI->listing_m->update_user_listings($user_id);
            
            // Inform user that invoice services are activated
            
            // send email to client
            $client = $CI->user_m->get($invoice->user_id);
            $email_address = $client->user_email;
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.sw_settings('noreply');
            
            $subject = __('Thanks on your payment', 'sw_win');
            $message = __('Activated services related to invoice', 'sw_win').': #'.$invoice->invoicenum;
            
            $ret1 = wp_mail( $email_address, $subject, $message, $headers );
        }
    }

    if($provider_name == 'WooCommerce')
    {
        return;
    }
    
    exit();
}

function sw_win_show_plugin_date()
{
    $data = array();
    $data['pcode'] = sw_settings('sw_purchase_code');
    $data['email'] = get_option( 'admin_email' );
    echo json_encode($data);
    exit();
}

function sw_notice($message)
{
    $output = '<div class="alert alert-info" role="alert">'.$message.'</div>';
    
    //echo $output;
    
    return $output;
}

function sw_remove_querystring_var($url, $key) { 
	$url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
	$url = substr($url, 0, -1); 
	return $url; 
}

function sw_wp_editor($content, $id, $settings=NULL)
{
    if($settings === NULL)$settings=array('textarea_rows'=>3, 'drag_drop_upload'=>true);
    
    wp_editor( stripslashes($content), $id, $settings );
}

function sw_add_wp_image($filename_source)
{
    $file = $filename_source;
    $filename = basename($file);
    
    $parent_post_id = 0;
    
    $upload_file = wp_upload_bits($filename, null, file_get_contents($file));
    if (!$upload_file['error']) {
    	$wp_filetype = wp_check_filetype($filename, null );
    	$attachment = array(
    		'post_mime_type' => $wp_filetype['type'],
    		'post_parent' => $parent_post_id,
    		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
    		'post_content' => '',
    		'post_status' => 'inherit'
    	);
    	$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
    	if (!is_wp_error($attachment_id)) {
    		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
    		wp_update_attachment_metadata( $attachment_id,  $attachment_data );
            
            return $attachment_id;
    	}
    }
    
    return NULL;
}

function sw_classified_installed()
{
    //if(isset($_GET['not_installed']) && $_GET['not_installed'] == 'true')
    //    return FALSE;
    
    global $wpdb;
    
    $table_name = $wpdb->prefix.'sw_invoice';
    
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
    {
        return FALSE;
    }
    
    return TRUE;
}

function sw_get_compatible_theme()
{
    $first_compatible = '';
    
    if(!is_dir(get_theme_root().'/'.get_template().'/SW_Win_Classified'))
    if ($handle = opendir(get_theme_root())) {
        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            if(is_dir(get_theme_root().'/'.$entry.'/SW_Win_Classified'))
            {
                $first_compatible = $entry;
            }
        }
        closedir($handle);
    }
    
    return $first_compatible;
}

function sw_get_compatible_plugins()
{
    $compatible_plugins = array('revslider/revslider.php',
                                'elementor/elementor.php',
                                'elementor-local/elementor-local.php',
                                'elementor-yordy/elementor-yordy.php',
                                'elementor-moison/elementor-moison.php',
                                'elementor-devon/elementor-devon.php',
                                'elementor-selio/elementor-selio.php',
                                'js_composer/js_composer.php',
                                'SW_Neighborhood_Walker/sw_neighborhood_walker.php',
                                'SW_Win_Nexos/sw_win_nexos.php',
                                SW_WIN_SLUG.'_Compare/sw_win_classified_compare.php',
                                SW_WIN_SLUG.'_Report/sw_win_classified_report.php',
                                SW_WIN_SLUG.'_Savesearch/sw_win_classified_savesearch.php',
                                SW_WIN_SLUG.'_Pdf/sw_win_classified_pdf.php',
                                SW_WIN_SLUG.'_Currencyconverter/sw_win_classified_currencyconverter.php',
                                SW_WIN_SLUG.'_Dependentfields/sw_win_classified_dependentfields.php',
                                SW_WIN_SLUG.'_Facebooklogin/sw_win_classified_facebooklogin.php',
                                SW_WIN_SLUG.'_Favorites/sw_win_classified_favorites.php',
                                SW_WIN_SLUG.'_Geomap/sw_win_classified_geomap.php',
                                SW_WIN_SLUG.'_Quicksubmission/sw_win_classified_quicksubmission.php',
                                SW_WIN_SLUG.'_Rankpackages/sw_win_classified_rankpackages.php',
                                SW_WIN_SLUG.'_Reviews/sw_win_classified_reviews.php',
                                'SW_Win_Devon_Share/sw_win_devon_share.php',
                                'SW_Win_Yordy_Share/sw_win_yordy_share.php',
                                'SW_Win_Yordy_Widgets/sw_win_yordy_widgets.php',
                                'SW_Win_Devon_Widgets/sw_win_devon_widgets.php',
                                'SW_Comments_Emoji/sw_comments_emoji.php',
                                'SW_Win_Selio_Share/sw_win_selio_share.php',
                                'SW_Win_Selio_Widgets/sw_win_selio_widgets.php',
                                'SW_Win_Moison_Share/sw_win_moison_share.php',
                                'SW_Win_Moison_Widgets/sw_win_moison_widgets.php',
                                SW_WIN_SLUG.'_Subscriptions/sw_win_classified_subscriptions.php',
                                SW_WIN_SLUG.'_Calendar/sw_win_classified_calendar.php',
                            );

    $non_activated = array();

    
    foreach($compatible_plugins as $file)
    {
        if(file_exists(WP_PLUGIN_DIR.'/'.$file))
        {
            if ( is_plugin_active( $file ) ) {
                //plugin is activated
            } 
            else
            {
                $non_activated[] = $file;
            }
        }
    }
    
    return $non_activated;
}

function sw_install_compatible_plugins()
{
    $compatible_plugins = array('revslider/revslider.php',
                                'elementor/elementor.php',
                                'elementor-local/elementor-local.php',
                                'elementor-yordy/elementor-yordy.php',
                                'elementor-moison/elementor-moison.php',
                                'elementor-devon/elementor-devon.php',
                                'elementor-selio/elementor-selio.php',
                                'js_composer/js_composer.php',
                                'SW_Neighborhood_Walker/sw_neighborhood_walker.php',
                                'SW_Win_Nexos/sw_win_nexos.php',
                                SW_WIN_SLUG.'_Compare/sw_win_classified_compare.php',
                                SW_WIN_SLUG.'_Report/sw_win_classified_report.php',
                                SW_WIN_SLUG.'_Savesearch/sw_win_classified_savesearch.php',
                                SW_WIN_SLUG.'_Pdf/sw_win_classified_pdf.php',
                                SW_WIN_SLUG.'_Currencyconverter/sw_win_classified_currencyconverter.php',
                                SW_WIN_SLUG.'_Dependentfields/sw_win_classified_dependentfields.php',
                                SW_WIN_SLUG.'_Facebooklogin/sw_win_classified_facebooklogin.php',
                                SW_WIN_SLUG.'_Favorites/sw_win_classified_favorites.php',
                                SW_WIN_SLUG.'_Geomap/sw_win_classified_geomap.php',
                                SW_WIN_SLUG.'_Quicksubmission/sw_win_classified_quicksubmission.php',
                                SW_WIN_SLUG.'_Rankpackages/sw_win_classified_rankpackages.php',
                                SW_WIN_SLUG.'_Reviews/sw_win_classified_reviews.php',
                                'SW_Win_Devon_Share/sw_win_devon_share.php',
                                'SW_Win_Yordy_Share/sw_win_yordy_share.php',
                                'SW_Win_Devon_Widgets/sw_win_devon_widgets.php',
                                'SW_Comments_Emoji/sw_comments_emoji.php',
                                'SW_Win_Selio_Share/sw_win_selio_share.php',
                                'SW_Win_Selio_Widgets/sw_win_selio_widgets.php',
                                'SW_Win_Yordy_Widgets/sw_win_yordy_widgets.php',
                                'SW_Win_Moison_Share/sw_win_moison_share.php',
                                'SW_Win_Moison_Widgets/sw_win_moison_widgets.php',
                                SW_WIN_SLUG.'_Subscriptions/sw_win_classified_subscriptions.php',
                                SW_WIN_SLUG.'_Calendar/sw_win_classified_calendar.php'
                            );

    $activated = array();

    
    foreach($compatible_plugins as $file)
    {
        if(file_exists(WP_PLUGIN_DIR.'/'.$file))
        {
            if ( is_plugin_active( $file ) ) {
                //plugin is activated
            } 
            else
            {
                sw_run_activate_plugin($file);

                $activated[] = $file;
            }
        }
    }
    
    return $activated;
}

function sw_run_activate_plugin( $plugin ) {

    $current = get_option( 'active_plugins' );
    $plugin = plugin_basename( trim( $plugin ) );

    if ( file_exists(WP_PLUGIN_DIR.'/'.$plugin) )
    if ( !in_array( $plugin, $current ) ) {
        $current[] = $plugin;
        sort( $current );
        do_action( 'activate_plugin', trim( $plugin ) );
        update_option( 'active_plugins', $current );
        do_action( 'activate_' . trim( $plugin ) );
        do_action( 'activated_plugin', trim( $plugin) );
    }

    return null;
}

// QTranslate X example
//echo 'DATA:<br />';
//echo 'CURRENT: '.qtranxf_getLanguage().'<br />';
//echo 'DEFAULT: '.qtranxf_getLanguageDefault().'<br />';
//echo 'ALL LANGUAGES: ';dump(qtranxf_getSortedLanguages()).'<br />';



if ( ! function_exists('sw_get_languages'))
{

    function sw_get_languages($lang_code_id = NULL)
    {
        
        
        $langauges = array();
        $langauges[1] = array('title'=>get_bloginfo("language"), 'lang_code'=>'en', 'id'=>1);
        //$langauges[2] = array('title'=>__('Croatian', 'sw_win'), 'lang_code'=>'hr', 'id'=>2);
        
        // [qTranslate X]
        if(function_exists('qtranxf_getSortedLanguages'))
        {
            global $q_config;
            
            // for wp all import, must be tested
            if(sw_count($q_config) == 0)
            {
                //error_reporting(0);
                //qtranxf_init_language();
            }

            $all_langs = qtranxf_getSortedLanguages();

            if(sw_count($all_langs) > 0)
            {
                $langauges = array();
                
                foreach(qtranxf_getSortedLanguages() as $key=>$lang_code)
                {
                    $langauges[$key+1] = array('title'=>$q_config['language_name'][$lang_code], 'lang_code'=>$lang_code, 'id'=>$key+1);
                }
            }

        }
        else{
            
        }
        // [/qTranslate X]

        // [WPML]
        if(function_exists('icl_sw_get_languages'))
        {
            $langauges = array();
            $wpml_langs = icl_sw_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');

            $k=0;
            foreach($wpml_langs as $key=>$lang_data)
            {
                $k++;
                $langauges[$k] = array('title'=>$lang_data['translated_name'], 'lang_code'=>$lang_data['code'], 'id'=>$k);
            }
        }

        // WPML 4
        // Also used for polylang, required activated "WPML compatibility mode of Polylang"
        elseif(has_filter('wpml_active_languages'))
        {
            $langauges = array(); // for polylang, WPML don't need that because of strange 
                                  // default language mechanism in polylang

            $wpml_langs = apply_filters( 'wpml_active_languages', NULL );
            
            if(empty($wpml_langs))
                $wpml_langs = get_query_var('lang', 'all');
            
            $k=0;
            foreach($wpml_langs as $key=>$lang_data)
            {
                $k++;

                // for polylang
                if(!isset($lang_data['code']) && isset($lang_data['language_code'])) 
                    $lang_data['code'] = $lang_data['language_code'];

                if(empty($lang_data['translated_name']) && !empty($lang_data['native_name']))
                    $lang_data['translated_name'] = $lang_data['native_name'];
                // for polylang, end

                $langauges[$lang_data['id']] = array('title'=>$lang_data['translated_name'], 'lang_code'=>$lang_data['code'], 'id'=>$lang_data['id']);
            }

        }

        // [/WPML]
        
        if(!empty($lang_code_id))
        {
            if(is_numeric($lang_code_id))
            {
                foreach($langauges as $lang)
                {
                    if($lang['id'] == $lang_code_id)
                    {
                        return $lang['lang_code'];
                    }
                }
            }
            else
            {
                foreach($langauges as $lang)
                {
                    if($lang['lang_code'] == $lang_code_id)
                    {
                        return $lang['id'];
                    }
                }
            }
            
            return FALSE;
        }
        
        return $langauges;
    }

}

if ( ! function_exists('sw_default_language'))
{

    function sw_default_language()
    {
        // [qTranslate X]
        if(function_exists('qtranxf_getLanguage'))
        {
            return qtranxf_getLanguageDefault();
        }
        // [/qTranslate X]
        
        // [WPML]
        if(function_exists('icl_sw_get_languages'))
        {
            $langauges = array();
            $wpml_langs = icl_sw_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
            foreach($wpml_langs as $key=>$lang_data)
            {
                if($lang_data['major'] == 1)
                    return $lang_data['code'];
            }
        }

        // WPML 4

        elseif(has_filter('wpml_default_language'))
        {
            $langauges = array();
            $wpml_langs = apply_filters( 'wpml_default_language', NULL );

            return $wpml_langs;
        }

        // [/WPML]

        return 'en';
    }

}

if ( ! function_exists('sw_current_language'))
{

    function sw_current_language()
    {
        // [qTranslate X]
        if(function_exists('qtranxf_getLanguage'))
        {
            return qtranxf_getLanguage();
        }
        
        // [WPML]
        if(function_exists('icl_sw_get_languages'))
        {
            $langauges = array();
            $wpml_langs = icl_sw_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
            foreach($wpml_langs as $key=>$lang_data)
            {
                if($lang_data['active'] == 1)
                    return $lang_data['code'];
            }
        }

        // WPML 4

        elseif(has_filter('wpml_current_language'))
        {
            $langauges = array();
            $wpml_langs = apply_filters( 'wpml_current_language', NULL ); // returning only lang code
            
            if($wpml_langs !== FALSE)
                return $wpml_langs;
        }

        // [/WPML]
        
        return 'en';
    }

}

if ( ! function_exists('sw_current_language_id'))
{

    function sw_current_language_id()
    {
        $lang_id = sw_get_languages(sw_current_language());
        
        if(is_numeric($lang_id))return $lang_id;
        
        return '1';
    }

}

if ( ! function_exists('sw_default_language_id'))
{
    function sw_default_language_id()
    {
        $lang_id = sw_get_languages(sw_default_language());
        
        if(is_numeric($lang_id))return $lang_id;
        
        return '1';
    }
}

if ( ! function_exists('sw_get_language_name'))
{
    function sw_get_language_name($id_or_code)
    {
        $langs = sw_get_languages();
        
        foreach($langs as $lang)
        {
            if($lang['lang_code'] == $id_or_code || $lang['id'] == $id_or_code)
            {
                return $lang['title'];
            }
        }

        return '';
    }
}


if ( ! function_exists('sw_get_language_url'))
{

    function sw_get_language_url($lang_code)
    {
        // [qTranslate X]
        if(function_exists('qtranxf_convertURL'))
        {
            return qtranxf_convertURL('', $lang_code, false, true);
        }
        // [/qTranslate X]
        
        // [WPML]
        if(function_exists('icl_sw_get_languages'))
        {
            $langauges = array();
            $wpml_langs = icl_sw_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
            
            foreach($wpml_langs as $key=>$lang_data)
            {
                if($lang_data['code'] == $lang_code)
                    return $lang_data['url'];
            }
        }

        // WPML 4

        elseif(has_filter('wpml_active_languages'))
        {
            $langauges = array();
            $wpml_langs = apply_filters( 'wpml_active_languages', NULL );

            foreach($wpml_langs as $key=>$lang_data)
            {
                // for polylang
                if(!isset($lang_data['code']) && isset($lang_data['language_code'])) 
                $lang_data['code'] = $lang_data['language_code'];
                if(empty($lang_data['translated_name']) && !empty($lang_data['native_name']))
                    $lang_data['translated_name'] = $lang_data['native_name'];
                // for polylang, end

                $url = sw_wpml_ls_language_url( $lang_data['url'], $lang_data );
                $lang_data['url'] = $url;

                if($lang_data['code'] == $lang_code)
                    return $url;
            }

        }

        // [/WPML]
        
    }
    
}


function sw_is_user_in_role( $user, $role  ) {
    return in_array( $role, $user->roles );
}

function sw_is_logged_user()
{
    $current_user = wp_get_current_user();
    if ( 0 == $current_user->ID ) {
        return false;
    } else {
        // Logged in.
        return true;
    }
}

function sw_get_current_user_role() {
    if( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $role = ( array ) $user->roles;
      return $role[0];
    } else {
      return '';
    }
   }

function sw_user_in_role($role)
{
    if(!sw_is_logged_user())
        return false;
    
    $current_user = wp_get_current_user();
    
    return sw_is_user_in_role( $current_user, $role  );
}

function sw_win_upload_dir()
{
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['baseurl'];
    return preg_replace('/^https?:/', '', $upload_dir.'/sw_win');
}

function sw_win_upload_path()
{
    //$dir = ABSPATH . UPLOADS . '/sw_win/';

    $dir = WP_CONTENT_DIR . '/uploads/sw_win/';
    return $dir;
}

function sw_win_create_folders()
{
    //Create files if not exists
    if(!is_dir(WP_CONTENT_DIR . '/uploads/sw_win/'))
    {
        mkdir(WP_CONTENT_DIR . '/uploads/sw_win/');
    }

    if(!is_dir(WP_CONTENT_DIR . '/uploads/sw_win/files/'))
    {
        mkdir(WP_CONTENT_DIR . '/uploads/sw_win/files/');
    }

    if(!is_dir(WP_CONTENT_DIR . '/uploads/sw_win/files/thumbnail'))
    {
        mkdir(WP_CONTENT_DIR . '/uploads/sw_win/files/thumbnail');
    }

    if(!is_dir(WP_CONTENT_DIR . '/uploads/sw_win/files/strict_cache'))
    {
        mkdir(WP_CONTENT_DIR . '/uploads/sw_win/files/strict_cache');
    }

}

function sw_win_table_exists($table)
{
    global $wpdb;
    $table_name = $wpdb->prefix.$table;
    return ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name);
}

function sw_win_prepare_json($json)
{
    $json = str_replace("\r", '', $json);
    $json = str_replace('"', '\"', $json);
    //$json = str_replace("\t", '', $json);
    $json = str_replace("\n", '<br />', $json);

    return $json;
}

function sw_win_prepare_json_basic($json)
{
    $json = str_replace("\r", '', $json);
    $json = str_replace("\n", '<br />', $json);

    return $json;
}

function sw_win_viewe($str)
{
    echo $str;
}

if(!function_exists('sw_count')) {
    function sw_count($mixed='') {
        $count = 0;
        
        if(!empty($mixed) && (is_array($mixed))) {
            $count = count($mixed);
        } else if(!empty($mixed) && function_exists('is_countable') && version_compare(PHP_VERSION, '7.3', '<') && is_countable($mixed)) {
            $count = count($mixed);
        }
        else if(!empty($mixed) && is_object($mixed)) {
            $count = 1;
        }
        return $count;
    }
}

if(!function_exists('get_current_url'))
{
    function get_current_url()
    {
        global $wp;
        $current_url = home_url(add_query_arg(array(),$wp->request));
        
        return $current_url;
    }
}


if ( ! function_exists('sw_listing_url'))
{
    function sw_listing_url($listing)
    {
        $listing_uri = $listing->idlisting;
        
        if(!empty($listing->slug))
            $listing_uri = $listing->slug;

        $custom_uri='';
        if(substr_count(get_permalink(sw_settings('listing_preview_page')), '?') > 0)
        {
            // if doesn't using custom permalink / mod_rewrite
            $custom_uri = '&slug=';
        }
        return get_permalink(sw_settings('listing_preview_page')).$custom_uri.$listing_uri;
    }
}


if ( ! function_exists('sw_agent_url'))
{
    function sw_agent_url($user)
    {
        $listing_uri = $user->ID;
        
        if(!empty($user->user_nicename))
            $listing_uri = $user->user_nicename;
        
        $custom_uri='';
        if(substr_count(get_permalink(sw_settings('user_profile_page')), '?') > 0)
        {
            // if doesn't using custom permalink / mod_rewrite
            $custom_uri = '&slug=';
        }
        
        return get_permalink(sw_settings('user_profile_page')).$custom_uri.$listing_uri;
    }
}

if(!function_exists('sw_strtolower')){
    function sw_strtolower($string){ 
      $convert_to = array( 
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", 
        "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï", 
        "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж", 
        "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", 
        "ь", "э", "ю", "я" 
      ); 
      $convert_from = array( 
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", 
        "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï", 
        "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", 
        "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ", 
        "Ь", "Э", "Ю", "Я" 
      ); 

      return str_replace($convert_from, $convert_to, $string); 
    } 
} 

function sw_remove_admin_bar_links() {
    global $wp_admin_bar;
    
    if(sw_settings('disable_woostore_navigation'))
        $wp_admin_bar->remove_menu('view-store');
}

add_action( 'wp_before_admin_bar_render', 'sw_remove_admin_bar_links' );