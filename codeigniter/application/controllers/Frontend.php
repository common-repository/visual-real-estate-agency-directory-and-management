<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress widgets, use for example: $this->CI->load

*/

class Frontend extends MY_Widgetcontroller {

	public function __construct(){
		parent::__construct();
        
        $this->CI->load->model('field_m');
        $this->CI->load->model('repository_m');
        $this->CI->load->model('file_m');
        $this->CI->load->model('listing_m');
        
        $this->CI->load->library('pagination');
	}
    
	public function index(&$output=NULL, $atts=array())
	{

	}
    
	public function resultspage(&$output=NULL, $atts=array(), $instance=NULL)
	{        
        $offset = $this->CI->input->get_post('offset', true);
        prepare_frontend_search_query_GET();
        $this->data['listings_count'] = $this->CI->listing_m->total_lang(array(), sw_current_language_id());
       
        prepare_frontend_search_query_GET('listing_m', array('search_is_activated'=>1), array('sw_listing.rank DESC'));
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang(sw_settings('per_page'), $offset, sw_current_language_id());

        /* Pagination configuration */ 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        
        $config['base_url'] = '';
        $config['total_rows'] = $this->data['listings_count'];
        $config['per_page'] = sw_settings('per_page');
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['suffix'] = "#results";
        /* End Pagination */
        
        $this->CI->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->CI->pagination->create_links();
        
        $this->data['subview'] = 'frontend/resultspage';
        $this->print_template($output);
	}
    
	public function tags(&$output=NULL, $atts=array(), $instance=NULL)
	{        
        $this->data['listing_id_slug'] = get_query_var( 'slug' );

        $this->data['subview'] = 'frontend/tags';
        $this->print_template($output);
	}
    
	public function listingpreview(&$output=NULL, $atts=array(), $instance=NULL)
	{
	    $this->CI->load->model('review_m');
       
        /* [Fetch listing] */
       
        $this->data['listing_id_slug'] = get_query_var( 'slug' );

        if(empty($this->data['listing_id_slug']))
        {
            $output.= sw_notice(__('Listing not defined', 'sw_win'));
            return;
        }
        
        if(is_numeric($this->data['listing_id_slug']))
            $conditions = array('search_idlisting'=>$this->data['listing_id_slug'], 'search_is_activated'=>1);
        else
        {
            $this->CI->load->model('slug_m');
            $table_id = $this->CI->slug_m->getid($this->data['listing_id_slug']);

            $conditions = array('search_idlisting'=>$table_id, 'search_is_activated'=>1);
        }

        prepare_frontend_search_query_GET('listing_m', $conditions);
        $listings = $this->CI->listing_m->get_pagination_lang(1, 0, sw_current_language_id());
        
        // echo $this->CI->db->last_query();
        
        if(empty($listings))
        {
            $output.= sw_notice(__('Listing not found', 'sw_win').': '.$this->data['listing_id_slug']);
            return;
        }
        
        /* [Private listings] */
        if(sw_settings('private_listings') && !sw_is_logged_user())
        {
           if(sw_settings('register_page'))
                wp_redirect(get_permalink(sw_settings('register_page')).'?redirect_to='.get_current_url().'&message='. urlencode(esc_html__('Please login to see listing','sw_win')));
           else 
               wp_redirect(wp_login_url(get_current_url()));
           exit;
        }
        /* [/Private listings] */

        $this->data['listing'] = $listings[0];
        
        // For use on widgets
        $this->CI->data['listing'] = $this->data['listing'];
        
        /* [/Fetch listing] */
        
        /* [Fetch fields] */
        
        $this->data['fields'] = $this->CI->field_m->get_nested(sw_current_language_id());        
        
        // For use on widgets
        $this->CI->data['fields'] = $this->data['fields'];
        
        /* [/Fetch fields] */ 
        
        /* [Fetch related listings] */
        
        $this->data['related'] = $this->CI->listing_m->get_related($this->data['listing'], sw_current_language_id());        
        
        /* [/Fetch related listings] */ 
        
        /* [Fetch images] */
        
        $this->data['images'] = array();
        
        if(!empty($this->data['listing']->repository_id))
            $this->data['images'] = $this->CI->file_m->get_repository($this->data['listing']->repository_id);
        
        /* [/Fetch images] */   
        
        /* [Stars Reviews] */
        
        $this->data['already_reviewed'] = false;
        
        /* [Edit Button] */
        
        if(sw_user_in_role('administrator'))
        {
            $this->data['edit_url'] = admin_url("admin.php?page=listing_addlisting&id=".$this->data['listing']->idlisting);
        }
        elseif(sw_user_in_role('AGENT') ||
               sw_user_in_role('OWNER') ||
               sw_user_in_role('AGENCY'))
        {
            if($this->CI->listing_m->is_related($this->data['listing']->idlisting, get_current_user_id()))
            {
                $this->data['edit_url'] = admin_url("admin.php?page=ownlisting_addlisting&id=".$this->data['listing']->idlisting);
            }
        }
        
        // proccess form
        if(sw_is_logged_user() && isset($_POST['stars']))
        {   
            $rules = $this->CI->review_m->form_listing;
            
            $this->CI->form_validation->set_rules($rules);
            if($this->CI->form_validation->run() == TRUE)
            {
                $data_db = $this->CI->review_m->array_from_post($this->CI->review_m->get_post_from_rules($rules));
                $data_db['user_id'] = get_current_user_id();
                $data_db['is_visible'] = true;
                $data_db['listing_id'] = $this->data['listing']->idlisting;
                
                if(!$this->CI->review_m->exists_listing($data_db['user_id'], $data_db['listing_id'])){
                    $id = $this->CI->review_m->save($data_db, NULL);
                    $this->CI->load->model('repository_m');
                    $this->CI->repository_m->save(array('is_activated'=>1), $data_db['repository_id']);
                }
            }
        }
        
        if(sw_is_logged_user())
        {
            $this->data['already_reviewed'] = 
                        $this->CI->review_m->exists_listing(get_current_user_id(), 
                                                            $this->data['listing']->idlisting) > 0;
        }
        
        // Fetch reviews
        
        $this->data['reviews_all'] = $this->CI->review_m->get_by(array('lang_id'=>sw_current_language_id(), 'is_visible' => 1, 
                                                                       'sw_review.listing_id'=>$this->data['listing']->idlisting),
                                                                 false, false, 10);
        $this->data['avarage_stars'] = intval($this->CI->review_m->avg_rating_listing($this->data['listing']->idlisting)+0.5);
        
        // For use on widgets
        $this->CI->data['avarage_stars'] = $this->data['avarage_stars'];
        $this->CI->data['images'] = $this->data['images'];
        
        /* [/Stars Reviews] */
        
        /* [Update views counter] */
        
        $this->CI->listing_m->update_counter($this->data['listing']);
        
        /* [/Update views counter] */

        $this->data['subview'] = 'frontend/listingpreview';
        $this->print_template($output);
	}
    
