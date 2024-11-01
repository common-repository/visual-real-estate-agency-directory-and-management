<?php

class Token_m extends MY_Model {
    
    protected $_table_name = 'sw_tokenapi';
    protected $_order_by = 'idtokenapi';
    
    public function get_token($POST)
    {
        $this->db->select('user_id');
        $this->db->from($this->_table_name);
        $this->db->where('token', $POST['token']);
        $query = $this->db->get();
        
        if (is_object($query))
        {
            $row = $query->row();
            return $row;
        }
        
        return false;
    }
    
}



