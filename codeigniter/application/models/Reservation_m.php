<?php

class Reservation_m extends My_Model {
	public $_table_name = 'sw_reservation';
	public $_order_by = 'idreservation DESC';
    public $_primary_key = 'idreservation';
    public $_own_columns = array('sw_listing_agent.user_id', 'sw_calendar.user_id');
    public $_timestamps = TRUE;

    public $form_admin = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'user_id' => array('field'=>'user_id', 'label'=>__('User', 'sw_win'), 'design'=>'dropdown_user', 'rules'=>'trim'),
            'listing_id' => array('field'=>'listing_id', 'label'=>__('Listing', 'sw_win'), 'design'=>'dropdown_listing', 'rules'=>'trim|callback__calendar_exists|required'),
            'date_from' => array('field'=>'date_from', 'label'=>__('Date from', 'sw_win'), 'design'=>'datetimepicker', 'rules'=>'trim|required'),
            'date_to' => array('field'=>'date_to', 'label'=>__('Date to', 'sw_win'), 'design'=>'datetimepicker', 'rules'=>'trim|callback__check_available|required'),
            'guests_number' => array('field'=>'guests_number', 'label'=>__('Guests number', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'total_price' => array('field'=>'total_price', 'label'=>__('Total price', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'date_paid_advance' => array('field'=>'date_paid_advance', 'label'=>__('Payment date for Advance', 'sw_win'), 'design'=>'datetimepicker', 'rules'=>'trim'),
            'date_paid_total' => array('field'=>'date_paid_total', 'label'=>__('Payment date for Total', 'sw_win'), 'design'=>'datetimepicker', 'rules'=>'trim'),
            'total_paid' => array('field'=>'total_paid', 'label'=>__('Total paid (money received)', 'sw_win'), 'design'=>'input', 'rules'=>'trim'),
            'currency_code' => array('field'=>'currency_code', 'value'=>sw_settings('default_currency'), 'label'=>__('Currency', 'sw_win'), 'design'=>'input_readonly', 'rules'=>'trim|required'),
            'is_confirmed' => array('field'=>'is_confirmed', 'label'=>__('Confirm availability (Owner/agent must confirm before payment)', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'is_payment_informed' => array('field'=>'is_payment_informed', 'label'=>__('Is Payment informed (by client)', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
            'is_payment_completed' => array('field'=>'is_payment_completed', 'label'=>__('Is Payment completed (checked by owner/agent)', 'sw_win'), 'design'=>'checkbox', 'rules'=>'trim'),
        );
	}

    /* [START] For dinamic data table */
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields = $this->db->list_fields($this->_table_name);
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE)
    {
        $this->db->select('sw_reservation.*, sw_listing.address, sw_listing_lang.*, sw_reservation.listing_id as listing_id, '.$this->users_table.'.display_name');
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->join($this->users_table, 'sw_reservation.user_id = '.$this->users_table.'.ID', 'left');
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
        $this->db->select('sw_reservation.*, sw_listing.address, sw_listing_lang.*, sw_reservation.listing_id as listing_id, '.$this->users_table.'.display_name');
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->join($this->users_table, 'sw_reservation.user_id = '.$this->users_table.'.ID', 'left');
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
        
        $this->db->select('sw_reservation.*, sw_calendar.payment_details, sw_listing.address, sw_listing_lang.*, sw_reservation.listing_id as listing_id, '.$this->users_table.'.display_name');
        //$this->db->from($this->_table_name);
        $this->db->join('sw_listing', $this->_table_name.'.listing_id = sw_listing.idlisting', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->join($this->users_table, 'sw_reservation.user_id = '.$this->users_table.'.ID', 'left');
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
    
    public function check_deletable($id)
    {
        if(sw_user_in_role('administrator')) return true;
        
        $favorite = $this->get($id);
        if(isset($favorite->user_id) && $favorite->user_id == get_current_user_id())
            return true;
            
        return false;
    }
    
    
    /* [END] For dinamic data table */

    public function is_defined($listing_id, $date_from, $date_to, $except_id = NULL)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('listing_id', $listing_id);
        $this->db->where('is_confirmed', '1');
        
        if(is_numeric($except_id))
        {
            $this->db->where('idreservation !=', $except_id);
        }
        
        // Check dates availability
        $this->db->where('date_from <', $date_to);
        $this->db->where('date_to >', $date_from);
        
        $query = $this->db->get();
        $results = $query->result();
        
        return $results;
    }

    public function is_related($object_id, $user_id, $method = 'edit')
    {
        $this->db->select('*');
        $this->db->from($this->_table_name); 
        $this->db->join('sw_calendar', $this->_table_name.'.calendar_id = sw_calendar.idcalendar', 'left');
        $this->db->join('sw_listing_agent', $this->_table_name.'.listing_id = sw_listing_agent.listing_id', 'left');
        $this->db->where($this->_table_name.'.idreservation', $object_id);
        $this->db->where('sw_listing_agent.user_id', $user_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return true;
        
        return false;
    }

    public function get_enabled_dates($listing_id)
    {
        $dates_available = array();

        $this->db->select('*');
        $this->db->from('sw_rates');
        $this->db->where('listing_id', $listing_id);
        $this->db->where('date_to >', date('Y-m-d H:i:s'));
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $results = $query->result();

            foreach($results as $row)
            {
                $period = new DatePeriod(new DateTime($row->date_from), new DateInterval('P1D'), new DateTime($row->date_to));
                foreach ($period as $date) {
                    $dates_available[$date->format("Y-m-d")] = '\''.$date->format("Y-m-d").'\'';
                }
            }
        }

        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('listing_id', $listing_id);
        $this->db->where('date_to >', date('Y-m-d H:i:s'));
        $this->db->where('is_confirmed', 1);
        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $results = $query->result();

            foreach($results as $row)
            {
                $period = new DatePeriod(new DateTime($row->date_from), new DateInterval('P1D'), new DateTime($row->date_to));

                if(date("Y-m-d", strtotime($row->date_from)) != date("Y-m-d", strtotime($row->date_to))) // if same date, maybe few hours are available
                foreach ($period as $date) {
                    unset($dates_available[$date->format("Y-m-d")]);
                }
            }

        }

        return $dates_available;
    }

    public function calculate_price($listing_id, $date_from, $date_to)
    {
        $hours = (strtotime($date_to)-strtotime($date_from)) / 3600;
        $price = NULL;

        $this->db->select('*');
        $this->db->from('sw_rates');
        $this->db->where('listing_id', $listing_id);
        $this->db->where('date_from <=', $date_from);
        $this->db->where('date_to >=', $date_to);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
        {
            $results = $query->result();

            foreach($results as $row)
            {
                if($hours<24) // By hour
                {
                    if(!empty($row->rate_hour))
                    {
                        $price=$hours*$row->rate_hour;
                    }
                    elseif(!empty($row->rate_night))
                    {
                        $price=$row->rate_night;
                    }
                    elseif(!empty($row->rate_week))
                    {
                        $price=$row->rate_week;
                    }
                    elseif(!empty($row->rate_month))
                    {
                        $price=$row->rate_month;
                    }
                }
                elseif($hours<24*7) // By day
                {
                    if(!empty($row->rate_night))
                    {
                        $price=($hours/24)*$row->rate_night;
                    }
                    elseif(!empty($row->rate_week))
                    {
                        $price=$row->rate_week;
                    }
                    elseif(!empty($row->rate_month))
                    {
                        $price=$row->rate_month;
                    }
                    elseif(!empty($row->rate_hour))
                    {
                        $price=$hours*$row->rate_hour;
                    }
                }
                elseif($hours<24*7*30) // By week
                {
                    if(!empty($row->rate_week))
                    {
                        $price=($hours/168)*$row->rate_week;
                    }
                    elseif(!empty($row->rate_month))
                    {
                        $price=$row->rate_month;
                    }
                    elseif(!empty($row->rate_night))
                    {
                        $price=($hours/24)*$row->rate_night;
                    }
                    elseif(!empty($row->rate_hour))
                    {
                        $price=$hours*$row->rate_hour;
                    }
                }
                else // By month
                {
                    if(!empty($row->rate_month))
                    {
                        $price=($hours/720)*$row->rate_month;
                    }
                    elseif(!empty($row->rate_week))
                    {
                        $price=($hours/168)*$row->rate_week;
                    }
                    elseif(!empty($row->rate_night))
                    {
                        $price=($hours/24)*$row->rate_night;
                    }
                    elseif(!empty($row->rate_hour))
                    {
                        $price=$hours*$row->rate_hour;
                    }
                }
            }
        }

        return $price;
    }

}