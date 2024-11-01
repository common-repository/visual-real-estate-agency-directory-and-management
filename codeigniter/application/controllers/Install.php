<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends My_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
        ini_set('max_execution_time', 900);    
            
        $this->load->model('install_m');

        $this->data['form_object'] = (object) $this->install_m->get_fields();
        
        // additional fields
        $this->data['form_object']->sw_purchase_code = sw_settings('sw_purchase_code');

        $theme = sw_get_compatible_theme();

        $plugins = sw_get_compatible_plugins();
        
        $rules = $this->install_m->form_index;
        
        if((!empty($theme) || sw_count($plugins) > 0 ) && !isset($_GET['skiptheme']) && sw_classified_installed() == FALSE)
        {
            $rules = $this->install_m->form_theme;
        }
        
        $this->form_validation->set_rules($rules);
        
        // [Check requirements]
        
        $this->data['pre_requirements'] = '';
        if(PHP_VERSION_ID < 50500)
            $this->data['pre_requirements'].=__('PHP 5.5 is required, please update your PHP version','sw_win').'<br />';
            
        /*
        if(!is_writable(SW_WIN_PLUGIN_PATH.'files'))
        {
            $this->data['pre_requirements'].=__('Allow chmod writing permissions on folder','sw_win').': '.SW_WIN_PLUGIN_PATH.'files'.'<br />';
        }
        
        if(!is_writable(SW_WIN_PLUGIN_PATH.'files/strict_cache'))
        {
            $this->data['pre_requirements'].=__('Allow chmod writing permissions on folder','sw_win').': '.SW_WIN_PLUGIN_PATH.'files/strict_cache'.'<br />';
        }
        
        if(!is_writable(SW_WIN_PLUGIN_PATH.'files/thumbnail'))
        {
            $this->data['pre_requirements'].=__('Allow chmod writing permissions on folder','sw_win').': '.SW_WIN_PLUGIN_PATH.'files/thumbnail'.'<br />';
        }
        */
        
        if(!empty($this->data['pre_requirements']))
            $this->data['pre_requirements'] = __('Before installation please:','sw_win').'<br />'.$this->data['pre_requirements'];
        
        // [/Check requirements]
        
        $this->data['install_log'] = '';
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            // save data
            $data = $this->install_m->array_from_rules($rules);

            $this->data['install_log'] = $this->install_m->save_install($data);

            if(!empty($data['sw_purchase_code']))
            {
                $this->load->model('settings_m');
                $this->settings_m->save_settings(array('sw_purchase_code'=>$data['sw_purchase_code']));
            }
            

            if(isset($_POST['switch_theme']) || isset($_POST['switch_plugins']) || sw_count($_POST) == 0)
            {
                wp_redirect(admin_url("admin.php?page=install_index&skiptheme=true")); exit;
            }
            
            // reload and show message
            if(empty($this->data['install_log']))
            { 
                wp_redirect(admin_url("admin.php?page=install_index&updated=true")); exit;
            }

        }
        
        // Load view
		$this->data['subview'] = 'admin/install/index';
        $this->load->view('admin/_layout_main', $this->data);
        
	}

	public function check_inform($str)
	{
	    if(!function_exists('curl_version'))
            return TRUE;

        $purchase_code = urlencode($str);
        $codecanyon_username = 'v3';
        $my_url = urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $email = urlencode(get_option( 'admin_email' ));
        
        // jSON URL which should be requested
        $json_url = 'http://geniuscript.com/winclassified/report.php?email='.$email.'&purchase_code=inform_'.$str.'&item_id=wp_classified_rep&username='.$codecanyon_username.'&url='.$my_url;

        $args = array(
            'user-agent'  =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
        ); 

        $json = wp_remote_get(esc_url_raw( $json_url ), $args);
        $json = wp_remote_retrieve_body($json);
        
        $decoded_json = json_decode($json);

        if(!is_object($decoded_json))
            return true;

    	return TRUE;
	}

	public function check_purchase($str)
	{
        if(sw_is_codecanyon_purchase($str) === FALSE)
        {
            $this->form_validation->set_message('check_purchase', __('Wrong purchase code', 'sw_win'));
            return FALSE;
        }
        
        return TRUE;
	}
    
}
