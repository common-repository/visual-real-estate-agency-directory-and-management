<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class provider_paypal {

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
    }
    
    public function pay($config)
    {
        if(empty($config['business']))
        {
            echo __('PayPal email address missing', 'sw_win');
            exit();
        }

		$this->CI->load->library('paypal', $config);
		
		#$this->CI->paypal->add(<name>,<price>,<quantity>[Default 1],<code>[Optional]);
        
		$this->CI->paypal->add($config['item_name'], $config['item_price'], 1); //First item
		//$this->CI->paypal->add('Pants',1.99, 1); 	  //Second item
		//$this->CI->paypal->add('Blowse',10,10,'B-199-26'); //Third item with code
		
		$this->CI->paypal->pay(); //Proccess the payment
    }
    
    public function check_notify($response){
        
        if(isset($response['ipn_track_id']))
            return true;        
        
        return false;
    }
    
    public function get_invoice()
    {
        $payment_query = explode('_', $_GET['payment']);
        
        if(isset($payment_query[0]))
            return $payment_query[0];
        
        return NULL;
    }
    
    public function get_name()
    {
        return 'PayPal';
    }

}