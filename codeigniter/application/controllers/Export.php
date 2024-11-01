<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export extends My_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
	}
    
	public function mailchimp()
	{
        $this->load->helper('download');
        $this->load->model('user_m');
        
	    // Fetch all users
		$users = $this->user_m->get();
        
        $data = '';
        
        foreach($users as $row)
        {
            if(strpos($row->user_email, '@') > 1)
            {
                $data.= $row->user_email."\r\n";
            }
        }
        
        if(strlen($data) > 2)
            $data = substr($data,0,-1);
        
        $name = 'user_email_export.txt';
        
        force_download($name, $data);
	}
    
}
