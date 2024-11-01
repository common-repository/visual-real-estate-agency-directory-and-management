<?php

class Searchform_m extends My_Model {
    
	public $_table_name = 'sw_search_form';
	public $_order_by = 'sw_search_form.idsearch_form DESC';
    public $_primary_key = 'idsearch_form';
    public $_own_columns = array();
    public $_timestamps = FALSE;

    public $form_admin = array();

    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'form_name' => array('field'=>'form_name', 'label'=>__('Form name', 'sw_win'), 'rules'=>'trim|required'),
            'type' => array('field'=>'type', 'label'=>__('Type', 'sw_win'), 'rules'=>'trim|required'),
            'fields_order' => array('field'=>'fields_order', 'label'=>__('Fields order', 'sw_win'), 'rules'=>'trim|stripslashes|required')
        );
	}

    public function delete($id)
    {
        parent::delete($id);
    }

}