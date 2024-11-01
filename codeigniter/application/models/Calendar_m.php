<?php

class Calendar_m extends My_Model {
	public $_table_name = 'sw_calendar';
	public $_order_by = 'idcalendar DESC';
    public $_primary_key = 'idcalendar';
    public $_own_columns = array('sw_listing_agent.user_id', 'sw_calendar.user_id');
    public $_timestamps = TRUE;

    public $form_admin = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'user_id' => array('field'=>'user_id', 'label'=>__('User', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'listing_id' => array('field'=>'listing_id', 'label'=>__('Listing', 'sw_win'), 'design'=>'dropdown_listing', 'rules'=>'trim|required|callback__unique_calendar'),
            'calendar_title' => array('field'=>'calendar_title', 'label'=>__('Calendar Title', 'sw_win'), 'design'=>'input', 'rules'=>'trim|required'),
            'calendar_type' => array('field'=>'calendar_type', 'values'=>array(''=>'', 'DAY'=>__('Daily', 'sw_win'), 'HOUR'=>__('Hourly', 'sw_win')), 'label'=>__('Calendar Type', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim|required'),
            'is_activated' => array('field'=>'is_activated', 'label'=>__('Is activated', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'payment_details' => array('field'=>'payment_details', 'label'=>__('Payment instruction details', 'sw_win'), 'design'=>'textarea', 'rules'=>'trim'),

        );
	}

    /* [START] For dinamic data table */
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields = $this->db->list_fields('sw_calendar');
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE)
    {
        $this->db->select('sw_calendar.*, sw_listing.address, sw_listing_lang.*, sw_calendar.listing_id as listing_id');
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');

        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
            $this->db->join($this->users_table, $this->_table_name.'.user_id = '.$this->users_table.'.ID', 'left');

            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }

        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        $this->db->where($where);
        
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function get_pagination_lang($limit, $offset, $lang_id=1, $check_permission=FALSE)
    {
        $this->db->select('sw_calendar.*, sw_listing.address, sw_listing_lang.*, sw_calendar.listing_id as listing_id');
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
            $this->db->join($this->users_table, $this->_table_name.'.user_id = '.$this->users_table.'.ID', 'left');

            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }

        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        
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
            $lang_id = 1;
        }
        else
        {
            $lang_id = $where['lang_id'];
        }
        
        $this->db->select('sw_calendar.*, sw_listing.address, sw_listing_lang.*, sw_calendar.listing_id as listing_id');
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->join($this->users_table, $this->_table_name.'.user_id = '.$this->users_table.'.ID', 'left');
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
        
        return parent::get_by($where, $single);
    }
    
    public function check_deletable($id)
    {
        if(sw_user_in_role('administrator')) return true;
            
        return false;
    }

    
    /* [END] For dinamic data table */

    public function is_related($object_id, $user_id, $method = 'edit')
    {
        $this->db->select('*');
        $this->db->from($this->_table_name); 
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->where($this->_table_name.'.idcalendar', $object_id);
        $this->db->where('sw_listing_agent.user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return true;
        
        return false;
    }

}