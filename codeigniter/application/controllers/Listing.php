<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listing extends My_Controller {

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
		$this->data['subview'] = 'admin/listing/manage';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function addlisting($id=NULL)
	{
        $this->data['agents'] = array();
        $this->data['locations'] = array();
        $this->data['categories'] = array();

        // Get parameters
        $id = $this->input->get('id');
       
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
            
            // TODO: Remove, just fore test purposes
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
        
        $rules = $this->listing_m->form_admin;
        $rules_lang = $this->listing_m->rules_lang;
        
        $this->form_validation->set_rules(array_merge($rules, $rules_lang));
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->listing_m->array_from_post($this->listing_m->get_post_from_rules($rules));
            $data_lang = $this->listing_m->array_from_post($this->listing_m->get_lang_post_fields());
            
            $id = $this->listing_m->save_with_lang($data, $data_lang, $id);
            
            wp_redirect(admin_url("admin.php?page=listing_addlisting&id=$id&updated=true")); exit;
        }
        
        
        
        // Load view
		$this->data['subview'] = 'admin/listing/addlisting';
        $this->load->view('admin/_layout_main', $this->data);
	}
        
	public function clonelisting($listing_id=NULL)
	{
        $this->data['agents'] = array();
        // Get parameters
        $listing_id = $this->input->get('listing_id');
        
        // If limit reached, error/warning!
        if(empty($listing_id)){
            exit(__('Listing can`t clone, listing missing', 'sw_win'));
        }
        /* fetch data */
        $listing_data = $this->listing_m->get_lang($listing_id, sw_default_language_id());
                
        if(empty($listing_data)){
            exit(__('Listing can`t clone, listing missing', 'sw_win'));
        }
        
        $id = NULL;
        /* end fetch data */
       
        $rules = $this->listing_m->form_admin;
        $rules_lang = $this->listing_m->rules_lang;
        
        // Process
        $data = array();
        foreach ($rules as $key => $value) {
            if(isset($listing_data->$key))
                $data[$key] = $listing_data->$key;
        }
        // Create new repository
        $repository_id = $this->repository_m->save(array('model_name'=>'listing_m'));
        $data_lang['repository_id'] = $repository_id;
        // Process the form
        foreach ($rules_lang as $key => $value) {
            if(isset($listing_data->$key))
                $data_lang[$key] = $listing_data->$key;
        }
        
        $id = $this->listing_m->save_with_lang($data, $data_lang, $id);
        wp_redirect(admin_url("admin.php?page=listing_addlisting&id=$id&updated=true")); exit;
        
        // Load view
		$this->data['subview'] = 'admin/listing/addlisting';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remlisting($id = NULL, $redirect='1')
	{   
        // Get parameters
        $id = $this->input->get('id');
        
        if(is_numeric($id))
            $this->listing_m->delete($id);
	}
    
	public function fields()
	{
        // Fetch all fields
        $this->data['fields_nested'] = $this->field_m->get_nested(sw_current_language_id());
        
        //dump($this->data['fields_nested']);
        
        // Load view
		$this->data['subview'] = 'admin/listing/fields';
        $this->load->view('admin/_layout_main', $this->data);
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
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
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
            $row->edit = btn_edit(admin_url("admin.php?page=listing_addlisting&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=listing_manage&function=remlisting&id=".$row->{"id$controller"}));
            
            if($row->is_activated==0)
                $row->{"id$controller"} .= ' <span class="label label-danger">'.__("Not activated", "sw_win").'</span>';

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
    
    public function updateajax()
    {
        // Save order from ajax call
        if(isset($_POST['sortable']) && $this->config->item('app_type') != 'demo')
        {
            $this->field_m->save_order($_POST['sortable']);
        }
        
        $data = array();
        $length = strlen(json_encode($data));
        header('Content-Type: application/json; charset=utf8');
        header('Content-Length: '.$length);
        echo json_encode($data);
        
        exit();
    }
    
	public function addfield($id=NULL)
	{
        // Get parameters
        $id = $this->input->get('id');
        
        $this->data['make_searchable_visible'] = false;
        
        // Set up the form
        if(empty($id))
        {

        }
        else
        {
            $this->data['form_object'] = $this->field_m->get_lang($id);
            
            // Fetch file repository
            $repository_id = $this->data['form_object']->repository_id;
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('model_name'=>'field_m'));
                
                // Update page with new repository_id
                $this->field_m->save(array('repository_id'=>$repository_id), $this->data['form_object']->idfield);
            }
            
            if($this->data['form_object']->type == 'INPUTBOX' ||
               $this->data['form_object']->type == 'DROPDOWN')
            {
                if(!$this->db->field_exists('field_'.$id, 'sw_listing_lang'))
                    $this->data['make_searchable_visible'] = true;
            }
            else if($this->data['form_object']->type == 'INTEGER' ||
                $this->data['form_object']->type == 'DATETIME')
            {
                if(!$this->db->field_exists('field_'.$id.'_int', 'sw_listing_lang'))
                    $this->data['make_searchable_visible'] = true;
            }
            else if($this->data['form_object']->type == 'CHECKBOX')
            {
                // TODO: currently works but better performance possible
            } 
            
            
            
        }

        $this->data['fields_no_parents'] = $this->field_m->get_no_parents(1, $id);

        $rules = $this->field_m->form_admin;
        $rules_lang = $this->field_m->rules_lang;
        
        $this->form_validation->set_rules(array_merge($rules, $rules_lang));
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->field_m->array_from_post($this->field_m->get_post_from_rules($rules));
            
            if($id == NULL)
            {
                $parent_id = $this->input->post('parent_id');
                $data['order'] = $this->field_m->max_order($parent_id);
            }
            
            if(isset($data['make_searchable']))
            {
                // Add specific columns for search numerical from/to
                if($data['type'] == 'INPUTBOX' ||
                   $this->data['form_object']->type == 'DROPDOWN')
                {
                    if(!$this->db->field_exists('field_'.$id, 'sw_listing_lang'))
                    {
                        $ret = $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."sw_listing_lang` ADD `field_".$id."` VARCHAR(200) NULL ;");
                    }
                }
                else if($data['type'] == 'INTEGER' ||
                        $this->data['form_object']->type == 'DATETIME' )
                {
                    if(!$this->db->field_exists('field_'.$id.'_int', 'sw_listing_lang'))
                    {
                        $this->db->query("ALTER TABLE `".$GLOBALS['table_prefix']."sw_listing_lang` ADD `field_".$id."_int` INT(11) NULL ;");
                    }
                } 
                else if($data['type'] == 'CHECKBOX')
                {
                    // TODO: currently works but better performance possible
                } 
            }
            
            unset($data['make_searchable']);
            
            $data_lang = $this->field_m->array_from_post($this->field_m->get_lang_post_fields());
            $id = $this->field_m->save_with_lang($data, $data_lang, $id);
            
            wp_redirect(admin_url("admin.php?page=listing_addfield&id=$id&updated=true")); exit;
        }
        
        
        // Load view
		$this->data['subview'] = 'admin/listing/addfield';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function searchform($id=1)
	{
	   $this->load->model('searchform_m');
       
        $this->fields = $this->field_m->get_fields(sw_current_language_id());
        
        $this->data['form_object'] = $this->searchform_m->get($id);
        
        if(empty($this->data['form_object']))$id=NULL;
       
        $rules = $this->searchform_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            
            $data = $this->searchform_m->array_from_post($this->field_m->get_post_from_rules($rules));

            $id = $this->searchform_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=listing_searchform&updated=true")); exit;
        }
       
        // Load view
		$this->data['subview'] = 'admin/listing/searchform';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
	public function resultitem($id=2)
	{
	   $this->load->model('searchform_m');
       
        $this->fields = $this->field_m->get_fields(sw_current_language_id());
        
        $this->data['form_object'] = $this->searchform_m->get($id);
        
        if(empty($this->data['form_object']))$id=NULL;
       
        $rules = $this->searchform_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            
            $data = $this->searchform_m->array_from_post($this->field_m->get_post_from_rules($rules));

            $id = $this->searchform_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=listing_resultitem&updated=true")); exit;
        }
       
        // Load view
		$this->data['subview'] = 'admin/listing/resultitem';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remfield($id=NULL)
	{
        // Get parameters
        $id = $this->input->get('id');
        
        if($this->field_m->check_deletable($id))
        {
            $this->field_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=listing_fields&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
    public function favorites()
	{
        // Fetch all results
        $this->data['results'] = array();

        // Load view
		$this->data['subview'] = 'admin/listing/favorites';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    // json for datatables
    public function datatablefavorites()
    {
        
        
        // configuration
        $columns = sw_favorites_columns();
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
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
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
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=listing_favorites&function=remfavorite&id=".$row->{"id$controller"}));
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
        
        if($this->favorite_m->check_deletable($id))
        {
            $this->favorite_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=listing_favorites&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
    public function reviews()
	{
        // Fetch all results
        $this->data['results'] = array();

        // Load view
		$this->data['subview'] = 'admin/listing/reviews';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    // json for datatables
    public function datatablereviews()
    {
        
        
        // configuration
        $columns = sw_reviews_columns();
        $controller = 'review';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $this->load->model($controller.'_m');
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
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
            
            $row->edit = btn_edit(admin_url("admin.php?page=listing_reviews&function=editreview&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=listing_reviews&function=remreview&id=".$row->{"id$controller"}));
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
    
	public function editreview($id=NULL)
	{
        $this->load->model('review_m');
       
        // Get parameters
        $id = $this->input->get('id');
       
        // Set up the form
        if(empty($id))
        {
            exit('Missing ID');
        }
        else
        {
            $this->data['form_object'] = $this->review_m->get($id);
        }
        
        $rules = $this->review_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->review_m->array_from_post($this->review_m->get_post_from_rules($rules));
            
            if( $id === NULL )
            {
                
            }
            
            $id = $this->review_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=listing_reviews&function=editreview&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'admin/listing/editreview';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remreview($id=NULL)
	{
	    $this->load->model('review_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        if($this->review_m->check_deletable($id))
        {
            $this->review_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=listing_reviews&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
	public function messages()
	{
        // Fetch all results
        $this->data['results'] = array();

        // Load view
		$this->data['subview'] = 'admin/listing/messages';
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
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
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
            
            $row->edit = btn_read(admin_url("admin.php?page=listing_messages&function=editmessage&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=listing_messages&function=remmessage&id=".$row->{"id$controller"}));
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
                $this->messages_m->read_related("inquiry_".$this->data['form_object']->idinquiry);
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
            
            wp_redirect(admin_url("admin.php?page=listing_messages&function=editmessage&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'admin/listing/editmessage';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remmessage($id=NULL)
	{
	    $this->load->model('inquiry_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        if($this->inquiry_m->check_deletable($id))
        {
            $this->inquiry_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=listing_messages&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
	public function reports()
	{
        // Fetch all results
        $this->data['results'] = array();

        // Load view
		$this->data['subview'] = 'admin/listing/reports';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    // json for datatables
    public function datatablereports()
    {
        
        
        // configuration
        $columns = array('idreport', 'name','date_submit', 'email', 'message', 'sw_report.listing_id', 'field_10');
        $controller = 'report';
        
        // Fetch parameters
        $parameters = $this->input->post();
        $draw = $this->input->post_get('draw');
        $start = $this->input->post_get('start');
        $length = $this->input->post_get('length');
        $search = $this->input->post_get('search');

        if(isset($search['value']))
            $parameters['searck_tag'] = $search['value'];
        
        $this->load->model($controller.'_m');
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
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
            
            $row->edit = btn_read(admin_url("admin.php?page=listing_reports&function=editreport&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=listing_reports&function=remreport&id=".$row->{"id$controller"}));
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
    
	public function editreport($id=NULL)
	{
        $this->load->model('report_m');
       
        // Get parameters
        $id = $this->input->get('id');
       
        // Set up the form
        if(empty($id))
        {
            exit('Missing ID');
        }
        else
        {
            $this->data['form_object'] = $this->report_m->get($id);
        }
        
        $rules = $this->report_m->form_admin;
        
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            
            $data = $this->report_m->array_from_post($this->report_m->get_post_from_rules($rules));
            
            if($id == NULL)
            {
            }
            
            $id = $this->report_m->save($data, $id);
            
            wp_redirect(admin_url("admin.php?page=listing_reports&function=editreport&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'admin/listing/editreport';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function remreport($id=NULL)
	{
	    $this->load->model('report_m');
       
        // Get parameters
        $id = $this->input->get('id');
        
        if($this->report_m->check_deletable($id))
        {
            $this->report_m->delete($id);
            
            wp_redirect(admin_url("admin.php?page=listing_reports&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
	public function savesearch()
	{
        // Fetch all results
        $this->data['results'] = array();

        // Load view
		$this->data['subview'] = 'admin/listing/savesearch';
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
        
        $recordsTotal = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $recordsFiltered = $this->{$controller.'_m'}->total_lang(array(), sw_current_language_id());
        
        prepare_search_query_GET($columns, $controller.'_m');
        $data = $this->{$controller.'_m'}->get_pagination_lang($length, $start, sw_current_language_id());
        
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
            
            $row->edit = btn_edit(admin_url("admin.php?page=listing_savesearch&function=editsavesearch&id=".$row->{"id$controller"}));
            $row->delete = btn_delete_noconfirm(admin_url("admin.php?page=listing_savesearch&function=remsavesearch&id=".$row->{"id$controller"}));
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
            
            wp_redirect(admin_url("admin.php?page=listing_savesearch&function=editsavesearch&id=$id&updated=true")); exit;
        }
        
        // Load view
		$this->data['subview'] = 'admin/listing/editsavesearch';
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
            
            wp_redirect(admin_url("admin.php?page=listing_savesearch&updated=true")); exit;
        }
        
        echo __('Function disabled', 'sw_win');
    }
    
	public function settings($id=1)
	{
        $this->load->model('settings_m');

        $this->data['form_object'] = (object) $this->settings_m->get_fields();
        
        $rules = $this->settings_m->form_index;
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            // save data
            $data = $this->settings_m->array_from_rules($rules);

            $this->settings_m->save_settings($data);
            
            // reload and show message
            wp_redirect(admin_url("admin.php?page=listing_settings&updated=true")); exit;
            
//            $this->session->set_flashdata('message', lang_check('Changes saved'));
//            c_redirect('admin/settings/index/');
        }
       
        // Load view
		$this->data['subview'] = 'admin/listing/settings';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
	public function remove_all_listings($id=1)
	{
            
            //remove all values from current
            $this->load->model('listing_m');
            $listings = $this->listing_m->get();
            
            foreach( $listings as $listing ) {
                $this->listing_m->delete($listing->idlisting);
            }
            
            $sw_customposts = get_posts( array( 'post_type' => 'swlistings', 'numberposts' => -1));
            foreach( $sw_customposts as $post ) {
                // Delete's each post.
                wp_delete_post( $post->ID, true);
                // Set to False if you want to send them to Trash.
            }
            

            if ($handle = opendir(sw_win_upload_path().'files/strict_cache/')) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        @unlink(sw_win_upload_path().'files/strict_cache/'.$entry);
                    }
                }
                closedir($handle);
            }
            
            wp_redirect(admin_url("admin.php?page=listing_settings&updated=true&remove_all_listings=true"));
            exit;
        
    }
    
	public function remove_all_cache_images($id=1)
	{
            
            if ($handle = opendir(sw_win_upload_path().'files/strict_cache/')) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        @unlink(sw_win_upload_path().'files/strict_cache/'.$entry);
                    }
                }
                closedir($handle);
            }
            
            wp_redirect(admin_url("admin.php?page=listing_settings&updated=true&remove_all_cache_images=true"));
            exit;
        
    }
    

    public function copy_to_all_languages($id=1)
    {

        $this->load->model('field_m');
        $this->load->model('listing_m');
        $this->load->model('treefield_m');
        $this->load->model('slug_m');
        $message = '';
        
        $lang_id_default = sw_default_language_id();
        
        $sw_current_default_lang_id =  sw_default_language_id();
        $sw_test_exists_lang_id = $this->field_m->get_field_list($sw_current_default_lang_id);
        $sw_test_exists_lang_id_basic = $this->field_m->get_field_list(1);
        
        
        /* if need copy from deprecated lang_id = 1 */
        if($sw_current_default_lang_id != 1 && sw_count($sw_test_exists_lang_id_basic)>0 && sw_count($sw_test_exists_lang_id) == 0)
            $lang_id_default = 1;
        
        if(sw_count($sw_test_exists_lang_id_basic)==0 && sw_count($sw_test_exists_lang_id) == 0) {
            $message = str_replace(" ", '+', __('So nothing to copy. Default language, should have values', 'sw_win'));
            wp_redirect(admin_url("admin.php?page=listing_settings&updated=true&copy_to_all_languages=true&message=".$message));
            exit;
        }
        
                
        if($this->listing_m->total_lang(array(), $lang_id_default) > 200) {
            $message = str_replace(" ", '+', __('Max 200 listings for copy', 'sw_win'));
            wp_redirect(admin_url("admin.php?page=listing_settings&updated=true&copy_to_all_languages=true&message=".$message));
            exit;
        }
        
        $langs = sw_get_languages();
        $note_translated = array();
        foreach ($langs as $key => $lang) {
            if($lang['id'] == $lang_id_default) continue;
            $fields_lang = $this->field_m->get_field_list($lang['id']);
            $listings_lang = $this->listing_m->total_lang(array(), $lang['id']);
            $treefield_lang = $this->treefield_m->get_treefield($lang['id']); /* first value is Root, predefined */
            $slug_lang = $this->slug_m->get_by(array('lang_id'=>$lang['id']));
            
            if(empty($fields_lang) && empty($listings_lang) && sw_count($treefield_lang)==1 && sw_count($slug_lang)==0)
                $note_translated[]=$lang['id'];
        }
        
        /* all langs have some fields already translated */
        if(empty($note_translated)) {
            /* message */
            $message = str_replace(" ", '+', __('Something already translated so copy skipped', 'sw_win'));
        } else {
            /* start table fields_lang */
            $default_fields = $this->field_m->get_fields($lang_id_default);
            $basic_data = $this->field_m->form_admin;
            $basic_data_lang = $this->field_m->rules_lang;
            foreach ($default_fields as $key => $field) {
                $field_lang = $this->field_m->get_lang($field->idfield, $lang_id_default);
               
                /* fetch and generate default field data */
                $data = array();
                foreach ($basic_data as $k => $value) {
                    if(isset($field_lang->$k))
                        $data[$k] = $field_lang->$k;
                }

                /* fetch and generate translate field data */
                $data_lang = array();
                foreach ($basic_data_lang as $k => $value) {
                    $pos = strrpos($k, '_');
                    $field_lang_id = substr($k,$pos+1);
                    $field_lang_name = substr($k,0,$pos);

                    if(isset($field_lang->{$field_lang_name."_".$lang_id_default})){
                        if($field_lang_id == $lang_id_default){
                            /* default lang lang */
                            $data_lang[$k] = $field_lang->$k;
                        } elseif(in_array($field_lang_id, $note_translated)) {
                            /* new note translated lang */
                            $data_lang[$k] = $field_lang->{$field_lang_name."_".$lang_id_default};
                        } else {
                            /* other already translated langs and not default */
                            $data_lang[$k] = $field_lang->$k;
                        }
                    }
                }
                /* save */
                $id = $this->field_m->save_with_lang($data, $data_lang, $field->idfield);
            }
            /* end table fields_lang */
            
            /* start treefield */
            $treefields = $this->treefield_m->get_all_list(NULL, NULL, $lang_id_default);
            

            $basic_data = $this->treefield_m->form_admin;
            $basic_data_lang = $this->treefield_m->rules_lang;
            foreach ($treefields as $key => $treefield) {
                $treefield_lang = $this->treefield_m->get_lang($treefield->idtreefield);
                
                /* fetch and generate default field data */
                $data = array();
                foreach ($basic_data as $k => $value) {
                    if(isset($treefield_lang->$k))
                        $data[$k] = $treefield_lang->$k;
                }

                /* fetch and generate translate field data */
                $data_lang = array();
                foreach ($basic_data_lang as $k => $value) {
                    $pos = strrpos($k, '_');
                    $treefield_lang_id = substr($k,$pos+1);
                    $treefield_lang_name = substr($k,0,$pos);
                    
                    if(isset($treefield_lang->{$treefield_lang_name."_".$lang_id_default})){
                        if($treefield_lang_id == $lang_id_default){
                            /* default lang lang */
                            $data_lang[$k] = $treefield_lang->$k;
                        } elseif(in_array($treefield_lang_id, $note_translated)) {
                            /* new note translated lang */
                            $data_lang[$k] = $treefield_lang->{$treefield_lang_name."_".$lang_id_default};
                        } else {
                            /* other already translated langs and not default */
                            $data_lang[$k] = $treefield_lang->$k;
                        }
                    }
                }
                
                /* save */
                $id = $this->treefield_m->save_with_lang($data, $data_lang, $treefield->idtreefield);
            }
            /* end treefield */
            
            /* start listings */
            $listings = $this->listing_m->get_pagination_lang(FALSE, FALSE, $lang_id_default);
            $basic_data = $this->listing_m->form_admin;
            $basic_data_lang = array();
            $this->fields_list = $this->field_m->get_field_list(sw_default_language_id());
            foreach(sw_get_languages() as $key=>$lang)
            {
                foreach($this->fields_list as $key_field=>$field)
                {
                    $basic_data_lang['input_'.$field->idfield.'_'.$key] = array('field'=>'input_'.$field->idfield.'_'.$key, 'label'=>$field->field_name, 'rules'=>'trim');

                    if($field->is_required == '1' && 
                      ($field->is_translatable || sw_default_language() == $lang['lang_code']) &&
                      ( (sw_settings('multilanguage_required') && !sw_is_page(sw_settings('quick_submission'))) || sw_default_language() == $lang['lang_code'])
                      )
                    {
                        $basic_data_lang['input_'.$field->idfield.'_'.$key]['rules'].='|required';
                    }

                    if(is_numeric($field->max_length))
                    {
                        $basic_data_lang['input_'.$field->idfield.'_'.$key]['rules'].='|max_length['.$field->max_length.']';
                    }

                }
                $basic_data_lang['input_slug_'.$key] = array('field'=>'input_slug_'.$key, 'label'=>__('Slug', 'sw_win'), 'rules'=>'trim');
            }
            
            foreach ($listings as $key => $listing) {
                $listing_lang = $this->listing_m->get_lang($listing->idlisting,$lang_id_default);
         
                /* fetch and generate default field data */
                $data = array();
                foreach ($basic_data as $k => $value) {
                    if(isset($listing_lang->$k))
                        $data[$k] = $listing_lang->$k;
                }

                /* fetch and generate translate field data */
                $data_lang = array();
                foreach ($basic_data_lang as $k => $value) {
                    $pos = strrpos($k, '_');
                    $listing_lang_id = substr($k,$pos+1);
                    $listing_lang_name = substr($k,0,$pos);
                    
                    
                    if(isset($listing_lang->{$listing_lang_name."_".$lang_id_default})){
                        if($listing_lang_id == $lang_id_default){
                            /* default lang lang */
                            $data_lang[$k] = $listing_lang->$k;
                        } elseif(in_array($listing_lang_id, $note_translated)) {
                            /* slug */
                            /* skip copy if field is slug, will be quto generated in $this->listing_m->save_with_lang */
                            if($listing_lang_name == 'input_slug') continue;
                            /* end slug */
                            /* new note translated lang */
                            $data_lang[$k] = $listing_lang->{$listing_lang_name."_".$lang_id_default};
                        } else {
                            /* other already translated langs and not default */
                            $data_lang[$k] = $listing_lang->$k;
                        }
                    }
                }
                /* save */
                $id = $this->listing_m->save_with_lang($data, $data_lang, $listing->idlisting);
            }
            /* end listings */
            
            /* message */
            $message = str_replace(" ", '+', __('Tables updated', 'sw_win'));
        }
        
        wp_redirect(admin_url("admin.php?page=listing_settings&updated=true&copy_to_all_languages=true&message=".$message));
        exit;
        
    }
    
    public function sitemap_generate($id=1)
	{
            
            $xml_file_name = 'sitemap_listings.xml';
        
            $content = '';
            $content.= '<?xml version="1.0" encoding="UTF-8"?>'."\n".
                       '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'."\n".
                       '  	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\n".
                       '  	xmlns:xhtml="http://www.w3.org/1999/xhtml"'."\n".
                       '  	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9'."\n".
                       '			    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";

            //listings
            $controller = 'listing';
            $data_listings = $this->{$controller.'_m'}->get_pagination_lang(false, false, sw_current_language_id());
            foreach($data_listings as $listing)
            {
                $last_mod = '';
                if(!empty($listing->date_modified))
                    $last_mod = '	<lastmod>'.date('c',strtotime($listing->date_modified)).'</lastmod>'."\n";

                $content.= '<url>'."\n".
                                '	<loc>'.esc_url(listing_url($listing)).'</loc>'."\n".
                                $last_mod.
                                '	<changefreq>monthly</changefreq>'."\n".
                                '	<priority>0.5</priority>'."\n".
                                '</url>'."\n";
            }

            // agents
            $role__in = array('AGENT','AGENCY','OWNER');
            $data_users = get_users( array( 'search' => '', 'role__in' => $role__in, 
                                                  'order_by' => 'ID', 'order' => 'DESC'));
            
            foreach($data_users as $object)
            {
                $last_mod = '';
                if(!empty($object->user_registered))
                    $last_mod = '	<lastmod>'.date('c',strtotime($object->user_registered)).'</lastmod>'."\n";

                $content.= '<url>'."\n".
                                '	<loc>'.agent_url($object).'</loc>'."\n".
                                $last_mod.
                                '	<changefreq>monthly</changefreq>'."\n".
                                '	<priority>0.5</priority>'."\n".
                                '</url>'."\n";
            }

            $content.= '</urlset>';
            $fp = fopen(ABSPATH.$xml_file_name, 'w');
            fwrite($fp, $content);
            fclose($fp);
            wp_redirect(admin_url("admin.php?page=listing_settings&updated=true&sitemap_generated=true"));
            exit;
    }
    
    public function generated_all_translations($id=1)
	{
        /* enable themes for generate languages zip */
        $enable_themes = array('selio','devon','moison','nexos','yordy');

        /* enable plugins for generate languages zip by strripos_array(), please check function $this->strripos_array() */
        $enable_plugins = array(
            'elementor',
            'SW_',
            'wp-all-import-swlistings',
        );

        $zip_folder_theme = 'wp-themes';
        $zip_folder_lang = 'locale';
        $zip_folder_plugins = 'wp-plugins';
        
        $upload_dir = wp_upload_dir();
        $filename_zip = $upload_dir['path'].'/'.'theme+purpose.zip';
        $filename_zip_url = $upload_dir['url'].'/'.'theme+purpose.zip';
        
        $zip = new ZipArchive;
        $zip->open($filename_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        /* themes featch */
        $path_themes = get_theme_root();
        $themes = array_diff(scandir($path_themes) , array('..', '.'));
        $zip->addEmptyDir($zip_folder_theme);
        foreach ($themes as $theme) {
            if(in_array($theme, $enable_themes)) {
                if(file_exists($path_themes.'/'.$theme.'/locale') && is_dir($path_themes.'/'.$theme.'/locale')) {
                    $theme_lang_path = $path_themes.'/'.$theme.'/locale/';
                    $theme_lang_path_files = array_diff(scandir($theme_lang_path) , array('..', '.'));
                    $zip->addEmptyDir($zip_folder_theme.'/'.$theme);
                    $zip->addEmptyDir($zip_folder_theme.'/'.$theme.'/'.$zip_folder_lang);
                    foreach ($theme_lang_path_files as $lang_file) {
                        $zip->addFile($theme_lang_path.$lang_file, $zip_folder_theme.'/'.$theme.'/'.$zip_folder_lang.'/'.$lang_file);
                    }
                }
            }
        }
        /* end themes featch */

        /* themes plugins */
        $path_plugins = WP_PLUGIN_DIR;
        $plugins = array_diff(scandir($path_plugins) , array('..', '.'));
        $zip->addEmptyDir($zip_folder_plugins);
        foreach ($plugins as $plugin) {
            if($this->strripos_array($plugin, $enable_plugins) !== FALSE) {
                if(file_exists($path_plugins.'/'.$plugin.'/locale') && is_dir($path_plugins.'/'.$plugin.'/locale')) {
                    $plugin_lang_path = $path_plugins.'/'.$plugin.'/locale/';
                    $plugin_lang_path_files = array_diff(scandir($plugin_lang_path) , array('..', '.'));
                    $zip->addEmptyDir($zip_folder_plugins.'/'.$plugin);
                    $zip->addEmptyDir($zip_folder_plugins.'/'.$plugin.'/'.$zip_folder_lang);
                    foreach ($plugin_lang_path_files as $lang_file) {
                        $zip->addFile($plugin_lang_path.$lang_file, $zip_folder_plugins.'/'.$plugin.'/'.$zip_folder_lang.'/'.$lang_file);
                    }
                }
            }
        }
        /* end themes plugins */
        
        /* fields */
        $this->load->model('field_m') ;
        $fields = $this->field_m->get_fields();
        $trns_fields = array();
        foreach(sw_get_languages() as $key=>$row) {
            $fields = $this->field_m->get_fields($row['id']);
            $fields = (array) $fields;
            $trns_fields[$row['lang_code']] = $fields;
        }
        
        $zip->addEmptyDir('purpose');
        $zip->addFromString('purpose/fields.json', json_encode($trns_fields));
        /* end fields */
        
        $ret = $zip->close();
        wp_redirect($filename_zip_url);
        exit;
    }
    
    /* helper function for search in array by strripos(); */
    private function strripos_array($haystack='', $needle=array()) {
        foreach ($needle as $v) {
            if (strripos($haystack, $v) !== FALSE) {
                return true;
            } 
        }
        return false;
    }
    
	public function profile($id=NULL)
	{
        $this->load->model('profile_m');

        $user_id = get_current_user_id();

        $extra_link='';
        if(isset($_GET['user_id']))
        {
            $user_id = $_GET['user_id']; 
            $extra_link='&user_id='.$user_id;
        }

        $this->data['form_object'] = (object) $this->profile_m->get_by(array('user_id'=>$user_id), TRUE);

        $id = NULL;

        if(is_object($this->data['form_object']) && isset($this->data['form_object']->idprofile))
        {
            $id = $this->data['form_object']->idprofile;
        }

        /* [START] Agents */
        $this->data['agents'] = array();
        
        $user_info = get_userdata($user_id);
        if(sw_is_user_in_role($user_info, 'AGENCY'))
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
            wp_redirect(admin_url("admin.php?page=listing_profile&updated=true$extra_link")); exit;
        }
       
        // Load view
		$this->data['subview'] = 'admin/listing/profile';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function agentssave()
    {
        

        $this->load->model('profile_m');

        if(!sw_user_in_role('administrator'))
        {
            exit();
        }

        $user_id = $_POST['user_id'];

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

        $this->profile_m->save_agents($user_id, $agents_set);

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
