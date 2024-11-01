<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress shortcode, use for example: $this->CI->load

*/

class Shortcodes extends MY_Widgetcontroller {

	public function __construct(){
		parent::__construct();
        
        $this->CI->load->model('field_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->model('listing_m');
        $this->CI->load->model('review_m');
	}
    
    public function swlisting(&$output=NULL, $atts=array())
    {   
        
        if(empty($atts['id']))
        {
            $output.=__('Please define listing id', 'sw_win');
            return;
        }
        
        if(is_numeric($atts['id']))
            $conditions = array('search_idlisting'=>$atts['id'], 'search_is_activated'=>1);
        else
            $conditions = array('search_slug'=>$atts['id'], 'search_is_activated'=>1);

        prepare_frontend_search_query_GET('listing_m', $conditions);
        $listings = $this->CI->listing_m->get_pagination_lang(1, 0, sw_current_language_id());
        
        if(empty($listings))
        {
            $output.=sw_notice(__('Listing not found', 'sw_win').': '.$atts['id']);
            return;
        }

        $this->data['listing'] = $listings[0];
        
        $this->CI->load->model('file_m');
        $this->CI->load->model('review_m');
        
        /* [Fetch fields] */
        
        $this->data['fields'] = $this->CI->field_m->get_nested(sw_current_language_id());        
        
        /* [/Fetch fields] */ 
        
        /* [Fetch images] */
        
        $this->data['images'] = array();
        
        if(!empty($this->data['listing']->repository_id))
            $this->data['images'] = $this->CI->file_m->get_repository($this->data['listing']->repository_id);
        
        /* [/Fetch images] */   

        // Fetch reviews
        $this->data['reviews_all'] = $this->CI->review_m->get_by(array('lang_id'=>sw_current_language_id(), 'is_visible' => 1, 
                                                                       'sw_review.listing_id'=>$this->data['listing']->idlisting),
                                                                 false, false, 10);
        $this->data['avarage_stars'] = intval($this->CI->review_m->avg_rating_listing($this->data['listing']->idlisting)+0.5);
        
        /* [/Stars Reviews] */

        $this->data['subview'] = 'shortcodes/swlisting';
        $this->print_template($output);
    }
    
    public function swlistings(&$output=NULL, $atts=array())
    {
        $conditions = array();
        if(!empty($atts['text_criteria']) && stripos($atts['text_criteria'], 'location_id_') !== FALSE) {
            $location_id = str_replace('location_id_', '', $atts['text_criteria']);
            $atts['text_criteria'] ='';
            $conditions['search_location'] = $location_id;
        }
        if(!empty($atts['text_criteria']) && stripos($atts['text_criteria'], 'category_id_') !== FALSE) {
            $category_id = str_replace('category_id_', '', $atts['text_criteria']);
            $atts['text_criteria'] ='';
            $conditions['search_category'] = $category_id;
        }
        
        $conditions['search_is_activated']=1;
        
        if(!empty($atts['text_criteria']))
        {
            $conditions['search_smart'] = $atts['text_criteria'];
        }
        
        
        
        if(empty($atts['show_featured']))
            $atts['show_featured'] = 'ALSO_FEATURED';

        if($atts['show_featured'] == 'ONLY_FEATURED')
        {
            $conditions['search_is_featured'] = 1;
        }
        elseif($atts['show_featured'] == 'NO_FEATURED')
        {
            $conditions['search_is_featured'] = 'IS NULL';
        }
        
        if(!empty($atts['agent_id']) && !is_numeric($atts['agent_id']))
        {
            $this->CI->load->model('user_m');
            $rel_user = $this->CI->user_m->get_by("user_nicename = '".$atts['agent_id']."' OR user_email = '".$atts['agent_id']."'");
            
            if(isset($rel_user[0]))
                $atts['agent_id'] = $rel_user[0]->ID;
        }
        
        $conditions['search_order'] = 'idlisting DESC';
        
        //dump($atts);

        prepare_frontend_search_query_GET('listing_m', $conditions);
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang($atts['num_listings'], 0, sw_current_language_id(), FALSE, $atts['agent_id']);
        
        //echo $this->CI->db->last_query();

        $this->data['subview'] = 'shortcodes/swlistings';
        $this->print_template($output);
    }
    
    public function swfeaturedlistings(&$output=NULL, $atts=array())
    { 
        $conditions = array();
        if(!empty($atts['text_criteria']) && stripos($atts['text_criteria'], 'location_id_') !== FALSE) {
            $location_id = str_replace('location_id_', '', $atts['text_criteria']);
            $atts['text_criteria'] ='';
            $conditions['search_location'] = $location_id;
        }
        if(!empty($atts['text_criteria']) && stripos($atts['text_criteria'], 'category_id_') !== FALSE) {
            $category_id = str_replace('category_id_', '', $atts['text_criteria']);
            $atts['text_criteria'] ='';
            $conditions['search_category'] = $category_id;
        }
        
        $conditions['search_is_activated']=1;
        
        if(!empty($atts['text_criteria']))
        {
            $conditions['search_smart'] = $atts['text_criteria'];
        }
        
        if(empty($atts['show_featured']))
            $atts['show_featured'] = 'ALSO_FEATURED';

        if($atts['show_featured'] == 'ONLY_FEATURED')
        {
            $conditions['search_is_featured'] = 1;
        }
        elseif($atts['show_featured'] == 'NO_FEATURED')
        {
            $conditions['search_is_featured'] = NULL;
        }
        
        $conditions['search_order'] = 'idlisting DESC';
        
       
        $this->data['widget_id_short'] = $atts['widget_id_short'];

        prepare_frontend_search_query_GET('listing_m', $conditions);
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang($atts['num_listings'], 0, sw_current_language_id());

        $this->data['subview'] = 'shortcodes/swlistings';
        $this->print_template($output);
    }
    
    public function swagent(&$output=NULL, $atts=array())
	{
        if(empty($atts['id']))
        {
            $output.=__('Please define agent id', 'sw_win');
            return;
        }
        
        if(is_numeric($atts['id']))
            $conditions = array('ID'=>$atts['id']);
        else
            $conditions = array('user_email'=>$atts['id']);
        
        $this->CI->load->model('user_m');
        
        //prepare_frontend_search_query_GET('user_m', $conditions);
        $user = $this->CI->user_m->get_by($conditions, TRUE);
        
        if(empty($user))
        {
            $output.= sw_notice(__('Agent not found', 'sw_win').': '.$atts['id']);
            return;
        }
        
        $all_meta_for_user = get_user_meta($user->ID);
        $user_info = get_userdata($user->ID);
        
        $this->data['user'] = $user;
        $this->data['user_meta'] = $all_meta_for_user;
        
        // [/Fetch user]

        $this->data['subview'] = 'shortcodes/swagent';
        $this->print_template($output);
	}
    
    public function swagents(&$output=NULL, $atts=array())
	{
        $role__in = array('AGENT');
        $offset = 0;
       
        $conditions = array( 'search' => '', 'role__in' => $role__in, 
                                  'order_by' => 'ID', 'order' => 'DESC', 'offset' => $offset, 
                                  'number' => $atts['num_listings']);
        
        if(!empty($atts['text_criteria']))
        {
            $conditions['search'] = '*'.esc_attr( $atts['text_criteria'] ).'*';
        }
       
	    // Example: $role__in = array('AGENT', 'administrator');
        // More details: https://codex.wordpress.org/Function_Reference/get_users

        $this->data['agents'] = get_users( $conditions );
        
        //dump($this->data['agents']);

        $this->data['subview'] = 'shortcodes/swagents';
        $this->print_template($output);
    }
    
    public function swagencies(&$output=NULL, $atts=array())
	{
        $role__in = array('AGENCY');
        $offset = 0;
       
        $conditions = array( 'search' => '', 'role__in' => $role__in, 
                                  'order_by' => 'ID', 'order' => 'DESC', 'offset' => $offset, 
                                  'number' => $atts['num_listings']);
        
        if(!empty($atts['text_criteria']))
        {
            $conditions['search'] = '*'.esc_attr( $atts['text_criteria'] ).'*';
        }
       
	    // Example: $role__in = array('AGENT', 'administrator');
        // More details: https://codex.wordpress.org/Function_Reference/get_users

        $this->data['agencies'] = get_users( $conditions );
        
        //dump($this->data['agencies']);

        $this->data['subview'] = 'shortcodes/swagencies';
        $this->print_template($output);
	}
    
	public function swmaplistings(&$output=NULL, $atts=array())
	{
        $conditions = array();
        if(!empty($atts['text_criteria']) && stripos($atts['text_criteria'], 'location_id_') !== FALSE) {
            $location_id = str_replace('location_id_', '', $atts['text_criteria']);
            $atts['text_criteria'] ='';
            $conditions['search_location'] = $location_id;
        }
        if(!empty($atts['text_criteria']) && stripos($atts['text_criteria'], 'category_id_') !== FALSE) {
            $category_id = str_replace('category_id_', '', $atts['text_criteria']);
            $atts['text_criteria'] ='';
            $conditions['search_category'] = $category_id;
        }
        
        $conditions['search_is_activated']=1;
        
        if(!empty($atts['text_criteria']))
        {
            $conditions['search_smart'] = $atts['text_criteria'];
        }
        
        if(empty($atts['show_featured']))
            $atts['show_featured'] = 'ALSO_FEATURED';

        if($atts['show_featured'] == 'ONLY_FEATURED')
        {
            $conditions['search_is_featured'] = 1;
        }
        elseif($atts['show_featured'] == 'NO_FEATURED')
        {
            $conditions['search_is_featured'] = NULL;
        }
        
        if(!empty($atts['agent_id']) && !is_numeric($atts['agent_id']))
        {
            $this->CI->load->model('user_m');
            $rel_user = $this->CI->user_m->get_by("user_nicename = '".$atts['agent_id']."' OR user_email = '".$atts['agent_id']."'");
            
            if(isset($rel_user[0]))
                $atts['agent_id'] = $rel_user[0]->ID;
        }
        
        if(isset($this->data))
            $this->data = array_merge($this->data, $atts);

        prepare_frontend_search_query_GET('listing_m', $conditions);
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang($atts['num_listings'], 0, sw_current_language_id(), false, $atts['agent_id']);
        $this->data['num_listings'] = $atts['num_listings'];
        
        
        $this->data['subview'] = 'shortcodes/swmaplistings';
        $this->print_template($output);
	}
    
	public function swcontact(&$output=NULL, $atts=array(), $instance=NULL)
	{       
        $this->CI->load->model('inquiry_m');
        if(isset($this->data))
            $this->data = array_merge($this->data, $atts);
        else 
            $this->data = $atts;
            
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

        $this->data['receiver_email']=$atts['email'];

        $rules = $this->CI->inquiry_m->form_widget;
        
        $recaptcha_site_key = sw_settings('recaptcha_site_key');
        if(!empty($recaptcha_site_key))
            $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>__('Recaptcha', 'sw_win'), 
                                                    'rules'=>'trim|required|callback__captcha_check');
        
        $this->CI->form_validation->set_rules($rules);
        
        // Process the form
        if($this->data['widget_id'] == $this->CI->input->get_post('widget_id'))
        if($this->CI->form_validation->run() == TRUE)
        {
            
            $data = $this->CI->inquiry_m->array_from_post($this->CI->inquiry_m->get_post_from_rules($rules));
            
            unset($data['g-recaptcha-response']);
            
            $data_db = array();
            $data_db['date_sent'] = date('Y-m-d H:i:s');
            $data_db['json_object'] = json_encode($data);
            $data_db['email_sender'] = $data['email'];
            $data_db['message'] = $data['message'];
            $data_db['email_receiver'] = $this->data['receiver_email'];
            $data_db['listing_id'] = NULL;
            $data_db['user_id_sender'] = NULL;
            $data_db['user_id_receiver'] = NULL;
                
            $_POST['updated'] = 'false';
            
            // [sending email]
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.$data_db['email_sender'];
            
            $message_mail = $data['message'];
            $message_mail .= '<br/><br/><br/>'.__('Regard', 'sw_win').' '._ch($data['fullname']);
            $message_mail .= '<br/>'._ch($data['email']).', '._ch($data['phone']);
            
            if(is_array($this->data['receiver_email']))
            {
                foreach($this->data['receiver_email'] as $user)
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
        
        $this->data['subview'] = 'shortcodes/swcontact';
        $this->print_template($output);
	}
    
	public function swprimarysearch(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->CI->data['atts'] = $atts;
        $this->data['subview'] = 'shortcodes/swprimarysearch';
        $this->print_template($output);
	}
    
	public function swsecondarysearch(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->CI->data['atts'] = $atts;
        $this->data['subview'] = 'shortcodes/swsecondarysearch';
        $this->print_template($output);
	}
    
	public function swmap(&$output=NULL, $atts=array(), $instance=NULL)
	{
	}
    
}
