<?php

class Review_m extends My_Model {
	public $_table_name = 'sw_review';
	public $_order_by = 'idreview DESC';
    public $_primary_key = 'idreview';
    public $_own_columns = array('user_id');
    public $_timestamps = TRUE;

    public $form_admin = array();
    public $form_listing = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'listing_id' => array('field'=>'listing_id', 'label'=>__('Listing', 'sw_win'), 'rules'=>'trim|intval'),
            'agentprofile_id' => array('field'=>'user_id', 'label'=>__('User', 'sw_win'), 'rules'=>'trim|intval'),
            'stars' => array('field'=>'stars', 'label'=>__('Stars', 'sw_win'), 'rules'=>'trim|required'),
            'message' => array('field'=>'message', 'label'=>__('Message', 'sw_win'), 'rules'=>'trim'),
            'is_visible' => array('field'=>'is_visible', 'label'=>__('Is visible', 'sw_win'), 'rules'=>'trim'),
            'repository_id' => array('field'=>'repository_id', 'label'=>__('Repository id', 'sw_win'), 'rules'=>'trim'),
            'user_mail' => array('field'=>'user_mail', 'label'=>__('Mail', 'sw_win'), 'rules'=>'trim')
        );
        
        $this->form_listing = array(
            'stars' => array('field'=>'stars', 'label'=>__('Stars', 'sw_win'), 'rules'=>'trim|required|callback_disable_demo'),
            'repository_id' => array('field'=>'repository_id', 'label'=>__('Repository id', 'sw_win'), 'rules'=>'trim'),
            'message' => array('field'=>'message', 'label'=>__('Message', 'sw_win'), 'rules'=>'trim|required')
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
    
    public function get_by($where, $single = FALSE, $check_permission=FALSE, $limit = NULL)
    {
        //remove all values from current
        if(!isset($where['lang_id']))
        {
            $lang_id = sw_current_language_id();
        }
        else
        {
            $lang_id = $where['lang_id'];
        }
        
        $this->db->select('*, '.$this->_table_name.'.repository_id as review_repository_id');
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
        
        if($limit != NULL)
            $this->db->limit($limit);
        
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
        
        $review = $this->get($id);
        if(isset($review->user_id) && $review->user_id == get_current_user_id())
            return true;
            
        return false;
    }
    
     /* [END] For dinamic data table */
    
    public function avg_rating_listing($listing_id)
    {
        $this->db->where('listing_id', $listing_id);
        $this->db->where('is_visible', 1);
        $this->db->select_avg('stars');
        $query = $this->db->get($this->_table_name);
        
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
           return $row->stars;
        } 
        
        return '';
    }
    
    public function avg_rating_agentprofile($agentprofile_id)
    {
        $this->db->where('agentprofile_id', $agentprofile_id);
        $this->db->where('is_visible', 1);
        $this->db->select_avg('stars');
        $query = $this->db->get($this->_table_name);
        
        if ($query->num_rows() > 0)
        {
           $row = $query->row();
           return $row->stars;
        } 
        
        return '';
    }
    
    public function exists_listing($user_id, $listing_id)
    {
        $query = $this->db->get_where($this->_table_name, array('user_id'   => $user_id, 
                                                                'listing_id'=>$listing_id));
        return $query->num_rows();
    }
    
    public function exists_agentprofile($user_id, $agentprofile_id)
    {
        $query = $this->db->get_where($this->_table_name, array('user_id'   => $user_id, 
                                                                'agentprofile_id'=>$agentprofile_id));
        return $query->num_rows();
    }
    
        
    public function update_counter($id, $type='')
    {
        
        $counter_field ='';
        switch ($type) {
            case 'like': $counter_field= 'counter_like';
                        break;
            case 'love': $counter_field= 'counter_love';
                        break;
            case 'wow': $counter_field= 'counter_wow';
                        break;
            case 'angry': $counter_field= 'counter_angry';
                        break;

        }
        
        if($counter_field){
            $this->db->set($counter_field, $counter_field.'+1', FALSE);
            $this->db->where('idreview', $id);
            $this->db->update($this->_table_name); 
        } else {
            return false;
        }
    }

}