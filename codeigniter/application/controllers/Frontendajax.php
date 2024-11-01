<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress widgets, use for example: $this->CI->load

*/

class Frontendajax extends MY_Controller {

	public function __construct(){
		parent::__construct();
        
        $this->load->model('field_m');
        $this->load->model('repository_m');
        $this->load->model('listing_m');
        
        $this->load->library('pagination');
        
        $this->data['is_ajax'] = true;
        
	}
    
    
	public function index(&$output=NULL, $atts=array())
	{

	}
    
    public function relatedid($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        $ajax_output['message'] = __('No message returned!', 'sw_win');
        $results = array();
        
        $parameters = $_POST;
        
        $table = $this->input->post('table', true);
        $table_name = $table;
        
        if(empty($parameters['limit']))
            $parameters['limit'] = 10;
            
        if(empty($parameters['offset']))
            $parameters['offset'] = 0;
            
        if(empty($parameters['attribute_id']))
            $parameters['attribute_id'] = 'id';
            
        if(empty($parameters['attribute_value']))
            $parameters['attribute_value'] = 'address';
        
        if(substr($table,-2, 2) == '_m')
        {
            // it's model
            $table_name = substr($table,0, -2);
            $attr_id = $parameters['attribute_id'];
            $attr_val = $parameters['attribute_value'];
            $attr_search = $parameters['search_term'];
            $skip_id = $parameters['skip_id'];
            
            $id_part="";
            if(is_numeric($attr_search))
                $id_part = "$attr_id=$attr_search OR ";
        
            $this->load->model($table);
            
            $where = array();
            
            if(!empty($attr_search))
                $where["($id_part $attr_val LIKE '%$attr_search%')"] = NULL;
            
            //get_by($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = NULL, 
            //$search = array(), $where_in = NULL, $check_user = FALSE, $fetch_user_details=FALSE)
            
            if($table == 'listing_m')
            {
                if(!empty($skip_id))
                    $where["( id$table_name != $skip_id )"] = NULL;
                
                if(!empty($parameters['language_id']))
                    $where["lang_id"] = $parameters['language_id'];
                
                $q_results = $this->$table->get_by($where, FALSE, $parameters['limit'], 
                                                    "$attr_id DESC", $parameters['offset'],
                                                    TRUE);
            }
            else
            {
                $q_results = $this->$table->get_by($where, FALSE, $parameters['limit'], 
                                                    "$attr_id DESC", $parameters['offset']);
            }
            
            $ajax_output['sql'] = $this->db->last_query();

            foreach ($q_results as $key=>$row)
            {
                $results[$key]['key'] = $row->{$attr_id};
                $results[$key]['value'] = $row->{$parameters['attribute_id']}.', '.
                                            _ch($row->{$parameters['attribute_value']});
            }
            
            // get current value by ID
            $row = $this->$table->get($parameters['curr_id']);
            if(is_object($row))
            {
                $this->data['curr_val'] = $row->{$parameters['attribute_id']}.', '.
                                                _ch($row->{$parameters['attribute_value']});
            }
            else
            {
                $this->data['curr_val'] = '-';
            }
            
            $ajax_output['curr_val'] = $this->data['curr_val'];
            
            $this->data['success'] = true;
        
        }
        
        
        $ajax_output['results'] = $results;
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
    public function treefieldid($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        $ajax_output['message'] = __('No message returned!', 'sw_win');
        $results = array();
        
        $parameters = $_POST;
        
        $table = $this->input->post('table', true);
        $table_name = $table;
        
        if(empty($parameters['empty_value']))
            $parameters['empty_value'] = ' - ';
        
        if(empty($parameters['limit']))
            $parameters['limit'] = 10;
            
        if(empty($parameters['offset']))
            $parameters['offset'] = 0;
            
        if(empty($parameters['attribute_id']))
            $parameters['attribute_id'] = 'id';
            
        if(empty($parameters['attribute_value']))
            $parameters['attribute_value'] = 'address';
            
        if(empty($parameters['field_id']))
            $parameters['field_id'] = 1;
            
        if(empty($parameters['offset']))
            $parameters['offset'] = 0;

        if($parameters['offset'] == 0) // currently don't have load_more functionality'
        if(!empty($parameters['empty_value']))
        {
            $results[0]['key'] = '';
            $results[0]['value'] = $parameters['empty_value'];
        }

        if(substr($table,-2, 2) == '_m')
        {
            // it's model
            $table_name = substr($table,0, -2);
            $attr_id = $parameters['attribute_id'];
            $attr_val = $parameters['attribute_value'];
            $attr_search = $parameters['search_term'];
            $skip_id = $parameters['skip_id'];
            $language_id = $parameters['language_id'];

            if(empty($language_id))
                $language_id = sw_current_language_id();

            $id_part="";
            if(is_numeric($attr_search))
                $id_part = "$attr_id=$attr_search OR ";
        
            $this->load->model($table);
            
            $where = array();
            
            if(!empty($attr_search))
                $where["($id_part $attr_val LIKE '%$attr_search%')"] = NULL;
            
            //get_by($where, $single = FALSE, $limit = NULL, $order_by = NULL, $offset = NULL, 
            //$search = array(), $where_in = NULL, $check_user = FALSE, $fetch_user_details=FALSE)
            
            
            $q_results=array();
            if($table == 'treefield_m')
            {
                //if(!empty($skip_id))
                //    $where["( id$table_name != $skip_id )"] = NULL;
                
                //if(!empty($parameters['language_id']))
                //    $where["lang_id"] = $parameters['language_id'];
                    
                $table_name = 'sw_'.$table_name;
                    
                
//                $this->db->join($table_name.'_lang', $table_name.'.idtreefield = '.$table_name.'_lang.treefield_id');
//                $this->db->where('lang_id', sw_current_language_id());
//                $this->db->where('field_id', $parameters['field_id']);
                
//                $q_results = $this->$table->get_by($where, FALSE, $parameters['limit'], 
//                                                    "$attr_id DESC", $parameters['offset'],
//                                                    array(), NULL, TRUE);
                
                if($parameters['offset'] == 0) // currently don't have load_more functionality'
                    $q_results = $this->$table->get_table_tree($language_id, $parameters['field_id'], $skip_id, true, NULL, '', $where);
            
            
                //$ajax_output['sql'] = $this->db->last_query();
            }
            else
            {
                $q_results = $this->$table->get_by($where, FALSE, $parameters['limit'], 
                                                    "$attr_id DESC", $parameters['offset']);
            }
            
            $ind_order=1;
            foreach ($q_results as $key=>$row)
            {
                $level_gen='';
                if(empty($attr_search))
                    $level_gen = str_pad('', $row->level*12, '&nbsp;').'';
                
                $results[$ind_order]['key'] = $row->{$attr_id};
                $results[$ind_order]['value'] = $level_gen
                                          ._ch($row->{$parameters['attribute_value']});
                                          //.', '.$row->{$parameters['attribute_id']};
                                          
                $ind_order++;
            }
            
            // get current value by ID
            $row=NULL;
            if(!empty($parameters['curr_id']))
                $row = $this->$table->get_lang($parameters['curr_id'], $language_id);
                
            if(is_object($row))
            {
                $level_gen = str_pad('', $row->level*12, '&nbsp;').'';
                
                $this->data['curr_val'] = $level_gen
                                          ._ch($row->{$parameters['attribute_value'].'_'.$language_id});
                                          //.', '.$row->{$parameters['attribute_id']};
            }
            else
            {
                $this->data['curr_val'] = $parameters['empty_value'];
            }
            
            $ajax_output['curr_val'] = $this->data['curr_val'];
            
            $this->data['success'] = true;
        
        }
        
        $ajax_output['results'] = $results;
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
	public function resultslisting($output="", $atts=array(), $instance=NULL)
	{        
        $ajax_output = array();
        
        $offset = $this->input->get_post('offset', true);
        $map_num_listings = $this->input->get_post('map_num_listings', true);
        
        $language_id = $this->input->get_post('language_id', true);
        if(empty($language_id))
            $language_id = sw_current_language_id();
        
        prepare_frontend_search_query_GET();
        $this->data['listings_count'] = $this->listing_m->total_lang(array(), $language_id);
       
        prepare_frontend_search_query_GET('listing_m', array('search_is_activated'=>1), array('sw_listing.rank DESC'));
        $this->data['listings'] = $this->listing_m->get_pagination_lang(sw_settings('per_page'), $offset, $language_id);
        
//        echo $this->db->last_query();
//        exit();
        
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
        $_GET = array_merge($_POST, $_GET);
        unset($_GET['page'], $_GET['action']);
        /* End Pagination */
        
        $this->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->pagination->create_links();
        
        $ajax_output['sql'] = $this->db->last_query();
        
        $this->data['subview'] = 'frontend/resultspage';
        $this->print_template($output);
        
        if(!empty($map_num_listings))
        {
            prepare_frontend_search_query_GET('listing_m', array('search_is_activated'=>1), array('sw_listing.rank DESC'));
            $ajax_output['listings_map'] = $this->listing_m->get_pagination_lang($map_num_listings, 0, sw_current_language_id());
            
            foreach($ajax_output['listings_map'] as $key=>$listing)
            {
                $ajax_output['listings_map'][$key]->infowindow = _infowindow_content($listing);
                
                $pin_icon = plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/empty.png';
        
                if(file_exists(SW_WIN_PLUGIN_PATH.'assets/img/markers/'._field($listing, 14).'.png'))
                {
                    $pin_icon = plugins_url( SW_WIN_SLUG.'/assets', SW_WIN_PLUGIN_PATH).'/img/markers/'._field($listing, 14).'.png';
                }
                
                if(function_exists('sw_template_pin_icon'))
                {
                    $pin_icon = sw_template_pin_icon($listing);
                } 
                else
                {
                    // check for version with category related marker
                    $category = get_listing_category($listing);

                    if(isset($category->marker_icon_id))
                    {
                        $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
                        if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
                        {
                            $pin_icon = $img[0];
                        }
                    }
                }
                
                $ajax_output['listings_map'][$key]->pin_icon = $pin_icon;
                
                $font_icon = "";
                $category = get_listing_category($listing);
                // check for version with category related marker
                if(isset($category->font_icon_code) && !empty($category->font_icon_code))
                {
                    $font_icon = $category->font_icon_code;
                }
        
                $ajax_output['listings_map'][$key]->font_icon = $font_icon;
            }
        }
        
        $category ='';
        if(isset($_GET['search_category']) && !empty($_GET['search_category']) && is_numeric($_GET['search_category'])) {
            $category_obj = get_listing_category($_GET['search_category']);
            if(!empty($category_obj))
                $category = $category_obj->value;
        }
        
        $where = '';
        if(isset($_GET['search_where']) && !empty($_GET['search_where'])) {
            $where = $_GET['search_where'];
        }
        
        $near = __('All results', 'sw_win');
        if(!empty($category) && !empty($where)) {
            $near ='<b>'.$category.'</b> '.__('near', 'sw_win') .' '.$where;
        } elseif(!empty($category)) {
            $near =$category;
        } elseif(!empty($where)) {
            $near =$where;
        }
        
        $ajax_output['html'] = $output;
        $ajax_output['listings_count'] = $this->data['listings_count'];
        $ajax_output['near'] = $near;
        //$ajax_output['listings'] = $this->data['listings'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
	}
    
	public function agentlisting($output="", $atts=array(), $instance=NULL)
	{        
        $ajax_output = array();
        
        $offset = $this->input->get_post('offset', true);
        $user_id = $this->input->get_post('user_id', true);
        
        $map_num_listings = $this->input->get_post('map_num_listings', true);
        
        $language_id = $this->input->get_post('language_id', true);
        if(empty($language_id))
            $language_id = sw_current_language_id();
        
        prepare_frontend_search_query_GET('listing_m');
        $this->data['listings_count'] = $this->listing_m->total_lang(array(), $language_id, FALSE, $user_id);
       
       
        prepare_frontend_search_query_GET('listing_m');
        $this->data['listings'] = $this->listing_m->get_pagination_lang(sw_settings('per_page'), $offset, $language_id, FALSE, $user_id);

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
        $_GET = array_merge($_POST, $_GET);
        unset($_GET['page'], $_GET['action']);
        /* End Pagination */
        
        $this->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->pagination->create_links();
        
        $ajax_output['sql'] = $this->db->last_query();
        
        $this->data['subview'] = 'frontend/userprofilelistings';
        $this->print_template($output);
        
        if(!empty($map_num_listings))
        {
            prepare_frontend_search_query_GET();
            $ajax_output['listings_map'] = $this->listing_m->get_pagination_lang($map_num_listings, 0, $language_id);
            
            foreach($ajax_output['listings_map'] as $key=>$listing)
            {
                $ajax_output['listings_map'][$key]->infowindow = _infowindow_content($listing);
                
                $pin_icon = null;
        
                if(file_exists(SW_WIN_PLUGIN_PATH.'assets/img/markers/'._field($listing, 14).'.png'))
                {
                    $pin_icon = plugins_url( SW_WIN_SLUG.'/assets', SW_WIN_PLUGIN_PATH).'/img/markers/'._field($listing, 14).'.png';
                }
                
                $ajax_output['listings_map'][$key]->pin_icon = $pin_icon;
            }
        }
        
        $ajax_output['html'] = $output;
        $ajax_output['listings_count'] = $this->data['listings_count'];
        //$ajax_output['listings'] = $this->data['listings'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
	}
    
    public function addfavorite($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        
        $this->data['message'] = __('No message returned!', 'sw_win');
        $this->data['parameters'] = $_POST;
        $listing_id = $this->input->post('listing_id');

        $this->load->model('favorite_m');
        
        $this->data['success'] = false;
        
        $user_id = get_current_user_id();
        
        if($user_id == 0)
        {
            $this->data['message'] = __('Please login to use this feature', 'sw_win');
        }
        // Check if listing_id already saved, stop and write message
        else if($this->favorite_m->check_if_exists($user_id, $listing_id)>0)
        {
            $this->data['message'] = __('Favorite already exists!', 'sw_win');
            $this->data['success'] = true;
        }
        // Save favorites to database
        else
        {
            $data = array();
            $data['user_id'] = $user_id;
            $data['listing_id'] = $listing_id;
            
            $this->favorite_m->save($data);
            
            $this->data['message'] = __('Favorite added!', 'sw_win');
            $this->data['success'] = true;
        }
        
        

        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
    public function remfavorite($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        
        $this->data['message'] = __('No message returned!', 'sw_win');
        $this->data['parameters'] = $_POST;
        $listing_id = $this->input->post('listing_id');

        $this->load->model('favorite_m');
        
        $this->data['success'] = false;
        
        $user_id = get_current_user_id();
        
        if($user_id == 0)
        {
            $this->data['message'] = __('Please login to use this feature', 'sw_win');
        }
        // Check if listing_id already exist and remove
        else if($this->favorite_m->check_if_exists($user_id, $listing_id)>0)
        {
            $favorite_selected = $this->favorite_m->get_by(array('sw_favorite.listing_id'=>$listing_id, 'user_id'=>$user_id), TRUE);
            $this->favorite_m->delete($favorite_selected->idfavorite);
            
            $this->data['message'] = __('Favorite removed!', 'sw_win');
            $this->data['success'] = true;
        }
        // Error message
        else
        {
            $this->data['message'] = __('Favorite doesnt exists!', 'sw_win');
            $this->data['success'] = true;
        }

        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
    public function locationautocomplete($output="", $atts=array(), $instance=NULL)
    {
        $limit = $this->input->get_post('limit', true);
        $q = $this->input->get_post('q', true);
        
        $language_id = $this->input->get_post('language_id', true);
        if(empty($language_id))
            $language_id = sw_current_language_id();
        
        if(empty($limit))$limit=8;
        
        $this->load->library('ghelper');
        
        $words_array = $this->ghelper->getAutocomplete($q, $limit);

        $words_array = array_unique($words_array);
        $json_output = json_encode($words_array);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
	public function agents($output="", $atts=array(), $instance=NULL)
	{        
        $ajax_output = array();
        
        // Example: $role__in = array('AGENT', 'administrator');
        // More details: https://codex.wordpress.org/Function_Reference/get_users
        
        $role__in = array('AGENT');
        
        // [Fetch users]
        $offset = $this->input->get_post('offset', true);
        $search_term = $this->input->get_post('search_term', true);
        
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
        $_GET = array_merge($_POST, $_GET);
        unset($_GET['page'], $_GET['action']);
        /* End Pagination */
        
        $this->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->pagination->create_links();
        
        $ajax_output['sql'] = $this->db->last_query();
        
        $this->data['subview'] = 'frontend/agentsresults';
        $this->print_template($output);
        
        $ajax_output['html'] = $output;
        $ajax_output['agents_count'] = $this->data['agents_count'];
        //$ajax_output['listings'] = $this->data['listings'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
	public function agencies($output="", $atts=array(), $instance=NULL)
	{        
        $ajax_output = array();
        
        // Example: $role__in = array('AGENT', 'administrator');
        // More details: https://codex.wordpress.org/Function_Reference/get_users
        
        $role__in = array('AGENCY');
        
        // [Fetch users]
        $offset = $this->input->get_post('offset', true);
        $search_term = $this->input->get_post('search_term', true);
        
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
        $_GET = array_merge($_POST, $_GET);
        unset($_GET['page'], $_GET['action']);
        /* End Pagination */
        
        $this->pagination->initialize($config);
        $this->data['pagination_links'] =  $this->pagination->create_links();
        
        $ajax_output['sql'] = $this->db->last_query();
        
        $this->data['subview'] = 'frontend/agenciesresults';
        $this->print_template($output);
        
        $ajax_output['html'] = $output;
        $ajax_output['agencies_count'] = $this->data['agencies_count'];
        //$ajax_output['listings'] = $this->data['listings'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
	}
    
    public function addreport($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        
        $this->data['message'] = __('No message returned!', 'sw_win');
        $this->data['parameters'] = $_POST;
        $listing_id = $this->input->post('listing_id');

        $this->load->model('report_m');
        
        $this->data['success'] = false;
        
        $user_id = get_current_user_id();

        if($user_id == 0)
        {
            $this->data['message'] = __('Please login to report!', 'sw_win');
        }
        // Check if listing_id already saved, stop and write message
        else if($this->report_m->check_if_exists($user_id, $listing_id)>0)
        {
            $this->data['message'] = __('Report already exists!', 'sw_win');
            $this->data['success'] = true;
        }
        else if($this->input->post('message') == '' || 
                $this->input->post('name') == '' || 
                $this->input->post('phone') == '' || 
                $this->input->post('email') == '' )
        {
            $this->data['message'] = __('Please populate all fields!', 'sw_win');
            $this->data['success'] = false;
        }
        // Save report to database
        else
        {
            $data = array();
            $data['user_id'] = $user_id;
            $data['listing_id'] = $listing_id;
            $data['name'] = $this->input->post('name');
            $data['phone'] = $this->input->post('phone');
            $data['email'] = $this->input->post('email');
            $data['message'] = $this->input->post('message');
            $data['allow_contact'] = $this->input->post('allow_contact');
            
            $this->report_m->save($data);
            
            // [sending email]
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $headers[] = 'From: '.$data['email'];
            
            $admin_email = get_option( 'admin_email' );
            
            if(!empty($admin_email))
                $ret = wp_mail( $admin_email, __('Report listing', 'sw_win'), $data['message'].'<br />Listing ID: '.$data['listing_id'], $headers );
            
            $this->data['message'] = __('Report submited!', 'sw_win');
            $this->data['success'] = true;
        }

        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
    public function savesearch($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        
        $this->data['message'] = __('No message returned!', 'sw_win');
        
        unset($_POST['page'], $_POST['action']);
        
        $this->data['parameters'] = json_encode($_POST);

        $this->load->model('savesearch_m');
        
        $this->data['success'] = false;
        
        $user_id = get_current_user_id();
        
        if($user_id == 0)
        {
            $this->data['message'] = __('Login required!', 'sw_win');
        }
        // Check if listing_id already exist and remove
        else if(!$this->savesearch_m->check_if_exists($user_id, $this->data['parameters'])>0)
        {
            $data = array();
            $data['user_id'] = $user_id;
            $data['parameters'] = $this->data['parameters'];
            $data['is_activated'] = 1;
            $data['date_last_informed'] = date('Y-m-d H:i:s');
            $data['delivery_frequency_h'] = 48;
            $data['lang_id'] = sw_current_language_id();
            $data['date_next_inform'] = date('Y-m-d H:i:s', time()+48*60*60);
            
            $this->savesearch_m->save($data);
            
            $this->data['message'] = __('Search saved!', 'sw_win');
            $this->data['success'] = true;
        }
        // Error message
        else
        {
            $this->data['message'] = __('Search already saved!', 'sw_win');
            $this->data['success'] = true;
        }

        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
        
    public function getallcounters($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        $this->data['success'] = false;
        $this->data['message'] = __('No message returned!', 'sw_win');
        $this->load->model('listing_m');
        
        $post = $_POST;
        if(isset($post['form_id']))
            $form_id = $post['form_id'];
        else 
            $form_id = 1;
        
        $this->load->model('searchform_m');  
        $form = $this->searchform_m->get($form_id);
        $fields_value_json_1 = $form->fields_order;
        $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);
    
        $obj_widgets = json_decode($fields_value_json_1);
        
        $all_ids = array();
        if(is_object($obj_widgets->PRIMARY))
        {
            foreach($obj_widgets->PRIMARY as $key=>$obj)
            {
                if($obj->type == 'CHECKBOX')
                {
                    if(!isset($post['search_'.$obj->id]) || $post['search_'.$obj->id] !=1)
                        $all_ids[$obj->id] = $obj->id;
                }
            }
        }
        if(is_object($obj_widgets->SECONDARY))
        {
            foreach($obj_widgets->SECONDARY as $key=>$obj)
            {
                if($obj->type == 'CHECKBOX')
                {
                    if(!isset($post['search_'.$obj->id]) || $post['search_'.$obj->id] !=1)
                        $all_ids[$obj->id] = $obj->id;
                }
            }
        }
        
        $current_lang_id = sw_current_language_id();
        /* get counters per id */
        foreach ($all_ids as $key => $value) {
            $_POST['search_'.$value] = 1;
            prepare_frontend_search_query_GET();
            $all_ids[$key] = $this->listing_m->total_lang(array(), $current_lang_id);
            unset($_POST['search_'.$value]);
        }
        
        $this->data['counters'] = $all_ids;
        $this->data['success'] = true;
        
        $ajax_output['counters'] = $this->data['counters'];
        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        $json_output = json_encode($ajax_output);
    
        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        exit();
    }

    
    public function subscribe($output="", $atts=array(), $instance=NULL)
    {
        if(!function_exists('sw_mailchimp_post'))
            exit('Mailchimp post missing');

        $ajax_output = array();
        
        $this->data['message'] = __('No message returned!', 'sw_win');
        $this->data['parameters'] = $_POST;
        $this->data['success'] = false;

        if(empty($this->data['parameters']['subscriber_api_key']) && empty($this->data['parameters']['subscriber_lsit_id'])) {
            $this->data['message'] = __('Subscribe API not configured, please contact with administrator','sw_win'); 
        }
        else if( filter_var($this->data['parameters']['subscriber_email'], FILTER_VALIDATE_EMAIL) &&
            is_email($this->data['parameters']['subscriber_email']) ){
        
        $data = [
            'email'     => $this->data['parameters']['subscriber_email'],
            'status'    => 'subscribed',
        ];
        
        $apiKey = $this->data['parameters']['subscriber_api_key'];
        $listId = $this->data['parameters']['subscriber_lsit_id'];

        $memberId = md5(strtolower($data['email']));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
        
        $json = json_encode([
            'apikey' => $apiKey,
            'email_address' => $data['email'],
            'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
        ]);

        $httpCode=0;
        $result = sw_mailchimp_post($url, $apiKey, $httpCode, $json);
        if($httpCode == 200) {
            $this->data['success'] = true;
            $this->data['message'] = __('Your e-mail','sw_win').' '. $_POST['subscriber_email'] .' '.__(' has been added to our mailing list!','sw_win'); 
        } else {
             $this->data['message'] = __('There was a problem with your e-mail','sw_win').' '.$this->data['parameters']['subscriber_email']; 
        }
        
        } else {
           $this->data['message'] = __('There was a problem with your e-mail','sw_win').' '.$this->data['parameters']['subscriber_email']; 
        }
        
        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
        
    public function submitsmile($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        
        $this->data['message'] = __('No message returned!', 'sw_win');
        $this->data['parameters'] = $_POST;
        $review_id = $this->input->post('review_id');
        $review_type = $this->input->post('review_type');

        $this->load->model('review_m');
        $this->load->library('session');
        
        $this->data['success'] = false;
        
        $user_id = get_current_user_id();
        $data_sess = array();
        $data_sess['voted_reviews'] = $this->session->userdata('voted_reviews');
        
        if($user_id == 0)
        {
            $this->data['message'] = __('Please login for vote', 'sw_win');
        }
        // Check if already voted, stop and write message
        else if(isset($data_sess['voted_reviews'][$review_id]))
        {
            $this->data['message'] = __('You already voted on this review!', 'sw_win');
        }
        // Update counter to database
        else
        {
            $this->review_m->update_counter($review_id, $review_type);
            
            $this->data['message'] = __('Thanks for your vote', 'sw_win');
            $this->data['success'] = true;
            
            $data_sess = array();
            $data_sess['voted_reviews'] = $this->session->userdata('voted_reviews');
            $data_sess['voted_reviews'][$review_id] = true;
            $this->session->set_userdata($data_sess);
            
        }
        
        

        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
        
    public function login($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        
        $this->data['message'] = '';
        $this->data['parameters'] = $_POST;
        $error='';
        $redirect=false;

        $this->data['success'] = false;
        
        $user_id = get_current_user_id();
        
        if(!empty($user_id))
        {
            $this->data['message'] = __('You already logged', 'sw_win');
        } else {
            $this->load->model('user_m');

            $rules = $this->user_m->form_login;

            $this->form_validation->set_rules($rules);

            if($this->form_validation->run() == TRUE)
            {

                $data = $this->user_m->array_from_post($this->user_m->get_post_from_rules($rules));

                $credentials = array('user_login' => $data['username'],
                                     'user_password' => $data['password'],
                                     'remember' => true);

                $ret = wp_signon($credentials);

                $_POST = array();
                $_POST['updated'] = 'true';
                $_POST['widget_id'] = 'login';

                if(get_class($ret) == 'WP_Error')
                {
                    $_POST['updated'] = 'false';
                    $error .= '<p class="alert alert-danger">'. esc_html('That email/password combination does not exist', 'sw_win').'</p>';
                }
                else
                {
                    /* login success */
                    $this->data['success'] = true;
                    
                }

                //dump($ret);
            } else {
                $error .= validation_errors();
            }
        }
        
       

        $ajax_output['redirect'] = $redirect;
        $ajax_output['errors'] = $error;
        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }

    public function privatemessage($output="", $atts=array(), $instance=NULL)
    {
        $ajax_output = array();
        
        $this->data['message'] = '';
        $this->data['parameters'] = $_POST;
        
        $error='';
        $redirect=false;
        $this->data['success'] = false;
        
        $this->load->model('inquiry_m');

        $this->data = array_merge($this->data, $atts);
        
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
        if(empty($user_id_sender))
            $user_id_sender = NULL;
        if(isset($this->data['parameters']['page_message']) && $this->data['parameters']['page_message']=='listing_preview_page')
        {
            $this->data['listing_id_slug'] = get_query_var( 'slug' );
            
            if(is_numeric($this->data['listing_id_slug']))
                $conditions = array('search_idlisting'=>$this->data['listing_id_slug'], 'search_is_activated'=>1);
            else
            {
                $this->load->model('slug_m');
                $table_id = $this->slug_m->getid($this->data['listing_id_slug']);
    
                $conditions = array('search_idlisting'=>$table_id, 'search_is_activated'=>1);
            }
    
            prepare_frontend_search_query_GET('listing_m', $conditions);
            $listings = $this->listing_m->get_pagination_lang(1, 0, sw_current_language_id());
            
            if(empty($listings))
            {
                echo __('Listing not found', 'sw_win');
                return;
            }
    
            $this->data['listing'] = $listings[0];
            $listing_id = $this->data['parameters']['idlisting'];
            
            $agents = $this->listing_m->get_agents($listing_id);
            
            //dump($this->data['listing']);
            
            if(sw_count($agents) > 0)
                $this->data['receiver_email']=$agents;
        }        
        // [/if listing page, then send to agent/owner email]
        
        // [if user profile page, then send to agent/owner email]
        if(isset($this->data['parameters']['page_message']) && $this->data['parameters']['page_message']=='user_profile_page')
        {
            $this->data['user_id_slug'] = get_query_var( 'slug' );

            if(is_numeric($this->data['user_id_slug']))
                $conditions = array('ID'=>$this->data['user_id_slug']);
            else
                $conditions = array('user_nicename'=>$this->data['user_id_slug']);

            $user = $this->user_m->get_by($conditions, TRUE);

            if(!empty($user))
                $this->data['receiver_email']=$user->user_email;
        }
        // [/if user profile page, then send to agent/owner email]
       
        $rules = $this->inquiry_m->form_widget;
        
        $recaptcha_site_key = sw_settings('recaptcha_site_key');
        if(!empty($recaptcha_site_key))
            $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>__('Recaptcha', 'sw_win'), 
                                                    'rules'=>'trim|required|callback__captcha_check');
        
        $this->form_validation->set_rules($rules);

        if($this->form_validation->run() == TRUE)
        {

            $data = $this->inquiry_m->array_from_post($this->inquiry_m->get_post_from_rules($rules));

            unset($data['g-recaptcha-response']);

            $data_db = array();
            $data_db['date_sent'] = date('Y-m-d H:i:s');
            $data_db['json_object'] = json_encode($data);
            $data_db['email_sender'] = $data['email'];
            $data_db['message'] = $data['message'];
            $data_db['email_receiver'] = $this->data['receiver_email'];
            $data_db['listing_id'] = $listing_id;
            $data_db['user_id_sender'] = $user_id_sender;
            $data_db['user_id_receiver'] = $user_id_receiver;

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

                    $id = $this->inquiry_m->save($data_db, NULL);

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
                $id = $this->inquiry_m->save($data_db, NULL);

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
            $this->data['success'] = true;
            $this->data['message'] = __('Thanks message sent', 'sw_win');
            //dump($ret);
        } else {
            $error .= validation_errors();
        }

       

        $ajax_output['redirect'] = $redirect;
        $ajax_output['errors'] = $error;
        $ajax_output['success'] = $this->data['success'];
        $ajax_output['message'] = $this->data['message'];
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
    function messagelive($output="", $atts=array(), $instance=NULL) {
        $ajax_output = array();
        $ajax_output['success'] = false;
        $error ='';
        $this->load->model('messages_m');
        $post = $_POST;
        $function = $post['function'];
        unset($post['function']);
        unset($_POST['function']);

        $log = array();

        switch($function) {

            case('notification'):
                $where = array();
                $where['date_sent > '] = date('Y-m-d H:i:s', time() - 30);
                $where['user_id_receiver'] = get_current_user_id();
                $where['is_readed'] = 0;
                
                $messages = $this->messages_m->get_by($where);
                
                $this->load->model('inquiry_m');
                $this->load->model('listing_m');
                $this->load->model('user_m');
                
                /* add messages from inquiry_m */
                $where = array();
                $where['date_sent > '] = date('Y-m-d H:i:s', time() - 28);
                $where['user_id_receiver'] = get_current_user_id();
                $where['lang_id'] = sw_current_language_id();
                
                $inquirys = $this->inquiry_m->get_by($where);
                
                foreach ($inquirys as $value) {
                    $message = new stdClass();
                    $message->{'image_url'} ='';
                    $message->{'title'} ='';
                    $message->{'second_title'} ='';
                    $message->{'link'} ='';
                    $message->{'message'} = $value->message;
                    $message->{'related_key'} = 'inquiry_'.$value->idinquiry;
                    $message->{'date_sent'} = $value->date_sent;
                    $message->{'user_id_sender'} = $value->user_id_sender;
                    $message->{'user_id_receiver'} = $value->user_id_receiver;
                    $message->{'email_sender'} = $value->email_sender;
                    $message->{'email_receiver'} = $value->email_receiver;
                    $message->{'is_readed'} = $value->is_readed;
                    
                    $messages[] = $message;
                }
                /* end messages from inquiry_m */
                
                foreach ($messages as $key => $value) {
                    $messages[$key]->{'image_url'} = '';
                    $messages[$key]->{'title'} = '';
                    $messages[$key]->{'second_title'} = '';
                    $messages[$key]->{'link'} = '';
                    $messages[$key]->{'message'} = sw_character_limiter(strip_tags($messages[$key]->{'message'}), 50);
                    
                    if(empty($value->related_key)) continue;
                    
                    if(strpos($value->related_key, 'inquiry_') !== FALSE) {
                        $inquiry_id = str_replace('inquiry_', '', $value->related_key);
                        $inquiry = $this->inquiry_m->get($inquiry_id);
                        if(sw_user_in_role('administrator'))
                            $messages[$key]->{'link'} = admin_url('admin.php?page=listing_messages&function=editmessage&id='.$inquiry_id);
                        else
                            $messages[$key]->{'link'} = admin_url('admin.php?page=ownlisting_messages&function=editmessage&id='.$inquiry_id);
                            
                        if($inquiry && $value->user_id_sender) {
                            $user = $this->user_m->get($value->user_id_sender);
                            if($user)  {
                                $messages[$key]->{'image_url'} =  sw_profile_image($user, 120);
                                $messages[$key]->{'title'} =  $user->display_name;;
                            }
                        }
                        
                        if($inquiry && !empty($inquiry->listing_id)) {
                            $listing = $this->listing_m->get_lang($inquiry->listing_id, sw_default_language_id());
                            if($listing) {
                                if(isset($listing->{'input_10_'.sw_default_language_id()})) {
                                    $messages[$key]->{'second_title'} =  $messages[$key]->{'title'};
                                    $messages[$key]->{'title'} = $listing->{'input_10_'.sw_default_language_id()};
                                }
                                
                                if(!empty($listing->image_filename)){
                                    $messages[$key]->{'image_url'} =  _show_img($listing->image_filename, '520x330', false);
                                }    
                            }
                        } 
                        $this->messages_m->read_related( $value->related_key);
                    }
                    $messages[$key]->{'title'} = sw_character_limiter(strip_tags($messages[$key]->{'title'}), 18);
                }
                
                $ajax_output['messages'] = $messages;
                $ajax_output['success'] = true;
                break;

            case('update'):
                if(!isset($post['last_message_id'])|| empty($post['last_message_id'])) $post['last_message_id'] = 0;
                $messages = $this->messages_m->get_related($post['related_key'], array('idmessages >' => $post['last_message_id']));
                $this->messages_m->generete_list_messages($messages);
                $messages = array_reverse($messages);
                
                if(!empty($messages)) {
                    $this->messages_m->read_related($post['related_key']);
                }
                
                $ajax_output['messages'] = $messages;
                $ajax_output['success'] = true;
                break;

            case('send'):

                $rules = $this->messages_m->form_expansion;
                $error ='';

                $this->form_validation->set_rules($rules);
                if($this->form_validation->run() == TRUE)
                {
                    $data = $this->messages_m->array_from_post($this->messages_m->get_post_from_rules($rules));
                    $data['user_id_sender']= get_current_user_id();
                    $id = $this->messages_m->save($data);
                    if($id)
                        $ajax_output['success'] = true;
                    
                    // Email the user
                    if(isset($post['related_key']) && isset($post['email_receiver'])){
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        $headers[] = 'From: '.get_bloginfo('admin_email');

                        $inquiry_id = str_replace('inquiry_', '', $post['related_key']);
                        if(sw_user_in_role('administrator'))
                            $link = admin_url('admin.php?page=listing_messages&function=editmessage&id='.$inquiry_id);
                        else
                            $link = admin_url('admin.php?page=ownlisting_messages&function=editmessage&id='.$inquiry_id);


                        $subject = __('You received new message', 'sw_win');
                        $message = __('You received new message', 'sw_win').': '.$link;

                        $ret = wp_mail( $data['email_receiver'], $subject, $message, $headers );
                    }
                } else {
                    $error .= validation_errors();
                }
                break;
        }

        
        $ajax_output['errors'] = $error;
        
        $json_output = json_encode($ajax_output);

        //$length = mb_strlen($json_output);
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache');
        header('Content-Type: application/json; charset=utf8');
        //header('Content-Length: '.$length); // special characters causing troubles

        echo $json_output;
        
        exit();
    }
    
}
