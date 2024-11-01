<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Disable demo editing

function sw_win_restrict_demo() {

//	if ( ! current_user_can( 'manage_options' ) && ( ! wp_doing_ajax() ) ) {
//		wp_redirect( site_url() ); 
//		exit;
//	}

    //update_option( 'comment_registration', 1);
    
    if(isset($_GET['function']) && substr_count($_GET['function'], 'rem') || isset($_GET['plugin_status']))
    {
        echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
        exit;
    }

    remove_action( 'wp_head', 'rest_output_link_header', 10);    
    remove_action( 'template_redirect', 'rest_output_link_header', 11);
    
    /* fix validation  Line: 7 https://fonts.googleapis.com/ Status: 404 Not Found */
    //remove_action('wp_head', 'wp_resource_hints', 2);
    
    if(isset($_GET['file']) && substr_count($_GET['file'], '.php'))
    {
        echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
        exit;
    }
    
    if(isset($_GET['page']) && substr_count($_GET['page'], 'loco'))
    {
        echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
        exit;
    }
    
    if(isset($_GET['action']) &&(
            substr_count($_GET['action'], 'edit') ||
            stripos($_GET['action'], 'trash') !==FALSE
    ))
    {
        echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
        exit;
    }

    if(substr_count($_SERVER['REQUEST_URI'], 'plugins.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'customize.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'plugin-editor.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'customize.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'plugin-install.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'theme-editor.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'users.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'tools.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'import.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'export.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'media-new.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'upload.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'elementor') ||
       substr_count($_SERVER['REQUEST_URI'], 'themes.php') ||
       substr_count($_SERVER['REQUEST_URI'], 'user-new.php')  )
    {
        echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
        exit;
    }
    
    
    if(isset($_GET['_method']) && $_GET['_method'] == 'DELETE')
    {
        $data_obj = new stdClass();
        $data_obj->{"0"}->error = 'Action disabled in demo.';
        $data_obj->{"0"}->name = 'test.php';
        $data_obj->{"0"}->size = 0;
        $data_obj->{"0"}->thumbnail_url = 'http://';
        $data_obj->{"0"}->delete_url = 'http://';
        $data_obj->{"0"}->type = 'application/octet-stream';
        $data_obj->{"0"}->zoom_enabled = false;
        $data_obj->{"0"}->short_name = 'test.php';
        
        $data_obj_2 = new stdClass();
        $data_obj_2->{"test.php"} = 42;
        $data = array('files'=>$data_obj, 'orders'=>$data_obj_2, 'repository_id'=>"0");
    
        echo json_encode($data);
        exit;
    }
    
    if(isset($_POST['search']) || isset($_POST['table']) || isset($_POST['search_order']))return;
    
    if(empty($_POST))return;
    
    if(isset($_POST['page']) && $_POST['page'] == 'frontendajax_locationautocomplete')return;

    if(isset($_POST['page']) && $_POST['page'] == 'frontendajax_login')return;
    if(isset($_POST['page']) && $_POST['page'] == 'frontendajax_addfavorite')return;
    if(isset($_POST['page']) && $_POST['page'] == 'frontendajax_subscribe')return;
    if(isset($_POST['page']) && $_POST['page'] == 'frontendajax_getallcounters')return;
    if(isset($_POST['page']) && $_POST['page'] == 'frontendajax_agents')return;
    if(isset($_POST['page']) && $_POST['page'] == 'frontendajax_addreport')return;
    
    if(isset($_POST['action']) && $_POST['action'] == 'hartbeat')return;

    if(isset($_POST['action']) && $_POST['action'] == 'upload-attachment')
    {
        $data_obj = new stdClass();
        $data_obj->message = 'Action disabled in demo.';
        $data_obj->filename= 'unknown';
        
        $data = array('success'=>false, 'data'=>$data_obj);
    
        echo json_encode($data);
        exit;
    }
    
    if(isset($_POST['page']) && $_POST['page'] == 'files_listing')
    {
        $data_obj = new stdClass();
        $data_obj->{"0"}->error = 'Action disabled in demo.';
        $data_obj->{"0"}->name = 'test.php';
        $data_obj->{"0"}->size = 0;
        $data_obj->{"0"}->thumbnail_url = 'http://';
        $data_obj->{"0"}->delete_url = 'http://';
        $data_obj->{"0"}->type = 'application/octet-stream';
        $data_obj->{"0"}->zoom_enabled = false;
        $data_obj->{"0"}->short_name = 'test.php';
        
        $data_obj_2 = new stdClass();
        $data_obj_2->{"test.php"} = 42;
        $data = array('files'=>$data_obj, 'orders'=>$data_obj_2, 'repository_id'=>"0");
    
        echo json_encode($data);
        exit;
    }

//    echo '<pre>';
//    var_dump($_POST);
//    echo '</pre>';
    
    echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
    exit;
}

add_action( 'admin_init', 'sw_win_restrict_demo', 1 );

/**
*    Disables WordPress Rest API for external requests
*/
function sw_restrict_rest_api_to_localhost() {
    $whitelist = array('127.0.0.1', "::1");

    if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        die('REST API is disabled in demo.');
    }
}

add_action( 'rest_api_init', 'sw_restrict_rest_api_to_localhost', 1 );


function sw_hide_update_notice_to_all_but_admin_users() 
{
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
}
add_action( 'admin_head', 'sw_hide_update_notice_to_all_but_admin_users', 1 );

function sw_remove_core_updates(){
    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}

add_filter('pre_site_transient_update_core', 'sw_remove_core_updates');
add_filter('pre_site_transient_update_plugins', 'sw_remove_core_updates');
add_filter('pre_site_transient_update_themes', 'sw_remove_core_updates');

function remove_quick_edit( $actions ) {
    unset($actions['inline hide-if-no-js']);
    return $actions;
}

add_filter('page_row_actions','remove_quick_edit',10,1);
add_filter('post_row_actions','remove_quick_edit',10,1);

// define the comment_row_actions callback 
function filter_comment_row_actions( $array, $comment ) { 
    // make filter magic happen here... 
    return array(); 
}; 
// add the filter 
add_filter( 'comment_row_actions', 'filter_comment_row_actions', 10, 2 ); 

function sw_disable_create_newpost() {
    global $wp_post_types;
    $wp_post_types['post']->cap->create_posts = 'do_not_allow';
    $wp_post_types['page']->cap->create_posts = 'do_not_allow';
    //$wp_post_types['my-post-type']->cap->create_posts = 'do_not_allow';
    
}
add_action('init','sw_disable_create_newpost');

function disable_reset_lost_password() 
 {
   return false;
 }
add_filter( 'allow_password_reset', 'disable_reset_lost_password');

/* comment form */

if(substr_count($_SERVER['REQUEST_URI'], 'wp-comments-post.php')  )
{
    echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
    exit;
}
?>