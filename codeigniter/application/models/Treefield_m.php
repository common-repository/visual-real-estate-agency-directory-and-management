<?php

class Treefield_m extends My_Model {
	public $_table_name = 'sw_treefield';
	public $_order_by = 'sw_treefield.order';
    public $_primary_key = 'idtreefield';
    public $_own_columns = array();

    public $form_admin = array();
    
    public $rules_lang = array();
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'parent_id' => array('field'=>'parent_id', 'label'=>__('Parent', 'sw_win'), 'rules'=>'trim|required'),
            'marker_icon_id' => array('field'=>'marker_icon_id', 'label'=>__('Marker icon', 'sw_win'), 'rules'=>'trim'),
            'order' => array('field'=>'order', 'label'=>__('Custom Order', 'sw_win'), 'rules'=>'trim|numeric'),
            'featured_image_id' => array('field'=>'featured_image_id', 'label'=>__('Featured image', 'sw_win'), 'rules'=>'trim'),
            'font_icon_code' => array('field'=>'font_icon_code', 'label'=>__('Font icon', 'sw_win'), 'rules'=>'trim'),
        );

        foreach(sw_get_languages() as $lang)
        {
            $key = $lang['id'];
            $this->rules_lang["value_$key"] = array('field'=>"value_$key", 'label'=>__('Value', 'sw_win'), 'rules'=>'trim|required|callback__values_correction|callback__value_dropdown_check');
            $this->rules_lang["description_$key"] = array('field'=>"description_$key", 'label'=>__('Description', 'sw_win'), 'rules'=>'trim');
        }
        
	}
    
    public function get_value($treefield_id)
	{
	    static $array = array();
        
        if(sw_count($array) == 0)
        {
            $this->db->select($this->_table_name.'.idtreefield, value, order, parent_id');
            $this->db->from($this->_table_name);
            $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idtreefield = '.$this->_table_name.'_lang.treefield_id');
            $this->db->where('lang_id', sw_current_language_id());
            $this->db->order_by($this->_order_by);
            $query = $this->db->get();
            $results = $query->result();
    
            foreach($results as $result)
            {
                $array[$result->idtreefield] = $result->value;
            }
        }
        
        if(isset($array[$treefield_id]))
            return $array[$treefield_id];
            
        return '-';
	}

    public function get_treefield($lang_id = 1, $field_id=1, $curr_id = NULL, $empty_title=NULL)
	{
        $this->db->select($this->_table_name.'.idtreefield, value, order, parent_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idtreefield = '.$this->_table_name.'_lang.treefield_id');
        if(isset($curr_id))
            $this->db->where('idtreefield !=', $curr_id);
        $this->db->where('lang_id', $lang_id);
        $this->db->where('field_id', $field_id);
        $this->db->order_by('value');
        $query = $this->db->get();
        $options = $query->result();
        
        if($empty_title === NULL)
            $empty_title = __('Root', 'sw_win');

        // Return key => value pair array
        $array = array(0 => $empty_title);
        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->parent_id][$option->idtreefield] = $option;
            }
        }
        
        $this->get_treefield_recursive(0, $t_array, $array, 0);
        
        return $array;
	}
    
    private function get_treefield_recursive($parent_id, $t_array, &$array, $level)
    {
        if(isset($t_array[$parent_id]))
        foreach($t_array[$parent_id] as $key=>$option)
        {
            $level_gen = str_pad('', $level*12, '&nbsp;');

            $array[$key] = $level_gen.'|-'.$option->value;
            
            if(isset($t_array[$key]))
                $this->get_treefield_recursive($key, $t_array, $array, $level+1);
        }
    }

    public function get_lang($id, $lang_id=1)
    {
        $result = $this->get($id);
        
        $this->db->select('*');
        $this->db->from($this->_table_name.'_lang');
        $this->db->where('treefield_id', $id);
        $lang_result = $this->db->get()->result_array();
        
        foreach ($lang_result as $row)
        {
            foreach ($row as $key=>$val)
            {
                $result->{$key.'_'.$row['lang_id']} = $val;
            }
        }
        
        foreach(sw_get_languages() as $key_lang=>$val_lang)
        {
            foreach($this->rules_lang as $r_key=>$r_val)
            {
                if(!isset($result->{$r_key}))
                {
                    $result->{$r_key} = '';
                }
            }
        }
        
        return $result;
    }
    
    public function get_table_tree($lang_id = 1, $field_id=0, $current_id = NULL, $return_print=true, $custom_order=NULL, $custom_fields='', $where = array())
	{
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.idtreefield, value, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idtreefield = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('field_id', $field_id);
        $this->db->where('lang_id', $lang_id);
        //if($current_id != NULL)$this->db->where($this->_table_name.'.id !=', $current_id);
        if(sw_count($where) > 0)
            $this->db->where($where);

        if($custom_order != NULL)
        {
            $this->db->order_by($this->_table_name.'.'.$custom_order);
        }
        else
        {
            $this->db->order_by($this->_order_by);
        }

        
        
        $query = $this->db->get();
        
        if($query==FALSE)
            return false;
        
        $options = $query->result();

        // Return key => value pair array
        $array = array();
        
        if(sw_count($where) > 0 && sw_count($options))
        {
            return $options;
        }
        
        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->parent_id][$option->idtreefield] = $option;
            }
        }
        
        if(!$return_print)
        {
            return $t_array;
        }
        
        $this->_generate_table_tree_recursive(0, $t_array, $array, 0);
        return $array;
	}
    
    private function _generate_table_tree_recursive($parent_id, $t_array, &$array, $level)
    {
        if(isset($t_array[$parent_id]))
        foreach($t_array[$parent_id] as $key=>$option)
        {
            $level_gen = str_pad('', $level*12, '&nbsp;');
            
            $option->visual = $level_gen.'|-';
            $array[$key] = $option;
            
            if(isset($t_array[$key]))
                $this->_generate_table_tree_recursive($key, $t_array, $array, $level+1);
        }
    }
    
    public function get_all_childs($treefield_id, &$childs)
    {
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.idtreefield, level, parent_id');
        $this->db->from($this->_table_name);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $options = $query->result();

        $t_array = array();
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $t_array[$option->parent_id][$option->idtreefield] = $option;
            }
        }
        
        $this->_get_all_childs_recursive($treefield_id, $t_array, $childs);
    }
    
    private function _get_all_childs_recursive($parent_id, $t_array, &$array)
    {
        if(isset($t_array[$parent_id]))
        foreach($t_array[$parent_id] as $key=>$option)
        {
            $array[$key] = $key;
            
            if(isset($t_array[$key]))
                $this->_get_all_childs_recursive($key, $t_array, $array);
        }
    }

    public function count_listings($treefield_id, $column_name = 'category_id')
    {
        $where = "( $column_name=$treefield_id )";
        $this->db->where($where);
        $this->db->from('sw_listing');
        
        return $this->db->count_all_results(); // Produces an integer, like 17
    }
    
    public function get_all_list($where = NULL, $limit = NULL, $lang_id = NULL)
    {
        if(is_null($lang_id))
            $lang_id = sw_current_language_id();
        
        // Fetch pages without parents
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idtreefield = '.$this->_table_name.'_lang.treefield_id');
        $this->db->where('lang_id', $lang_id);

        if(!is_null($where))
            $this->db->where($where);

        $this->db->order_by($this->_order_by);
        $this->db->limit($limit);

        $query = $this->db->get();
        $results = $query->result();
        
        $list = array();
        foreach($results as $key=>$row)
        {
            $list[$row->idtreefield] = $row;
        }
        
        return $list;
    }
    
    public function save_with_lang($data, $data_lang, $id = NULL)
    {
        
        if(empty($data['parent_id']))
        {
            $data['level'] = 0;
        }
        elseif(empty($data['level']))
        {
            $parent_treefield = $this->get($data['parent_id']);
            $data['level'] = $parent_treefield->level + 1;
        }
        
        // Insert
        if($id === NULL)
        {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else
        {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }
        
        // Save lang data
        $this->db->delete($this->_table_name.'_lang', array('treefield_id' => $id));
        
        foreach(sw_get_languages() as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['lang_id'] = $lang_key;
                $curr_data_lang['treefield_id'] = $id;
                
                foreach($data_lang as $data_key=>$data_val)
                {
                    $pos = strrpos($data_key, "_");
                    if(substr($data_key,$pos+1) == $lang_key)
                    {
                        $curr_data_lang[substr($data_key,0,$pos)] = $data_val;
                    }
                }
                
                $this->db->set($curr_data_lang);
                $this->db->insert($this->_table_name.'_lang');
            }
        }

        return $id;
    }
    
    public function check_deletable($id)
    {
        $where = "( parent_id=$id OR idtreefield=$id )";
        $this->db->where($where);
        $this->db->from($this->_table_name);
        
        return ($this->db->count_all_results() == 0);
    }

    public function delete($id)
    {
        //Get all childs
        $childs = array();
        $this->get_all_childs($id, $childs);
        
        //Delete childs
        if(sw_count($childs) > 0)
        {
            $this->db->where_in('treefield_id', $childs);
            $this->db->delete('sw_treefield_lang'); 
            $this->db->where_in('treefield_id', $childs);
            $this->db->delete('sw_dependentfields');
            $this->db->where_in('idtreefield', $childs);
            $this->db->delete('sw_treefield');  
        }
        
        //remove all values from current
        $this->db->delete('sw_treefield', array('idtreefield' => $id));
        
        //Remove current translations
        $this->db->delete('sw_treefield_lang', array('treefield_id' => $id)); 
        
        $this->db->delete('sw_dependentfields', array('treefield_id' => $id)); 
        
        parent::delete($id);
    }

}