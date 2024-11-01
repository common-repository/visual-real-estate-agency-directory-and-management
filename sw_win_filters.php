<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter('the_content', 'sw_emd_content');  

function sw_is_page($page_id)
{
    if(empty($page_id)) return false;
    
    // [WPML]
    if(function_exists('icl_object_id'))
    {
        $page_id = apply_filters( 'wpml_object_id', intval($page_id), 'page', TRUE, NULL );
    }
    // [/WPML]
    
    return is_page($page_id);
}

function sw_emd_content( $content )
{
    // for WPML fix_fallback_links causing troubles with our urls
    remove_all_filters( 'the_content', 99 );

    static $count;
    // if($count > 0)return $content;
    $count++;
    
    global $wp_query;
    
    sw_win_load_basic_cssjs();
    
    if( is_singular() && is_main_query() && 
        in_the_loop() && $wp_query->current_post == 0 )
    {
        
        if ( sw_is_page(sw_settings('results_page'))  || 
                (sw_settings('enable_multiple_results_page') == 1 && strpos(get_page_template(), 'results') !== FALSE)) {
            sw_win_load_ci_function('Frontend', 'resultspage', array(&$content));
        }
        elseif ( sw_is_page(sw_settings('listing_preview_page')) ) {
            $content = "";
            
            sw_win_load_ci_function('Frontend', 'listingpreview', array(&$content));
        }
        elseif ( sw_is_page(sw_settings('user_profile_page')) ) {
            sw_win_load_ci_function('Frontend', 'userprofile', array(&$content));
        }
        elseif ( sw_is_page(sw_settings('agents_page')) ) {
            sw_win_load_ci_function('Frontend', 'agents', array(&$content));
        }
        elseif ( sw_is_page(sw_settings('agencies_page')) ) {
            sw_win_load_ci_function('Frontend', 'agencies', array(&$content));
        }
        elseif ( sw_is_page(sw_settings('quick_submission')) ) {
            if(function_exists('sw_win_load_ci_function_qs'))
                sw_win_load_ci_function_qs(array(&$content));
        }
        elseif ( sw_is_page(sw_settings('register_page'))) {
            sw_win_load_ci_function('Dashboard', 'register', array(&$content));
        }
        elseif ( sw_is_page(sw_settings('tags_page')) ) {
            sw_win_load_ci_function('Frontend', 'tags', array(&$content));
        }
        elseif ( sw_is_page(sw_settings('compare_page')) ) {
            sw_win_load_ci_function('Frontend', 'compare', array(&$content));
        }
        
    }

    return $content;
}

add_action( 'wp_loaded','sw_win_my_flush_rules' );
add_filter( 'rewrite_rules_array','sw_win_my_insert_rewrite_rules' );
add_filter( 'query_vars','sw_win_my_insert_query_vars' );

function sw_win_my_flush_rules() {
    $rules = get_option( 'rewrite_rules' );
    global $wp_rewrite;
     
    if ( ! isset( $rules['('.SW_WIN_LISTING_URI.')/(.+)$'] ) ) {
        $wp_rewrite->flush_rules();
    }
    
    if ( ! isset( $rules['('.SW_WIN_USER_URI.')/(.+)$'] ) ) {
        $wp_rewrite->flush_rules();
    }
    
    if ( ! isset( $rules['('.SW_WIN_TAGS_URI.')/(.+)$'] ) ) {
        $wp_rewrite->flush_rules();
    }
    
    // [WPML]
    if(function_exists('icl_object_id'))
    {
        // get current language slug, because WPML using different page for different language
        
        $page_current_id = apply_filters( 'wpml_object_id', sw_settings('listing_preview_page'), 'page', TRUE, NULL );
        $page_current_slug = get_post_field( 'post_name', $page_current_id );
        
        if ( ! isset( $rules['('.$page_current_slug.')/(.+)$'] ) ) {
            $wp_rewrite->flush_rules();
        }
        
        $page_current_id = apply_filters( 'wpml_object_id', sw_settings('user_profile_page'), 'page', TRUE, NULL );
        $page_current_slug = get_post_field( 'post_name', $page_current_id );
        
        if ( ! isset( $rules['('.$page_current_slug.')/(.+)$'] ) ) {
            $wp_rewrite->flush_rules();
        }
        
        $page_current_id = apply_filters( 'wpml_object_id', sw_settings('tags_page'), 'page', TRUE, NULL );
        $page_current_slug = get_post_field( 'post_name', $page_current_id );
        
        if ( ! isset( $rules['('.$page_current_slug.')/(.+)$'] ) ) {
            $wp_rewrite->flush_rules();
        }
    }
    // [/WPML]

}

