<?php

class Profile_m extends My_Model {
    
    protected $_table_name = 'sw_profile';
    protected $_order_by = 'idprofile';
    protected $_primary_key = 'idprofile';
    public $_own_columns = array();

    public $form_index = array();
    
	public function __construct(){
        parent::__construct();
        
        $this->load->model('user_m');

        $this->form_index = array(
            'lat' => array('field'=>'lat', 'class'=>'col-xs-6 col-sm-6', 'design'=>'input_readonly', 'label'=>__('Latitude', 'sw_win'), 'rules'=>'trim|required|numeric'),
            'lng' => array('field'=>'lng', 'class'=>'col-xs-6 col-sm-6', 'design'=>'input_readonly', 'label'=>__('Longitude', 'sw_win'), 'rules'=>'trim|required|numeric'),
            'address' => array('field'=>'address', 'label'=>__('Address', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'country' => array('field'=>'country', 'label'=>__('Country', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'city' => array('field'=>'city', 'label'=>__('City', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'position_title' => array('field'=>'position_title', 'label'=>__('Position title', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'zip_code' => array('field'=>'zip_code', 'label'=>__('ZIP code', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'phone_number' => array('field'=>'phone_number', 'label'=>__('Phone number', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'facebook' => array('field'=>'facebook', 'label'=>__('Facebook link', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'youtube' => array('field'=>'youtube', 'label'=>__('YouTube link', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'linkedin' => array('field'=>'linkedin', 'label'=>__('LinkedIn link', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'twitter' => array('field'=>'twitter', 'label'=>__('Twitter link', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'instagram' => array('field'=>'instagram', 'label'=>__('Instagram', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'profile_image' => array('field'=>'profile_image', 'label'=>__('Profile image', 'sw_win'), 'hint'=>__('Image alternative to gravatar (only admin can upload custom image)', 'sw_win'), 'design'=>'image', 'rules'=>'trim'),
            //'is_email_alerts_enabled' => array('field'=>'is_email_alerts_enabled', 'class'=>'col-xs-12 col-sm-12', 'label'=>__('Email alerts enabled', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim')
        );

        if(sw_user_in_role('AGENT'))
        {
            $this->form_index['agency_id'] = array('field'=>'agency_id', 'label'=>__('Agency email or ID', 'sw_win'), 'hint' => __('Email will be auto changed to ID, Agency verification required', 'sw_win'), 'design'=>'input', 'rules'=>'trim|callback__agency_email_to_id');
        }
        else if(sw_user_in_role('administrator') && isset($_GET['user_id']))
        {
            $user_id = $_GET['user_id'];
            $user_info = get_userdata($user_id);
            if(sw_is_user_in_role($user_info, 'AGENT'))
            {
                $this->form_index['agency_id'] = array('field'=>'agency_id', 'label'=>__('Agency email or ID', 'sw_win'), 'hint' => __('Email will be auto changed to ID, Agency verification required', 'sw_win'), 'design'=>'input', 'rules'=>'trim|callback__agency_email_to_id');
            }
        }

    }
    
    public function get_fields()
    {
        if(($fields_data = $this->cache_temp_load('fields_data')) === FALSE)
        {
            $query = $this->db->get($this->_table_name);
    
            $fields_data = array();
            
            if(is_object($query))
            foreach($query->result() as $key=>$setting)
            {
                $fields_data[$setting->field] = $setting->value;
            }
            
            $this->cache_temp_save($fields_data, 'fields_data');
        }

        return $fields_data;
    }
    
    public function get_field($field_name)
    {
        $fields = $this->get_fields();
        
        if(isset($fields[$field_name]))
            return $fields[$field_name];
            
        return NULL;
    }

    public function save_agents($agency_id, $agents_array)
    {
        if(!sw_user_in_role('administrator'))
        {
            if(get_current_user_id() != $agency_id || !sw_user_in_role('AGENCY'))return FALSE;
        }

        // Remove all current verified
        $data = array(
            'is_agency_verified' => NULL
         );

        $this->db->where('agency_id', $agency_id);
        $this->db->update($this->_table_name, $data); 


        // Add new verified again
         foreach($agents_array as $key=>$val)
         {
            $data = array(
                'is_agency_verified' => 1
             );
    
            $this->db->where('agency_id', $agency_id);
            $this->db->where('user_id', $key);
            $this->db->update($this->_table_name, $data); 
         }
        
        return TRUE;
    }

    public function related_listings_count($user_id)
    {
        $this->db->from('sw_listing_agent');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        return $query->num_rows();
    }

}