<?php

class Dependentfield_m extends MY_Model {
    
    protected $_table_name = 'sw_dependentfields';
    protected $_primary_key = 'iddependentfields';
    protected $_order_by = 'sw_dependentfields.iddependentfields';
    
    public $rules = array(
        'field_id' => array('field'=>'field_id', 'label'=>'lang:Dependent field', 'rules'=>'trim|required|xss_clean'),
        'treefield_id' => array('field'=>'selected_index', 'label'=>'lang:Selected index', 'rules'=>'trim|xss_clean'),
        'hidden_fields_list' => array('field'=>'visible', 'label'=>'lang:Hidden fields under selected', 'rules'=>'trim|xss_clean')
    );
    

	public function __construct(){
		parent::__construct();
	}

    public function get_new()
	{
        $item = new stdClass();
        $item->field_id = '';
        $item->selected_index = '';
        $item->hidden_fields_list = '';
        
        return $item;
	}
    
    public function get_fields_under($lang_id, $order=-1)
    {
        $this->db->select('*');
        $this->db->from('sw_field');
        $this->db->join('sw_field_lang', 'sw_field.idfield = sw_field_lang.field_id');
        $this->db->where('lang_id', $lang_id); 
        if(is_numeric($order))
            $this->db->where('order >', $order); 
        $this->db->where('is_submission_visible', 1); 
        $this->db->order_by('order'); 
        $query = $this->db->get();
        
        $results_array = array();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $results_array[$row->idfield] = $row;
            }
        } 
        
        return $results_array;
    }
    
    public function get_hidden_fields($category_id)
    {
        $arr = array();

        if(!function_exists('sw_pluginsLoaded_dependentfields'))
            return $arr;

        $field = $this->get_by(array('field_id'=>1, 'treefield_id'=>$category_id), true);
        
        if(isset($field->hidden_fields_list))
        {
            $arr = explode(',', $field->hidden_fields_list);
            $arr = array_flip($arr);
        }
        
        return $arr;
    }

    public function delete($id)
    {
        if($this->session->userdata('type') == 'ADMIN')
            parent::delete($id);
    }
    
}



