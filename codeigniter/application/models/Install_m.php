<?php

class Install_m extends My_Model {
    
    protected $_table_name = 'install';
    protected $_order_by = 'idinstall';
    protected $_primary_key = 'idinstall';
    public $_own_columns = array();

    public $form_index = array();
    public $form_theme = array();
    
	public function __construct(){
		parent::__construct();
        
        $this->form_index = array(
            'install_tables' => array('field'=>'install_tables', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Install required tables', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'remove_widgets' => array('field'=>'remove_widgets', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Remove existing widget', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'install_menu' => array('field'=>'install_menu', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Install menu and widgets', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'install_demolistings' => array('field'=>'install_demolistings', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Install demo listings', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'update_plugin' => array('field'=>'update_plugin', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Update plugin database', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim')
            );
        
        // check for compatible themes
        
        $first_compatible = sw_get_compatible_theme();
        $plugins = sw_get_compatible_plugins();


        
        $plugins_list = '';
        $plugins_list.='<br/><br/><table class="wp-list-table widefat plugins">';
            $plugins_list.='<thead>';
                $plugins_list.='<tr>';
                    $plugins_list.='<th scope="col" id="name" class="manage-column column-name column-primary">'.esc_html__('Plugin', 'sw_win').'</th>';
                    $plugins_list.='<th scope="col" id="description" class="manage-column column-description">'.esc_html__('Description', 'sw_win').'</th>';
                $plugins_list.='</tr> ';
            $plugins_list.='</thead>';
            $plugins_list.='<tbody id="the-list">';
                foreach ($plugins as $plugin) {
                    $plugin_data = get_plugin_data( WP_PLUGIN_DIR .'/'.$plugin, true, true ) ;

                    $plugins_list.='<tr class="active">';
                        $plugins_list.='<td class="plugin-title column-primary">';
                            $plugins_list.='<strong style="font-weight: 600;">'. esc_html($plugin_data['Name']).'</strong>';
                        $plugins_list.='</td>';
                        $plugins_list.='<td class="column-description desc">';
                            $plugins_list.='<div class="plugin-description" style="font-weight: 600;"><p>'. $plugin_data['Description'].'</p></div>';
                        $plugins_list.='</td>';
                    $plugins_list.='</tr> ';    
                }
            $plugins_list.='</tbody>';
        $plugins_list.='</table>';
        
        
        
        if(!empty($first_compatible))
        {
            $this->form_theme['switch_theme'] = array('field'=>'switch_theme', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Switch to compatible theme', 'sw_win').': '.$first_compatible, 'design'=>'checkbox', 'rules'=>'trim');
        }

        if(sw_count($plugins) > 0)
        {
            $this->form_theme['switch_plugins'] = array('field'=>'switch_plugins', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Activate compatible plugins', 'sw_win').': '.$plugins_list, 'design'=>'checkbox', 'rules'=>'trim');
        }
        

        $multipurpose_values = array();
        
        if(file_exists(get_template_directory().'/demo_content/multipurpose/'))
        {
            //$multipurpose_values[''] = __('Default', 'sw_win');

            $files = array();
            $dir = get_template_directory().'/demo_content/multipurpose/';

            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if(strpos($file, '.') === false)
                            $multipurpose_values[$file] = ucfirst(str_replace('_', ' ', $file));
                    }
                    closedir($dh);
                }
            }
        }
        ksort($multipurpose_values); 

        if(sw_count($multipurpose_values) > 0)
            $this->form_index['multipurpose'] = array('field'=>'multipurpose', 'label'=>__('Portal version', 'sw_win'), 'design'=>'radio', 'rules'=>'trim', 'values'=>$multipurpose_values);


        if(function_exists('sw_is_codecanyon_version'))
        {
            $this->form_index['inform_new_versions'] = array('field'=>'inform_new_versions', 'label'=>__('Inform me about updates and news to email', 'sw_win'), 
            'design'=>'checkbox', 'rules'=>'trim');

            $this->form_index['sw_purchase_code'] = array('field'=>'sw_purchase_code', 'label'=>__('Purchase code', 'sw_win'), 
            'design'=>'input', 'rules'=>'trim|required|callback_check_purchase', 
            'hint'=>'<a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">'.__('Guide to find purchase code', 'sw_win').'</a>');
        }
        else
        {
            $this->form_index['inform_new_versions'] = array('field'=>'inform_new_versions', 'label'=>__('Inform me about updates and news to email', 'sw_win'), 
            'design'=>'checkbox', 'rules'=>'trim|callback_check_inform');
        }

        
	}

    public function get_fields()
    {
        $fields_data = array();
        
        if(!sw_win_table_exists('sw_invoice'))
        {
            $fields_data['install_tables'] = '1';
            $fields_data['remove_widgets'] = '1';
            $fields_data['install_demolistings'] = '1';
            $fields_data['install_menu'] = '1';
            $fields_data['switch_theme'] = '1';
            $fields_data['switch_plugins'] = '1';
        }
        else if(sw_win_classified_version() > sw_win_classified_version_db())
        {
            $fields_data['update_plugin'] = '1';
        }

        $fields_data['inform_new_versions'] = '';
        
        return $fields_data;
    }
    
    public function save_install($post_data)
    {
        global $GLOBALS;
        
        $install_log = '';

        sw_win_create_folders();
        
        $ptype = config_item('purpose_type');
        
        $settings_data = array();
        
        if(isset($post_data['switch_theme']) && $post_data['switch_theme'] == 1)
        {

            $first_compatible = '';
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
            
            if(!empty($first_compatible))
            {
                switch_theme($first_compatible);
                
                if(file_exists(get_theme_root().'/'.$first_compatible.'/inc/helpers/install.php'))
                {
                    require_once( get_theme_root().'/'.$first_compatible.'/inc/helpers/install.php' );
                    
                    if(function_exists('sw_install_compatible_plugins') && 
                       isset($post_data['switch_plugins']) && $post_data['switch_plugins'] == 1)
                        sw_install_compatible_plugins($this, $install_log, $post_data);
                }
                    
                $install_log.= '<div class="alert alert-success" role="alert">Theme switched to '.$first_compatible.', now click on Install one more time</div>';
                return $install_log;
            }
        }

        if(isset($post_data['switch_plugins']) && $post_data['switch_plugins'] == 1)
        {
            $plugins = sw_install_compatible_plugins($this, $install_log, $post_data);

            $install_log.= '<div class="alert alert-success" role="alert">Plugins activated: '.join(', '.$plugins).', now click on Install one more time</div>';
            return $install_log;
        }

        //if(!isset($post_data['install_tables']))
        //    return $install_log;
        
        if($post_data['install_tables'] == 1)
        {
            // Check if tables exists
            
            /* updated database titles from old version */
            if(TRUE){
                if(sw_win_table_exists('file'))
                 $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."file`
                                     RENAME TO `".$GLOBALS['table_prefix']."sw_file`;");

                if(sw_win_table_exists('cacher'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."cacher`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_cacher`;");

                if(sw_win_table_exists('currency'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."currency`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_currency`;");

                if(sw_win_table_exists('favorite'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."favorite`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_favorite`;");

                if(sw_win_table_exists('field'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."field`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_field`;");

                if(sw_win_table_exists('field_lang'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."field_lang`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_field_lang`;");

                if(sw_win_table_exists('inquiry'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."inquiry`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_inquiry`;");

                if(sw_win_table_exists('invoice'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."invoice`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_invoice`;");

                if(sw_win_table_exists('listing'))
                    $this->db->query("RENAME TABLE `".$GLOBALS['table_prefix']."listing`
                                        TO `".$GLOBALS['table_prefix']."sw_listing`;");

                if(sw_win_table_exists('listing_agent'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."listing_agent`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_listing_agent`;");

                if(sw_win_table_exists('listing_field'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."listing_field`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_listing_field`;");

                if(sw_win_table_exists('listing_lang'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."listing_lang`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_listing_lang`;");

                if(sw_win_table_exists('packagerank'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."packagerank`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_packagerank`;");

                if(sw_win_table_exists('repository'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."repository`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_repository`;");

                if(sw_win_table_exists('review'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."review`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_review`;");

                if(sw_win_table_exists('form'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."form`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_form`;");

                if(sw_win_table_exists('settings'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."settings`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_settings`;");

                if(sw_win_table_exists('slug'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."slug`
                               RENAME TO `".$GLOBALS['table_prefix']."sw_slug`;");

                if(sw_win_table_exists('search_form'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."search_form`
                               RENAME TO `".$GLOBALS['table_prefix']."sw_search_form`;");
            }
            
            if(sw_win_table_exists('sw_invoice'))
            {
                $install_log.='<div class="alert alert-warning" role="alert">'.__('Tables already exists', 'sw_win').'</div>';
            }
            else
            {
                // Import table structures
                
                // Run sql import file
                if(!file_exists(SW_WIN_PLUGIN_PATH.'sql_scripts/install_tables_'.$ptype.'.sql'))
                {
                    $install_log.= '<div class="alert alert-danger" role="alert">Missing file: sql_scripts/install_tables_'.$ptype.'.sql</div>';
                    return $install_log;
                }
                
                $sql = file_get_contents(SW_WIN_PLUGIN_PATH.'sql_scripts/install_tables_'.$ptype.'.sql');
                $sql = str_replace('"dont run this file manually!"', '', $sql);
                $sql = str_replace('`wp_', '`'.$GLOBALS['table_prefix'], $sql);
                
                $db_error = '';
                foreach (explode(";", $sql) as $sql) 
                {
                    $sql = trim($sql);
                    //echo  $sql.'<br/>============<br/>';
                    if($sql) 
                    {
                        if(empty($db_error))
                        {
                            $this->db->query($sql);
                            
                            $dbe = $this->db->error();
                            $dbe = $dbe['message'];
                            
                            if($dbe != '')
                                $db_error.= '<br />'.$dbe;
                        }
                        else
                        {
                            break;
                        }
                    } 
                }
                
                if(!empty($db_error))
                {
                    $install_log.= '<div class="alert alert-danger" role="alert">'.$db_error.'</div>';
                    return $install_log;
                }
                
                /* updated database titles */
                if(sw_win_table_exists('file'))
                 $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."file`
                                     RENAME TO `".$GLOBALS['table_prefix']."sw_file`;");

                if(sw_win_table_exists('cacher'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."cacher`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_cacher`;");

                if(sw_win_table_exists('currency'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."currency`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_currency`;");

                if(sw_win_table_exists('favorite'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."favorite`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_favorite`;");

                if(sw_win_table_exists('field'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."field`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_field`;");

                if(sw_win_table_exists('field_lang'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."field_lang`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_field_lang`;");

                if(sw_win_table_exists('inquiry'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."inquiry`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_inquiry`;");

                if(sw_win_table_exists('invoice'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."invoice`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_invoice`;");

                if(sw_win_table_exists('listing'))
                    $this->db->query("RENAME TABLE `".$GLOBALS['table_prefix']."listing`
                                        TO `".$GLOBALS['table_prefix']."sw_listing`;");

                if(sw_win_table_exists('listing_agent'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."listing_agent`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_listing_agent`;");

                if(sw_win_table_exists('listing_field'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."listing_field`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_listing_field`;");

                if(sw_win_table_exists('listing_lang'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."listing_lang`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_listing_lang`;");

                if(sw_win_table_exists('packagerank'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."packagerank`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_packagerank`;");

                if(sw_win_table_exists('repository'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."repository`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_repository`;");

                if(sw_win_table_exists('review'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."review`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_review`;");

                if(sw_win_table_exists('form'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."form`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_form`;");

                if(sw_win_table_exists('settings'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."settings`
                                        RENAME TO `".$GLOBALS['table_prefix']."sw_settings`;");

                if(sw_win_table_exists('slug'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."slug`
                               RENAME TO `".$GLOBALS['table_prefix']."sw_slug`;");

                if(sw_win_table_exists('search_form'))
                    $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."search_form`
                               RENAME TO `".$GLOBALS['table_prefix']."sw_search_form`;");
                
                // Remove some data
                $this->load->model('settings_m');
                                
                $settings_data['recaptcha_site_key'] = NULL;
                $settings_data['recaptcha_secret_key'] = NULL;
                $settings_data['use_sandbox'] = NULL;
                $settings_data['facebook_login_enabled'] = NULL;
                $settings_data['facebook_app_id'] = NULL;
                $settings_data['facebook_app_secret'] = NULL;
                $settings_data['use_walker'] = NULL;
                $settings_data['auto_translate'] = NULL;
                $settings_data['skip_numbers_copy'] = NULL;
                $settings_data['limit_curl_calls'] = 20;
                $settings_data['google_translate_api_key'] = NULL;
                $settings_data['show_categories'] = 1;
                $settings_data['recursive_search'] = 1;
                $settings_data['per_page'] = 12;

                if(!function_exists('sw_is_codecanyon_version'))
                {
                    $settings_data['maps_api_key'] = 'AIzaSyAZXlQPa9UvIQPRgxTRfAULFklDB4oEO10';
                }
                
                // Add required pages and set it in plugin settings

                $post_insert = sw_create_page(__('Register / Login', 'sw_win'));
                $settings_data['register_page'] = $post_insert->ID;
                $post_insert = sw_create_page(__('Results page', 'sw_win'));
                $settings_data['results_page'] = $post_insert->ID;
                $post_insert = sw_create_page(__('Listing preview', 'sw_win'));
                $settings_data['listing_preview_page'] = $post_insert->ID;
                $post_insert = sw_create_page(__('Listing tags', 'sw_win'));
                $settings_data['tags_page'] = $post_insert->ID;
                $post_insert = sw_create_page(__('Agent profile', 'sw_win'));
                $settings_data['user_profile_page'] = $post_insert->ID;
                $post_insert = sw_create_page(__('Agents', 'sw_win'));
                $settings_data['agents_page'] = $post_insert->ID;

                //if(function_exists('sw_is_codecanyon_version')) 
                //{     some problem with elementor
                    $post_insert = sw_create_page(__('Quick submission', 'sw_win'));
                    $settings_data['quick_submission'] = $post_insert->ID;
                //}
                
                $this->settings_m->save_settings($settings_data);
                
                $id_pin_icon = sw_add_wp_image(SW_WIN_PLUGIN_PATH.'assets/img/markers/apartment.png');
                $data_update = array('marker_icon_id'=>$id_pin_icon);
                $this->db->update('sw_treefield', $data_update, array('idtreefield' => 1));
                
                $id_pin_icon = sw_add_wp_image(SW_WIN_PLUGIN_PATH.'assets/img/markers/house.png');
                $data_update = array('marker_icon_id'=>$id_pin_icon);
                $this->db->update('sw_treefield', $data_update, array('idtreefield' => 2));

                $id_pin_icon = sw_add_wp_image(SW_WIN_PLUGIN_PATH.'assets/img/markers/land.png');
                $data_update = array('marker_icon_id'=>$id_pin_icon);
                $this->db->update('sw_treefield', $data_update, array('idtreefield' => 3));
                
                $id_pin_icon = sw_add_wp_image(SW_WIN_PLUGIN_PATH.'assets/img/markers/commercial.png');
                $data_update = array('marker_icon_id'=>$id_pin_icon);
                $this->db->update('sw_treefield', $data_update, array('idtreefield' => 4));
                $this->db->update('sw_treefield', $data_update, array('idtreefield' => 5));
                $this->db->update('sw_treefield', $data_update, array('idtreefield' => 6));
                $this->db->update('sw_treefield', $data_update, array('idtreefield' => 7));
                
            }
        }

        if($post_data['install_menu'] == 1 && function_exists('sw_template_install_menu'))
        {
            sw_template_install_menu($this, $install_log, $post_data);
        }
        elseif($post_data['install_menu'] == 1)
        {
            $page_id = sw_settings('register_page', true);
            $page_data = get_page( $page_id );
            
            if(empty($page_data))
            {
                $install_log.= '<div class="alert alert-warning" role="alert">Plugin pages not defined, define it first</div>';
                return $install_log;
            }

            $menus = get_registered_nav_menus();

            // first menu defined by template
            $first_menu = key($menus);

            if ( has_nav_menu($first_menu) ) {
                 $install_log.= '<div class="alert alert-warning" role="alert">Assigned menu already exists, add pages manually</div>';
            }
            else
            {
                // create menu and assign to first
                
                // Check if the menu exists
                $menu_name = 'Primary menu';
                $menu_exists = wp_get_nav_menu_object( $menu_name );
                
                $menu_id = $menu_exists;
                
                // If it doesn't exist, let's create it.
                if( !$menu_exists){
                    $menu_id = wp_create_nav_menu($menu_name);

                	// Set up default menu items
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' =>  __('Home'),
                        'menu-item-classes' => 'home',
                        'menu-item-url' => home_url( '/' ), 
                        'menu-item-status' => 'publish'));
                        
                    $page_id = sw_settings('register_page');
                    $page_data = get_page( $page_id );
                    
                    if(!empty($page_data))
                    wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page_data->post_title,
                                                               'menu-item-object' => 'page',
                                                               'menu-item-object-id' => $page_id,
                                                               'menu-item-type' => 'post_type',
                                                               'menu-item-status' => 'publish'));
                                                               
                    $page_id = sw_settings('agents_page');
                    $page_data = get_page( $page_id );
                    
                    if(!empty($page_data))
                    wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page_data->post_title,
                                                               'menu-item-object' => 'page',
                                                               'menu-item-object-id' => $page_id,
                                                               'menu-item-type' => 'post_type',
                                                               'menu-item-status' => 'publish'));
                    
                    if(function_exists('sw_is_codecanyon_version'))
                    {
                    $page_id = sw_settings('quick_submission');
                    $page_data = get_page( $page_id );
                    
                    if(!empty($page_data))
                    wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page_data->post_title,
                                                               'menu-item-object' => 'page',
                                                               'menu-item-object-id' => $page_id,
                                                               'menu-item-type' => 'post_type',
                                                               'menu-item-status' => 'publish'));
                    }

                    // assign menu to top menu
                    $locations = get_theme_mod( 'nav_menu_locations' );
                    $locations[$first_menu] = $menu_id;
                    set_theme_mod('nav_menu_locations', $locations);
                    
                    $install_log.= '<div class="alert alert-success" role="alert">Menu added and assigned to first</div>';
                }
                else
                {
                    // assign menu to top menu
                    $locations = get_theme_mod( 'nav_menu_locations' );
                    $locations[$first_menu] = $menu_id;
                    set_theme_mod('nav_menu_locations', $locations);
                    
                    $install_log.= '<div class="alert alert-success" role="alert">Menu "Primary menu" already exists and assigned</div>';
                }
                

            }
            
            // Add widgets example

            $sidebars_widgets = get_option( 'sidebars_widgets' );
            
            $sidebar_index=0;
            foreach($sidebars_widgets as $sidebar_id=>$widget)
            {
                if($sidebar_id != 'wp_inactive_widgets' && $sidebar_id != 'array_version')
                {
                    $sidebar_index++;
                    // Remove all widgets
                    
                    if($post_data['remove_widgets'] == 1)
                    {
                        $sidebars_widgets[$sidebar_id] = array();
                        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
                        
                        $install_log.= '<div class="alert alert-success" role="alert">Old widgets removed from '.$sidebar_id.'</div>';
                    }
                    
                    $widget_name = 'sw_win_primarysearch_widget';
                    $widget_options = get_option('widget_'.$widget_name);
                    $widget_options[1] = array('title'=>'');
                    
                    // [Check and skip import if found]
                    $skip_widget_import = false;
                    foreach($sidebars_widgets[$sidebar_id] as $val)
                    {
                        if(strpos($val, $widget_name) !== false)
                            $skip_widget_import = true;
                    }
                    
                    if($skip_widget_import)
                    {
                        $install_log.= '<div class="alert alert-warning" role="alert">Widget import skipped, some related widget found in '.$sidebar_id.'</div>';
                        continue;
                    }
                    // [/Check and skip import if found]

                    if(isset($sidebars_widgets[$sidebar_id]) && !in_array($widget_name.'-1', $sidebars_widgets[$sidebar_id])) { //check if sidebar exists and it is empty
                        
                        if(empty($sidebars_widgets[$sidebar_id]))
                        {
                            $sidebars_widgets[$sidebar_id] = array($widget_name.'-1'); //add a widget to sidebar
                        }
                        else
                        {
                            $sidebars_widgets[$sidebar_id][] = $widget_name.'-1';
                        }

                        update_option('widget_'.$widget_name, $widget_options); //update widget default options
                        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
                    }
                    
                    if($sidebar_index > 1)
                    {
                        $install_log.= '<div class="alert alert-success" role="alert">Example widgets added in '.$sidebar_id.'</div>';
                        break;
                    }

                    $widget_name = 'sw_win_currencyconverter_widget';
                    $widget_options = get_option('widget_'.$widget_name);
                    $widget_options[1] = array('title'=>'');

                    if(isset($sidebars_widgets[$sidebar_id]) && !in_array($widget_name.'-1', $sidebars_widgets[$sidebar_id])) { //check if sidebar exists and it is empty
                        
                        if(empty($sidebars_widgets[$sidebar_id]))
                        {
                            $sidebars_widgets[$sidebar_id] = array($widget_name.'-1'); //add a widget to sidebar
                        }
                        else
                        {
                            $sidebars_widgets[$sidebar_id][] = $widget_name.'-1';
                        }

                        update_option('widget_'.$widget_name, $widget_options); //update widget default options
                        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
                    }

                    $widget_name = 'sw_win_latestlisting_widget';
                    $widget_options = get_option('widget_'.$widget_name);
                    $widget_options[1] = array('title'=>'');

                    if(isset($sidebars_widgets[$sidebar_id]) && !in_array($widget_name.'-1', $sidebars_widgets[$sidebar_id])) { //check if sidebar exists and it is empty
                        
                        if(empty($sidebars_widgets[$sidebar_id]))
                        {
                            $sidebars_widgets[$sidebar_id] = array($widget_name.'-1'); //add a widget to sidebar
                        }
                        else
                        {
                            $sidebars_widgets[$sidebar_id][] = $widget_name.'-1';
                        }

                        update_option('widget_'.$widget_name, $widget_options); //update widget default options
                        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
                    }
                    
                    $widget_name = 'sw_win_contactform_widget';
                    $widget_options = get_option('widget_'.$widget_name);
                    $widget_options[1] = array('title'=>'');

                    if(isset($sidebars_widgets[$sidebar_id]) && !in_array($widget_name.'-1', $sidebars_widgets[$sidebar_id])) { //check if sidebar exists and it is empty
                        
                        if(empty($sidebars_widgets[$sidebar_id]))
                        {
                            $sidebars_widgets[$sidebar_id] = array($widget_name.'-1'); //add a widget to sidebar
                        }
                        else
                        {
                            $sidebars_widgets[$sidebar_id][] = $widget_name.'-1';
                        }

                        update_option('widget_'.$widget_name, $widget_options); //update widget default options
                        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
                    }
                    
                    $install_log.= '<div class="alert alert-success" role="alert">Example widgets added in '.$sidebar_id.'</div>';
                    
                    break;
                }
            }
            
            
        }

        if($post_data['install_demolistings'] == 1 && function_exists('sw_template_install_demolistings'))
        {
            sw_template_install_demolistings($this, $install_log, $post_data);
        }
        elseif($post_data['install_demolistings'] == 1)
        {
            // Check if listings table is not empty
            $this->load->model('listing_m');
            $this->load->model('field_m');
            $this->load->model('file_m');
            $this->load->model('repository_m');
            $this->load->library('ghelper');
            
            $listings = $this->listing_m->get();
            
            if(sw_count($listings) > 0)
            {
                $install_log.= '<div class="alert alert-warning" role="alert">Install demo listings skipped, some already exists</div>';
            }
            else
            {
                include(SW_WIN_PLUGIN_PATH.'demo_listings/demo_listings.php');

                if(file_exists(get_template_directory().'/demo_content/demo_listings.php'))
                {
                    include(get_template_directory().'/demo_content/demo_listings.php');
                }
                
                // Open a known directory, and proceed to read its contents
                $dir = SW_WIN_PLUGIN_PATH.'demo_listings/images/';
                
                $files = array();
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) {
                        while (($file = readdir($dh)) !== false) {
                            if(strpos($file, '.jpg') !== false)
                            $files[] = $file;
                        }
                        closedir($dh);
                    }
                }

                if(sw_count($files) < 10)
                {
                    $dir = SW_WIN_PLUGIN_PATH.'demo_listings/placeholders/';

                    for($i=0; $i<22; $i++)
                    {
                        copy($dir.'800x800.jpg', $dir.'800x800_'.$i.'.jpg');
                    }

                    if (is_dir($dir)) {
                        if ($dh = opendir($dir)) {
                            while (($file = readdir($dh)) !== false) {
                                if(strpos($file, '.jpg') !== false)
                                $files[] = $file;
                            }
                            closedir($dh);
                        }
                    }
                }
                
                for($i=0; $i<15; $i++)
                {
                    $address = $d_address[$i].' '.rand(1,10);
                    $gps = $this->ghelper->getCoordinates($address);
                    
                    // If google geo taging return false, then get rand
                    if($gps['lat'] == 0)
                    {
                        $gps['lat'] = rand(4500, 4700) / 100;
                        $gps['lng'] = rand(1500, 1700) / 100;
                    }
                    
                    $repository_id = $this->repository_m->save(array('model_name'=>'listing_m'));
                    
                    // Add images into repository
                    $file1 = $files[$i];
                    $file2 = $files[$i+1];
                    $file3 = $files[$i+2];
                    
                    copy($dir.$file1, sw_win_upload_path().'files/'.$repository_id.$file1);
                    copy($dir.$file2, sw_win_upload_path().'files/'.$repository_id.$file2);
                    copy($dir.$file3, sw_win_upload_path().'files/'.$repository_id.$file3);
                    
                    $next_order=0;
                    
                    $next_order++;
                    $file_id = $this->file_m->save(array(
                        'repository_id' => $repository_id,
                        'order' => $next_order,
                        'filename' => $repository_id.$file1,
                        'filetype' => 'jpg'
                    ));
                    
                    $next_order++;
                    $file_id = $this->file_m->save(array(
                        'repository_id' => $repository_id,
                        'order' => $next_order,
                        'filename' => $repository_id.$file2,
                        'filetype' => 'jpg'
                    ));
                    
                    $next_order++;
                    $file_id = $this->file_m->save(array(
                        'repository_id' => $repository_id,
                        'order' => $next_order,
                        'filename' => $repository_id.$file3,
                        'filetype' => 'jpg'
                    ));
                    
                    $this->load->library('UploadHandler', array('initialize'=>FALSE));
                    $this->uploadhandler->regenerate_versions($repository_id.$file1, '');
                    $this->uploadhandler->regenerate_versions($repository_id.$file2, '');
                    $this->uploadhandler->regenerate_versions($repository_id.$file3, '');
                    
                    // Define general data
                    $data = array('address'=>$address,
                                  'gps'=>$gps['lat'].', '.$gps['lng'],
                                  'is_primary'=>1,
                                  'is_featured'=>$i%5==0,
                                  'is_activated'=>1,
                                  'category_id'=>rand(1, 7),
                                  'location_id'=>rand(8, 22),
                                  'repository_id'=>$repository_id);
                    
                    // Define language data
                    $data_lang = array('input_10_1'=>$d_titles[$i],
                                       'input_8_1'=>$d_titles[$i].' from '.$d_address[$i].' '.$d_descriptions[rand(0, sw_count($d_descriptions)-1)],
                                       'input_13_1'=>$d_titles[$i].' from '.$d_address[$i].' '.$d_descriptions[rand(0, sw_count($d_descriptions)-1)],
                                       //'input_14_1'=>$this->field_m->get_random_value(14, 1),
                                       //'input_2_1'=>$this->field_m->get_random_value(2, 1),
                                       'input_4_1'=>$this->field_m->get_random_value(4, 1),
                                       'input_36_1'=>rand(1, 100)*1000,
                                       'input_57_1'=>rand(1, 40)*50,
                                       'input_5_1'=>rand(5, 30)*10,
                                       'input_7_1'=>'Croatia',
                                        );
                    
                    // for checkboxes
                    foreach(array(22,23,29,31,32,30,11,27,33) as $j)
                    {
                        $data_lang['input_'.$j.'_1'] = (string) $this->field_m->get_random_value($j, 1);
                    }
                    
                    // for distances
                    for($j=47; $j<51; $j++)
                    {
                        $data_lang['input_'.$j.'_1'] = rand(1, 900)*10;
                    }
                    
                    $id = $this->listing_m->save_with_lang($data, $data_lang, NULL);
                    
                    
                }

                $install_log.= '<div class="alert alert-success" role="alert">Example demo listings added</div>';
            }
        }


        if($post_data['update_plugin'] == 1)
        {


            if(sw_win_classified_version() > sw_win_classified_version_db())
            {
                
                /* [Additional check for column issue in script] */
//                if (!$this->db->field_exists('category_id', 'wp_listing'))
//                {
//                    $this->db->query('ALTER TABLE  `listing` ADD  `category_id` INT( 11 ) NULL DEFAULT NULL AFTER  `transition_id` ;');
//                    
//                    $dbe = $this->db->error();
//                    $dbe = $dbe['message'];
//                    
//                    if($dbe != '')
//                        $db_error.= '<br />'.$dbe;
//                }
                

                for($i=sw_win_classified_version_db()+0.1; $i <= sw_win_classified_version()+0.1; $i+=0.1)
                {

                    // Run sql update file
                    if(file_exists(SW_WIN_PLUGIN_PATH.'sql_scripts/update_'.number_format($i,1,'.','').'.sql'))
                    {

                        $sql = file_get_contents(SW_WIN_PLUGIN_PATH.'sql_scripts/update_'.number_format($i,1,'.','').'.sql');
                        $sql = str_replace('"dont run this file manually!"', '', $sql);
                        $sql = str_replace('`wp_', '`'.$GLOBALS['table_prefix'], $sql);
                        
                        $db_error = '';
                        foreach (explode(";", $sql) as $sql) 
                        {
                            $sql = trim($sql);
                            //echo  $sql.'<br/>============<br/>';
                            if($sql) 
                            {
                                if(empty($db_error))
                                {
                                    $this->db->query($sql);
                                    
                                    $dbe = $this->db->error();
                                    $dbe = $dbe['message'];
                                    
                                    if($dbe != '')
                                        $db_error.= '<br />'.$dbe;
                                }
                                else
                                {
                                    break;
                                }
                            } 
                        }
                        
                        // custom sql for import
                        if(number_format($i,1,'.','') == '1.3')
                        {
                            // copy values field 12 to field 8
                            $this->db->update('sw_listing_field', array('field_id'=>'8'), array('field_id' => '12')); // to 8, from 12
                            
                            // update json_object
                            $query = $this->db->get('sw_listing_lang');
                            
                            foreach ($query->result() as $row)
                            {
                                $json_obj = json_decode($row->json_object);
                                if(is_object($json_obj) && isset($json_obj->field_12))
                                {
                                    $json_obj->field_8 = $json_obj->field_12;
                                    
                                    $this->db->update('sw_listing_lang', array('json_object'=>json_encode($json_obj)), 
                                                                      array('idlisting_lang' => $row->idlisting_lang));
                                }
                            }
                        }
                        
                        if(!empty($db_error))
                        {
                            $install_log.= '<div class="alert alert-danger" role="alert">'.$db_error.'</div>';
                            return $install_log;
                        }
                        else
                        {
                            $install_log.= '<div class="alert alert-success" role="alert">Updated to '.number_format($i,1,'.','').'</div>';
                        }
                    }
                }

            }
            else
            {
                $install_log.= '<div class="alert alert-warning" role="alert">Your db structure was updated!</div>';
            }
        }

        return $install_log;
    }

}