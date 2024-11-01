<?php

class Field_m extends My_Model {
	public $_table_name = 'sw_field';
	public $_order_by = 'sw_field.order, sw_field.idfield DESC';
    public $_primary_key = 'idfield';
    public $_own_columns = array();

    public $form_admin = array();
    
    public $rules_lang = array();
    
    public $field_types = array('', 'CATEGORY', 'CHECKBOX', 'INPUTBOX', 'TEXTAREA', 'DROPDOWN', 'TREE', 'UPLOAD', 'DECIMAL', 'INTEGER', 'DROPDOWN_MULTIPLE', 'DATETIME');
    
    public $field_type_color = array('CATEGORY'=>'danger', 'CHECKBOX'=>'success', 'INPUTBOX'=>'success', 'DROPDOWN'=>'success', 
                                      'TEXTAREA'=>'success', 'TREE'=>'warning', 'UPLOAD'=>'info', 'DECIMAL'=>'success', 
                                      'INTEGER'=>'success', 'HTMLTABLE'=>'info', 'PEDIGREE'=>'info', 'DROPDOWN_MULTIPLE'=>'warning', 'DATETIME'=>'info', 'TABLE'=>'info');
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'type' => array('field'=>'type', 'label'=>__('Type', 'sw_win'), 'rules'=>'trim|required'),
            'parent_id' => array('field'=>'parent_id', 'label'=>__('Parent', 'sw_win'), 'rules'=>'trim|required'),
            'is_table_visible' => array('field'=>'is_table_visible', 'label'=>__('Visible in table', 'sw_win'), 'rules'=>'trim'),
            'is_preview_visible' => array('field'=>'is_preview_visible', 'label'=>__('Visible on listing preview', 'sw_win'), 'rules'=>'trim'),
            'is_submission_visible' => array('field'=>'is_submission_visible', 'label'=>__('Visible in frontend', 'sw_win'), 'rules'=>'trim'),
            'is_required' => array('field'=>'is_required', 'label'=>__('Is required', 'sw_win'), 'rules'=>'trim'),
            'is_locked' => array('field'=>'is_locked', 'label'=>__('Is locked', 'sw_win'), 'rules'=>'trim'),
            'is_translatable' => array('field'=>'is_translatable', 'label'=>__('Is translatable', 'sw_win'), 'rules'=>'trim'),
            'is_quickvisible' => array('field'=>'is_quickvisible', 'label'=>__('Visible on quick submission', 'sw_win'), 'rules'=>'trim'),
            'columns_number' => array('field'=>'columns_number', 'label'=>__('Multiple column', 'sw_win'), 'rules'=>'trim'),
            'max_length' => array('field'=>'max_length', 'label'=>__('Max length', 'sw_win'), 'rules'=>'trim|is_natural'),
            'make_searchable' => array('field'=>'make_searchable', 'label'=>__('Make searchable', 'sw_win'), 'rules'=>'trim'),
            'image_id' => array('field'=>'image_id', 'label'=>__('Field image', 'sw_win'), 'rules'=>'trim'),
        );
        
        $this->field_types = array(''=>__('Select type', 'sw_win'), 'CATEGORY'=>__('CATEGORY', 'sw_win'), 'CHECKBOX'=>__('CHECKBOX', 'sw_win'), 'INPUTBOX'=>__('INPUTBOX', 'sw_win'), 
                                    'DROPDOWN'=>__('DROPDOWN', 'sw_win'), 'TEXTAREA'=>__('TEXTAREA', 'sw_win'),  
                                    'INTEGER'=>__('INTEGER', 'sw_win'), 'DROPDOWN_MULTIPLE'=>__('DROPDOWN_MULTIPLE', 'sw_win'), 'TABLE'=>__('TABLE', 'sw_win'), 'DATETIME'=>__('DATETIME', 'sw_win'));
        
        $this->columns_number = array(''=>__('Select columns', 'sw_win'), 
                                        '1'=>1,
                                        '2'=>2,
                                        '3'=>3,
                                        );
        
        foreach(sw_get_languages() as $lang)
        {
            $key = $lang['id'];
            $this->rules_lang["values_$key"] = array('field'=>"values_$key", 'label'=>__('Values', 'sw_win'), 'rules'=>'trim|callback__values_correction|callback__values_dropdown_check');
            $this->rules_lang["suffix_$key"] = array('field'=>"suffix_$key", 'label'=>__('Suffix', 'sw_win'), 'rules'=>'trim');
            $this->rules_lang["prefix_$key"] = array('field'=>"prefix_$key", 'label'=>__('Prefix', 'sw_win'), 'rules'=>'trim');
            $this->rules_lang["field_name_$key"] = array('field'=>"field_name_$key", 'label'=>__('Field name', 'sw_win'), 'rules'=>'trim|required');
            $this->rules_lang["hint_$key"] = array('field'=>"hint_$key", 'label'=>__('Hint', 'sw_win'), 'rules'=>'trim');
            $this->rules_lang["placeholder_$key"] = array('field'=>"placeholder_$key", 'label'=>__('Placeholder', 'sw_win'), 'rules'=>'trim');
        }
        
	}
    
    public function get_fields($lang_id = 1)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idfield = '.$this->_table_name.'_lang.field_id');
        $this->db->where('lang_id', $lang_id);
        $this->db->order_by($this->_order_by);
		$fields = $this->db->get()->result();
        
        return $fields;
    }
    
    public function get_tablefields($lang_id = 1)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idfield = '.$this->_table_name.'_lang.field_id');
        $this->db->where('lang_id', $lang_id);
        $this->db->where('is_table_visible', 1);
        $this->db->order_by($this->_order_by);
		$fields = $this->db->get()->result();
        
        return $fields;
    }
    
    public function get_random_value($field_id, $lang_id = 1)
    {
        $field_data = $this->get_field_data($field_id, $lang_id);

        if(!is_object($field_data))
        {
            return '';

            //exit('FIELD NOT EXISTS: '.$field_id);
        }
        
        if($field_data->type == 'DROPDOWN' || $field_data->type == 'DROPDOWN_MULTIPLE')
        {
            $values = explode(',',$field_data->values);
            if(sw_count($values) > 0)
            {
                $start=0;
                if(empty($values[0]))$start=1;
                
                return $values[rand($start, sw_count($values)-1)];
            }
        }
        elseif($field_data->type == 'INTEGER')
        {
            return rand(0,499);
        }
        elseif($field_data->type == 'CHECKBOX')
        {
            return rand(0,1);
        }
        elseif($field_data->type == 'TABLE')
        {
            return '';
        }
        
        return '';
    }
    
    public $fields_cache = array();
    public function get_field_data($field_id, $lang_id = 1)
    {
        if(isset($this->fields_cache[$lang_id]) && sw_count($this->fields_cache[$lang_id]) > 0)
        {
            if(isset($this->fields_cache[$lang_id][$field_id]))
                return $this->fields_cache[$lang_id][$field_id];
        }
        else
        {
            $fields = $this->get_fields($lang_id);
            //dump($fields);
            
            foreach($fields as $field)
            {
                $this->fields_cache[$lang_id][$field->idfield] = $field;
            }
            
            if(isset($this->fields_cache[$lang_id][$field_id]))
                return $this->fields_cache[$lang_id][$field_id];
        }
        
        return '';
    }
    
    public function get_field_list($lang_id = 1)
    {
        if(isset($this->fields_cache[$lang_id]) && sw_count($this->fields_cache[$lang_id]) > 0)
        {
            return $this->fields_cache[$lang_id];
        }
        else
        {
            $fields = $this->get_fields($lang_id);
            
            foreach($fields as $field)
            {
                $this->fields_cache[$lang_id][$field->idfield] = $field;
            }
            
            if(isset($this->fields_cache[$lang_id]))
                return $this->fields_cache[$lang_id];
        }
        
        return array();
    }
    
	public function get_nested($lang_id = 1)
	{
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idfield = '.$this->_table_name.'_lang.field_id');
        $this->db->where('lang_id', $lang_id);
        $this->db->order_by($this->_order_by);
		$pages = $this->db->get()->result_array();
        
		$array = array();
		foreach ($pages as $page) {
            if(!isset($this->field_types[$page['type']]))continue;
          
            $page['color'] = $this->field_type_color[$page['type']];
            $page['type'] = $page['type']; //$this->field_types[$page['type']];
          
			if (! $page['parent_id']) {
				// This page has no parent
				$array[$page['idfield']]['parent'] = $page;
			}
			else {
				// This is a child page
				$array[$page['parent_id']]['children'][] = $page;
			}
		}
        
		return $array;
	}
    
    public function get_no_parents($lang_id = 1, $curr_id = NULL)
	{
        // Fetch pages without parents
        $this->db->select($this->_table_name.'.idfield, field_name');
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.idfield = '.$this->_table_name.'_lang.field_id');
        $this->db->where('parent_id', 0);
        if(isset($curr_id))
            $this->db->where('idfield !=', $curr_id);
        $this->db->where('type', 'category');
        $this->db->where('lang_id', $lang_id);
        $this->db->order_by($this->_order_by);
        $query = $this->db->get();
        $options = $query->result();

        // Return key => value pair array
        $array = array(0 => __('No parent', 'sw_win'));
        if(sw_count($options))
        {
            foreach($options as $option)
            {
                $array[$option->idfield] = $option->field_name;
            }
        }
        
        return $array;
	}
    
    public function get_lang($id, $lang_id=1)
    {
        $result = $this->get($id);

        $this->db->select('*');
        $this->db->from($this->_table_name.'_lang');
        $this->db->where('field_id', $id);
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
    
    public function save_with_lang($data, $data_lang, $id = NULL)
    {        
        // [Save first/second image in repository]
        $curr_item = $this->get($id);
        $repository_id = NULL;
        if(is_object($curr_item))
        {
            $repository_id = $curr_item->repository_id;
        }
        
        $data['image_gallery'] = NULL;
        $data['image_filename'] = NULL;
        if(!empty($repository_id))
        {
            $this->load->model('file_m');
            $files = $this->file_m->get_by(array('repository_id'=>$repository_id));
            
            $image_repository = array();
            $data['image_gallery'] = '';
            foreach($files as $key_f=>$file_row)
            {
                if(is_object($file_row))
                {
                    if(file_exists(sw_win_upload_path().'files/thumbnail/'.$file_row->filename))
                    {
                        if(empty($data['image_filename']))
                        {
                            $data['image_filename'] = $file_row->filename;
                        }
                        
                        $data['image_gallery'].=$file_row->filename.',';
                    }
                }
            }
        }
        // [/Save first/second image in repository]

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
        $this->db->delete($this->_table_name.'_lang', array('field_id' => $id));
        
        foreach(sw_get_languages() as $lang_key=>$lang_val)
        {
            if(is_numeric($lang_key))
            {
                $curr_data_lang = array();
                $curr_data_lang['lang_id'] = $lang_key;
                $curr_data_lang['field_id'] = $id;
                
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
    
	public function save_order ($options)
	{
		if (is_array($options)) {
			foreach ($options as $order => $option) {
				if ($option['item_id'] != '' && $option['item_id'] != $option['parent_id']) {
					$data = array('parent_id' => (int) $option['parent_id'], 'order' => $order);
					$this->db->set($data)->where($this->_primary_key, $option['item_id'])->update($this->_table_name);
                    //echo $this->db->last_query();
				}
			}
		}
	}
    
    public function check_deletable($id)
    {
        $where = "( parent_id=$id OR idfield=$id ) AND ( is_locked=1 OR is_hardlocked=1 )";
        $this->db->where($where);
        $this->db->from($this->_table_name);
        
        return ($this->db->count_all_results() == 0);
    }

    public function delete($id)
    {
        //remove all values from current
        $this->db->delete('sw_listing_field', array('field_id' => $id));
        
        //Remove current translations
        $this->db->delete('sw_field_lang', array('field_id' => $id)); 
        
        parent::delete($id);
    }

}