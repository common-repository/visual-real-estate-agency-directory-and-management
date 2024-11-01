<?php

class Inquiry_m extends My_Model {
	public $_table_name = 'sw_inquiry';
	public $_order_by = 'sw_inquiry.idinquiry DESC';
    public $_primary_key = 'idinquiry';
    public $_own_columns = array('user_id_sender', 'user_id_receiver');

    public $form_widget = array();
    
    public $form_admin = array();
    
    public $rules_lang = array();
    
	public function __construct(){
		parent::__construct();
        
        $this->form_widget = array(
            'fullname' => array('field'=>'fullname', 'label'=>__('Full name', 'sw_win'), 'rules'=>'trim|required'),
            'widget_id' => array('field'=>'widget_id', 'label'=>__('Widget id', 'sw_win'), 'rules'=>'trim|required'),
            'phone' => array('field'=>'phone', 'label'=>__('Phone number', 'sw_win'), 'rules'=>'trim'),
            'subject' => array('field'=>'subject', 'label'=>__('Subject', 'sw_win'), 'rules'=>'trim|required'),
            'email' => array('field'=>'email', 'label'=>__('Your email', 'sw_win'), 'rules'=>'trim|required|valid_email'),
            'message' => array('field'=>'message', 'label'=>__('Message', 'sw_win'), 'rules'=>'trim|required'),
            'date_from' => array('field'=>'date_from', 'label'=>__('Date from', 'sw_win'), 'rules'=>'trim'),
            'date_to' => array('field'=>'date_to', 'label'=>__('Date to', 'sw_win'), 'rules'=>'trim')
        );
        
        $this->form_admin = array(
            'is_readed' => array('field'=>'is_readed', 'label'=>__('Read by receiver', 'sw_win'), 'rules'=>'trim')
        );
        
	}
    
    /* [START] For dinamic data table */
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE)
    {
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        $this->db->where($where);
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }
        
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function get_pagination_lang($limit, $offset, $lang_id=1, $check_permission=FALSE)
    {
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result();
            
        return array();
    }
    
    public function get_by($where, $single = FALSE, $check_permission=FALSE)
    {
        //remove all values from current
        if(!isset($where['lang_id']))
        {
            $where['lang_id'] = 1;
        }
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->where("( lang_id = {$where['lang_id']} OR lang_id is NULL )", NULL, false);
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }
        
        return parent::get_by($where, $single);
    }
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields1 = $this->db->list_fields('sw_listing_lang');
            $fields2 = $this->db->list_fields($this->_table_name);
            $fields = array_merge($fields1, $fields2);
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function check_deletable($id)
    {
        if(sw_user_in_role('administrator')) return true;
        
        $inquiry = $this->get($id);
        if(isset($inquiry->user_id_receiver) && $inquiry->user_id_receiver == get_current_user_id())
            return true;
            
        if(isset($inquiry->user_id_sender) && $inquiry->user_id_sender == get_current_user_id())
            return true;
            
        return false;
    }
    
     /* [END] For dinamic data table */

}