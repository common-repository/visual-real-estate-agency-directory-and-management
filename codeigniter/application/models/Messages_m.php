<?php

class Messages_m extends My_Model {
	public $_table_name = 'sw_messages';
	public $_order_by = 'sw_messages.idmessages DESC';
    public $_primary_key = 'idmessages';
    public $_own_columns = array();

    public $form_widget = array();
    
    public $form_admin = array();
    
    public $rules_lang = array();
    
	public function __construct(){
		parent::__construct();
        
        $this->form_widget = array(
            //'user_id_sender' => array('field'=>'user_id_sender', 'label'=>__('user_id_sender', 'sw_win'), 'rules'=>'trim|required'),
            'user_id_receiver' => array('field'=>'user_id_receiver', 'label'=>__('user_id_receiver', 'sw_win'), 'rules'=>'trim|required'),
            'email_receiver' => array('field'=>'email_receiver', 'label'=>__('email_receiver', 'sw_win'), 'rules'=>'trim|required'),
            'email_sender' => array('field'=>'email_sender', 'label'=>__('email_sender', 'sw_win'), 'rules'=>'trim|required'),
            'message' => array('field'=>'message', 'label'=>__('message', 'sw_win'), 'rules'=>'trim|required'),
        );
        
        $this->form_expansion = array(
            'related_key' => array('field'=>'related_key', 'label'=>__('related_key', 'sw_win'), 'rules'=>'trim|required'),
             //'user_id_sender' => array('field'=>'user_id_sender', 'label'=>__('user_id_sender', 'sw_win'), 'rules'=>'trim|required'),
            'user_id_receiver' => array('field'=>'user_id_receiver', 'label'=>__('user_id_receiver', 'sw_win'), 'rules'=>'trim|required'),
            'email_receiver' => array('field'=>'email_receiver', 'label'=>__('email_receiver', 'sw_win'), 'rules'=>'trim|required'),
            'email_sender' => array('field'=>'email_sender', 'label'=>__('email_sender', 'sw_win'), 'rules'=>'trim|required'),
            'message' => array('field'=>'message', 'label'=>__('message', 'sw_win'), 'rules'=>'trim|required'),
        );
        
        $this->form_admin = array(
            'is_readed' => array('field'=>'is_readed', 'label'=>__('Read by receiver', 'sw_win'), 'rules'=>'trim')
        );
        
	}
        
    public function get_related ($related_key = NULL, $where = array(), $single = FALSE) {
        if($related_key !== NULL)
            $where['related_key'] = trim($related_key);
        
        return parent::get_by($where, $single);
    }
    
    public function generete_list_messages(&$messages) {
        $this->load->model('user_m');
        
        $users_tmp = array();
        foreach ($messages as $key => $value) {
            $messages[$key]->{'profile_image'} = '#';
            $messages[$key]->{'profile_url'} = '#';
            $messages[$key]->{'display_name'} = __('Not registered', 'sw_win');

            if(!empty($value->user_id_sender) && isset($users_tmp[$value->user_id_sender])){
                $messages[$key]->{'profile_image'} = $users_tmp[$value->user_id_sender]['profile_image'];
                $messages[$key]->{'display_name'} = $users_tmp[$value->user_id_sender]['display_name'];
                $messages[$key]->{'profile_url'} = $users_tmp[$value->user_id_sender]['profile_url'];
            }elseif(!empty($value->user_id_sender)) {
                $user = $this->user_m->get($value->user_id_sender);
                if(!$user) continue;
                $user_tmp = array();
                $user_tmp['display_name'] = $user->display_name;
                $user_tmp['profile_image'] = sw_profile_image($user, 120);
                $user_tmp['profile_url'] = agent_url($user);
                $messages[$key]->{'profile_image'} = $user_tmp['profile_image'];
                $messages[$key]->{'display_name'} = $user_tmp['display_name'];
                $messages[$key]->{'profile_url'} = $user_tmp['profile_url'];

                $users_tmp[$value->user_id_sender]=$user_tmp;
            }
        }
    }
    
    public function save($data = array(), $id=NULL)
    {
        $data['date_sent'] = date('Y-m-d H:i:s', time());
        return parent::save($data, $id);
    }
    
    
    public function read_related($related_key = NULL, $where = array())
    {
        if($related_key === NULL)
            return false;
        
        $this->load->model('user_m');
        $user_id= get_current_user_id();
        if($user_id){
            $this->db->set('is_readed', 1);
            if(!empty($where))
                $this->db->where($where);
            
            $this->db->where(array('related_key'=> $related_key));
            $this->db->where(array('user_id_receiver'=> $user_id));
            $this->db->update($this->_table_name);
            return true;
        }
        
        return false;
    }
    
}