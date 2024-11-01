<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress widgets, use for example: $this->CI->load

*/

class Widgets extends MY_Widgetcontroller {

	public function __construct(){
		parent::__construct();
        
        $this->CI->load->model('field_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->model('listing_m');
        $this->CI->load->model('user_m');
	}
    
    
	public function index(&$output=NULL, $atts=array())
	{
        $this->data['subview'] = 'widgets/index';
        $this->print_template($output);
	}
    
    public function addlisting(&$output=NULL, $atts=array(), $instance=NULL)
    {
        $this->data['subview'] = 'widgets/addlisting';
        $this->print_template($output);
    }
    
	public function latestlistings(&$output=NULL, $atts=array(), $instance=NULL)
	{
        // dump($atts);
        
        $conditions = array('search_smart'=>$atts['text_criteria'], 'search_is_activated'=>1);
        
        if($atts['show_featured'] == 'ALSO_FEATURED')
        {
            // no criteria
        }
        elseif($atts['show_featured'] == 'ONLY_FEATURED')
        {
            $conditions['search_is_featured'] = 1;
        }
        elseif($atts['show_featured'] == 'NO_FEATURED')
        {
            $conditions['search_is_featured'] = 'IS NULL';
        }
        
        $conditions['search_order'] = 'idlisting DESC';

        $this->data['atts'] = $atts;
        
        // dump($conditions);

        prepare_frontend_search_query_GET('listing_m', $conditions);
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang($atts['num_listings'], 0, sw_current_language_id());

        // echo $this->CI->db->last_query();

        $this->data['subview'] = 'widgets/latestlistings';
        $this->print_template($output);
	}
    
    public function latestagents(&$output=NULL, $atts=array(), $instance=NULL)
	{
	    // Example: $role__in = array('AGENT', 'administrator');
        // More details: https://codex.wordpress.org/Function_Reference/get_users
        
        $role__in = array('AGENT');
        $offset = 0;
       
        $this->data['agents'] = get_users( array( 'search' => '', 'role__in' => $role__in, 
                                                  'order_by' => 'ID', 'order' => 'DESC', 'offset' => $offset, 
                                                  'number' => $atts['num_listings']) );

        $this->data['subview'] = 'widgets/latestagents';
        $this->print_template($output);
	}
    
    public function mortgage(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['subview'] = 'widgets/mortgage';
        $this->print_template($output);
	}
    
    public function currencyconverter(&$output=NULL, $atts=array(), $instance=NULL)
	{
	    $this->CI->load->model('currency_m');

        $this->data['conversion_table'] = $this->CI->currency_m->get_conversions_table();
        
        $this->data['dropdown_currency'] = $this->CI->currency_m->get_form_dropdown();
       
        $this->data['subview'] = 'widgets/currencyconverter';
        $this->print_template($output);
	}
    
    public function listingagent(&$output=NULL, $atts=array(), $instance=NULL)
	{
        if(isset($this->CI->data['listing']->idlisting))
        {
            $listing_id = $this->CI->data['listing']->idlisting;
        }
        else
        {
            return;
        }

        $this->data['agents'] = $this->CI->listing_m->get_agents($listing_id);

        if(sw_count($this->data['agents']) == 0)return; 

        // echo $this->CI->db->last_query();

        $this->data['subview'] = 'widgets/listingagent';
        $this->print_template($output);
    }
    
    public function listingagency(&$output=NULL, $atts=array(), $instance=NULL)
	{
        if(isset($this->CI->data['listing']->idlisting))
        {
            $listing_id = $this->CI->data['listing']->idlisting;
        }
        else
        {
            return;
        }

        $this->data['agencies'] = $this->CI->listing_m->get_agency($listing_id);

        if(sw_count($this->data['agencies']) == 0)return; 

        // echo $this->CI->db->last_query();

        $this->data['subview'] = 'widgets/listingagency';
        $this->print_template($output);
	}
    
	public function maplistings(&$output=NULL, $atts=array(), $instance=NULL)
	{
        if(!is_numeric($atts['num_listings']))$atts['num_listings']=100;
        //dump($atts);
        
        $this->data = array_merge($this->CI->data, $atts);

        /* If agent profile */

        $user_id = NULL;

        if(sw_is_page(sw_settings('user_profile_page')))
        {
            // [Fetch user]
            $this->data['user_id_slug'] = get_query_var( 'slug' );
            
            if(is_numeric($this->data['user_id_slug']))
                $conditions = array('ID'=>$this->data['user_id_slug']);
            else
                $conditions = array('user_nicename'=>$this->data['user_id_slug']);

            $user = $this->CI->user_m->get_by($conditions, TRUE);

            $user_id = $user->ID;
        }

        // [autoselect if in uri]
        $page_title = get_the_title();
        $page_title = trim($page_title);

        $CI =& get_instance();
        $CI->load->model('treefield_m');  
        $CI->load->model('field_m');
        $f_id = 4; //filter field for by uri
        $field_data = $CI->field_m->get_field_data($f_id);
        
        /* multiple */
        if(stripos ($page_title, '&#8211;')!== FALSE) {
           $title_parts = explode('&#8211;', $page_title);

           $flag_category = false;
           $flag_location = false;
           $flag_field_4 = false;

           foreach ($title_parts as $title_part) {
                $title_part = trim($title_part);

                if(!$flag_category) {
                    $treefield_value = $CI->treefield_m->get_all_list(array('value'=>$title_part, 'field_id'=>1), 1);
                    if(sw_count($treefield_value) > 0){
                        $_GET['search_category']=key($treefield_value);
                        $flag_category = true;
                    }
                }
                if(!$flag_location) {
                    $treefield_value = $CI->treefield_m->get_all_list(array('value'=>$title_part, 'field_id'=>2), 1);
                    if(sw_count($treefield_value) > 0){
                        $_GET['search_location']=key($treefield_value);
                        $flag_location = true;
                    }
                }
                if(!$flag_field_4){
                    if(isset($field_data->values) && stripos ($field_data->values, $title_part)!== FALSE){
                        $_GET['search_'.$f_id]= substr($field_data->values, stripos ($field_data->values, $title_part), strlen($title_part));
                    }
                }
            }
        } else {
            $treefield_value = $CI->treefield_m->get_all_list(array('value'=>$page_title, 'field_id'=>1), 1);
            if(sw_count($treefield_value) > 0)
                $_GET['search_category']=key($treefield_value);

            $treefield_value = $CI->treefield_m->get_all_list(array('value'=>$page_title, 'field_id'=>2), 1);
            if(sw_count($treefield_value) > 0)
                $_GET['search_category']=key($treefield_value);
            
            if(isset($field_data->values) && stripos ($field_data->values, $page_title)!== FALSE)
                $_GET['search_'.$f_id]= substr($field_data->values, stripos ($field_data->values, $page_title), strlen($page_title));
        }
        // [/autoselect if in uri]

        prepare_frontend_search_query_GET();

        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang($atts['num_listings'], 0, sw_current_language_id(), FALSE, $user_id);

        $this->data['subview'] = 'widgets/maplistings';
        $this->print_template($output);
	}
    
	public function primarysearch(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['atts'] = $atts;
        $this->data['instance'] = $instance;

        $this->data['subview'] = 'widgets/primarysearch';
        $this->print_template($output);
	}
    
	public function secondarysearch(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['atts'] = $atts;
        $this->data['instance'] = $instance;
       
        $this->data['subview'] = 'widgets/secondarysearch';
        $this->print_template($output);
	}
    
	public function contactform(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['atts'] = $atts;
        $this->data['instance'] = $instance;
        
        
       
        $this->CI->load->model('inquiry_m');

        $this->CI->data = array_merge($this->CI->data, $atts);
        
        // [Populate logged user details]
        $current_user = wp_get_current_user();
        if($current_user->ID != 0 && !isset($_POST['email']))
        {
            $_POST['email'] = $current_user->user_email;
            
            if(!empty($current_user->user_firstname))
                $_POST['fullname'] = $current_user->user_firstname.' '.
                                     $current_user->user_lastname;
        }
        // [/Populate logged user details]

        // [if listing page, then send to agent/owner email]
        $listing_id = NULL;
        $user_id_sender = get_current_user_id();
        $user_id_receiver = NULL;
        $message_reservation = '';
        if(empty($user_id_sender))
            $user_id_sender = NULL;
        
        if(sw_is_page(sw_settings('listing_preview_page')))
        {
            $this->CI->data['listing_id_slug'] = get_query_var( 'slug' );
            
            if(is_numeric($this->CI->data['listing_id_slug']))
                $conditions = array('search_idlisting'=>$this->CI->data['listing_id_slug'], 'search_is_activated'=>1);
            else
            {
                $this->CI->load->model('slug_m');
                $table_id = $this->CI->slug_m->getid($this->CI->data['listing_id_slug']);
    
                $conditions = array('search_idlisting'=>$table_id, 'search_is_activated'=>1);
            }
    
            prepare_frontend_search_query_GET('listing_m', $conditions);
            $listings = $this->CI->listing_m->get_pagination_lang(1, 0, sw_current_language_id());
            
            if(empty($listings))
            {
                echo __('Listing not found', 'sw_win');
                return;
            }
    
            $this->CI->data['listing'] = $listings[0];
            $listing_id = $this->CI->data['listing']->idlisting;
            
            $agents = $this->CI->listing_m->get_agents($listing_id);
            
            //dump($this->CI->data['listing']);
            
            if(sw_count($agents) > 0)
                $this->CI->data['receiver_email']=$agents;


            // if reservation, dates included

            if(function_exists('sw_win_load_ci_function_calendar') && is_user_logged_in())
            {
                if(!empty($_POST['date_from']) && !empty($_POST['date_to']))
                {

                    $client = $this->CI->user_m->get(get_current_user_id());

                    // Save reservation
                    $this->CI->load->model('reservation_m');
                    $this->CI->load->model('calendar_m');

                    $calendar = $this->CI->calendar_m->get_by(array('sw_calendar.listing_id'=>$listing_id), true);

                    if(sw_count($calendar))
                    {

                        $data_res = array();
                        $data_res['calendar_id'] = $calendar->idcalendar;
                        $data_res['listing_id'] = $listing_id;
                        $data_res['user_id'] = get_current_user_id();
                        $data_res['date_from'] = date('Y-m-d H:i:s', strtotime($_POST['date_from']));
                        $data_res['date_to'] = date('Y-m-d H:i:s', strtotime($_POST['date_to']));
                        $data_res['guests_number'] = $_POST['guests_number'];
                        $data_res['currency_code'] = sw_settings('default_currency');
                        $data_res['guests_number'] = $_POST['guests_number'];
                        $data_res['total_price'] = $this->CI->reservation_m->calculate_price($data_res['listing_id'], $data_res['date_from'], $data_res['date_to']);
                        $res_id = $this->CI->reservation_m->save($data_res, NULL);
                        // Message for reservation

                        $message_reservation = __('Client want to reserve listing #', 'sw_win').$listing_id.'<br />';
                        $message_reservation.= __('Date from:', 'sw_win').$_POST['date_from'].'<br />';
                        $message_reservation.= __('Date to:', 'sw_win').$_POST['date_to'].'<br />';
                        $message_reservation.= __('Guests number:', 'sw_win').$_POST['guests_number'].'<br />';
                        $message_reservation.= __('Now you should check and confirm reservation', 'sw_win').'<br />';

                        $href = admin_url('admin.php?page=owncalendars_reservations&function=addreservation&id='.$res_id);

                        if($this->CI->data['receiver_email'] == NULL)
                        {
                            $href = admin_url('admin.php?page=calendars_reservations&function=addreservation&id='.$res_id);
                        }

                        $message_reservation .= '<br/><a href="'.esc_url($href).'">'.__('Edit reservation', 'sw_win').'</a>'.'<br /><br />';
                        $message_reservation.= '<strong>'.__('Client info', 'sw_win').':</strong><br />';
                        $message_reservation.= $client->display_name.'<br />';
                        $message_reservation.= $client->user_email.'<br />';

                    }
                    elseif(!empty($_POST['date_from']))
                    {
                        $message_reservation = __('Client want to reserve listing #', 'sw_win').$listing_id.'<br />';
                        $message_reservation.= __('Date from:', 'sw_win').$_POST['date_from'];
                        $message_reservation.= __('Date to: NOT DEFINED', 'sw_win').'<br />';
                    }
                }
            }
        }        
        // [/if listing page, then send to agent/owner email]
        
        // [if user profile page, then send to agent/owner email]
        if(sw_is_page(sw_settings('user_profile_page')))
        {
            $this->CI->data['user_id_slug'] = get_query_var( 'slug' );

            if(is_numeric($this->CI->data['user_id_slug']))
                $conditions = array('ID'=>$this->CI->data['user_id_slug']);
            else
                $conditions = array('user_nicename'=>$this->CI->data['user_id_slug']);

            $user = $this->CI->user_m->get_by($conditions, TRUE);

            if(!empty($user))
                $this->CI->data['receiver_email']=$user->user_email;
        }
        // [/if user profile page, then send to agent/owner email]

        $rules = $this->CI->inquiry_m->form_widget;
        
        $recaptcha_site_key = sw_settings('recaptcha_site_key');
        if(!empty($recaptcha_site_key))
            $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>__('Recaptcha', 'sw_win'), 
                                                    'rules'=>'trim|required|callback__captcha_check');

        if(function_exists('sw_win_load_ci_function_calendar') && is_user_logged_in())
        {
            if(!empty($_POST['date_from']) && sw_count($calendar))
            {
                $rules['date_to'] = array('field'=>'date_to', 'label'=>__('Date to', 'sw_win'), 
                'rules'=>'trim|required|callback__check_available');
            }
        }

        
        $this->CI->form_validation->set_rules($rules);
        
        // Process the form
        if($this->CI->data['widget_id'] == $this->CI->input->get_post('widget_id'))
        if($this->CI->form_validation->run() == TRUE)
        {
            
            $data = $this->CI->inquiry_m->array_from_post($this->CI->inquiry_m->get_post_from_rules($rules));
            
            unset($data['g-recaptcha-response']);
            
            $data_db = array();
            $data_db['date_sent'] = date('Y-m-d H:i:s');
            $data_db['json_object'] = json_encode($data);
            $data_db['email_sender'] = $data['email'];
            $data_db['message'] = $data['message'];
            $data_db['email_receiver'] = $this->CI->data['receiver_email'];
            $data_db['listing_id'] = $listing_id;
            $data_db['user_id_sender'] = $user_id_sender;
            $data_db['user_id_receiver'] = $user_id_receiver;
                
            if($data_db['email_receiver'] === NULL)
                $data_db['email_receiver'] = get_option('admin_email');

            $_POST['updated'] = 'false';
            
            // [sending email]
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.$data_db['email_sender'];
            
            $message_mail = $data['message'];

            if(!empty($message_reservation))
            {
                $message_mail.= '<br /><br />'.$message_reservation;
            }
            
            if(sw_is_page(sw_settings('listing_preview_page')))
            {
                $href = listing_url($this->CI->data['listing']);   
                $message_mail .= '<br/><a href="'.esc_url($href).'">'.__('Open listing', 'sw_win').'</a>';
            }
            
            $message_mail .= '<br/><br/><br/>'.__('Regard', 'sw_win').' '._ch($data['fullname']);
            $message_mail .= '<br/>'._ch($data['email']).', '._ch($data['phone']);
            
            if(is_array($this->CI->data['receiver_email']))
            {
                foreach($this->CI->data['receiver_email'] as $user)
                {
                    $data_db['email_receiver'] = $user->user_email;
                    $data_db['user_id_receiver'] = $user->ID;
                    
                    $id = $this->CI->inquiry_m->save($data_db, NULL);
                    
                    $ret = wp_mail( $data_db['email_receiver'], $data['subject'], $message_mail, $headers );
                    
                    //dump($data_db);
                    
                    if($ret == TRUE)
                    {
                        $_POST['subject'] = '';
                        $_POST['message'] = '';
                        $_POST['updated'] = 'true';
                    }
                }
            }
            else
            {
                $id = $this->CI->inquiry_m->save($data_db, NULL);
                
                $ret = wp_mail( $data_db['email_receiver'], $data['subject'], $message_mail, $headers );
                
                //dump($data_db);
                
                if($ret == TRUE)
                {
                    $_POST['subject'] = '';
                    $_POST['message'] = '';
                    $_POST['updated'] = 'true';
                }
            }
            
            // [/sending email]
        }
        
        $this->data['subview'] = 'widgets/contactform';
        $this->print_template($output);
	}
    