	public function userprofile(&$output=NULL, $atts=array(), $instance=NULL)
	{
	    $this->CI->load->model('user_m');
        
        // [Fetch user]
        $this->data['user_id_slug'] = get_query_var( 'slug' );
        
        if(is_numeric($this->data['user_id_slug']))
            $conditions = array('ID'=>$this->data['user_id_slug']);
        else
            $conditions = array('user_nicename'=>$this->data['user_id_slug']);
        
        //prepare_frontend_search_query_GET('user_m', $conditions);
        $user = $this->CI->user_m->get_by($conditions, TRUE);
        
        if(empty($user))
        {
            $output.= sw_notice(__('User not defined', 'sw_win'));
            return;
        }
        
        $all_meta_for_user = get_user_meta($user->ID);
        $user_info = get_userdata($user->ID);
        
        $this->CI->data['user'] = $this->data['user'] = $user;
        $this->CI->data['user_meta'] = $this->data['user_meta'] = $all_meta_for_user;
        
        // [/Fetch user]
        
        // [Fetch user listings]
        $offset = $this->CI->input->get_post('offset', true);

        prepare_frontend_search_query_GET('listing_m');
        $this->CI->data['listings_count'] = $this->data['listings_count'] = $this->CI->listing_m->total_lang(array(), sw_current_language_id(), FALSE, $user->ID);
       
       
        prepare_frontend_search_query_GET('listing_m', array('search_is_activated'=>1), array('sw_listing.rank DESC'));
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang(sw_settings('per_page'), $offset, sw_current_language_id(), FALSE, $user->ID);

        //echo $this->CI->db->last_query();
        
        /* Pagination configuration */ 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        
        $config['base_url'] = '';
        $config['total_rows'] = $this->data['listings_count'];
        $config['per_page'] = sw_settings('per_page');
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['suffix'] = "#results-profile";
        /* End Pagination */
        
        $this->CI->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->CI->pagination->create_links();
        
        // [/Fetch user listings]

        $this->data['subview'] = 'frontend/userprofile';
        $this->print_template($output);
	}
    
