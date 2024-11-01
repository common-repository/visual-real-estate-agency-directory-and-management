<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Widgetcontroller {
    
    public $CI = NULL;
    
	public function __construct(){
        
        $this->CI =& get_instance();
        
        $this->CI->load->helper('form');
        $this->CI->load->library('form_validation');
        
        $this->CI->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible" role="alert">', 
                                                     '</div>');

	}
    
    public function print_template(&$output = NULL)
    {
        if(empty($this->data['subview']))
            $this->data['subview'] = 'errors/html/error404';
        
        // [Load custom view from template]
        if(is_child_theme() && file_exists(get_stylesheet_directory().'/SW_Win_Classified/views/'.$this->data['subview'].'.php'))
        {
            $this->CI->load->add_package_path(get_stylesheet_directory().'/SW_Win_Classified/');
        } else if(file_exists(get_template_directory().'/SW_Win_Classified/views/'.$this->data['subview'].'.php'))
        {
            $this->CI->load->add_package_path(get_template_directory().'/SW_Win_Classified/');
        }
        
        // [/Load custom view from template]

        //$this->data['subview_print'] = $this->CI->load->view($this->data['subview'], $this->data, TRUE);
        
        $output_t = $this->CI->load->view('_layout_widget', $this->data, TRUE);
//        $output =  str_replace('assets/', c_base_url('assets/admin/assets/'), $output);
//        $output =  str_replace('img/', c_base_url('assets/admin/img/'), $output);
//        $output =  str_replace('tmp/', c_base_url('assets/admin/tmp/'), $output);
//        
//        if(config_item('litecache_enabled') === TRUE)
//        {
//            $this->litecache->save_cache($output);
//        }
        
        if(is_null($output))
        {
            echo $output_t;
        }
        elseif(strpos( $output,'[swcontent]') === FALSE)
        {
            $output.=$output_t;
        }
        else
        {
            $output= str_replace ('[swcontent]', $output_t, $output);
        }
    }
    


    
}