<?php

class Subscriptions_m extends My_Model {
	public $_table_name = 'sw_subscriptions';
	public $_order_by = 'idsubscriptions DESC';
    public $_primary_key = 'idsubscriptions';
    public $_own_columns = array();
    public $_timestamps = TRUE;

    public $form_admin = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'currency_code' => array('field'=>'currency_code', 'label'=>__('Currency code', 'sw_win'), 'rules'=>'trim|required'),
            'subscription_name' => array('field'=>'subscription_name', 'label'=>__('Package name', 'sw_win'), 'rules'=>'trim|required'),
            'days_limit' => array('field'=>'days_limit', 'label'=>__('Days', 'sw_win'), 'rules'=>'trim|required|is_numeric'),
            'listing_limit' => array('field'=>'listing_limit', 'label'=>__('Listings limit', 'sw_win'), 'rules'=>'trim|required|is_numeric'),
            'subscription_price' => array('field'=>'subscription_price', 'label'=>__('Price', 'sw_win'), 'rules'=>'trim|required|is_numeric|callback_check_rank'),
            'is_default' => array('field'=>'is_default', 'label'=>__('Is default', 'sw_win'), 'rules'=>'trim'),
            'featured_limit' => array('field'=>'featured_limit', 'label'=>__('Featured limit', 'sw_win'), 'rules'=>'trim|is_numeric'),
            'set_activated' => array('field'=>'set_activated', 'label'=>__('Set activated', 'sw_win'), 'rules'=>'trim'),
            'set_private' => array('field'=>'set_private', 'label'=>__('Set private', 'sw_win'), 'rules'=>'trim'),
            'user_type' => array('field'=>'user_type', 'label'=>__('User type', 'sw_win'), 'rules'=>'trim'),
            'woo_item_id' => array('field'=>'woo_item_id', 'label'=>__('Woo item id', 'sw_win'), 'rules'=>'trim')
        );
	}

    /* [START] For dinamic data table */
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields = $this->db->list_fields('sw_subscriptions');
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function get_pagination_lang($limit, $offset, $lang_id=1, $check_permission=FALSE, $agent_id=NULL)
    {
        $this->db->from($this->_table_name);
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            $gen_q[]=$this->_table_name.'.user_type = \''.sw_get_current_user_role().'\'';
            $gen_q[]=$this->_table_name.'.user_type is NULL';

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
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE, $agent_id=NULL)
    {
        $this->db->from($this->_table_name);
        $this->db->where($where);

        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            $gen_q[]=$this->_table_name.'.user_type = \''.sw_get_current_user_role().'\'';
            $gen_q[]=$this->_table_name.'.user_type is NULL';

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /* [END] For dinamic data table */
    
    public function get_conversions_table($where = array())
    {
        $this->db->order_by($this->_order_by);
        
        if($where)
            $this->db->where($where); 
        
        $query = $this->db->get($this->_table_name);
        
        $conversions_table = array();
        
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                $conversions_table['code'][$row->currency_code] = $row;
                $conversions_table['symbol'][$row->currency_symbol] = $row;
           }
        }
        
        return $conversions_table;
    }
    
    public function get_form_dropdown($column = 'subscription_name', $where = array(), $show_empty=TRUE)
    {
        $filter = $this->_primary_filter;

        $this->db->order_by($this->_order_by);
        
        if($where)
            $this->db->where($where); 
        
        $dbdata = $this->db->get($this->_table_name)->result_array();
        
        $results = array();
        foreach($dbdata as $key=>$row){
            if(isset($row[$column]))
            {
                if(!empty($row['currency_symbol']))
                {
                    $results[$row[$this->_primary_key]] = $row[$column].' ('.$row['currency_symbol'].')';
                }
                else
                {
                    $results[$row[$this->_primary_key]] = $row[$column];
                }
            }
            
        }
        return $results;
    }

    public function is_related($object_id, $user_id, $method='view')
    {
        $obj = $this->get($object_id, TRUE);

        $user = get_userdata( $user_id );
        
        if($method == 'edit' && !sw_is_user_in_role( $user, 'administrator' ))
            return false;

        if(empty($obj->user_type))
            return true;

        if(sw_is_user_in_role( $user, $obj->user_type ))
            return true;
        
        return false;
    }

}