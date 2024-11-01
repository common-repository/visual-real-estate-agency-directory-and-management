<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Mymemorytranslation
{
    public $clientID; // Customer ID
    public $clientSecret; // Primary Account Key
    private $accessToken = NULL;
    private $validEmail = 'sandi@iwinter.com.hr';
    
    private $callsCount=20;

    public function __construct($params = array())
    {
        $cid = '';
        $secret = '';
        $validEmail = '';
        
        if(is_array($params))
        {
            if(isset($params['clientID']))
                $cid = $params['clientID'];
            
            if(isset($params['clientSecret']))
                $secret = $params['clientSecret'];
                
            if(isset($params['validEmail']))
                $validEmail = $params['validEmail'];
        }
        
        $this->clientID = $cid;
        $this->clientSecret = $secret;
        $this->validEmail = $validEmail;
        
        if(sw_settings('limit_curl_calls') !== NULL)
        {
            $this->callsCount = sw_settings('limit_curl_calls');
        }
        
    }

    public function translate($word, $from, $to)
    {   
        $CI =& get_instance();
        $CI->load->helper('text');
        
	    if(!function_exists('curl_version'))
            return $word;
            
        $this->callsCount--;
        
        if($this->callsCount < 0)
        {
            return $word;
        }
        
        $word = strip_tags($word);
        $word = str_replace("&nbsp;"," ",$word);
        $word = preg_replace("/[[:blank:]]+/"," ",$word);
        $word = str_replace(" .",".",$word);
        $word = str_replace(". ",".",$word);
        $word = str_replace(".",". ",$word);
        
        $word = character_limiter($word, 400);
        
        $params = "q=".urlencode($word)."&langpair=".$from."|".$to;
        
        if(!empty($this->validEmail))
            $params .= "&de=".$this->validEmail;
        
        $json_url = "http://api.mymemory.translated.net/get?$params";
        
        $translatedStr = '';

        $args = array(
            'user-agent'  =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
        ); 

        $json = wp_remote_get(esc_url_raw( $json_url ), $args);
        $json = wp_remote_retrieve_body($json);
        
        $decoded_json = json_decode($json);
        
        if(!is_object($decoded_json))
            return $word;
            
        if($decoded_json->responseStatus != '200')
            $translatedStr = 'ERROR: ';
        
        if($decoded_json->responseData->translatedText != '')
            $translatedStr = $decoded_json->responseData->translatedText;

        return $translatedStr;
    }

}

?>