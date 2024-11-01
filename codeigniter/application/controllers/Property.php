<?php

class Property extends CI_Controller
{
    
    private $lang_config = array();

    public function __construct()
    {
        parent::__construct();
        
        $this->lang_config = config_item('lang_config');
        
        $this->load->model('settings_m');
        $this->load->model('listing_m');
    }
    
    public function _remap($method)
    {
        $lang_code = $this->uri->segment(3);
        
        if(is_numeric($method) && !empty($lang_code))
        {
            $listing = $this->listing_m->get_lang($method, $this->lang_config[$lang_code]['id']);
    
            if(!empty($listing))
            {
                $listing_url = listing_url($listing);
                
                redirect($listing_url, 'location');
            }
        }
        
        exit('Not found');
    }

	public function index()
	{
		echo 'Hello, property here!';
        exit();
	}

}
