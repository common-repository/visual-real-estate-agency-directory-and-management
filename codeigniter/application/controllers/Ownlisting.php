<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ownlisting extends My_Controller {

	public function __construct(){
		parent::__construct();
        
        $this->load->model('file_m');
        $this->load->model('field_m');
        $this->load->model('repository_m');
        $this->load->model('listing_m');
        $this->load->model('treefield_m');
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
		$this->data['subview'] = 'agent/listing/manage';
        $this->load->view('agent/_layout_main', $this->data);
	}
    
	public function addlisting($id=NULL)
	{
        $this->data['agents'] = array();
        $this->data['locations'] = array();
        $this->data['categories'] = array();
        
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('listing_m', $id, 'edit');
        
        $is_new = ($id === NULL);
        
        $user_id = get_current_user_id();
        
        // Set up the form
        if(empty($id))
        {
            if(!isset($_POST['repository_id']))
            {
                // Create new repository
                $repository_id = $this->repository_m->save(array('model_name'=>'listing_m'));
                $_POST['repository_id'] = $repository_id;
            }
            
            $this->data['repository_id'] = $_POST['repository_id'];
        }
        else
        {
            $this->data['form_object'] = $this->listing_m->get_lang($id, sw_default_language_id());
            $this->data['repository_id'] = $this->data['form_object']->repository_id;
            
            // TODO: Remove, just for test purposes
            if(empty($this->data['repository_id']))
            {
                // Create new repository
                $repository_id = $this->repository_m->save(array('model_name'=>'listing_m'));
                $_POST['repository_id'] = $repository_id;
                $this->data['repository_id'] = $_POST['repository_id'];
            }
            
            $this->data['agents'] = $this->listing_m->get_agents_dropdown($id);
            $this->data['locations'] = $this->listing_m->get_treefield_dropdown($id, 2);
            $this->data['categories'] = $this->listing_m->get_treefield_dropdown($id, 1);
        }
        
        $this->data['fields_list'] = $this->field_m->get_fields(sw_current_language_id());
        
        // [Rank packages]
        if(file_exists(APPPATH.'models/Packagerank_m.php')){
            $this->load->model('packagerank_m');

            $this->data['rank_packages'] = $this->packagerank_m->get();
        } else {
            $this->data['rank_packages'] = array();
        }
        // [/Rank packages]
        
        $rules = $this->listing_m->form_agent;
        $rules_lang = $this->listing_m->rules_lang;
        
        $this->form_validation->set_rules(array_merge($rules, $rules_lang));
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $package_rank = $this->input->post('packagerank', true);
            
            $data = $this->listing_m->array_from_post($this->listing_m->get_post_from_rules($rules));
            
            $data_lang = $this->listing_m->array_from_post($this->listing_m->get_lang_post_fields());
            
            $id = $this->listing_m->save_with_lang($data, $data_lang, $id);
            
            if($is_new && sw_settings('listing_activation_required') == 1)
            {
                // send email to client
                $current_user = wp_get_current_user();
                $email_address = $current_user->user_email;
                
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.sw_settings('noreply');
                
                $subject = __('Thanks on your submission', 'sw_win');
                $message = __('Admin need to verify your submission to become public visible', 'sw_win');
                
                $ret1 = wp_mail( $email_address, $subject, $message, $headers );
                
                // send email to admin
                $email_address = get_option('admin_email');
                
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.sw_settings('noreply');
                
                $subject = __('New non activated property', 'sw_win');
                $message = __('Please check and activate new property', 'sw_win').': #'.$id;
                
                $ret2 = wp_mail( $email_address, $subject, $message, $headers );
            }
            
            // [Save invoice, ask for payment]
            if(!empty($package_rank))
            {
                // fetch package details
                $package = $this->packagerank_m->get($package_rank);
                
                if(!empty($package) && $package->package_price > 0)
                {
                    $this->load->model('invoice_m');
                    
                    $this->invoice_m->disable_by_listing($id);
                    
                    $invoice = array();
                    $invoice['invoicenum'] = $this->invoice_m->invoice_suffix($id.$user_id);
                    $invoice['date_created'] =  date('Y-m-d H:i:s');
                    $invoice['date_paid'] = NULL;
                    $invoice['user_id'] = $user_id;
                    $invoice['listing_id'] = $id;
                    $invoice['is_activated'] = NULL;
                    $invoice['vat_percentage'] = sw_settings('default_vat');
                    $invoice['company_details'] = NULL;
                    $invoice['price'] = $package->package_price;
                    $invoice['currency_code'] = sw_settings('default_currency');
                    $invoice['paid_via'] = NULL;
                    $invoice['note'] = NULL;
                    $invoice['data_json'] = json_encode(array('item'=>$package));
                    
                    // Create invoice for payment if price > 0
                    $invoice_id = $this->invoice_m->save($invoice);
                    // Open payment console
                    
                    wp_redirect(admin_url("admin.php?page=ownlisting_invoices&function=viewinvoice&id=$invoice_id&listingsaved=true")); exit;
                }

            }
            // [/Save invoice, ask for payment]
            
            wp_redirect(admin_url("admin.php?page=ownlisting_addlisting&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'agent/listing/addlisting';
        $this->load->view('agent/_layout_main', $this->data);
	}
    
    public function remlisting($id = NULL, $redirect='1')
	{        
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('listing_m', $id, 'remove');
        
        if(is_numeric($id))
            $this->listing_m->delete($id);
	}
    
    // json for datatables
    public function datatable()
    {
        
        
        // configuration
        $columns = array('idlisting', 'address', 'field_10', 'field_4');
        $controller = 'listing';
        
        if(sw_settings('show_categories'))
        {
            $columns[] = 'category_id';
        }
        else
        {
            $columns[] = 'field_2';
        }
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id(), true);
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            foreach($columns as $val)
            {
                if(isset($row->$val))
                {
                    
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
            
            if(sw_settings('show_categories'))
            {
                if($row->category_id != '-')
                $row->category_id = $row->category_id.', '.$this->treefield_m->get_value($row->category_id);
            }
            
            $row->edit = btn_edit(admin_url("admin.php?page=ownlisting_addlisting&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=ownlisting_manage&function=remlisting&id=".$row->{"id$controller"}));

            if($row->is_activated == 1 && sw_settings('expire_days') > 0)
            {
                if(strtotime($row->date_modified) < time()-sw_settings('expire_days')*86400)
                    $row->{"id$controller"} .= ' <span class="label label-danger">'.__("Expired", "sw_win").'</span>';
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
    
    
    public function favorites()
	{
        // Fetch all results
        $this->data['results'] = array();

        // Load view
		$this->data['subview'] = 'agent/listing/favorites';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    // json for datatables
    public function datatablefavorites()
    {
        
        
        // configuration
        $columns = array('idfavorite', 'sw_favorite.listing_id', 'favorite.user_id', 'note', 'display_name');
        $controller = 'favorite';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $this->load->model($controller.'_m');
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id(), true);
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            foreach($columns as $val)
            {
                // if column contain also "table_name.*"
                $splited = explode('.', $val);
                if(sw_count($splited) == 2)
                    $val = $splited[1];

                if(isset($row->$val))
                {
                    // Customize listing_id value preview, add title
                    if($val == 'listing_id')
                        $row->$val = $row->$val.', '._field($row, 10);
                    
                    // Customize listing_id value preview, add user
                    if($val == 'user_id')
                        $row->$val = $row->$val.', '.$row->display_name;
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
            
            $row->edit = btn_open(listing_url($row));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=ownlisting_favorites&function=remfavorite&id=".$row->{"id$controller"}));
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
    
    public function remfavorite($id=NULL)
	{
	    $this->load->model('favorite_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('favorite_m', $id, 'remove');
        
        if($this->favorite_m->check_deletable($id))
        {
            $this->favorite_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=ownlisting_favorites&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
	public function messages()
	{
        // Fetch all results
        $this->data['results'] = array();
        
        // Load view
		$this->data['subview'] = 'agent/listing/messages';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    // json for datatables
    public function datatablemessages()
    {
        
        
        // configuration
        $columns = array('idinquiry', 'is_readed','date_sent', 'email_sender', 'message', 'sw_inquiry.listing_id', 'field_10');
        $controller = 'inquiry';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $this->load->model($controller.'_m');
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id(), true);
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            foreach($columns as $val)
            {
                // if column contain also "table_name.*"
                $splited = explode('.', $val);
                if(sw_count($splited) == 2)
                    $val = $splited[1];

                if(isset($row->$val))
                {
                    // Customize listing_id value preview, add title
                    if($val == 'listing_id')
                        $row->$val = $row->$val.', '._field($row, 10);
                        
                    // Customize is_readed value preview, add title
                    if($val == 'is_readed')
                    {
                        if($row->$val == 1)
                        {
                           $row->$val = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
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
            
            $row->edit = btn_read(admin_url("admin.php?page=ownlisting_messages&function=editmessage&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=ownlisting_messages&function=remmessage&id=".$row->{"id$controller"}));
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
    
	public function editmessage($id=NULL)
	{
        $this->load->model('inquiry_m');
        $this->load->model('messages_m');
        $this->load->model('user_m');
        $this->data['current_user_id']= get_current_user_id();
        
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('inquiry_m', $id, 'edit');
       
        // Set up the form
        if(empty($id))
        {
            exit('Missing ID');
        }
        else
        {
            $this->data['form_object'] = $this->inquiry_m->get($id);
            
            $json = json_decode($this->data['form_object']->json_object);
            if(isset($json->phone))
                $this->data['form_object']->phone = $json->phone; 
            
                        
            $this->data['messages'] = array();
            $messages = $this->messages_m->get_related("inquiry_".$this->data['form_object']->idinquiry);
            if(!empty($messages)) {
                $this->messages_m->generete_list_messages($messages);
                $this->data['messages'] = array_reverse($messages);
            }
            
            $live_user_id_receiver = '';
            $live_emailreceiver ='';
            $live_emailsender = '';
            
            if($this->data['form_object']->user_id_sender !=$this->data['current_user_id']) {
                $live_user_id_receiver = $this->data['form_object']->user_id_sender;
                $live_emailreceiver = $this->data['form_object']->email_sender;
                $live_emailsender = $this->data['form_object']->email_receiver;
            } else {
                $live_user_id_receiver = $this->data['form_object']->user_id_receiver ; 
                $live_emailreceiver = $this->data['form_object']->email_receiver;
                $live_emailsender = $this->data['form_object']->email_sender;
            }
                   
            $this->data['live_user_id_receiver'] = $live_user_id_receiver;
            $this->data['live_emailreceiver'] = $live_emailreceiver;
            $this->data['live_emailsender'] = $live_emailsender;
        }
        
        $rules = $this->inquiry_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            
            $data = $this->inquiry_m->array_from_post($this->inquiry_m->get_post_from_rules($rules));
            
            if($id == NULL)
            {
            }
            
            $id = $this->inquiry_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=ownlisting_messages&function=editmessage&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'agent/listing/editmessage';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remmessage($id=NULL)
	{
	    $this->load->model('inquiry_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('inquiry_m', $id, 'remove');
        
        if($this->inquiry_m->check_deletable($id))
        {
            $this->inquiry_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=listing_messages&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }

	public function subscriptions()
	{
        // Fetch all results
        $this->data['results'] = array();
       
        // Load view
		$this->data['subview'] = 'agent/listing/subscriptions';
        $this->load->view('admin/_layout_main', $this->data);
    }

	public function viewsubscription($id=NULL)
	{
        $this->load->model('subscriptions_m');
        $this->load->model('user_m');
        $this->load->model('listing_m');
        
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('subscriptions_m', $id, 'view');
       
        // Set up the form
        if(empty($id))
        {
            exit(__('Adding not supported'));
        }
        else
        {
            $this->data['form_object'] = $this->subscriptions_m->get($id);
        }

        
        // Load view
		$this->data['subview'] = 'agent/listing/viewsubscription';
        $this->load->view('admin/_layout_main', $this->data);
	}

    // json for datatables
    public function datatablesubs()
    {

        // configuration
        $columns = array('idsubscriptions', 'subscription_name', 'listing_limit', 'days_limit', 'subscription_price' );
        $controller = 'subscriptions';
        
        $this->load->model($controller.'_m');
        
        $user_package_id = NULL;
        $user_package_expire = NULL;
        if(sw_is_logged_user())
        {
            $user = wp_get_current_user();
            $user_package_id = profile_data($user, 'package_id');
            $user_package_expire = profile_data($user, 'package_expire');
        }

        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id(), true);
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->view = btn_read(admin_url("admin.php?page=ownlisting_subscriptions&function=viewsubscription&id=".$row->{"id$controller"}), __('View', 'sw_win'));
            //$row->delete = btn_delete_noconfirm(admin_url("admin.php?page=ownlisting_invoices&function=reminvoice&id=".$row->{"id$controller"}));
            
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

                    if($val == 'idsubscriptions')
                    {
                        if($row->is_default == 1)
                        {
                           $row->$val.= '&nbsp;<span class="label label-default">Default</span>';
                        }

                        if( intval($user_package_id) == intval($row->idsubscriptions) )
                        {
                            $days_expire = intval((strtotime($user_package_expire)-time())/86400);

                            $days_text = '';
                            if($days_expire >= 0)
                                $days_text = ' ('.intval((strtotime($user_package_expire)-time())/86400).')';

                            $row->$val.= '&nbsp;<span class="label label-primary">Current'.$days_text.'</span>';

                            if(strtotime($user_package_expire) < time())
                            {
                                $row->$val.= '&nbsp;<span class="label label-danger">Expired</span>';
                            }
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
  
    
	public function invoices()
	{
        // Fetch all results
        $this->data['results'] = array();
       
        // Load view
		$this->data['subview'] = 'agent/listing/invoices';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function viewinvoice($id=NULL)
	{
        $this->load->model('invoice_m');
        $this->load->model('user_m');
        $this->load->model('listing_m');
        $this->load->model('subscriptions_m');
        
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('invoice_m', $id, 'edit');
       
        // Set up the form
        if(empty($id))
        {
            exit(__('Adding not supported'));
        }
        else
        {
            $this->data['form_object'] = $this->invoice_m->get($id);
        }
        
        $this->data['users'] = $this->user_m->get_form_dropdown('display_name', 
                                                                    array('ID'=>$this->data['form_object']->user_id), FALSE);
        
        $this->data['listings'] = $this->listing_m->get_form_dropdown('field_10', 
                                                                    array('idlisting'=>$this->data['form_object']->listing_id), FALSE);

        $this->data['subscriptions'] = $this->subscriptions_m->get_form_dropdown('subscription_name', 
                                                                    array('idsubscriptions'=>$this->data['form_object']->subscription_id), FALSE);

        // Load view
		$this->data['subview'] = 'agent/listing/viewinvoice';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function reminvoice($id = NULL, $redirect='1')
	{   
        $this->load->model('invoice_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('invoice_m', $id, 'remove');
        
        if(is_numeric($id))
            $this->invoice_m->delete($id);
            
        exit();
	}
    
    // json for datatables
    public function datatableinvoice()
    {
        // configuration
        $columns = array('idinvoice', 'invoicenum', 'display_name', 'field_10', 'subscription_name', 'price', 'is_activated', 'invoice.listing_id','sw_invoice.subscription_id', 'user_id' );
        $controller = 'invoice';
        
        $this->load->model($controller.'_m');
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m', array('subscription_name'));
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m', array('subscription_name'));
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id(), true);
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->edit = btn_read(admin_url("admin.php?page=ownlisting_invoices&function=viewinvoice&id=".$row->{"id$controller"}), __('View', 'sw_win'));
            //$row->delete = btn_delete_noconfirm(admin_url("admin.php?page=ownlisting_invoices&function=reminvoice&id=".$row->{"id$controller"}));
            
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

    public function purchasesubs()
    {
        $this->load->model('invoice_m');
        $this->load->model('user_m');
        $this->load->model('listing_m');
        $this->load->model('subscriptions_m');

        // Get parameters
        $id = $this->input->get('id');

        check_access('subscriptions_m', $id, 'view');

        $user_package_id = NULL;
        $user_package_expire = NULL;
        $user_package_details = NULL;
        $is_free_package_user = false;
        if(sw_is_logged_user())
        {
            $user = wp_get_current_user();
            $user_package_id = profile_data($user, 'package_id');
            $user_package_expire = profile_data($user, 'package_expire');
        
            $user_package_details = $this->subscriptions_m->get($user_package_id);
            if(is_object($user_package_details) && $user_package_details->subscription_price == 0)
            {
              $is_free_package_user = true;
            }
        }

        $user_id = get_current_user_id();
        
        $days_expire = intval((strtotime($user_package_expire)-time())/86400);

        if($days_expire > 0 && $user_package_id != $id && !$is_free_package_user)
        {
            echo '<br />'.__('You are subscribed for other subscription package, because of that you are not able to purchase this one until current expire','sw_win');
            exit();
        }

        $package = $this->subscriptions_m->get($id);

        // generate invoice

        $this->load->model('invoice_m');

        // $this->invoice_m->disable_by_subscription($user_id);
        
        $invoice = array();
        $invoice['invoicenum'] = $this->invoice_m->invoice_suffix($id.$user_id.'S');
        $invoice['date_created'] =  date('Y-m-d H:i:s');
        $invoice['date_paid'] = NULL;
        $invoice['user_id'] = $user_id;
        $invoice['subscription_id'] = $id;
        $invoice['is_activated'] = NULL;
        $invoice['vat_percentage'] = sw_settings('default_vat');
        $invoice['company_details'] = NULL;
        $invoice['price'] = $package->subscription_price;
        $invoice['currency_code'] = sw_settings('default_currency');
        $invoice['paid_via'] = NULL;
        $invoice['note'] = 'Subscription package '.$id;
        $invoice['data_json'] = json_encode(array('item'=>$package));
        
        // Create invoice for payment if price > 0
        $invoice_id = $this->invoice_m->save($invoice);
        // Open payment console
        
        wp_redirect(admin_url("admin.php?page=ownlisting_invoices&function=viewinvoice&id=$invoice_id&listingsaved=true")); exit;

        // extend/change package
        //$data_user_new = array();

        //$days_expire_new = date('Y-m-d H:i:s', time()+$days_expire*86400+$package->days_limit*86400);





    }
    
    public function woopayment()
    {
        $this->load->model('invoice_m');
        $this->load->model('user_m');
        $this->load->model('listing_m');
        $this->load->model('subscriptions_m');
        
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('invoice_m', $id, 'edit');
       
        // Set up the form
        if(empty($id))
        {
            exit(__('Adding not supported'));
        }
        else
        {
            $this->data['form_object'] = $this->invoice_m->get($id);
        }
        
        $this->data['users'] = $this->user_m->get_form_dropdown('display_name', 
                                                                    array('ID'=>$this->data['form_object']->user_id), FALSE);

        $this->data['subscriptions'] = $this->subscriptions_m->get_form_dropdown('subscription_name', 
                                                                    array('idsubscriptions'=>$this->data['form_object']->subscription_id), FALSE);

        $woo_package_item_id = NULL;
        $package_details = $this->subscriptions_m->get($this->data['form_object']->subscription_id);
        if(is_object($package_details) && !empty($package_details->woo_item_id))
        {
            $woo_package_item_id = $package_details->woo_item_id;

            // check if this id exists
            $array_id=range(1, $woo_package_item_id);
            $args     = array( 'post_type' => 'product', 'posts_per_page' => -1, 'post__in' => $array_id,
                'meta_query' => array(
                array(
                    'key' => '_subscriptio',
                    'value' => "yes",
                    'compare' => '=',
                )
            ) );
            $products = get_posts( $args ); 
            
            if(isset($products[0]))
            {
                $cart_url = apply_filters( 'woocommerce_get_cart_url', wc_get_page_permalink( 'cart' ) );

                wp_redirect( $cart_url.'?add-to-cart='.$woo_package_item_id );
                
                exit();
            }
        }

        $product_name = '';
        if(!empty($this->data['form_object']->listing_id))
        {
            $listing = $this->listing_m->get($this->data['form_object']->listing_id);
            $product_name = $listing->field_10;
        }

        $subscription_name = '';
        if(!empty($this->data['form_object']->subscription_id))
        {
            $subscription = $this->subscriptions_m->get($this->data['form_object']->subscription_id);
            $product_name = $subscription->subscription_name;
        }

        if(get_woocommerce_currency() != $this->data['form_object']->currency_code)
        {
            echo '<br /><div class="bootstrap-wrapper"><div class="alert alert-danger" role="alert">';
            echo __('Different currency code defined in WooCommerce, please change it to: ').$this->data['form_object']->currency_code;
            echo '</div></div>';
            exit();
        }

        $term = get_term_by('name', 'SW Listing Invoices', 'product_cat');

        $cat_id = NULL;

        if(isset($term->term_id))
        {
            $cat_id = $term->term_id;
        }
        else
        {
            $cat = wp_insert_term(
                'SW Listing Invoices', // the term 
                'product_cat', // the taxonomy
                array(
                  'description'=> 'SW Listing Invoices',
                  'slug' => 'sw-isting-invoices'
                )
              );

            $cat_id = $cat['term_id'];
        }


        $product_obj = get_page_by_path( "sw-invoice-".$this->data['form_object']->idinvoice.'-'.$this->data['form_object']->invoicenum, OBJECT, 'product' );

        //dump($this->data['form_object']);

        if(isset($product_obj->ID))
        {
            $product_obj = (array) $product_obj;
        }
        else
        {
            $data = [
                'name' => $product_name.' #'.$this->data['form_object']->invoicenum,
                "slug" => "sw-invoice-".$this->data['form_object']->idinvoice.'-'.$this->data['form_object']->invoicenum,
                'description' => __('Invoice for listings: ').$this->data['form_object']->invoicenum,
                'regular_price' => $this->data['form_object']->price,
                'virtual' => true,
                "catalog_visibility" => "hidden",
                "manage_stock" => true,
                "stock_quantity" => 1,
                "shipping_required" => false,
                "categories" => array(array('id'=> $cat_id))
            ];
            $request = new WP_REST_Request( 'POST' );
            $request->set_body_params( $data );
            $products_controller = new WC_REST_Products_Controller;
            $response = $products_controller->create_item( $request );
            $product_obj = $response->data;

            $product_obj['ID'] = $product_obj['id'];
        }

        // select ID
        $product_id = $product_obj['ID'];

        $cart_url = apply_filters( 'woocommerce_get_cart_url', wc_get_page_permalink( 'cart' ) );

        wp_redirect( $cart_url.'?add-to-cart='.$product_id );
        
        exit();
    }

    public function bankpayment()
    {
        $this->load->model('invoice_m');
        $this->load->model('user_m');
        $this->load->model('listing_m');
        
        // Get parameters
        $id = $this->input->get('id');
        
        check_access('invoice_m', $id, 'edit');
       
        // Set up the form
        if(empty($id))
        {
            exit(__('Adding not supported'));
        }
        else
        {
            $this->data['form_object'] = $this->invoice_m->get($id);
        }
        
        $this->data['users'] = $this->user_m->get_form_dropdown('display_name', 
                                                                    array('ID'=>$this->data['form_object']->user_id), FALSE);
        
        $this->data['listings'] = $this->listing_m->get_form_dropdown('field_10', 
                                                                    array('idlisting'=>$this->data['form_object']->listing_id), FALSE);

        
        // Load view
		$this->data['subview'] = 'agent/listing/bankpayment';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function bankpaymentnotice()
    {
        // Get parameters
        $id = $this->input->get('id');
        
        // send email to admin
        $email_address = get_option('admin_email');
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = 'From: '.sw_settings('noreply');
        
        $subject = __('New invoice payment', 'sw_win').': #'.$id;;
        $message = __('Please check if invoice is paid and activate services', 'sw_win').': #'.$id.'<br />';
        $message.= '<a href="'.admin_url("admin.php?page=packagerank_invoices&function=addinvoice&id=$id").'">'.__('Link to edit invoice', 'sw_win').'</a>';
        
        $ret2 = wp_mail( $email_address, $subject, $message, $headers );
        
        wp_redirect(admin_url("admin.php?page=ownlisting_invoices&function=viewinvoice&id=$id&paid=true")); exit;
    }
    
	public function savesearch()
	{
        // Fetch all results
        $this->data['results'] = array();

        // Load view
		$this->data['subview'] = 'agent/listing/savesearch';
        $this->load->view('admin/_layout_main', $this->data);
    }

    // json for datatables
    public function datatablesavesearch()
    {
        // configuration
        $columns = array('idsavesearch', 'display_name','date_submit', 'is_activated', 'date_last_informed');
        $controller = 'savesearch';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $this->load->model($controller.'_m');
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id(), true);
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id(), true);
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            foreach($columns as $val)
            {
                // if column contain also "table_name.*"
                $splited = explode('.', $val);
                if(sw_count($splited) == 2)
                    $val = $splited[1];

                if(isset($row->$val))
                {
                    // Customize listing_id value preview, add title
                    if($val == 'listing_id')
                        $row->$val = $row->$val.', '._field($row, 10);
                        
                    // Customize is_readed value preview, add title
                    if($val == 'is_activated')
                    {
                        if($row->$val == 1)
                        {
                           $row->$val = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
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
            
            $row->edit = btn_edit(admin_url("admin.php?page=ownlisting_savesearch&function=editsavesearch&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=ownlisting_savesearch&function=remsavesearch&id=".$row->{"id$controller"}));
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
    
	public function editsavesearch($id=NULL)
	{
        $this->load->model('savesearch_m');
       
        // Get parameters
        $id = $this->input->get('id');
       
        // Set up the form
        if(empty($id))
        {
            exit('Missing ID');
        }
        else
        {
            $this->data['form_object'] = $this->savesearch_m->get($id);
        }
        
        $rules = $this->savesearch_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            
            $data = $this->savesearch_m->array_from_post($this->savesearch_m->get_post_from_rules($rules));
            
            if($id == NULL)
            {
            }
            
            $id = $this->savesearch_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=ownlisting_savesearch&function=editsavesearch&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'agent/listing/editsavesearch';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remsavesearch($id=NULL)
	{
	    $this->load->model('savesearch_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        if($this->savesearch_m->check_deletable($id))
        {
            $this->savesearch_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=ownlisting_savesearch&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
    public function dopayment()
    {
        $this->load->model('invoice_m');
        
        // Get parameters
        $invoice_id = $this->input->get('id');
        $provider = $this->input->get('provider');
        
        $invoice_data = $this->invoice_m->get($invoice_id);

        if(empty($invoice_data))exit(__('Wrong invoice ID', 'sw_win'));

        $json_data = json_decode($invoice_data->data_json);
        
        $item_name = 'Package';
        if(isset($json_data->item->package_name))
            $item_name = $json_data->item->package_name.' #'.$invoice_data->listing_id;

        $config = array();
		$config['business'] 			= sw_settings('paypal_email');
		$config['cpp_header_image'] 	= ''; //Image header url [750 pixels wide by 90 pixels high]
		$config['return'] 				= admin_url("admin.php?page=ownlisting_invoices");
		$config['cancel_return'] 		= admin_url("admin.php?page=ownlisting_invoices&function=viewinvoice&id=$invoice_id");
		$config['notify_url'] 			= get_site_url().'?payment='.$invoice_id.'_'.md5(SECURE_AUTH_KEY.$invoice_id); //IPN Post
		$config['production'] 			= sw_settings('use_sandbox') != '1'; //Its false by default and will use sandbox
		$config["invoice"]				= $invoice_id.'_'.date('w'); //The invoice id
        $config["currency_code"]        = sw_settings('default_currency');
        $config['item_name'] = $item_name;
        $config['item_price'] = $invoice_data->price;

        $this->load->library('Paymentconsole');
        $this->paymentconsole->pay($provider, array_merge($config, array('invoice_data'=>$invoice_data, 'json'=>$json_data)));

    }

    public function profile($id=NULL)
	{
        $this->load->model('profile_m');

        $user_id = get_current_user_id();

        $this->data['form_object'] = (object) $this->profile_m->get_by(array('user_id'=>$user_id), TRUE);

        $id = NULL;

        if(isset($this->data['form_object']->idprofile) && isset($this->data['form_object']->idprofile))
        {
            $id = $this->data['form_object']->idprofile;
        }
        
        /* [START] Agents */
        $this->data['agents'] = array();

        if(sw_user_in_role('AGENCY'))
        {
            $this->data['agents'] = $this->profile_m->get_by(array('agency_id'=>$user_id));
        }

        /* [END] Agents */

        $rules = $this->profile_m->form_index;
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            // save data
            $data = $this->profile_m->array_from_rules($rules);

            $data['user_id'] = $user_id;
            $data['gps'] = $data['lat'].','.$data['lng'];

            if(!is_object($this->data['form_object']) || !isset($data['agency_id']) || $this->data['form_object']->agency_id != $data['agency_id'] || empty($data['agency_id']))
                $data['is_agency_verified'] = NULL;

            $this->profile_m->save($data, $id);

            // reload and show message
            wp_redirect(admin_url("admin.php?page=ownlisting_profile&updated=true")); exit;
        }
       
        // Load view
		$this->data['subview'] = 'agent/listing/profile';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function agentssave()
    {
        

        $this->load->model('profile_m');

        if(!sw_user_in_role('AGENCY'))
        {
            exit();
        }

        $data = array();

        $agents_set = array();
        foreach($_POST as $key=>$val)
        {
            $agent = explode('_', $key);

            if(isset($agent[1]) && is_numeric($agent[1]))
            {
                $agent_id = $agent[1];
                $agents_set[$agent_id] = $val;
            }
        }

        $this->profile_m->save_agents(get_current_user_id(), $agents_set);

        $json = array(
            "data" => $data,
            "success" => true
        );

        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length);
        echo json_encode($json);
        
        exit();
    }
    
}
