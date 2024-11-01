<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($lang_code='')
	{
	    // Open website wp
	    
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http").'://'.
						$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	    
	    $actual_link = substr($actual_link, 0, strpos($actual_link, '/wp-content'));
		

	    redirect($actual_link.'/?lang='.$lang_code, 'location');
	    
		//$this->load->view('welcome_message');
	}
}
