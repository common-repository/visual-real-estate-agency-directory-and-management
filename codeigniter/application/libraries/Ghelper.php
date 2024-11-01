<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ghelper {

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
    }
    
    /**
    * Reads an URL to a string
    * @param string $url The URL to read from
    * @return string The URL content
    */
    private function getURL($url){

        $args = array(
            'user-agent'  =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
        ); 

        $tmp = wp_remote_get(esc_url_raw( $url ), $args);
        $tmp = wp_remote_retrieve_body($tmp);

    	if ($tmp != false && !empty($tmp)){
    	 	return $tmp;
    	}
    }
    
    public function getAutocomplete($name_part, $limit)
    {
        $api_key = sw_settings('maps_api_key');
        $results = array();
        
        if(!sw_settings('open_street_map_enabled') && empty($api_key))
            return $results;

        $name_part = str_replace(' ','+',$name_part);
        
        
        if(sw_settings('open_street_map_enabled')){
            //$url = 'http://photon.komoot.de/api/?q='.$name_part;
            $url = 'https://api.teleport.org/api/cities/?search='.$name_part;
        } else {
            $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input='.$name_part.'&types=(cities)&key='.$api_key;
        }
        $data = $this->getURL($url);
		if ($data){
			$resp = json_decode($data, true);
        if(sw_settings('open_street_map_enabled')){
            if(!empty($resp) && isset($resp['_embedded']) && isset($resp["_embedded"]["city:search-results"]) && !empty($resp["_embedded"]["city:search-results"])) {
                $k = 0;
                foreach($resp["_embedded"]["city:search-results"] as $prediction)
                {
                    if($k >= $limit) break;
                    /*
                    $str = $prediction["matching_full_name"];
                    $pos = strpos($str, ",");
                    $value = substr($str, 0, $pos);
                    */
                    if(isset($prediction["matching_alternate_names"][0])){
                        $results[] = $prediction["matching_alternate_names"][0]['name'];
                        $k++;
                    }
                }
            }
        } else {
            if(isset($resp['status']))
			if($resp['status'] == 'OK'){

                foreach($resp['predictions'] as $prediction)
                {
                    $results[] = $prediction['structured_formatting']['main_text'];

                    //$results[] = $prediction['description'];
                }
                
			}
            }         
        }

        return $results;
    }
    
	/**
	* Get Latitude/Longitude/Altitude based on an address
	* @param string $address The address for converting into coordinates
	* @return array An array containing Latitude/Longitude/Altitude data
	*/
	public function getCoordinates($address){

        $api_key = sw_settings('maps_api_key');
        $results = array();
        
        if(!sw_settings('open_street_map_enabled') && empty($api_key))
            return $results;

        $address = str_replace(' ','+',$address);
        
        if(function_exists('mb_strtolower'))
            $address = mb_strtolower($address);
        
        if(sw_settings('open_street_map_enabled')){
            $url = 'https://nominatim.openstreetmap.org/search?format=json&q=' . $address;
        } else {
            $url = 'https://maps.google.com/maps/api/geocode/json?address=' . $address.'&key='.$api_key;
        }
        
                
        $this->CI->load->model('cacher_m');
        $loaded_value = $this->CI->cacher_m->load($address);
        
       // $loaded_value = false;
        if($loaded_value === FALSE)
        {
            $data = $this->getURL($url);
        }
        else
        {
            $data = $loaded_value;
        }
		if ($data){
			$resp = json_decode($data, true);
            
        //check cache if changed map api
        if($loaded_value !== FALSE)
        {
            if(sw_settings('open_street_map_enabled')){
                if(isset($resp['status'])) {
                    $data = $this->getURL($url);
                    if ($data){
			$resp = json_decode($data, true);
                        $this->CI->db->set('value', $data);
                        $this->CI->db->where('index_real', $address);
                        $this->CI->db->update($this->CI->cacher_m->get_table_name());
                    }
                }
            } else {
                if(!isset($resp['status'])) {
                    $data = $this->getURL($url);
                    if ($data){
			$resp = json_decode($data, true);
                        $this->CI->db->set('value', $data);
                        $this->CI->db->where('index_real', $address);
                        $this->CI->db->update($this->CI->cacher_m->get_table_name());
                    }
                }
            }
        }           
          if(sw_settings('open_street_map_enabled')){
              if(!empty($resp) && isset($resp[0]) && isset($resp[0]['lat']) && isset($resp[0]['lon'])) {
                if($loaded_value === FALSE)
                {
                    $this->CI->cacher_m->cache($address, $data);
                }
                
                return array('lat' => $resp[0]['lat'], 'lng' => $resp[0]['lon'], 'alt' => 0);
              }
              
              
          } else {
            if(isset($resp['status']))
			if($resp['status'] == 'OK'){
                if($loaded_value === FALSE)
                {
                    $this->CI->cacher_m->cache($address, $data);
                }
             
			 	//all is ok
			 	$lat = $resp['results'][0]['geometry']['location']['lat'];
                $lng = $resp['results'][0]['geometry']['location']['lng'];
			 	if (!empty($lat) && !empty($lng)){
			 	   return array('lat' => $lat, 'lng' => $lng, 'alt' => 0);
				}
			}
		}
            }  
                
		//return default data
		return array('lat' => 0, 'lng' => 0, 'alt' => 0);
	}
    
    // Modified from:
    // http://www.sitepoint.com/forums/showthread.php?656315-adding-distance-gps-coordinates-get-bounding-box
    /**
    * bearing is 0 = north, 180 = south, 90 = east, 270 = west
    *
    */
    function getDueCoords($latitude, $longitude, $bearing, $distance, $distance_unit = "km", $return_as_array = FALSE) {
    
        if ($distance_unit == "m") {
          // Distance is in miles.
        	  $radius = 3963.1676;
        }
        else {
          // distance is in km.
          $radius = 6378.1;
        }
        
        //	New latitude in degrees.
        $new_latitude = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad($bearing))));
        		
        //	New longitude in degrees.
        $new_longitude = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad($bearing)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($new_latitude))));
        
        if ($return_as_array) {
          //  Assign new latitude and longitude to an array to be returned to the caller.
          $coord = array();
          $coord['lat'] = $new_latitude;
          $coord['lng'] = $new_longitude;
        }
        else {
          $coord = $new_latitude . ", " . $new_longitude;
        }
        
        return $coord;
    
    }	
    
}

?>