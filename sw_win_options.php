<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SW_Win_Adminlistings {
    
    public $options;
    
    public function __construct()
    {

    }
    
    public static function add_menu_page()
    {
        //add_options_page('Listings', 'Listings settings', 'administrator', __FILE__, array('SW_Win_Listings', 'display_options_page'));
        global $config;

        // Add a new top-level menu (ill-advised):
        add_menu_page(__('Listings','sw_win'), __('Listings','sw_win'), 
                        'manage_options', 'listing_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'),
                        /*plugins_url( SW_WIN_SLUG.'/images/location.png' ),*/
                        'dashicons-location',
                        30 );

        // Add a submenu to the custom top-level menu:
        
        add_submenu_page('listing_manage', 
                            __('Manage','sw_win'), 
                            __('Manage','sw_win'),
                            'manage_options', 'listing_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
                            
        add_submenu_page('listing_manage', 
                            __('Add listing','sw_win'), 
                            __('Add listing','sw_win'),
                            'manage_options', 'listing_addlisting', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
    
        add_submenu_page('listing_manage', 
                            __('Fields','sw_win'), 
                            __('Fields','sw_win'),
                            'manage_options', 'listing_fields', array('SW_Win_Adminlistings', 'sw_win_load_ci'));

        add_submenu_page('listing_manage', 
                            __('Add field','sw_win'), 
                            __('Add field','sw_win'),
                            'manage_options', 'listing_addfield', array('SW_Win_Adminlistings', 'sw_win_load_ci'));

        add_submenu_page('listing_manage', 
                            __('Categories','sw_win'), 
                            __('Categories','sw_win'),
                            'manage_options', 'treefield_categories', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        
        if(sw_settings('show_locations'))
        {
            add_submenu_page('listing_manage', 
                                __('Locations','sw_win'), 
                                __('Locations','sw_win'),
                                'manage_options', 'treefield_locations', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        }
                            
        add_submenu_page('listing_manage', 
                            __('Search form','sw_win'), 
                            __('Search form','sw_win'),
                            'manage_options', 'listing_searchform', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        
        add_submenu_page('listing_manage', 
                            __('Result item','sw_win'), 
                            __('Result item','sw_win'),
                            'manage_options', 'listing_resultitem', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        
        if(function_exists('sw_show_favorites'))
        {
            add_submenu_page('listing_manage', 
            __('Favorites','sw_win'), 
            __('Favorites','sw_win'),
            'manage_options', 'listing_favorites', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        }
        
        if(function_exists('sw_show_reviews'))
        {
        add_submenu_page('listing_manage', 
                            __('Reviews','sw_win'), 
                            __('Reviews','sw_win'),
                            'manage_options', 'listing_reviews', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        }

        add_submenu_page('listing_manage', 
                            __('Messages','sw_win'), 
                            __('Messages','sw_win'),
                            'manage_options', 'listing_messages', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        
        if(function_exists('sw_win_report_added'))
        {
            add_submenu_page('listing_manage', 
                                __('Reports','sw_win'), 
                                __('Reports','sw_win'),
                                'manage_options', 'listing_reports', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        }
        
        if(function_exists('sw_win_pluginsLoaded_savesearch'))
        {
            add_submenu_page('listing_manage', 
                                __('Save Search','sw_win'), 
                                __('Save Search','sw_win'),
                                'manage_options', 'listing_savesearch', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        }
                            
        add_submenu_page('listing_manage', 
                            __('Settings','sw_win'), 
                            __('Settings','sw_win'),
                            'manage_options', 'listing_settings', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
                            
        if (!function_exists('sw_is_codecanyon_version'))
        {
            
            add_submenu_page('listing_manage', 
                __('Go Premium','sw_win'), 
                __('Go Premium','sw_win'),
                'manage_options', 'https://codecanyon.net/item/real-estate-portal-for-wordpress/19850873?ref=sanljiljan', NULL);
                
        }

        add_menu_page(__('Currencies','sw_win'), __('Currencies','sw_win'), 
                        'manage_options', 'currency_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'),
                        /*plugins_url( SW_WIN_SLUG.'/images/currency.png' ),*/
                        'dashicons-custom-fa-euro',
                        31 );
        
        if(function_exists('sw_win_load_ci_function_rankpackages'))
        {
        add_menu_page(__('Rank Packages','sw_win'), __('Rank Packages','sw_win'), 
                        'manage_options', 'packagerank_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'),
                        /*plugins_url( SW_WIN_SLUG.'/images/package.png' ),*/
                        'dashicons-custom-package',
                        31 );
                        
        add_submenu_page('packagerank_manage', 
                            __('Manage','sw_win'), 
                            __('Manage','sw_win'),
                            'manage_options', 'packagerank_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
                            
        add_submenu_page('packagerank_manage', 
                            __('Invoices','sw_win'), 
                            __('Invoices','sw_win'),
                            'manage_options', 'packagerank_invoices', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        }

        if(function_exists('sw_win_load_ci_function_subscriptions'))
        {
    
    
        add_menu_page(__('Subscriptions','sw_win'), __('Subscriptions','sw_win'), 
                        'manage_options', 'subscriptions_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'),
                        /*plugins_url( SW_WIN_SLUG.'/images/subscription.png' ),*/
                        'dashicons-share-alt',
                        32 );
                        
        add_submenu_page('subscriptions_manage', 
                            __('Manage','sw_win'), 
                            __('Manage','sw_win'),
                            'manage_options', 'subscriptions_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
                            
        add_submenu_page('subscriptions_manage', 
                            __('Invoices','sw_win'), 
                            __('Invoices','sw_win'),
                            'manage_options', 'subscriptions_invoices', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
    
            
        }

        if(function_exists('sw_win_load_ci_function_calendar'))
        {
            add_menu_page(__('Calendars','sw_win'), __('Calendars','sw_win'), 
                'manage_options', 'calendars_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'),
                /*plugins_url( SW_WIN_SLUG.'/images/calendar.png' ),*/
                'dashicons-calendar-alt',
                32 );
            
            add_submenu_page('calendars_manage', 
                            __('Manage','sw_win'), 
                            __('Manage','sw_win'),
                            'manage_options', 'calendars_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
                            
            add_submenu_page('calendars_manage', 
                            __('Rates','sw_win'), 
                            __('Rates','sw_win'),
                            'manage_options', 'calendars_rates', array('SW_Win_Adminlistings', 'sw_win_load_ci'));

                            add_submenu_page('calendars_manage', 
                            __('Reservations','sw_win'), 
                            __('Reservations','sw_win'),
                            'manage_options', 'calendars_reservations', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
        }
        
        // Add import tools

        add_submenu_page('tools.php',
                            __('Listing install', 'sw_win'),
                            __('Listing install', 'sw_win'),
                            'manage_options', 'install_index', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
                            
        add_submenu_page('tools.php',
                            __('MailChimp', 'sw_win'),
                            __('MailChimp', 'sw_win'),
                            'manage_options', 'export_mailchimp', array('SW_Win_Adminlistings', 'sw_win_load_ci'));

        // Add AGENT/OWNER/AGENCY additional details

        add_menu_page(__('My profile','sw_win'), __('My profile','sw_win'), 
        'manage_options', 'listing_profile', array('SW_Win_Agentlistings', 'sw_win_load_ci'),
        /*plugins_url( SW_WIN_SLUG.'/images/profile.png' ),*/
        'dashicons-admin-users',
        50 );

    }

    public static function sw_win_load_ci() {
        
        if(sw_settings('loadfest') == '1')
        {
            echo '';
            exit;
        }
        
        sw_win_load_ci();
    }
    
    public static function listing_fields_page() {
        sw_win_load_ci();
    }

    public static function listing_searchform_page() {
        sw_win_load_ci();
    }


}

class SW_Win_Agentlistings {
    
    public $options;
    
    public function __construct()
    {

    }
    
    public static function add_menu_page()
    {
        // Add a new top-level menu (ill-advised):
        add_menu_page(__('Listings','sw_win'), __('Listings','sw_win'), 
                        'edit_own_listings', 'ownlisting_manage', array('SW_Win_Agentlistings', 'sw_win_load_ci'),
                        'dashicons-location',
                        30 );

        // Add a submenu to the custom top-level menu:
        
        add_submenu_page('ownlisting_manage', 
                            __('Manage','sw_win'), 
                            __('Manage','sw_win'),
                            'edit_own_listings', 'ownlisting_manage', array('SW_Win_Agentlistings', 'sw_win_load_ci'));
                            
        add_submenu_page('ownlisting_manage', 
                            __('Add listing','sw_win'), 
                            __('Add listing','sw_win'),
                            'edit_own_listings', 'ownlisting_addlisting', array('SW_Win_Agentlistings', 'sw_win_load_ci'));
        
        if(function_exists('sw_show_favorites'))
        {
        add_submenu_page('ownlisting_manage', 
                            __('Favorites','sw_win'), 
                            __('Favorites','sw_win'),
                            'edit_own_listings', 'ownlisting_favorites', array('SW_Win_Agentlistings', 'sw_win_load_ci'));
        }

        if(function_exists('sw_win_pluginsLoaded_savesearch'))
        {
            add_submenu_page('ownlisting_manage', 
                                __('Save Search','sw_win'), 
                                __('Save Search','sw_win'),
                                'edit_own_listings', 'ownlisting_savesearch', array('SW_Win_Agentlistings', 'sw_win_load_ci'));
        }
                            
        add_submenu_page('ownlisting_manage', 
                            __('Messages','sw_win'), 
                            __('Messages','sw_win'),
                            'edit_own_listings', 'ownlisting_messages', array('SW_Win_Agentlistings', 'sw_win_load_ci'));
        
        if(function_exists('sw_win_load_ci_function_subscriptions'))
        {
    
        add_submenu_page('ownlisting_manage', 
            __('Subscription','sw_win'), 
            __('Subscription','sw_win'),
            'edit_own_listings', 'ownlisting_subscriptions', array('SW_Win_Agentlistings', 'sw_win_load_ci'));

            
        }


        if(function_exists('sw_win_load_ci_function_rankpackages') || function_exists('sw_win_load_ci_function_subscriptions'))
        {
            
        add_submenu_page('ownlisting_manage', 
                            __('Invoices','sw_win'), 
                            __('Invoices','sw_win'),
                            'edit_own_listings', 'ownlisting_invoices', array('SW_Win_Agentlistings', 'sw_win_load_ci'));
        }

        if(function_exists('sw_win_load_ci_function_calendar'))
        {

            if(sw_user_in_role('AGENT') || sw_user_in_role('AGENCY') || sw_user_in_role('OWNER'))
            {
                add_menu_page(__('Calendars','sw_win'), __('Calendars','sw_win'), 
                    'edit_own_listings', 'owncalendars_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'),
                    plugins_url( SW_WIN_SLUG.'/images/calendar.png' ),
                    32 );

                add_submenu_page('owncalendars_manage', 
                                __('Manage','sw_win'), 
                                __('Manage','sw_win'),
                                'edit_own_listings', 'owncalendars_manage', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
                
                add_submenu_page('owncalendars_manage', 
                                __('Rates','sw_win'), 
                                __('Rates','sw_win'),
                                'edit_own_listings', 'owncalendars_rates', array('SW_Win_Adminlistings', 'sw_win_load_ci'));

                add_submenu_page('owncalendars_manage', 
                                __('Reservations','sw_win'), 
                                __('Reservations','sw_win'),
                                'edit_own_listings', 'owncalendars_reservations', array('SW_Win_Adminlistings', 'sw_win_load_ci'));

                add_submenu_page('owncalendars_manage', 
                                __('My reservations','sw_win'), 
                                __('My reservations','sw_win'),
                                'edit_own_listings', 'owncalendars_myreservations', array('SW_Win_Adminlistings', 'sw_win_load_ci'));
            }
            else
            {
                add_menu_page(__('My reservations','sw_win'), __('My reservations','sw_win'), 
                    'edit_own_profile', 'owncalendars_myreservations', array('SW_Win_Adminlistings', 'sw_win_load_ci'),
                    plugins_url( SW_WIN_SLUG.'/images/calendar.png' ),
                    32 );
            }



        }

        // Add AGENT/OWNER/AGENCY additional details
        if( sw_user_in_role('AGENT') && !sw_settings('hide_menu_items_agent'))
        {
            add_menu_page(__('Agent profile','sw_win'), __('Agent profile','sw_win'), 
            'edit_own_profile', 'ownlisting_profile', array('SW_Win_Agentlistings', 'sw_win_load_ci'),
            'dashicons-admin-users',
            50 );
        }
        else if( sw_user_in_role('OWNER') && !sw_settings('hide_menu_items_owner') )
        {
            add_menu_page(__('Owner profile','sw_win'), __('Owner profile','sw_win'), 
            'edit_own_profile', 'ownlisting_profile', array('SW_Win_Agentlistings', 'sw_win_load_ci'),
            'dashicons-admin-users',
            50 );
        }
        else if( sw_user_in_role('AGENCY') )
        {
            add_menu_page(__('Agency profile','sw_win'), __('Agency profile','sw_win'), 
            'edit_own_profile', 'ownlisting_profile', array('SW_Win_Agentlistings', 'sw_win_load_ci'),
            'dashicons-admin-users',
            50 );
        }

    }
    
    public static function sw_win_load_ci() {
        sw_win_load_ci();
    }

}

// Load this page only when loading admin menu, and not every single page
add_action('admin_menu', 'sw_win_register_admin_menu');

function sw_win_register_admin_menu()
{
    SW_Win_Adminlistings::add_menu_page();
    
    SW_Win_Agentlistings::add_menu_page();
}

add_action('admin_init', 'sw_win_register_admin_init');

function sw_win_register_admin_init()
{
    // run ob_start to prevent output headers
    ob_start();
    
    sw_register_admin_resources();

    wp_enqueue_style('sw_win_bootstrap_wrapper', plugins_url('assets/css/bootstrap-wrapper.css', __FILE__));

    wp_enqueue_script('admin_js_helpers', plugins_url(SW_WIN_SLUG.'/assets/js/jquery.helpers.js'), false, '1.0.0', false);
    
    wp_register_script( 'wpmediaelement',  plugins_url(SW_WIN_SLUG.'/assets/js/jquery.wpmediaelement.js'), false, false, false );
    wp_enqueue_script(  'wpmediaelement' );

    new SW_Win_Adminlistings();
    
    ob_flush();
}

add_action('wp_ajax_ci_action', 'sw_win_ci_ajax_action_function');
add_action('wp_ajax_nopriv_ci_action', 'sw_win_ci_ajax_action_function');

function sw_win_ci_ajax_action_function(){

    define('SW_FROM_WORDPRESS', 'yes');
    
    if(isset($_GET['page']))
        $_POST['page'] = $_GET['page'];

    if(empty($controller) && isset($_POST['page']))
    {
        $exp = explode('_', $_POST['page']);
        
        $controller = $exp[0];
        $method = $exp[1];
    }
    
    $old_server_data = $_SERVER;

    $_SERVER['PATH_INFO']   = '/'.$controller.'/'.$method;
    $_SERVER['REQUEST_URI'] = '/'.$controller.'/'.$method.'/?'.$_SERVER['QUERY_STRING'];

    require_once(dirname(__FILE__).'/codeigniter/wordpress_index.php');
    
    $_SERVER = $old_server_data;

    //Don't forget to always exit in the ajax function.
    exit();
}

function sw_win_load_ci($controller = '', $method = 'index', $parameters = array())
{
    if(defined('SW_FROM_WORDPRESS'))
    {
        echo 'Not supported operation';

        return;
    }

    define('SW_FROM_WORDPRESS', 'yes');

    if(empty($controller) && isset($_GET['page']))
    {
        $exp = explode('_', $_GET['page']);
        
        if(isset($_GET['function']))
        {
            $exp[1] = $_GET['function'];
        }
        
        $controller = $exp[0];
        $method = $exp[1];
    }
    
    $old_server_data = $_SERVER;

    $_SERVER['PATH_INFO']   = '/'.$controller.'/'.$method;
    $_SERVER['REQUEST_URI'] = '/'.$controller.'/'.$method.'/?'.$_SERVER['QUERY_STRING'];

    require_once(dirname(__FILE__).'/codeigniter/wordpress_index.php');
    
    $_SERVER = $old_server_data;
    
}


//add_action( 'show_user_profile', 'sw_user_edit_section', 1 );
add_action( 'edit_user_profile', 'sw_user_edit_section', 1 );

function sw_user_edit_section() {

    if(sw_user_in_role('administrator'))
    {
        echo '<a href="'.admin_url("admin.php?page=listing_profile&function=profile&user_id=".$_GET['user_id']).'">'.__('Edit user profile', 'sw_win').'</a>';
    }

}

function sw_remove_menus(){
    if( sw_user_in_role('AGENT') && sw_settings('hide_menu_items_agent'))
     {
        remove_menu_page( 'index.php' );                  //Dashboard
        remove_menu_page( 'jetpack' );                    //Jetpack* 
        remove_menu_page( 'edit.php' );                   //Posts
        remove_menu_page( 'upload.php' );                 //Media
        remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments
        remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page( 'plugins.php' );                //Plugins
        remove_menu_page( 'profile.php' );                //Plugins
        remove_menu_page( 'ownlisting_profile' );                //Plugins
        remove_menu_page( 'users.php' );                  //Users
        remove_menu_page( 'tools.php' );                  //Tools
        remove_menu_page( 'options-general.php' );        //Settings
     }
     else if( sw_user_in_role('OWNER') && sw_settings('hide_menu_items_owner') )
     {
        remove_menu_page( 'index.php' );                  //Dashboard
        remove_menu_page( 'jetpack' );                    //Jetpack* 
        remove_menu_page( 'edit.php' );                   //Posts
        remove_menu_page( 'upload.php' );                 //Media
        remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments
        remove_menu_page( 'themes.php' );                 //Appearance
        remove_menu_page( 'plugins.php' );                //Plugins
        remove_menu_page( 'profile.php' );                //Plugins
        remove_menu_page( 'ownlisting_profile' );                //Plugins
        remove_menu_page( 'users.php' );                  //Users
        remove_menu_page( 'tools.php' );                  //Tools
        remove_menu_page( 'options-general.php' );        //Settings
     }
}
add_action( 'admin_menu', 'sw_remove_menus' );

?>
