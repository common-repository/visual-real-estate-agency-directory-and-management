<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

if(!function_exists('session_status')){
    exit('PHP version 5.4 is required for this version of Google API');
}

class Gtranslation
{
    public $clientID; // Customer ID
    public $clientSecret; // Primary Account Key
    
    // Your commercial google translate server API key
    private $apiKey = '';
    
    private $callsCount=20;

    public function __construct($params = array())
    {
        $cid = '';
        $secret = '';
        
        if(is_array($params))
        {
            if(isset($params['clientID']))
                $cid = $params['clientID'];
            
            if(isset($params['clientSecret']))
                $secret = $params['clientSecret'];
        }
        
        $this->clientID = $cid;
        $this->clientSecret = $secret;
        
        if(sw_settings('limit_curl_calls') !== NULL)
        {
            $this->callsCount = sw_settings('limit_curl_calls');
        }
        
        if(sw_settings('google_translate_api_key') !== NULL)
        {
            $this->apiKey = sw_settings('google_translate_api_key');
        }
        
    }
    
    function translate_commercial_api($word, $from, $to)
    {
        $url = 'https://www.googleapis.com/language/translate/v2?key=' . $this->apiKey . '&q=' . rawurlencode($word) . '&source='.$from.'&target='.$to;
        
        $args = array(
            'user-agent'  =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
        ); 

        $response = wp_remote_get(esc_url_raw( $url ), $args);
        $response = wp_remote_retrieve_body($response);

        $responseDecoded = json_decode($response, true);

        if(isset($responseDecoded['data']['translations'][0]['translatedText']))
            return $responseDecoded['data']['translations'][0]['translatedText'];
            
        return $word;
    }
    
    public function translate($word, $from, $to)
    {
        $CI =& get_instance();
        $CI->load->helper('text');
        
        $word = strip_tags($word);
        $word = str_replace("&nbsp;"," ",$word); // change HTML space with char space
        $word = preg_replace("/[[:blank:]]+/"," ",$word); // replace multiple spaces with one
        $word = str_replace(" .",".",$word);
        $word = str_replace(". ",".",$word);
        $word = str_replace(".",". ",$word);
        
        $word = character_limiter($word, 400);
        
	    if(!function_exists('curl_version'))
            return '';
            
        $this->callsCount--;
        
        if($this->callsCount < 0)
        {
            return $word;
        }
            
        if(!empty($this->apiKey))
        {
            return $this->translate_commercial_api($word, $from, $to);
        }
        
        return $word;
    }

}

?>