    public function compare(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['atts'] = $atts;
        $this->data['instance'] = $instance;
       
        if(isset($this->CI->data['listing']->idlisting))
        {
            $listing_id = $this->CI->data['listing']->idlisting;
        }
        else
        {
            return;
        }

        $this->data['agents'] = $this->CI->listing_m->get_agents($listing_id);

        // echo $this->CI->db->last_query();

        $this->data['subview'] = 'widgets/compare';
        $this->print_template($output);
	}
    
    public function savesearch(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['atts'] = $atts;
        $this->data['instance'] = $instance;
        $this->data['subview'] = 'widgets/savesearch';
        $this->print_template($output);
	}
    
    public function geomap(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['atts'] = $atts;
        $this->data['instance'] = $instance;
        
        $this->data['subview'] = 'widgets/geomap';
        $this->print_template($output);
    }
    
    public function pdfexport(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->data['atts'] = $atts;
        $this->data['instance'] = $instance;
        
        if(!sw_is_page(sw_settings('listing_preview_page')))
        {
            echo '<div class="alert alert-danger">';
            echo '"Pdf Export" can\'t be used in this widget placeholder';
            echo '</div>';
            return;
            
        }
        
        
            $this->data['subview'] = 'widgets/pdfexport';
            $this->print_template($output);
	}
    
}
