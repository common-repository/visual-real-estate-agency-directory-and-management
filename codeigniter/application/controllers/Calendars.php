<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendars extends My_Controller {

	public function __construct(){
		parent::__construct();

        $this->load->model('calendar_m');
        $this->load->model('rates_m');
        $this->load->model('reservation_m');
	}
    
    
	public function index()
	{
	   
        $query = $this->db->get('wp_options');
        foreach ($query->result() as $row)
        {
            dump($row);
        }
       
		$this->load->view('welcome_message');
	}
    
	public function manage()
	{
        // Fetch all results
        $this->data['results'] = array();
       
        // Load view
		$this->data['subview'] = 'admin/calendars/manage';
        $this->load->view('admin/_layout_main', $this->data);
	}

	public function addcalendar($id=NULL)
	{
        // Get parameters
        $id = $this->input->get('id');

        // Set up the form
        if(empty($id))
        {
        }
        else
        {
            $this->data['form_object'] = $this->calendar_m->get($id);
        }

        $rules = $this->calendar_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->calendar_m->array_from_post($this->calendar_m->get_post_from_rules($rules));

            if(empty($data['user_id']))
                $data['user_id'] = get_current_user_id();

            $id = $this->calendar_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=calendars_manage&function=addcalendar&id=$id&updated=true")); exit;
        }

        // Load view
		$this->data['subview'] = 'admin/calendars/addcalendar';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remcalendar($id = NULL, $redirect='1')
	{   
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->calendar_m->delete($id);
	}
    
    // json for datatables
    public function datatable()
    {
        
        
        // configuration
        $columns = array('idcalendar', 'sw_calendar.listing_id', 'calendar_title', 'calendar_type', 'sw_calendar.is_activated', 'field_10' );
        $controller = 'calendar';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m', array('field_10'));
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m', array('field_10'));
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
        $query = $this->db->last_query();
        
        $values_calendar = array(''=>'', 'DAY'=>__('Daily', 'sw_win'), 'HOUR'=>__('Hourly', 'sw_win'));

        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->edit = btn_edit(admin_url("admin.php?page=".$controller."s_manage&function=add".$controller."&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=".$controller."s_manage&function=rem".$controller."&id=".$row->{"id$controller"}));
            
            foreach($columns as $val)
            {
                if(isset($row->$val))
                {
                    if($val == 'calendar_type')
                    {
                        if(isset($values_calendar[$row->$val]))
                        {
                            $row->$val = $values_calendar[$row->$val];

                        }
                    }

                    if($val == 'idcalendar')
                    {
                        if($row->is_activated == 1)
                        {
                           $row->$val .= '&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                        }
                    }

                }
                elseif(isset($row->json_object))
                {
                    $json = json_decode($row->json_object);
                    if(isset($json->$val))
                    {
                        $row->$val = $json->$val;
                    }
                    else
                    {
                        $row->$val = '-';
                    }
                }
                else
                {
                    $row->$val = '-';
                }
            }
            
            if($row->is_activated==0)
                $row->{"id$controller"} .= ' <span class="label label-danger">'.__("Not activated", "sw_win").'</span>';

           if(isset($row->listing_id))
           {
                $row->listing_id = $row->listing_id.', '._field($row, 10);
           }

        }

        //format array is optional
        $json = array(
                "parameters" => $parameters,
                "query" => $query,
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
                );

        //$length = strlen(json_encode($data));
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length);
        echo json_encode($json);
        
        exit();
    }
    
	public function rates()
	{
        // Fetch all results
        $this->data['results'] = array();
       
        // Load view
		$this->data['subview'] = 'admin/calendars/rates';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function addrate($id=NULL)
	{
        // Get parameters
        $id = $this->input->get('id');

        // Set up the form
        if(empty($id))
        {
        }
        else
        {
            $this->data['form_object'] = $this->rates_m->get($id);
        }

        $rules = $this->rates_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->rates_m->array_from_post($this->rates_m->get_post_from_rules($rules));

            if(empty($id) && empty($data['calendar_id']))
            {
                $calendar = $this->calendar_m->get_by(array('sw_calendar.listing_id'=>$data['listing_id']), true);

                $data['calendar_id'] = $calendar->idcalendar;
            }

            if(!empty($data['date_from']))
            {
                $data['date_from'] = date('Y-m-d H:i:s', strtotime($data['date_from']));
            }

            if(!empty($data['date_to']))
            {
                $data['date_to'] = date('Y-m-d H:i:s', strtotime($data['date_to']));
            }

            $id = $this->rates_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=calendars_rates&function=addrate&id=$id&updated=true")); exit;
        }

        // Load view
		$this->data['subview'] = 'admin/calendars/addrate';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remrate($id = NULL, $redirect='1')
	{   
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->rates_m->delete($id);
	}
    
    // json for datatables
    public function datatablerates()
    {
        // configuration
        $columns = array('idrates', 'sw_rates.listing_id', 'date_from', 'date_to', 'field_10' );
        $controller = 'rates';
        
        $this->load->model($controller.'_m');
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m', array('field_10'));
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m', array('field_10'));
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->edit = btn_edit(admin_url("admin.php?page=calendars_rates&function=addrate&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=calendars_rates&function=remrate&id=".$row->{"id$controller"}));
            
            foreach($columns as $val)
            {
                if(isset($row->$val))
                {
                    // Customize is_readed value preview, add title
                    if($val == 'is_activated')
                    {
                        if($row->is_activated == 1)
                        {
                           $row->$val = '&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                        }
                    }
                }
                elseif(isset($row->json_object))
                {
                    $json = json_decode($row->json_object);
                    if(isset($json->$val))
                    {
                        $row->$val = $json->$val;
                    }
                    else
                    {
                        $row->$val = '-';
                    }
                }
                else
                {
                    $row->$val = '-';
                }
            }

            if(isset($row->listing_id))
            {
                 $row->listing_id = $row->listing_id.', '._field($row, 10);
            }

        }

        //format array is optional
        $json = array(
                "parameters" => $parameters,
                "query" => $query,
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
                );

        //$length = strlen(json_encode($data));
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length);
        echo json_encode($json);
        
        exit();
    }

	public function reservations()
	{
        // Fetch all results
        $this->data['results'] = array();
       
        // Load view
		$this->data['subview'] = 'admin/calendars/reservations';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function addreservation($id=NULL)
	{
        // Get parameters
        $id = $this->input->get('id');

        // Set up the form
        if(empty($id))
        {
        }
        else
        {
            $this->data['form_object'] = $this->reservation_m->get($id);
        }

        $rules = $this->reservation_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->reservation_m->array_from_post($this->reservation_m->get_post_from_rules($rules));

            if(empty($id) && empty($data['calendar_id']))
            {
                $calendar = $this->calendar_m->get_by(array('sw_calendar.listing_id'=>$data['listing_id']), true);

                $data['calendar_id'] = $calendar->idcalendar;
            }

            if(!empty($data['date_from']))
            {
                $data['date_from'] = date('Y-m-d H:i:s', strtotime($data['date_from']));
            }

            if(!empty($data['date_to']))
            {
                $data['date_to'] = date('Y-m-d H:i:s', strtotime($data['date_to']));
            }

            // send email

            $this->load->model('listing_m');
            $this->load->model('user_m');

            if(!empty($id) && isset($data['is_confirmed']) && $data['is_confirmed'] == 1 && 
                $this->data['form_object']->is_confirmed == 0)
            {
                // Inform client about availability confirmation
                $client = $this->user_m->get($data['user_id']);
                $me = $this->user_m->get(get_current_user_id());

                $data_msg['email_receiver']=$client->user_email;
                $data_msg['email_sender']=$me->user_email;
                
                $data_msg['subject'] = __('Availability confirmation', 'sw_win');
                $message_mail =__('Your reservation availability is now confirmed', 'sw_win').'<br />';
                $message_mail.=__('Please pay by instructions in dashboard and confirm payment', 'sw_win').'<br />';
                $href = menu_page_url( 'owncalendars_myreservations', false).'&function=viewmyreservation&id='.$id;
                $message_mail .= '<br/><a href="'.esc_url($href).'">'.__('Open reservation in dashboard', 'sw_win').'</a>'.'<br /><br />';

                if(!empty($data['listing_id']))
                {
                    $data_msg['listing_id'] = $data['listing_id'];
                }

                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.$data_msg['email_sender'];

                $ret1 = wp_mail( $data_msg['email_receiver'], $data_msg['subject'], $message_mail, $headers );

                if($ret1 === false)
                {
                    echo __('Error sending email', 'sw_win');
                    exit();
                }
                
            }

            if(!empty($id) && isset($data['is_payment_completed']) && $data['is_payment_completed'] == 1 &&
                $this->data['form_object']->is_payment_completed == 0)
            {
                // Inform client about final confirmation

                $client = $this->user_m->get($data['user_id']);
                $me = $this->user_m->get(get_current_user_id());

                $data_msg['email_receiver']=$client->user_email;
                $data_msg['email_sender']=$me->user_email;
                $data_msg['subject'] = __('Reservation confirmation', 'sw_win');
                $message_mail =__('Your reservation payment is now confirmed', 'sw_win').'<br />';
                $href = menu_page_url( 'owncalendars_myreservations', false).'&function=viewmyreservation&id='.$id;
                $message_mail .= '<br/><a href="'.esc_url($href).'">'.__('Open reservation in dashboard', 'sw_win').'</a>'.'<br /><br />';

                if(!empty($data['listing_id']))
                {
                    $data_msg['listing_id'] = $data['listing_id'];
                }

                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.$data_msg['email_sender'];

                $ret2 = wp_mail( $data_msg['email_receiver'], $data_msg['subject'], $message_mail, $headers );

                if($ret2 === false)
                {
                    echo __('Error sending email', 'sw_win');
                    exit();
                }
            }

            // end send email

            $id = $this->reservation_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=calendars_reservations&function=addreservation&id=$id&updated=true")); exit;
        }

        // Load view
		$this->data['subview'] = 'admin/calendars/addreservation';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remreservation($id = NULL, $redirect='1')
	{   
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->reservation_m->delete($id);
	}
    
    // json for datatables
    public function datatablereservation()
    {
        // configuration
        $columns = array('idreservation', 'display_name', 'sw_reservation.listing_id', 'date_from', 'date_to', 'field_10', 'is_confirmed' );
        $controller = 'reservation';
        
        $this->load->model($controller.'_m');
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m', array('field_10', 'display_name'));
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m', array('field_10', 'display_name'));
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->edit = btn_edit(admin_url("admin.php?page=calendars_reservations&function=addreservation&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=calendars_reservations&function=remreservation&id=".$row->{"id$controller"}));
            
            foreach($columns as $val)
            {
                if(isset($row->$val))
                {
                    // Customize is_readed value preview, add title
                    if($val == 'is_activated')
                    {
                        if($row->is_activated == 1)
                        {
                           $row->$val = '&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                        }
                    }
                }
                elseif(isset($row->json_object))
                {
                    $json = json_decode($row->json_object);
                    if(isset($json->$val))
                    {
                        $row->$val = $json->$val;
                    }
                    else
                    {
                        $row->$val = '-';
                    }
                }
                else
                {
                    $row->$val = '-';
                }
            }

            if(isset($row->listing_id))
            {
                 $row->listing_id = $row->listing_id.', '._field($row, 10);
            }

            if($row->is_confirmed==0)
                $row->{"id$controller"} .= ' <span class="label label-danger">'.__("Not confirmed", "sw_win").'</span>';
            
        }

        //format array is optional
        $json = array(
                "parameters" => $parameters,
                "query" => $query,
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
                );

        //$length = strlen(json_encode($data));
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length);
        echo json_encode($json);
        
        exit();
    }

	public function check_rank($str)
	{
        $rank = $this->input->post_get('rank');
        $package_days = $this->input->post_get('package_days');
        $package_price = $this->input->post_get('package_price');

        if( ($package_days == 0 || $rank == 0) && $package_price > 0)
        {
            $this->form_validation->set_message('check_rank', __('Rank 0 or expire 0 days can\'t have price > 0, because will expire immediately', 'sw_win'));
            return FALSE;
        }
        
        return TRUE;
	}
    
}
