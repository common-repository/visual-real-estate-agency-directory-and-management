<?php

class Myreservation_m extends Reservation_m {
	public $_table_name = 'sw_reservation';
	public $_order_by = 'idreservation DESC';
    public $_primary_key = 'idreservation';
    public $_own_columns = array('sw_reservation.user_id');
    public $_timestamps = TRUE;

    public $form_admin = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'user_id' => array('field'=>'user_id', 'label'=>__('User', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'listing_id' => array('field'=>'listing_id', 'label'=>__('Listing', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim|callback__calendar_exists|required'),
            'date_from' => array('field'=>'date_from', 'label'=>__('Date from', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim|required'),
            'date_to' => array('field'=>'date_to', 'label'=>__('Date to', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim|callback__check_available|required'),
            'total_price' => array('field'=>'total_price', 'label'=>__('Total price', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'date_paid_advance' => array('field'=>'date_paid_advance', 'label'=>__('Payment date for Advance', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'date_paid_total' => array('field'=>'date_paid_total', 'label'=>__('Payment date for Total', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'total_paid' => array('field'=>'total_paid', 'label'=>__('Total paid (money received)', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'currency_code' => array('field'=>'currency_code', 'value'=>sw_settings('default_currency'), 'label'=>__('Currency', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim|required'),
            'is_confirmed' => array('field'=>'is_confirmed', 'label'=>__('Confirm availability (Owner/agent must confirm before payment)', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'is_payment_informed' => array('field'=>'is_payment_informed', 'label'=>__('Is Payment informed (by client)', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
            'is_payment_completed' => array('field'=>'is_payment_completed', 'label'=>__('Is Payment completed (checked by owner/agent)', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim'),
        );
	}

    public function is_related($object_id, $user_id, $method = 'edit')
    {
        $this->db->select('*');
        $this->db->from($this->_table_name); 
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return true;
        
        return false;
    }

}