function sw_win_my_insert_rewrite_rules( $rules )
{

    $newrules = array();
    $newrules['('.SW_WIN_LISTING_URI.')/(.+)$'] = 'index.php?pagename=$matches[1]&slug=$matches[2]';
    $newrules['('.SW_WIN_USER_URI.')/(.+)$'] = 'index.php?pagename=$matches[1]&slug=$matches[2]';
    $newrules['('.SW_WIN_TAGS_URI.')/(.+)$'] = 'index.php?pagename=$matches[1]&slug=$matches[2]';
    
    // [WPML]
    if(function_exists('icl_object_id'))
    {
        // get current language slug, because WPML using different page for different language
        
        $page_current_id = apply_filters( 'wpml_object_id', sw_settings('listing_preview_page'), 'page', TRUE, NULL );
        $page_current_slug = get_post_field( 'post_name', $page_current_id );

        $newrules['('.$page_current_slug.')/(.+)$'] = 'index.php?pagename=$matches[1]&slug=$matches[2]';

        // for multilang folder country code, to detect url
        // for all lang_codes
        if(function_exists('pll_get_post'))
        foreach(sw_get_languages() as $lang)
        {
            if(isset($lang['lang_code']))
            {
                $newrules['('.$lang['lang_code'].')/('.$page_current_slug.')/(.+)$'] = 'index.php?pagename=$matches[2]&slug=$matches[3]';
            }
        }
        
        $page_current_id = apply_filters( 'wpml_object_id', sw_settings('user_profile_page'), 'page', TRUE, NULL );
        $page_current_slug = get_post_field( 'post_name', $page_current_id );
        
        $newrules['('.$page_current_slug.')/(.+)$'] = 'index.php?pagename=$matches[1]&slug=$matches[2]';
        
        if(function_exists('pll_get_post'))
        foreach(sw_get_languages() as $lang)
        {
            if(isset($lang['lang_code']))
                $newrules['('.$lang['lang_code'].')/('.$page_current_slug.')/(.+)$'] = 'index.php?pagename=$matches[2]&slug=$matches[3]';
        }
        
        $page_current_id = apply_filters( 'wpml_object_id', sw_settings('tags_page'), 'page', TRUE, NULL );
        $page_current_slug = get_post_field( 'post_name', $page_current_id );
        
        $newrules['('.$page_current_slug.')/(.+)$'] = 'index.php?pagename=$matches[1]&slug=$matches[2]';

        if(function_exists('pll_get_post'))
        foreach(sw_get_languages() as $lang)
        {
            if(isset($lang['lang_code']))
                $newrules['('.$lang['lang_code'].')/('.$page_current_slug.')/(.+)$'] = 'index.php?pagename=$matches[2]&slug=$matches[3]';
        }
    }
    // [/WPML]

    return $newrules + $rules;
}

function sw_win_my_insert_query_vars( $vars ) {
 
    array_push($vars, 'slug');
     
    return $vars;
}


