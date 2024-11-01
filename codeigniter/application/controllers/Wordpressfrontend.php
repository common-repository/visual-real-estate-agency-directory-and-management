<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wordpressfrontend extends My_Controller {

	public function __construct(){
		parent::__construct();
        
        $this->load->model('field_m');
        $this->load->model('repository_m');
        $this->load->model('listing_m');
	}
    
    
	public function index()
	{

	}
    

    
    
    
}