	public function agents(&$output=NULL, $atts=array(), $instance=NULL)
	{
        // Example: $role__in = array('AGENT', 'administrator');
        // More details: https://codex.wordpress.org/Function_Reference/get_users
        
        $role__in = array('AGENT');
        
        // [Fetch users]
        $offset = $this->CI->input->get_post('offset', true);
        $search_term = $this->CI->input->get_post('search_term', true);
        
        if(empty($offset))$offset=0;
        
        $user_query = new WP_User_Query( array( 'role__in' => $role__in, 'fields' => 'all', '
                                                 search' => '*'.esc_attr( $search_term ).'*') );
        $this->data['agents_count'] = $user_query->get_total();
        
        $this->data['agents'] = get_users( array( 'search' => '', 'role__in' => $role__in, 
                                                  'order_by' => 'ID', 'order' => 'DESC', 'offset' => $offset, 
                                                  'number' => sw_settings('per_page'), 
                                                  'search' => '*'.esc_attr( $search_term ).'*') );
        // [/Fetch users]
        
        /* Pagination configuration */ 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        
        $config['base_url'] = '';
        $config['total_rows'] = $this->data['agents_count'];
        $config['per_page'] = sw_settings('per_page');
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = FALSE;
        $config['first_url'] = '?offset=0&search_term='.esc_attr( $search_term );
        $config['last_link'] = FALSE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['suffix'] = "#results-agents";
        /* End Pagination */
        
        $this->CI->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->CI->pagination->create_links();

        $this->data['subview'] = 'frontend/agents';
        $this->print_template($output);
    }
    
    public function agencies(&$output=NULL, $atts=array(), $instance=NULL)
	{
        // Example: $role__in = array('AGENT', 'administrator');
        // More details: https://codex.wordpress.org/Function_Reference/get_users
        
        $role__in = array('AGENCY');
        
        // [Fetch users]
        $offset = $this->CI->input->get_post('offset', true);
        $search_term = $this->CI->input->get_post('search_term', true);
        
        if(empty($offset))$offset=0;
        
        $user_query = new WP_User_Query( array( 'role__in' => $role__in, 'fields' => 'all', '
                                                 search' => '*'.esc_attr( $search_term ).'*') );
        $this->data['agencies_count'] = $user_query->get_total();
        
        $this->data['agencies'] = get_users( array( 'search' => '', 'role__in' => $role__in, 
                                                  'order_by' => 'ID', 'order' => 'DESC', 'offset' => $offset, 
                                                  'number' => sw_settings('per_page'), 
                                                  'search' => '*'.esc_attr( $search_term ).'*') );
        // [/Fetch users]
        
        /* Pagination configuration */ 
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        
        $config['base_url'] = '';
        $config['total_rows'] = $this->data['agencies_count'];
        $config['per_page'] = sw_settings('per_page');
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = FALSE;
        $config['first_url'] = '?offset=0&search_term='.esc_attr( $search_term );
        $config['last_link'] = FALSE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['suffix'] = "#results-agencies";
        /* End Pagination */
        
        $this->CI->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->CI->pagination->create_links();

        $this->data['subview'] = 'frontend/agencies';
        $this->print_template($output);
	}
    
	public function compare(&$output=NULL, $atts=array(), $instance=NULL)
	{
	    $this->CI->load->helper('text');
       
	    if(!isset($_COOKIE['sw_compare_list']) || empty($_COOKIE['sw_compare_list']))
        {
            sw_notice(__('Comparision listings not found', 'sw_win'));
            return;
        }
       
	    // get cookie details
        $listings_cookie = explode('|,|', $_COOKIE['sw_compare_list']);
        $listings_list = array();
        foreach($listings_cookie as $listing_details)
        {
            $listing_array = explode('|:|', $listing_details);
            
            if(isset($listing_array[0]))
                $listings_list[] = $listing_array[0];
        }
        
        if(sw_count($listings_list) < 2)
        {
            sw_notice(__('Min 2 listings are required in comparision list to compare', 'sw_win'));
            return;
        }
        
        //dump($listings_list);
        
        // [Fetch listings related]
        
        $custom_vars = array('search_is_activated'=>1, 'search_idlisting'=>$listings_list);
        prepare_frontend_search_query_GET('listing_m', $custom_vars);
        $this->data['listings'] = $this->CI->listing_m->get_pagination_lang(4, 0, sw_current_language_id(), FALSE, NULL);
        
        //dump($this->data['listings']);
        
        // [/Fetch listings related]
        
        /* [Fetch fields] */
        
        $this->data['fields'] = $this->CI->field_m->get_field_list(sw_current_language_id());        
        
        //dump($this->data['fields']);
        
        /* [/Fetch fields] */ 
    
        $this->data['subview'] = 'frontend/compare';
        $this->print_template($output);
    }
}