function sw_wpml_ls_language_url( $arg1, $data ) {
    
    global $wpdb, $wp_rewrite, $sitepress, $sitepress_settings;

    //$sitepress_settings = array(); big error, wpml will stop working
    if(is_object($sitepress))
        $sitepress_settings = $sitepress->get_settings();
    
    //dump($arg1);
    //dump($data);
    
    if(sw_is_page(sw_settings('listing_preview_page')) /*|| sw_is_page(icl_object_id(sw_settings('listing_preview_page'), 'page', false, $data['lang_code']))*/)
    {
        
        
        $slug = get_query_var( 'slug' );
        
        $custom_uri = '';
        //$custom_uri = '?slug=';
        
        if(substr_count($arg1, '?') > 0)
        {
            // if doesn't using custom permalink / mod_rewrite
            $custom_uri = '&slug=';

            $arg1 = sw_remove_querystring_var($arg1, 'slug');
        }
        
        /* troubles with polylang
        if(isset($sitepress_settings[ 'language_negotiation_type' ]) && 
                 $sitepress_settings[ 'language_negotiation_type' ] == 1)
        {
            $custom_uri='';
        }
        */
        
        // [Change slug for another lang slug]
        if(isset($data['code']))
        {
            // Get listing ID from slug
            $myrows = $wpdb->get_results( " SELECT * FROM ".$GLOBALS['table_prefix']."sw_slug WHERE slug='$slug' " );

            if(isset($myrows[0]))
            {
                $listing_id = $myrows[0]->table_id;
                $lang_code = $data['code'];
                $table = 'sw_listing';
                
                // Get slug
                $myrows = $wpdb->get_results( " SELECT * FROM ".$GLOBALS['table_prefix']."sw_slug WHERE `table_id`='$listing_id' ".
                                              " AND `table`='$table' AND `lang_code`='$lang_code' " );

                if(isset($myrows[0]))
                {
                    $slug = $myrows[0]->slug;
                }                       
            }
        }
        // [/Change slug for another lang slug]

        // just check if function is runned multiple times
        if(substr($arg1, -strlen($slug)) == $slug)
            return $arg1;
        
        return $arg1.$custom_uri.$slug;
    }
    
    if(sw_is_page(sw_settings('user_profile_page')))
    {
        $slug = get_query_var( 'slug' );
        
        $custom_uri = '';
        
        if(substr_count($arg1, '?') > 0)
        {
            // if doesn't using custom permalink / mod_rewrite
            $custom_uri = '&slug=';
            
            $arg1 = sw_remove_querystring_var($arg1, 'slug');
        }

        /* troubles with polylang
        if(isset($sitepress_settings[ 'language_negotiation_type' ]) && 
                 $sitepress_settings[ 'language_negotiation_type' ] == 1)
        {
            $custom_uri='';
        }
        */

        // just check if function is runned multiple times
        if(substr($arg1, -strlen($slug)) == $slug)
            return $arg1;

        return $arg1.$custom_uri.$slug;
    }
    
    if(sw_is_page(sw_settings('tags_page')))
    {
        $slug = get_query_var( 'slug' );
        
        $custom_uri = '';
        
        if(substr_count($arg1, '?') > 0)
        {
            // if doesn't using custom permalink / mod_rewrite
            $custom_uri = '&slug=';
            
            $arg1 = sw_remove_querystring_var($arg1, 'slug');
        }

        /* troubles with polylang
        if(isset($sitepress_settings[ 'language_negotiation_type' ]) && 
                 $sitepress_settings[ 'language_negotiation_type' ] == 1)
        {
            $custom_uri='';
        }
        */

        // just check if function is runned multiple times
        if(substr($arg1, -strlen($slug)) == $slug)
            return $arg1;

        return $arg1.$custom_uri.$slug;
    }
    
    return $arg1;
}

add_filter( 'wpml_ls_language_url', 'sw_wpml_ls_language_url' , 10, 3);

add_filter('document_title_parts', 'sw_override_post_title', 10);
function sw_override_post_title($title){
    global $wpdb;

    if(sw_is_page(sw_settings('listing_preview_page')))
    { 
        $slug = get_query_var( 'slug' );
        
        $myrows = $wpdb->get_results( " SELECT * FROM ".$GLOBALS['table_prefix']."sw_listing_lang WHERE slug='$slug' " );
        if(isset($myrows[0]) && isset($myrows[0]->field_10))
        {
            $title['title'] = strip_tags($myrows[0]->field_10);
        }
    }

    return $title; 
}

