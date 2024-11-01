<?php

class Rates_m extends My_Model {
	public $_table_name = 'sw_rates';
	public $_order_by = 'idrates DESC';
    public $_primary_key = 'idrates';
    public $_own_columns = array('sw_listing_agent.user_id', 'sw_calendar.user_id');
    public $_timestamps = TRUE;

    public $form_admin = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'listing_id' => array('field'=>'listing_id', 'label'=>__('Listing', 'sw_win'), 'design'=>'dropdown_listing', 'rules'=>'trim|callback__calendar_exists|required'),
            'date_from' => array('field'=>'date_from', 'label'=>__('Date from', 'sw_win'), 'design'=>'datetimepicker', 'rules'=>'trim|required'),
            'date_to' => array('field'=>'date_to', 'label'=>__('Date to', 'sw_win'), 'design'=>'datetimepicker', 'rules'=>'trim|callback__check_date|required'),
            'rate_hour' => array('field'=>'rate_hour', 'label'=>__('Rate per hour', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'rate_night' => array('field'=>'rate_night', 'label'=>__('Rate per night/day', 'sw_win'), 'design'=>'input', 'rules'=>'trim|required'),
            'rate_week' => array('field'=>'rate_week', 'label'=>__('Rate per week', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'rate_month' => array('field'=>'rate_month', 'label'=>__('Rate per month', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'currency_code' => array('field'=>'currency_code', 'value'=>sw_settings('default_currency'), 'label'=>__('Currency', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim|required'),
            'min_stay_days' => array('field'=>'min_stay_days', 'label'=>__('Min stay (days)', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'changeover_day' => array('field'=>'changeover_day', 'values'=>array(''=>'','0'=>__('Monday', 'sw_win'), 
                                                                                '1'=>__('Tuesday', 'sw_win'),'2'=>__('Wednesday', 'sw_win'),
                                                                                '3'=>__('Thursday', 'sw_win'),'4'=>__('Friday', 'sw_win'),
                                                                                '5'=>__('Saturday', 'sw_win'),'6'=>__('Sunday', 'sw_win')), 'label'=>__('Changeover day', 'sw_win'), 'design'=>'dropdown', 'rules'=>'trim'),
            
        );
	}

    /* [START] For dinamic data table */
    
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
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE)
    {
        $this->db->select('sw_rates.*, sw_listing.address, sw_listing_lang.*, sw_rates.listing_id as listing_id');
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->join($this->users_table, 'sw_calendar.user_id = '.$this->users_table.'.ID', 'left');
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
        $this->db->select('sw_rates.*, sw_listing.address, sw_listing_lang.*, sw_rates.listing_id as listing_id');
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
            $this->db->join($this->users_table, 'sw_calendar.user_id = '.$this->users_table.'.ID', 'left');

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
        
        $this->db->select('sw_rates.*, sw_listing.address, sw_listing_lang.*, sw_rates.listing_id as listing_id');
        //$this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');

        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
            $this->db->join($this->users_table, 'sw_calendar.user_id = '.$this->users_table.'.ID', 'left');

            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }

        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        
        return parent::get_by($where, $single);
    }
    
    public function check_deletable($id)
    {
        if(sw_user_in_role('administrator')) return true;
        
        $favorite = $this->get($id);
        if(isset($favorite->user_id) && $favorite->user_id == get_current_user_id())
            return true;
            
        return false;
    }
    
    
    /* [END] For dinamic data table */

    public function is_defined($listing_id, $date_from, $date_to, $except_id = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('listing_id', $listing_id);
        
        if(is_numeric($except_id))
        {
            $this->db->where('idrates !=', $except_id);
        }
        
        // Check dates availability
        $this->db->where('date_from <', $date_to);
        $this->db->where('date_to >', $date_from);
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }

    public function is_available($listing_id, $date_from, $date_to, $except_id = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('listing_id', $listing_id);
        
        if(is_numeric($except_id))
        {
            $this->db->where('idrates !=', $except_id);
        }
        
        // Check dates availability
        $this->db->where('date_from <=', $date_from);
        $this->db->where('date_to >=', $date_to);
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }

    public function is_related($object_id, $user_id, $method = 'edit')
    {
        $this->db->select('*');
        $this->db->from($this->_table_name); 
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->where($this->_table_name.'.idrates', $object_id);
        $this->db->where('sw_listing_agent.user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return true;
        
        return false;
    }

}