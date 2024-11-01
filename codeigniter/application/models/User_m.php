<?php

class User_m extends My_Model {
	public $_table_name = 'users';
	public $_order_by = 'users.display_name';
    public $_primary_key = 'ID';
    public $_own_columns = array();

    public $form_register = array();
    public $form_login = array();
    
	public function __construct(){
		parent::__construct();
        
        $this->_table_name = $this->users_table;
        $this->_order_by = $this->users_table.'.display_name';
        
        $this->form_register = array(
            'account_type' => array('field'=>'account_type', 'label'=>__('Account type', 'sw_win'), 'rules'=>'trim|required'),
            'email' => array('field'=>'email', 'label'=>__('Email', 'sw_win'), 'rules'=>'trim|valid_email|callback__unique_email|required'),
            'username' => array('field'=>'username', 'label'=>__('Username', 'sw_win'), 'rules'=>'trim|callback__unique_username|required'),
            'password' => array('field'=>'password', 'label'=>__('Password', 'sw_win'), 'rules'=>'trim|matches[re_password]|min_length[8]|required'),
            're_password' => array('field'=>'re_password', 'label'=>__('Re-enter password', 'sw_win'), 'rules'=>'trim|required')
        );
        
        $this->form_login = array(
            'username' => array('field'=>'username', 'label'=>__('Username', 'sw_win'), 'rules'=>'trim|required'),
            'password' => array('field'=>'password', 'label'=>__('Password', 'sw_win'), 'rules'=>'trim|required')
        );
        
	}
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields = $this->db->list_fields($this->_table_name);
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }

    public function get_by($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = NULL, $check_permission=FALSE, $agent_id=NULL)
    {        
        $this->db->limit($limit);
        $this->db->offset($offset);
        
        return parent::get_by($where, $single);
    }

    public function hash_token($string)
	{
	   //return $string;
       
       if(config_item('hash_function') == '')
       {
           if (function_exists('hash')) {
                return substr(hash('sha512', $string.config_item('encryption_key')), 0, 32);
           }
    
           return substr(md5($string.config_item('encryption_key')), 0, 32);
       }
       else if(config_item('hash_function') == 'hash')
       {
            return substr(hash('sha512', $string.config_item('encryption_key')), 0, 32);
       }
       else if(config_item('hash_function') == 'md5')
       {
            return substr(md5($string.config_item('encryption_key')), 0, 32);
       }
	}

}

?>