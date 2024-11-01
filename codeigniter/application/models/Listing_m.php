<?php

class Listing_m extends My_Model {
	public $_table_name = 'sw_listing';
	public $_order_by = 'sw_listing.idlisting DESC';
    public $_primary_key = 'idlisting';
    public $_own_columns = array();
    public $_timestamps = TRUE;

    public $form_admin = array();
    
    public $form_agent = array();
    
    public $rules_lang = array();
    
    public $field_types = array('', 'CATEGORY', 'CHECKBOX', 'INPUTBOX', 'TEXTAREA', 'DROPDOWN', 'TREE', 'UPLOAD', 'DECIMAL', 'INTEGER', 'DROPDOWN_MULTIPLE');
    
    public $field_type_color = array('CATEGORY'=>'danger', 'CHECKBOX'=>'success', 'INPUTBOX'=>'success', 'DROPDOWN'=>'success', 
                                      'TEXTAREA'=>'success', 'TREE'=>'warning', 'UPLOAD'=>'info', 'DECIMAL'=>'success', 
                                      'INTEGER'=>'success', 'HTMLTABLE'=>'info', 'PEDIGREE'=>'info', 'DROPDOWN_MULTIPLE'=>'warning', 'DATETIME'=>'info');
    
    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();

        $this->form_admin = array(
            'repository_id' => array('field'=>'repository_id', 'label'=>__('Repository', 'sw_win'), 'rules'=>'trim'),
            'address' => array('field'=>'address', 'label'=>__('Address', 'sw_win'), 'rules'=>'trim'),
            'gps' => array('field'=>'gps', 'label'=>__('Gps', 'sw_win'), 'rules'=>'trim'),
            'date_modified' => array('field'=>'date_modified', 'label'=>__('Date modified', 'sw_win'), 'rules'=>'trim|min_length[10]'),
            'date_rank_expire' => array('field'=>'date_rank_expire', 'label'=>__('Date rank expire', 'sw_win'), 'rules'=>'trim|min_length[10]'),
            'transition_id' => array('field'=>'transition_id', 'label'=>__('Transition id', 'sw_win'), 'rules'=>'trim'),
            'rank' => array('field'=>'rank', 'label'=>__('Rank', 'sw_win'), 'rules'=>'trim|is_numeric'),
            'user_id' => array('field'=>'user_id', 'label'=>__('User id', 'sw_win'), 'rules'=>'trim'),
            'is_primary' => array('field'=>'is_primary', 'label'=>__('Is primary', 'sw_win'), 'rules'=>'trim'),
            'related_id' => array('field'=>'related_id', 'label'=>__('Related id', 'sw_win'), 'rules'=>'trim'),
            'is_featured' => array('field'=>'is_featured', 'label'=>__('Is featured', 'sw_win'), 'rules'=>'trim'),
            'is_activated' => array('field'=>'is_activated', 'label'=>__('Is activated', 'sw_win'), 'rules'=>'trim'),
            'category_id' => array('field'=>'category_id', 'label'=>__('Category', 'sw_win'), 'rules'=>'trim'),
            'location_id' => array('field'=>'location_id', 'label'=>__('Location', 'sw_win'), 'rules'=>'trim'),
            'category_id_multi' => array('field'=>'category_id_multi', 'label'=>__('Categories', 'sw_win'), 'rules'=>'trim'),
            'location_id_multi' => array('field'=>'location_id_multi', 'label'=>__('Locations', 'sw_win'), 'rules'=>'trim')
        );
        
        $this->form_agent = array(
            'repository_id' => array('field'=>'repository_id', 'label'=>__('Repository', 'sw_win'), 'rules'=>'trim'),
            'address' => array('field'=>'address', 'label'=>__('Address', 'sw_win'), 'rules'=>'trim'),
            'gps' => array('field'=>'gps', 'label'=>__('Gps', 'sw_win'), 'rules'=>'trim'),
            'transition_id' => array('field'=>'transition_id', 'label'=>__('Transition id', 'sw_win'), 'rules'=>'trim'),
            'user_id' => array('field'=>'user_id', 'label'=>__('User id', 'sw_win'), 'rules'=>'trim|callback__check_subscription'),
            'is_primary' => array('field'=>'is_primary', 'label'=>__('Is primary', 'sw_win'), 'rules'=>'trim'),
            'related_id' => array('field'=>'related_id', 'label'=>__('Related id', 'sw_win'), 'rules'=>'trim'),
            'category_id' => array('field'=>'category_id', 'label'=>__('Category', 'sw_win'), 'rules'=>'trim'),
            'location_id' => array('field'=>'location_id', 'label'=>__('Location', 'sw_win'), 'rules'=>'trim'),
            'category_id_multi' => array('field'=>'category_id_multi', 'label'=>__('Categories', 'sw_win'), 'rules'=>'trim'),
            'location_id_multi' => array('field'=>'location_id_multi', 'label'=>__('Locations', 'sw_win'), 'rules'=>'trim')
        );
        
