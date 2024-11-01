<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriptions extends My_Controller {

	public function __construct(){
		parent::__construct();

        $this->load->model('subscriptions_m');
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
		$this->data['subview'] = 'admin/subscriptions/manage';
        $this->load->view('admin/_layout_main', $this->data);
	}

	public function addsubscription($id=NULL)
	{
        // Get parameters
        $id = $this->input->get('id');

        // Set up the form
        if(empty($id))
        {
        }
        else
        {
            $this->data['form_object'] = $this->subscriptions_m->get($id);
        }

        //sw_code_after_payment( 7216 );
        //exit('end testing');
        
        // get wc products subscriptio, for selection
        $args     = array( 'post_type' => 'product', 'posts_per_page' => -1, 
                           'meta_query' => array(
                            array(
                                'key' => '_subscriptio',
                                'value' => "yes",
                                'compare' => '=',
                            )
                        ) );
        $products = get_posts( $args ); 
        $subscription_products = array(''=>'');

        foreach($products as $product)
        {
            $subscription_products[$product->ID] = $product->post_title;
            //if($meta_values['_subscriptio'][0] == "yes")
        }

        $this->data['woo_items'] = $subscription_products;

        $rules = $this->subscriptions_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->subscriptions_m->array_from_post($this->subscriptions_m->get_post_from_rules($rules));

            $id = $this->subscriptions_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=subscriptions_manage&function=addsubscription&id=$id&updated=true")); exit;
        }

        // Load view
		$this->data['subview'] = 'admin/subscriptions/addsubscription';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remsubscription($id = NULL, $redirect='1')
	{   
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->subscriptions_m->delete($id);
	}
    
    // json for datatables
    public function datatable()
    {
        
        
        // configuration
        $columns = array('idsubscriptions', 'subscription_name', 'listing_limit', 'days_limit', 'subscription_price' );
        $controller = 'subscriptions';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->edit = btn_edit(admin_url("admin.php?page=subscriptions_manage&function=addsubscription&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=subscriptions_manage&function=remsubscription&id=".$row->{"id$controller"}));
            
            foreach($columns as $val)
            {
                if(isset($row->$val))
                {
                    // Customize is_readed value preview, add title
//                    if($val == 'idpackage_rank')
//                    {
//                        if($row->is_activated == 1)
//                        {
//                           $row->$val .= '&nbsp;<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
//                        }
//                    }
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
		$this->data['subview'] = 'admin/subscriptions/invoices';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function addinvoice($id=NULL)
	{
        $this->load->model('invoice_m');
        $this->load->model('user_m');
        $this->load->model('listing_m');
        $this->load->model('subscriptions_m');
        
        // Get parameters
        $id = $this->input->get('id');
        
        $is_new = ($id === NULL);
       
        // Set up the form
        if(empty($id))
        {
            exit(__('Adding not supported','sw_win'));
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


        $rules = $this->invoice_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->invoice_m->array_from_post($this->invoice_m->get_post_from_rules($rules));

            $id = $this->invoice_m->save($data, $id);
            
            // Activate services rank packages
            if(!$is_new && $this->data['form_object']->is_activated == 0 && $data['is_activated'] == 1 &&  !empty($this->data['form_object']->listing_id))
            {
                //Get package details
                $json_data = json_decode($this->data['form_object']->data_json);
                if(isset($json_data->item->package_price) && $json_data->item->package_days > 0)
                {
                    // Activate services to related listing
                    $listing_id = $this->data['form_object']->listing_id;
                    $listing = $this->listing_m->get($listing_id);
                    $package_days = $json_data->item->package_days;
                    
                    $data_update = array();
                    $data_update['rank'] = $json_data->item->rank;
                    $data_update['date_rank_expire'] = date('Y-m-d H:i:s', time() + $package_days*24*60*60);
                    
                    if(empty($listing->is_activated) && 
                       empty($listing->is_disalbed) && 
                       empty($this->data['form_object']->is_disabled))
                    {
                        $data_update['is_activated'] = 1;
                        $data_update['date_activated'] = date('Y-m-d H:i:s', time());
                    }
                    
                    $this->listing_m->save($data_update, $listing_id);
                    
                    // Inform user that invoice services are activated
                    
                    // send email to client
                    $client = $this->user_m->get($this->data['form_object']->user_id);
                    $email_address = $client->user_email;
                    
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $headers[] = 'From: '.sw_settings('noreply');
                    
                    $subject = __('Thanks on your payment', 'sw_win');
                    $message = __('Admin activated services related to invoice', 'sw_win').': #'.$this->data['form_object']->invoicenum;
                    
                    $ret1 = wp_mail( $email_address, $subject, $message, $headers );
                    
                    
                }
            }

            // Activate services subscription package
            if(!$is_new && $this->data['form_object']->is_activated == 0 && $data['is_activated'] == 1 &&  !empty($this->data['form_object']->subscription_id))
            {

                //Get package details
                $data_json = json_decode($this->data['form_object']->data_json);

                if(isset($data_json->item->subscription_price) && $data_json->item->days_limit > 0)
                {
                    $this->load->model('listing_m');
                    $this->load->model('user_m');
                    $this->load->model('profile_m');
                    $this->load->model('subscriptions_m');

                    $invoice = $this->data['form_object'];

                    $user = get_userdata( $invoice->user_id );
                    $user_package_id = profile_data($user, 'package_id');
                    $user_package_expire = profile_data($user, 'package_expire');

                    $user_id = $invoice->user_id;
        
                    $profile = (object) $this->profile_m->get_by(array('user_id'=>$user_id), TRUE);
                    
                    $days_expire = intval((strtotime($user_package_expire)-time())/86400);

                    if($days_expire < 0) $days_expire = 0;
        
                    // extend/change package
                    $data_user_new = array();
            
                    $days_expire_new = date('Y-m-d H:i:s', time()+$days_expire*86400+intval($data_json->item->days_limit)*86400);
        
                    
                    // Activate services to related listing
                    $data_update = array();
                    $data_update['package_id'] = $invoice->subscription_id;
                    $data_update['package_expire'] = $days_expire_new;
                    
                    $this->profile_m->save($data_update, $profile->idprofile);

                    // Inform user that invoice services are activated
                    
                    // send email to client
                    $client = $this->user_m->get($invoice->user_id);
                    $email_address = $client->user_email;
                    
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $headers[] = 'From: '.sw_settings('noreply');
                    
                    $subject = __('Thanks on your payment', 'sw_win');
                    $message = __('Admin activated services related to invoice', 'sw_win').': #'.$this->data['form_object']->invoicenum;
                    
                    $ret1 = wp_mail( $email_address, $subject, $message, $headers );
                    
                    
                }
            }
            
            wp_redirect(admin_url("admin.php?page=subscriptions_invoices&function=addinvoice&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'admin/subscriptions/addinvoice';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function reminvoice($id = NULL, $redirect='1')
	{   
        $this->load->model('invoice_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->invoice_m->delete($id);
            
        exit();
	}
    
    // json for datatables
    public function datatableinvoice()
    {
        // configuration
        $columns = array('idinvoice', 'invoicenum', 'display_name', 'subscription_name', 'price', 'is_activated', 'sw_invoice.listing_id', 'sw_invoice.subscription_id', 'user_id' );
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
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array('sw_invoice.listing_id IS NULL'=>NULL), sw_current_language_id());

        prepare_search_query_GET($columns, $controller.'_m', array('subscription_name'));
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array('sw_invoice.listing_id IS NULL'=>NULL), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m', array('subscription_name'));
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id(), FALSE, array('sw_invoice.listing_id IS NULL'=>NULL));
        
        $query = $this->db->last_query();
        
        // Add buttons
        foreach($data as $key=>$row)
        {
            $row->edit = btn_edit(admin_url("admin.php?page=subscriptions_invoices&function=addinvoice&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=subscriptions_invoices&function=reminvoice&id=".$row->{"id$controller"}));
            
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
