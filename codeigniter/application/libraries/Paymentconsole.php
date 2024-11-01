<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paymentconsole {

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
    }
    
    public function pay($provider, $config)
    {
        if(file_exists(APPPATH.'libraries/payment_providers/provider_'.$provider.'.php'))
        {
            include(APPPATH.'libraries/payment_providers/provider_'.$provider.'.php');
            
            $class = "provider_$provider";
            $object = new $class();
            
            $object->pay($config);
            
        }
        else
        {
            exit('Provider file missing');
        }
    }
    
    
    
}