        $this->load->model('field_m');
        $this->fields_list = $this->field_m->get_field_list(sw_default_language_id());
        
        foreach(sw_get_languages() as $key=>$lang)
        {
            foreach($this->fields_list as $key_field=>$field)
            {
                $this->rules_lang['input_'.$field->idfield.'_'.$key] = array('field'=>'input_'.$field->idfield.'_'.$key, 'label'=>$field->field_name, 'rules'=>'trim');
            
                if($field->is_required == '1' && 
                  ($field->is_translatable || sw_default_language() == $lang['lang_code']) &&
                  ( (sw_settings('multilanguage_required') && !sw_is_page(sw_settings('quick_submission'))) || sw_default_language() == $lang['lang_code'])
                  )
                {
                    $this->rules_lang['input_'.$field->idfield.'_'.$key]['rules'].='|required';
                }
                
                if(is_numeric($field->max_length))
                {
                    $this->rules_lang['input_'.$field->idfield.'_'.$key]['rules'].='|max_length['.$field->max_length.']';
                }

            }
            
            $this->rules_lang['input_slug_'.$key] = array('field'=>'input_slug_'.$key, 'label'=>__('Slug', 'sw_win'), 'rules'=>'trim');
        }
	}
    
    public function get_lang($id, $lang_id=1)
    {
        $result = $this->get($id);
        
        if(!empty($result)){
            $this->db->where('listing_id', $id);
            $query = $this->db->get('sw_listing_field');

            foreach ($query->result() as $key=>$row)
            {
                $result->{'input_'.$row->field_id.'_'.$row->lang_id} = $row->value;
            }
            
            $this->db->from('sw_listing_lang');
            $this->db->where('listing_id', $id);
            $query = $this->db->get();
            
            foreach ($query->result() as $key=>$row)
            {
                $result->{'input_slug_'.$row->lang_id} = $row->slug;
                $result->{'slug'} = $row->slug;
            }
            
        }
        
        return $result;
    }
    
    public function get_agents($listing_id)
    {
        $this->db->from('sw_listing_agent');
        $this->db->join($this->users_table, 'sw_listing_agent.user_id = '.$this->users_table.'.ID', 'left');
        $this->db->join('sw_profile', 'sw_listing_agent.user_id = sw_profile.user_id', 'left');
        $this->db->where('listing_id', $listing_id);
        $query = $this->db->get();
        
        return $query->result();
    }

    public function get_agency($listing_id)
    {
        $this->db->select('profile_b.*, '.$this->users_table.'.*');
        $this->db->from('sw_listing_agent');
        $this->db->join('sw_profile as profile_a', 'sw_listing_agent.user_id = profile_a.user_id');
        $this->db->join($this->users_table, 'profile_a.agency_id = '.$this->users_table.'.ID', 'left');
        $this->db->join('sw_profile as profile_b', 'profile_a.agency_id = profile_b.user_id', 'left');
        $this->db->where('listing_id', $listing_id);
        $this->db->where('profile_a.is_agency_verified', 1);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    public function get_agents_dropdown($listing_id)
    {
        $this->db->from('sw_listing_agent');
        $this->db->join($this->users_table, 'sw_listing_agent.user_id = '.$this->users_table.'.ID');
        $this->db->where('listing_id', $listing_id);
        $query = $this->db->get();
        
        $agents_dropdown = array();
        
        foreach ($query->result() as $key=>$row)
        {
            $agents_dropdown[$row->user_id] = $row->user_id.', '.$row->display_name;
        }
        
        return $agents_dropdown;
    }

    public function get_treefield_dropdown($listing_id, $field_id=1)
    {
        $this->db->from('sw_treefield_listing');
        $this->db->join('sw_treefield_lang', 'sw_treefield_listing.treefield_id = sw_treefield_lang.treefield_id');
        $this->db->join('sw_treefield', 'sw_treefield_listing.treefield_id = sw_treefield.idtreefield');
        $this->db->where('listing_id', $listing_id);
        $this->db->where('field_id', $field_id);
        $this->db->where('lang_id', sw_current_language_id());
        $query = $this->db->get();
        
        $agents_dropdown = array();
        
        foreach ($query->result() as $key=>$row)
        {
            $level_gen = str_pad('', $row->level*12, '&nbsp;');

            $agents_dropdown[$row->treefield_id] = $level_gen.$row->value;
        }
        
        return $agents_dropdown;
    }
    
    public function get_related($listing, $lang_id=1)
    {
        $id_listing = $listing->idlisting;
        $id_related = $listing->related_id;
        
        $where['lang_id'] = $lang_id;
        
        if(empty($id_related))
        {
            $this->db->where("( related_id = $id_listing )");
        }
        else
        {
            $this->db->where("( idlisting = $id_related OR related_id = $id_listing )");
        }
        
        $this->db->where("( is_activated = 1 )");
        
        return $this->get_by($where, FALSE);
    }
    
    /* [START] For dinamic data table */
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields1 = $this->db->list_fields('sw_listing_lang');
            $fields2 = $this->db->list_fields('sw_listing');
            $fields = array_merge($fields1, $fields2);
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function get_pagination_lang($limit, $offset, $lang_id=1, $check_permission=FALSE, $agent_id=NULL)
    {
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.'.$this->_primary_key.'= '.$this->_table_name.'_lang.listing_id');
        $this->db->where('lang_id', $lang_id);
        
        if( (!sw_user_in_role('administrator') && $check_permission) || (!is_null($agent_id) && !empty($agent_id) ) )
        {
            $this->db->join('sw_listing_agent', $this->_table_name.'.idlisting = sw_listing_agent.listing_id', 'left');
            
            if(!is_null($agent_id) && !empty($agent_id))
            {
                $this->db->where('sw_listing_agent.user_id', $agent_id);
            }
            else
            {
                $this->db->where('sw_listing_agent.user_id', get_current_user_id());
            }
        }
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result();
            
        return array();
    }
    
    public function get_by($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = NULL, $check_permission=FALSE, $agent_id=NULL)
    {
        //remove all values from current
        if(isset($where['lang_id']))
        {
            $this->db->join($this->_table_name.'_lang', $this->_table_name.'.'.$this->_primary_key.'= '.$this->_table_name.'_lang.listing_id');
        }
        
        if( (!sw_user_in_role('administrator') && $check_permission) || (!is_null($agent_id) && !empty($agent_id) ))
        {
            $this->db->join('sw_listing_agent', $this->_table_name.'.idlisting = sw_listing_agent.listing_id', 'left');
            
            if(!is_null($agent_id) && !empty($agent_id))
            {
                $this->db->where('sw_listing_agent.user_id', $agent_id);
            }
            else
            {
                $this->db->where('sw_listing_agent.user_id', get_current_user_id());
            }
        }
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        
        if($order_by === NULL)
            $order_by = $this->_order_by;
        
        $this->db->order_by($order_by);
        
        return parent::get_by($where, $single);
    }
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE, $agent_id=NULL)
    {
        $this->db->from($this->_table_name);
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.'.$this->_primary_key.'= '.$this->_table_name.'_lang.listing_id');
        $this->db->where($where);
        $this->db->where('lang_id', $lang_id);
        
        if( (!sw_user_in_role('administrator') && $check_permission) || (!is_null($agent_id) && !empty($agent_id) ))
        {
            $this->db->join('sw_listing_agent', $this->_table_name.'.idlisting = sw_listing_agent.listing_id', 'left');
            
            if(!is_null($agent_id) && !empty($agent_id))
            {
                $this->db->where('sw_listing_agent.user_id', $agent_id);
            }
            else
            {
                $this->db->where('sw_listing_agent.user_id', get_current_user_id());
            }
        }
        
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /* [END] For dinamic data table */
    
    public function save($data, $id=NULL)
    {
        // Save lat lng in decimal for radius/rectangle search
        if(!empty($data['gps']))
        {
            $gps = explode(', ', $data['gps']);
            $data['lat'] = floatval($gps[0]);
            $data['lng'] = floatval($gps[1]);
        }
        
        // [Save users data]
        unset($data['user_id'], $data['category_id_multi'], $data['location_id_multi']);
        
        if(!sw_user_in_role('administrator'))
        {
            if(empty($id) && sw_settings('listing_activation_required') == 0)
            {
                $data['is_activated'] = 1;
            }
            else
            {
                unset($data['is_activated'],
                      $data['is_featured']);
            }
        }
        // [/Save users data]
        
        // [Save first image in repository]
        $curr_estate = $this->get($id);
        $repository_id = NULL;
        if(is_object($curr_estate))
        {
            $repository_id = $curr_estate->repository_id;

            // Repository can be defined only on first save / creation
            unset($data['repository_id']);
        }
        else if(!empty($data['repository_id']))
        {
            // Activate repository
            $this->repository_m->save(array('is_activated'=>1), $data['repository_id']);
            $repository_id = $data['repository_id'];
        }
        
        $data['image_repository'] = NULL;
        $data['image_filename'] = NULL;
        if(!empty($repository_id))
        {
            $this->load->model('file_m');
            $files = $this->file_m->get_by(array('repository_id'=>$repository_id));
            
            $image_repository = array();
            foreach($files as $key_f=>$file_row)
            {
                if(is_object($file_row))
                if(file_exists(sw_win_upload_path().'files/thumbnail/'.$file_row->filename))
                {
                    if(empty($data['image_filename']))
                        $data['image_filename'] = $file_row->filename;
                        
                    $image_repository[] = $file_row->filename;
                }
            }
            
            $data['image_repository'] = json_encode($image_repository);
        }
        // [/Save first image in repository]
        
        $data['last_edit_ip']=$this->input->ip_address();
        
        if(!empty($data['is_primary']))
        {
            $data['related_id'] = NULL;
        }
        else
        {
            // Non admin user can't select different related id if 
            // it's not their own listing
            if(!sw_user_in_role('administrator'))
            {
                if(isset($data['related_id']) && !$this->is_related($data['related_id'], get_current_user_id()))
                    unset($data['related_id']);  
            }
            
            if(isset($data['related_id']) && $data['related_id'] == $id)
                unset($data['related_id']);
        }
        
        return parent::save($data, $id);
    }
    
    public function save_with_lang($data, $lang_data, $id = NULL, $user_id_set = NULL)
    {
        $this->load->model('slug_m');
        
        $is_new = ($id === NULL);

        // Delete all
        if(!empty($id))
        {
            $this->db->where('listing_id', $id);
            $this->db->where('value !=', 'SKIP_ON_EMPTY');
            $this->db->delete('sw_listing_field'); 
        }
        
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields_lang')) === FALSE)
        {
            $fields = $this->db->list_fields('sw_listing_lang');
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields_lang');
        }

        $id = $this->save($data, $id);
        
        // [Save users data]
        if($is_new)
        {
            $user_id = get_current_user_id();
            
            if($user_id_set !== NULL)
                $user_id = $user_id_set;
            
            if(is_numeric($user_id) && $user_id > 0)
            {
                $this->db->set(array('listing_id'=>$id,
                                     'user_id'=>$user_id));
                $this->db->insert('sw_listing_agent');
            }
        }
        else
        {
            if(sw_user_in_role('administrator'))
            {
                $this->db->where('listing_id', $id);
                $this->db->delete('sw_listing_agent'); 
                
                if(is_array($data['user_id']))
                foreach($data['user_id'] as $val)
                {
                    $this->db->set(array('listing_id'=>$id,
                                         'user_id'=>$val));
                    $this->db->insert('sw_listing_agent');
                }
            }
        }
        // [/Save users data]

        // [Save multi categories/locations data]
        if(sw_settings('enable_multiple_treefield'))
        {
            $this->db->where('listing_id', $id);
            $this->db->delete('sw_treefield_listing'); 
            
            if(is_array($data['location_id_multi']))
                foreach($data['location_id_multi'] as $val)
                {
                    $this->db->set(array('listing_id'=>$id,
                                        'treefield_id'=>$val));
                    $this->db->insert('sw_treefield_listing');
                }

            if(is_array($data['category_id_multi']))
                foreach($data['category_id_multi'] as $val)
                {
                    $this->db->set(array('listing_id'=>$id,
                                        'treefield_id'=>$val));
                    $this->db->insert('sw_treefield_listing');
                }
        }
        // [/Save multi categories/locations data]
        
        // [Save languages data]
        
        $insert_batch = array();
        $data_listing_lang = array();
        
        // [Save slug]
        foreach(sw_get_languages() as $key=>$lang)
        {
            $slug = '';
            
            if(isset($lang_data['input_slug_'.$lang['id']]))
            {
                $slug = $lang_data['input_slug_'.$lang['id']];
            }
            
            if(empty($slug) && isset($lang_data['input_10_'.$lang['id']]))
            {
                $slug = $lang_data['input_10_'.$lang['id']];
            }
            
            if(empty($slug) && isset($lang_data['input_10_'.sw_get_languages(sw_default_language())]))
            {
                // if multilanguage is not required, add default slug + lang code
                $slug = $lang_data['input_10_'.sw_get_languages(sw_default_language())]
                                          .'_'.sw_get_languages($lang['id']);
            }
            
            $slug = str_replace( '&quot;','', $slug );
            $slug = str_replace( '&#039;','', $slug );
            $slug = str_replace( '&#92;','', $slug );
            $value = $this->slug_m->save_slug($this->_table_name, $id, $lang['id'], $slug);
            $data_listing_lang[$lang['id']]['slug']=$value;
        }
        // [/Save slug]
        
        //dump($lang_data);
        
        // Fetch hidden dependent fields (to skip saving)
        
        
        
        $this->load->model('dependentfield_m');
        $hidden_fields=array();
        if(isset($data['category_id']))
            $hidden_fields = $this->dependentfield_m->get_hidden_fields($data['category_id']);
        
        foreach($lang_data as $key=>$value)
        {
            
            if(substr($key, 0, 5) == 'input' && !is_null($value))
            {
                $exp = explode('_', $key);
                $field_id = $exp[1];
                $lang_id = $exp[2];
                $lang_code = sw_get_languages($lang_id);
                 
                if(isset($hidden_fields[$field_id]))
                {
                    continue;
                }
                
                $val_numeric = get_numeric_val($value);
                
                if(isset($this->fields_list[$field_id])){
                    $field_data = $this->fields_list[$field_id];
                    /* comvert datetime value to unix timestamp */
                    if($field_data->type == 'DATETIME')
                    { 
                        if(!empty($value) && (bool)strtotime($value)) {
                            $val_numeric = strtotime($value);
                        }
                    }
                }
                
                $insert_arr = array('lang_id' => $lang_id,
                                    'listing_id' => $id,
                                    'field_id' => $field_id,
                                    'value' => $value,
                                    'value_num' => $val_numeric);

                                    //'value' => str_replace('\'', '`', $value),
                
                /* [listing_lang] */
                $data_listing_lang[$lang_id]['lang_id']=$lang_id;
                $data_listing_lang[$lang_id]['listing_id']=$id;
                $data_listing_lang[$lang_id]['json_object']['field_'.$field_id] = sw_win_prepare_json($value);
                
                if (isset($fields['field_'.$field_id]))
                {
                    $data_listing_lang[$lang_id]['field_'.$field_id]=$value;
                }
                
                if(is_numeric($val_numeric) && isset($fields['field_'.$field_id.'_int']))
                {
                    $data_listing_lang[$lang_id]['field_'.$field_id.'_int'] = floatval($val_numeric);
                }
                /* [/listing_lang] */

                if($value != 'SKIP_ON_EMPTY')
                    $insert_batch[] = $insert_arr;
                    
                /* [non-translatable-fields] */
                if(!isset($this->fields_list[$field_id]))
                    continue;

                $field_data = $this->fields_list[$field_id];
                
                if(
                      !$field_data->is_translatable ||
                      (!sw_user_in_role('administrator') && !sw_settings('multilanguage_required')) ||
                      sw_is_page(sw_settings('quick_submission'))     
                  )
                {
                    
                    
                    if($field_data->type == 'DROPDOWN' || $field_data->type == 'DROPDOWN_MULTIPLE')
                    {
                        // If it's dropdown, list index should be same (not just text copy)
                        
                        // Get selected index
                        $vals = explode(',',$field_data->values);
                        $index = array_search($value, $vals);
                        
                        if($index !== FALSE)
                        foreach(sw_get_languages() as $key_1=>$lang_1)
                        {
                            if($lang_1['id'] != $lang_id)
                            {
                                $insert_arr['lang_id'] = $lang_1['id'];

                                // Get value by selected index
                                $field_data_cus = $this->field_m->get_field_data($field_id, $lang_1['id']);
                                
                                if(empty($field_data_cus))continue;
                                
                                $field_data_cus->values;
                                $vals = explode(',',$field_data_cus->values);
                                
                                if(!isset($vals[$index]))continue;

                                $value_custom = $vals[$index];
                                $insert_arr['value'] = $value_custom;
                                $insert_arr['value_num'] = get_numeric_val($value_custom);
                                
                                if($value != 'SKIP_ON_EMPTY')
                                    $insert_batch[] = $insert_arr;
                                    
                                $data_listing_lang[$lang_1['id']]['lang_id']=$lang_1['id'];
                                $data_listing_lang[$lang_1['id']]['listing_id']=$id;
                                
                                if(isset($data_listing_lang[$lang_id]['field_'.$field_id]))
                                {
                                    $data_listing_lang[$lang_1['id']]['field_'.$field_id] = $insert_arr['value'];
                                }
                                
                                if(isset($data_listing_lang[$lang_id]['json_object']['field_'.$field_id]))
                                {
                                    $data_listing_lang[$lang_1['id']]['json_object']['field_'.$field_id] = sw_win_prepare_json($insert_arr['value']);
                                }
                                
                                if(isset($data_listing_lang[$lang_id]['field_'.$field_id.'_int']))
                                {
                                    $data_listing_lang[$lang_1['id']]['field_'.$field_id.'_int'] = $insert_arr['value_num'];
                                }
                            }
                        }
                    }
                    else
                    {
                        $source_lang_code = sw_default_language();
                        $source_lang_id = sw_default_language_id();
                        
                        if($lang_code == $source_lang_code)
                        foreach(sw_get_languages() as $key_1=>$lang_1)
                        {
                            
                            if($lang_1['lang_code'] != $source_lang_code)
                            {
                                if(isset($lang_data['input_'.$field_id.'_'.$lang_1['id']]) && 
                                   !is_null($lang_data['input_'.$field_id.'_'.$lang_1['id']]))
                                {
                                    continue;
                                }

                                $translated_value = $lang_data['input_'.$field_id.'_'.$source_lang_id];
                                
                                // skip numbers
                                if(sw_settings('skip_numbers_copy') == '1' && is_numeric($translated_value))
                                    continue;
                                
                                // auto translate if enabled
                                if(sw_settings('auto_translate') == '1')
                                    $translated_value = sw_translate($lang_data['input_'.$field_id.'_'.$source_lang_id], $lang_code, $lang_1['lang_code']);
                                
                                $insert_arr['lang_id'] = $lang_1['id'];
                                if($value != 'SKIP_ON_EMPTY')
                                {
                                     if(!is_numeric($insert_arr['value']))
                                     {
                                        $insert_arr['value'] = $translated_value;
                                     }
                                    
                                    $insert_batch[] = $insert_arr;
                                }
                                    
                                $data_listing_lang[$lang_1['id']]['lang_id']=$lang_1['id'];
                                $data_listing_lang[$lang_1['id']]['listing_id']=$id;
                                
                                if(isset($data_listing_lang[$lang_id]['field_'.$field_id]))
                                {
                                    $data_listing_lang[$lang_1['id']]['field_'.$field_id] = $translated_value;
                                }
                                
                                if(isset($data_listing_lang[$lang_id]['json_object']['field_'.$field_id]))
                                {
                                    $data_listing_lang[$lang_1['id']]['json_object']['field_'.$field_id] = sw_win_prepare_json($translated_value);
                                }
                                
                                if(isset($data_listing_lang[$lang_id]['field_'.$field_id.'_int']))
                                {
                                    $data_listing_lang[$lang_1['id']]['field_'.$field_id.'_int'] = $data_listing_lang[$lang_id]['field_'.$field_id.'_int'];
                                }
                            }
                        }
                    }
                /* [/non-translatable-fields] */
                }
            }
        }
        if(sw_count($insert_batch) > 0)
            $this->db->insert_batch('sw_listing_field', $insert_batch);
            
        $dbe = $this->db->error();
        $dbe = $dbe['message'];
        
        if($dbe != '')
        {
            echo 'QUERY: '.$this->db->last_query();
            echo '<br />';
            echo 'ERROR: '.$dbe;
            exit();
        }

        // Delete all users
//        if(!empty($data['agent']))
//        {
//            $this->db->where('property_id', $id);
//            $this->db->delete('property_user'); 
//            $this->db->set(array('property_id'=>$id,
//                                 'user_id'=>$data['agent']));
//            $this->db->insert('property_user');
//        }
        
        
        /* [property_lang] */
        foreach($data_listing_lang as $lang_id =>$property_data)
        {
            foreach($fields as $key_field=>$val_field)
            {
                if(!isset($data_listing_lang[$lang_id][$key_field]))
                    $data_listing_lang[$lang_id][$key_field] = NULL;
            }
            
            $data_listing_lang[$lang_id]['json_object'] = 
                json_encode($data_listing_lang[$lang_id]['json_object'], JSON_UNESCAPED_UNICODE);
                

        }
        
        if(sw_count($data_listing_lang) > 0)
        {
            $this->db->delete('sw_listing_lang', array('listing_id' => $id)); 
            
            $this->db->insert_batch('sw_listing_lang', $data_listing_lang); 
            
//            dump($data_sw_listing_lang);
//            exit();
        }
        
        // [/Save languages data]
        
        // if cache is enabled delete all db caches
        $this->db->cache_delete_all();
        
        return $id;
    }
    
    public function get_form_dropdown($column = 'field_10', $where = FALSE, $show_empty=TRUE, $lang_id=1)
    {
        $filter = $this->_primary_filter;
        
        if(!empty($this->_order_by))
        {
            $this->db->order_by($this->_order_by);
        }
        
        if($where)
            $this->db->where($where); 
        
        $this->db->join($this->_table_name.'_lang', $this->_table_name.'.'.$this->_primary_key.'= '.$this->_table_name.'_lang.listing_id');
        $this->db->where('lang_id', $lang_id);
        
        $dbdata = $this->db->get($this->_table_name)->result_array();
        
        $results = array();
        if($show_empty)
            $results[''] = '';
            
        foreach($dbdata as $key=>$row){
            if(isset($row[$column]))
            $results[$row[$this->_primary_key]] = $row['idlisting'].', '.$row[$column];
        }
        return $results;
    }

    public function check_user_permission($object_id, $user_id)
    {
        if(sw_is_user_in_role(get_userdata( $user_id ),'administrator') )
        {
            return TRUE;
        }

        return $this->is_related($object_id, $user_id);
    }
    
    public function is_related($object_id, $user_id, $method = 'edit')
    {
        $this->db->from('sw_listing_agent'); 
        $this->db->where('listing_id', $object_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return true;
        
        return false;
    }

    public function get_user_listings($user_id)
    {
        $this->db->from('sw_listing_agent'); 
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() == 0)
            return NULL;

        return $query->result();
    }

    public function update_user_listings($user_id)
    {
        $user_listings = $this->get_user_listings($user_id);

        $listings_in = array();
        foreach($user_listings as $listing)
        {
            $listings_in[] = $listing->listing_id;
        }

        $data = array(
            'date_modified' => date('Y-m-d H:i:s')
        );

        $this->db->where_in('idlisting', $listings_in);
        $this->db->update($this->_table_name, $data);
    }
    
    public function update_counter($listing)
    {
        if($listing->counter_views === NULL)
        {
            $this->db->set('counter_views', 1, FALSE);
            $this->db->where('idlisting', $listing->idlisting);
            $this->db->update($this->_table_name); 
        }
        else
        {
            // $this->db->set('counter_views', 'counter_views+'.rand(1,13), FALSE); // Fake views version
            
            $this->db->set('counter_views', 'counter_views+1', FALSE);
            $this->db->where('idlisting', $listing->idlisting);
            $this->db->update($this->_table_name); 
        }
    }

    public function delete($id)
    {
        //remove all values from current
        $this->load->model('repository_m');
        
        $object = $this->get($id);
        if($object && isset($object->repository_id) && !empty($object->repository_id))
            $this->repository_m->delete($object->repository_id);
        
        $this->db->delete('sw_listing_field', array('listing_id' => $id));
        $this->db->delete('sw_listing_lang', array('listing_id' => $id));
        $this->db->delete('sw_listing_agent', array('listing_id' => $id));
        $this->db->delete('sw_slug', array('table' => 'listing', 'table_id' => $id)); 
        
        parent::delete($id);
    }

}