if(in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	add_filter('wpseo_title', 'sw_filter_product_wpseo_title', 10, 1);

	function sw_filter_product_wpseo_title($seo_title) {
		global $wpdb;

			if(sw_is_page(sw_settings('listing_preview_page')))
			{ 
					$slug = get_query_var( 'slug' );
					
					$myrows = $wpdb->get_results( " SELECT * FROM ".$GLOBALS['table_prefix']."sw_listing_lang WHERE slug='$slug' " );
					if(isset($myrows[0]) && isset($myrows[0]->field_10))
					{
                        $seo_title = strip_tags($myrows[0]->field_10);
					}
			}

			return $seo_title; 
	}
}


//If anyone else is having problems with this, it may be due to the Yoast plugin. Use:
//add_filter( 'pre_get_document_title', function( $title ){
//    return $title;
//}, 999, 1 );

/* seo sw feature, wp overwrite wp_head */
add_action( 'wp_head', 'sw_seo_metatags');
function sw_seo_metatags(){
    sw_win_load_ci_frontend();
    
    if(
    /* if seo plugin enable, echo sw_SEO only for listing preview page and profile page */
    (in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins'))) && (is_page(sw_settings('listing_preview_page'))  ||  is_page(sw_settings('user_profile_page'))))
    
    /* if seo plugin not enabled, echo  sw_SEO for all pages */
    ||  (!in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins'))))    
            
    ){ 
        echo   '<meta property="og:url" content="'.get_current_url().'" />';
        echo   '<meta property="og:type" content="'.get_post_type().'" />';
        echo   '<meta property="og:title"  content="'.esc_html(wp_get_document_title()).'" />';
        $sw_listing_image_url = '';
        $sw_keywords = '';
        $sw_listing_description = sw_listing_description();
        $sw_listing_image_url = sw_listing_preview_image();
        $sw_keywords = sw_listing_keywords();

        if (!empty($sw_listing_description)){
            echo '<meta name="description" content="'.esc_html(strip_tags($sw_listing_description)).'">';
            echo '<meta property="og:description" content="'.esc_html(strip_tags($sw_listing_description)).'"/>';
        } elseif(sw_featured_excerpt()){
            echo '<meta name="description" content="'.esc_html(sw_featured_excerpt()).'">';
            echo '<meta property="og:description" content="'.esc_html(sw_featured_excerpt()).'" />';

        } else{
            echo '<meta name="description" content="'.esc_html(get_bloginfo('description')).'">';
            echo '<meta property="og:description"  content="'.esc_html(get_bloginfo('description')).'" />';
        }

        $sw_featured_image = sw_featured_image();
        if(!empty($sw_listing_image_url)){
            echo '<meta property="og:image" content="'.esc_url($sw_listing_image_url).'" />';
        } elseif ( '' !== $sw_featured_image ){
            echo '<meta property="og:image" content="'.esc_url($sw_featured_image).'" />';
        } elseif(get_header_image()) {
            $background_image = get_header_image();
            echo '<meta property="og:image" content="'.esc_url($background_image).'" />';
        }

        if(!empty($sw_keywords) && $sw_keywords !='-'){
            echo '<meta name="keywords" content="'.esc_html($sw_keywords).'">';
        }
    }
}
/* disable some sw_seo feature if Yost seo plugin detected, for only for listing preview page and profile page */
add_action( 'template_redirect', 'sw_disable_seo' );
function sw_disable_seo() {
    sw_win_load_ci_frontend();
    if(in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins'))) && (is_page(sw_settings('listing_preview_page')) || is_page(sw_settings('user_profile_page')))){ 
        add_filter( 'wpseo_title', '__return_false');
        add_filter( 'wpseo_metadesc', '__return_false');
        add_filter( 'wpseo_opengraph_image', '__return_false');
        add_filter( 'wpseo_opengraph_type', '__return_false');
        add_filter( 'wpseo_opengraph_desc', '__return_false');
    }
}

/* canonical wp overwrite */
remove_action( 'wp_head', 'rel_canonical');
add_action( 'wp_head', 'sw_rel_canonical');
function sw_rel_canonical(){
    if ( ! is_singular() ) {
            return;
    }

    if ( ! $id = get_queried_object_id() ) {
            return;
    }

    $url = get_permalink( $id );

    $page = get_query_var( 'page' );
    if ( $page >= 2 ) {
            if ( '' == get_option( 'permalink_structure' ) ) {
                    $url = add_query_arg( 'page', $page, $url );
            } else {
                    $url = trailingslashit( $url ) . user_trailingslashit( $page, 'single_paged' );
            }
    }
    
    if(sw_is_page(sw_settings('listing_preview_page')))
    { 
        $url = get_current_url();
    }

    echo '<link rel="canonical" href="' . esc_url( $url ) . "\" />\n";
}

function sw_add_meta_link() {
    global $wpdb;
    
    if(sw_is_page(sw_settings('listing_preview_page')))
    {
        $slug = get_query_var( 'slug' );
        
        $myrows = $wpdb->get_results( " SELECT * FROM ".$GLOBALS['table_prefix']."sw_listing_lang WHERE slug='$slug' " );
        if(isset($myrows[0]) && isset($myrows[0]->json_object))
        {
            $json_dec = json_decode($myrows[0]->json_object);
            
            if(isset($json_dec->field_78))
                echo '<meta name="keywords" content="'.strip_tags($json_dec->field_78).'" />';
        }
    }
}
/*add_action( 'wp_head', 'sw_add_meta_link' );*/

/* add sw lsitngs/profiles pages into  Yoast sitemap */
add_filter( 'wpseo_sitemap_index', 'sw_add_sitemap_custom_items' );
function sw_add_sitemap_custom_items() {
    global $wpdb;
    $sitemap_custom_items = '';
    //listings
    $myrows = $wpdb->get_results("SELECT * FROM `".$GLOBALS['table_prefix']."sw_listing` JOIN `".$GLOBALS['table_prefix']."sw_listing_lang` ON `wp_sw_listing`.`idlisting`= `".$GLOBALS['table_prefix']."sw_listing_lang`.`listing_id` WHERE `lang_id` = 1 ORDER BY `".$GLOBALS['table_prefix']."sw_listing`.`idlisting` DESC");
    if(!empty($myrows)) {
        foreach($myrows as $listing) {
            $last_mod = '';
            if(!empty($listing->date_modified))
                $last_mod = '	<lastmod>'.date('c',strtotime($listing->date_modified)).'</lastmod>'."\n";

                $sitemap_custom_items .= '<sitemap>'."\n".
                            '	<loc>'.esc_url(sw_listing_url($listing)).'</loc>'."\n".
                        $last_mod.
                        '</sitemap>'."\n";
        }
    }

    // agents
    $role__in = array('AGENT','AGENCY','OWNER');
    $data_users = get_users( array( 'search' => '', 'role__in' => $role__in, 
                                          'order_by' => 'ID', 'order' => 'DESC'));

    foreach($data_users as $object)
    {
        $last_mod = '';
        if(!empty($object->user_registered))
            $last_mod = '	<lastmod>'.date('c',strtotime($object->user_registered)).'</lastmod>'."\n";

        $sitemap_custom_items.= '<sitemap>'."\n".
                        '	<loc>'.sw_agent_url($object).'</loc>'."\n".
                        $last_mod.
                        '	<changefreq>monthly</changefreq>'."\n".
                        '	<priority>0.5</priority>'."\n".
                        '</sitemap>'."\n";
    }
    
    return $sitemap_custom_items;
}

?>