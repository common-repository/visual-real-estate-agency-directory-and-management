<?php

class Slug_m extends My_Model {
	public $_table_name = 'sw_slug';
	public $_order_by = 'sw_slug.idslug DESC';
    public $_primary_key = 'idslug';
    public $_own_columns = array();
    
	public function __construct(){
		parent::__construct();
	}
    
    public function save_slug($table, $table_id, $lang_id, $slug)
    {
        // remove slug from this related id, table, lang
        $this->db->delete($this->_table_name, array('table'=>$table,
                                                    'table_id'=>$table_id,
                                                    'lang_id'=>$lang_id));
                                                
        // generate slug
        if(empty($slug))$slug=$table_id.sw_get_languages($lang_id);
        $slug = sw_generate_slug($slug);
        
        // check if slug already exists and auto modify
        $this->check_slug($slug);
        
        if(!empty($slug))
        {
            $data = array();
            $data['table'] = $table;
            $data['table_id'] = $table_id;
            $data['lang_id'] = $lang_id;
            $data['slug'] = $slug;
            $data['lang_code'] = sw_get_languages($lang_id);
            $this->save($data, NULL);
        }

        //return saved slug
        return $slug;
    }
    
    private function check_slug(&$slug)
    {        
        if(!empty($slug))
        {
            // check exact
            $this->db->select('*');
            $this->db->from($this->_table_name);
            $this->db->where('slug', $slug);
            $query = $this->db->get();
            $num = $query->num_rows();
            if($num == 0)return;
            
            
            $this->db->select('*');
            $this->db->from($this->_table_name);
            $this->db->like('slug', $slug, 'after');
            
            $query = $this->db->get();
            $num = $query->num_rows();

            if($num > 0)
            {
                $slug.=$num;
                $this->check_slug($slug);
            }
        }
        
    }
    
    public function getid($slug)
    {
            $slug = $this->get_by(array('slug'=>$slug));
            
            if(isset($slug[0]))
                return $slug[0]->table_id;
                
            return NULL;
    }

}