<?php

class Favorite_m extends My_Model {
	public $_table_name = 'sw_favorite';
	public $_order_by = 'idfavorite DESC';
    public $_primary_key = 'idfavorite';
    public $_own_columns = array('user_id');
    public $_timestamps = TRUE;

    public $form_admin = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'note' => array('field'=>'note', 'label'=>__('Currency code', 'sw_win'), 'rules'=>'trim')
        );
	}

    /* [START] For dinamic data table */
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE)
    {
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join($this->users_table, $this->_table_name.'.user_id = '.$this->users_table.'.ID', 'left');
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
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
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
        
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
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
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields1 = $this->db->list_fields('sw_listing_lang');
            $fields2 = $this->db->list_fields($this->users_table);
            $fields3 = $this->db->list_fields($this->_table_name);
            $fields = array_merge($fields1, $fields2, $fields3);
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
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
    
    public function check_if_exists($user_id, $listing_id)
    {
        $query = $this->db->get_where($this->_table_name, array('user_id'   => $user_id, 
                                                                'listing_id'=>$listing_id));
        return $query->num_rows();
    }

}