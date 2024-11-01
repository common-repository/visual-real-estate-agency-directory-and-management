<?php

class Tokenapi extends CI_Controller
{
    private $data = array();
    private $settings = array();
    
    private $key='4PcY4Dku0JkuretevfEPMnG9BGBPi';
    private $enabled=TRUE;
    
    public function __construct()
    {
        parent::__construct();
        
        if(!$this->enabled && ENVIRONMENT != 'development')exit('DISABLED');
        
        $this->lang->load('mobile');
        $this->load->helper('language');
        $this->load->helper('mobile');

        $this->load->model('settings_m');
        $this->settings = $this->settings_m->get_fields();
        
        $this->load->model('user_m');
        $this->load->model('token_m');
        
        header('Content-Type: application/json');
    }
   
	public function index()
	{
        $this->data['message'] = lang_check('Hello, API here!');

        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call:
    /index.php/tokenapi/authenticate/?username=admin&password=admin&key=4PcY4Dku0JkuretevfEPMnG9BGBPi
    
    */
    public function authenticate()
    {
        $this->data['message'] = lang_check('Something is wrong with request');

        $POST = array_merge($this->input->get(), $this->input->post());
        //$this->data['parameters'] = $POST;

        if(isset($POST['key'], $POST['username'], $POST['password']) && $POST['key'] == $this->key)
        {
            require_once("../../../../wp-load.php"); // WP core is needed to complete authentication

            $user = get_user_by('login', $POST['username']);
            $password = $POST['password'];
            $user_meta=get_userdata($user->ID);

            // Check if user exists
            if(wp_check_password($password, $user->data->user_pass, $user->ID))
            {

                $user_data = array();
                $user_data['name_surname'] = $user->user_nicename;
                $user_data['username'] = $user->user_login;
                $user_data['remember'] = false;
                $user_data['id'] = $user->ID;
                $user_data['lang'] = NULL;
                $user_data['last_login'] = NULL;
                $user_data['loggedin'] = NULL;
                $user_data['type'] = $user_meta->roles[0];
                $user_data['profile_image'] = get_gravatar($user->user_email, 100);
                $user_data['last_activity'] = NULL;

                $this->data['user_data'] = $user_data;
                

                // Generate, return token
                $token = $this->user_m->hash_token($POST['username'].$POST['password'].time().rand(1,9999));
                $this->data['token'] = $token;
                
                $ip = '';
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
            
                // Delete all previous user token logs
                $this->db->where('user_id', $user->ID);
                $this->db->delete('sw_tokenapi');
                        
                // Save token
                $data = array();
                $data['date_last_access'] = date('Y-m-d H:i:s');
                $data['ip'] = $ip;
                $data['username'] = $POST['username'];
                $data['user_id'] = $user->ID;
                $data['token'] = $this->data['token'];
                $data['other'] = '';
                $this->token_m->save($data);
                
                $this->data['message'] = lang_check('Results available');
            }
        }
        
        echo json_encode($this->data);
        exit();
    }
    
    /*
    
    Example call:
    /index.php/tokenapi/user/?token=b02ec8d9b3d7ca1bb8e9e8880245166c
    
    */
	public function user()
	{
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
        
        $POST = array_merge($this->input->get(), $this->input->post());
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        if(is_object($token))
        {
            $user=get_userdata($token->user_id);

            $user_data = array();
            $user_data['name_surname'] = $user->user_nicename;
            $user_data['username'] = $user->user_login;
            $user_data['remember'] = false;
            $user_data['id'] = $user->ID;
            $user_data['lang'] = NULL;
            $user_data['last_login'] = NULL;
            $user_data['loggedin'] = NULL;
            $user_data['type'] = $user_meta->roles[0];
            $user_data['image_user_filename'] = get_gravatar($user->user_email, 100);
            $user_data['last_activity'] = NULL;

            $user_data['address'] = profile_data($user, 'address');
            $user_data['phone'] = profile_data($user, 'phone_number');
            $user_data['mail'] = $user->user_email;
            $user_data['description'] = wp_trim_words(get_the_author_meta( "user_description", $token->user_id ), 10, '...' );
            $user_data['language'] = NULL;
            
            $this->data['results'] = $user_data;
            
            $this->data['message'] = lang_check('Results available');
        }

        echo json_encode($this->data);
        exit();
	}

    /*
    
    Example call:
    /index.php/tokenapi/register/?mail=sandi1@gmail.com&password=sandi1&key=4PcY4Dku0JkuretevfEPMnG9BGBPi
    
    */
	public function register()
	{
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['success'] = FALSE;
        $POST = array_merge($this->input->get(), $this->input->post());
        //$this->data['parameters'] = $POST;

        if(config_db_item('property_subm_disabled')==TRUE)
        {
            $this->data['message'] = lang_check('Registration disabled on server');
        }
        else if(isset($POST['key'], $POST['mail'], $POST['password']) && $POST['key'] == $this->key)
        {
            $this->load->library('session');
            
            $user_exists = $this->user_m->get_by(array(
                'user_login = \''.$POST['mail'].'\' OR user_email = \''.$POST['mail'].'\'' => NULL
            ), TRUE);
        
            // Additional check to login with email
            if(sw_count($user_exists) > 0)
            {
                $this->data['message'] = lang_check('Email already exists');
            }            
            else if (!filter_var($POST['mail'], FILTER_VALIDATE_EMAIL)) {
                $this->data['message'] = lang_check('Invalid email');
            }
            else if(strlen($POST['password']) < 6)
            {
                $this->data['message'] = lang_check('Longer password required');
            }
            else
            {
                //$this->load->model('language_m');
                $this->data['message'] = '';

                $data = array();
                $data['account_type'] = 'OWNER';

                $user_id = wp_create_user( $POST['mail'], $POST['password'], $POST['mail'] );
                
                // Set the nickname
                wp_update_user(
                    array(
                        'ID'          =>    $user_id,
                        'nickname'    =>    $POST['mail']
                    )
                );
                
                $available_acc_types = config_item('account_types');
                
                // Set the role
                if(isset($available_acc_types[$data['account_type']]))
                {
                    $user = new WP_User( $user_id );
                    $user->set_role($data['account_type']);
                }

                // Email the user
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.get_bloginfo('admin_email');
                
                $subject = __('Welcome to our website!', 'sw_win');
                $message = __('You are now registered to our website', 'sw_win').': '.get_site_url();
                
                $ret = wp_mail(  $POST['mail'], $subject, $message, $headers );
                
                if(!empty($user_id))
                {
                    $this->data['message'] = lang_check('Register success');
                    $this->data['success'] = TRUE;
                } 
            }
        }
        
        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call for GET:
    /index.php/tokenapi/favorites/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&lang_code=en
    
    Example call for POST:
    /index.php/tokenapi/favorites/POST/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&lang_code=en&property_id=8
    
    Example call for DELETE:
    /index.php/tokenapi/favorites/DELETE/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&property_id=8
    
    */
	public function favorites($method='GET')
	{
        $data_tmp['listing_uri'] = config_item('listing_uri');
        if(empty($data_tmp['listing_uri']))$data_tmp['listing_uri'] = 'property';    
            
        
        $this->load->model('listing_m');
        $this->load->model('field_m');
        $this->load->model('favorite_m');
        $this->load->model('treefield_m');
       
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
         $POST = array_merge($this->input->get(), $this->input->post());
        
        if(isset($POST['lang_code']))
        {
            $lang_id = sw_get_languages($POST['lang_code']);
        }
        
        if(empty($lang_id))$lang_id=sw_default_language_id();

        $lang_code = sw_get_languages($lang_id);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
    
        if(is_object($token))
        {

            if($method == 'GET')
            {
                $field_list = $this->field_m->get_field_list(sw_default_language_id());
                
                $this->db->join('sw_favorite', 'sw_listing.idlisting = sw_favorite.listing_id', 'right');
                $this->db->where('user_id', $token->user_id);
                $estates = $this->listing_m->get_by(array('is_activated' => 1, 'lang_id'=>$lang_id));
                
                // Set website details
                $json_data = array();
                // Add listings to rss feed     
                foreach($estates as $key=>$row){
                    $estate_date = array();

                    $title = _field($row, 10);
                    $url = listing_url($row);

                    $row->id = $row->idlisting;
                    $row->property_id = $row->idlisting;
                    $row->json_object = json_decode($row->json_object);
                    $row->image_repository = json_decode($row->image_repository);
                    
                    
                    $category = NULL;
                    if(empty($row->field_2))
                    {
                        $row->field_2 = '-';
                        $row->json_object->field_2 = $row->field_2;
                        $this->data['category'] = NULL;
                        
                        if(!empty($row->category_id))
                        {
                            $category = $this->treefield_m->get_lang($row->category_id);
                            
                            if(isset($category->{"value_".$lang_id}))
                            {
                                $row->field_2 = $category->{"value_".$lang_id};
                                $row->json_object->field_2 = $row->field_2;
                            }
                                
                        }
        
                    }
                    
                    if(empty($row->field_4))
                    {
                        $row->field_4 = '-';
                        $row->json_object->field_4 = $row->field_4;
                    }
        
                    if(isset($row->json_object->field_14) && !empty($row->json_object->field_14) && $row->json_object->field_14 != 'empty')
                    {
                        $row->json_object->field_6 = $row->json_object->field_14;
                    }
                    else
                    {
                        //check for category value
                        if(isset($row->field_2) && !empty($row->field_2))
                        {
                            $row->json_object->field_6 = sw_generate_slug($row->field_2, 'underscore');
                        }
                        else
                        {
                            // if nothing exists
                            $row->json_object->field_6 = 'empty';
                        }
                    }

                    // prepare fields, null are not allowed by old mobile api
                    foreach($field_list as $key_f=>$row_f)
                    {
                        if(!isset($row->json_object->{"field_".$row_f->idfield}))
                        {
                            $row->{"field_".$row_f->idfield} = "";
                            $row->json_object->{"field_".$row_f->idfield} = $row->{"field_".$row_f->idfield};
                        }
                        
                        if($row_f->type == 'CHECKBOX')
                        {
                            if(isset($row->json_object->{"field_".$row_f->idfield}) && 
                                     $row->json_object->{"field_".$row_f->idfield} == '1')
                                $row->json_object->{"field_".$row_f->idfield} = 'true';
                        }
                    }
                    
                    $estate_date['url'] = $url;
                    $estate_date['listing'] = $row;
                    
                    // Add first agent/owner data
                    
                    $this->data['agents'] = $this->listing_m->get_agents($row->idlisting);
                    
                    if(isset($this->data['agents'][0]))
                    {
                        $row->name_surname = $this->data['agents'][0]->display_name;
                        $row->mail = $this->data['agents'][0]->user_email;
                        $row->phone = "-";
                        $row->agent_id = $this->data['agents'][0]->ID;
                        $row->image_user_filename = "";
                    }
                    
                    $json_data[] = $estate_date;
                }
                
                $this->data['results'] = $json_data;
                
                $this->data['message'] = lang_check('Results available');
            }
            elseif($method == 'POST' && isset($POST['property_id']))
            {
                $property_id = $POST['property_id'];
                
                $this->data['success'] = false;
                // Check if property_id already saved, stop and write message
                
                
                if($this->favorite_m->check_if_exists($token->user_id, $property_id)>0)
                {
                    $this->data['message'] = lang_check('Favorite already exists!');
                    $this->data['success'] = true;
                }
                // Save favorites to database
                else
                {
                    $data = array();
                    $data['user_id'] = $token->user_id;
                    $data['listing_id'] = $property_id;
                    $data['date_last_informed'] = date('Y-m-d H:i:s');
                    $data['date_submit'] = date('Y-m-d H:i:s');
                    $data['date_modified'] = date('Y-m-d H:i:s');
                    
                    $this->favorite_m->save($data);
                    
                    $this->data['message'] = lang_check('Favorite added!');
                    $this->data['success'] = true;
                } 
            }
            elseif($method == 'DELETE' && isset($POST['property_id']))
            {
                $property_id = $POST['property_id'];
                
                $this->data['success'] = false;
                // Check if property_id already saved, stop and write message
                if($this->favorite_m->check_if_exists($token->user_id, $property_id)>0)
                {
                    $favorite_selected = $this->favorite_m->get_by(array('sw_favorite.listing_id'=>$property_id, 'user_id'=>$token->user_id), TRUE);
                    $this->favorite_m->delete($favorite_selected->idfavorite);
                    
                    $this->data['message'] = lang_check('Favorite removed!');
                    $this->data['success'] = true;
                }
                // Save favorites to databasefi
                else
                {
                    $this->data['message'] = lang_check('Favorite doesnt exists!');
                    $this->data['success'] = true;
                }
            }
        }

        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call:
    /index.php/tokenapi/submission/?token=b02ec8d9b3d7ca1bb8e9e8880245166c
                                    &lang_code=en
                                    &input_address=Vukovar
                                    &input_title=nice home
                                    &input_4=nice home
                                    &input_description=my description
                                    &input_36=10000
                                    
    To edit or send iamges separated send also property_id=XX
    
    */
    
    public function submission()
    {
        $title_field_id = 10;
        $content_short_field_id = 8;
        $content_long_field_id = 13;
        
        $this->load->model('listing_m');
        $this->load->model('field_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        $this->load->model('treefield_m');
        
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['success'] = FALSE;
        $this->data['token_available'] = FALSE;
        
        $POST = array_merge($this->input->get(), $this->input->post());

        if(isset($POST['lang_code']))
        {
            $lang_id_selected = sw_get_languages($POST['lang_code']);
        }
        
        $lang_id_def = sw_default_language_id();
        $lang_code_def = sw_get_languages($lang_id_def);

        $token = $this->token_m->get_token($POST);
        
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        //var_dump($POST);
        
        if(config_db_item('property_subm_disabled')==TRUE)
        {
            $this->data['message'] = lang_check('Registration disabled on server');
        }
        else if(isset($POST['lang_code']) && $lang_id_selected != $lang_id_def)
        {
            $this->data['message'] = lang_check('Only default lang is supported');
        }
        else if(is_object($token) && isset($POST['lang_code']) && isset($POST['input_address'], 
                                                   $POST['input_title'],
                                                   $POST['input_description'],
                                                   $POST['input_4']))
        {

            $existing_fields = $this->field_m->get_field_list($lang_id_def);

            // check if fields exists
            foreach($POST as $key=>$val)
            {
                $exp = explode('_', $key);
                
                if(sw_count($exp) == 2 && $exp[0]=='input' && is_numeric($exp[1]))
                {
                    if(!isset($existing_fields[$exp[1]]) && $exp[1] != '2') // skip 2 because type field #2 is different in WP, as category
                    {    
                        unset($POST['input_'.$exp[1]]);

                        $this->data['message'] = lang_check('Field not found: #').$exp[1];
                        echo json_encode($this->data);
                        exit();
                    }
                }
            }
            
            if(isset($POST['property_id']))
            {
                
                if(empty($POST['input_description']) || empty($POST['input_title']))
                {
                    $this->data['message'] = lang_check('Please populate all fields!');
                    echo json_encode($this->data);
                    exit();
                }

                $this->load->library('session');

                // check permission for edit
                if($this->listing_m->check_user_permission($POST['property_id'], $token->user_id)>0)
                {

                    // edit
                    $data = array();

                    $data['date_modified'] = date('Y-m-d H:i:s');
                    $data['address'] = $POST['input_address'];

                    // fetch gps
                    $this->load->library('ghelper');
                    $coor = $this->ghelper->getCoordinates($data['address']);
                    $data['gps'] = $coor['lat'].', '.$coor['lng'];
                                        
                    // get title
                    $dynamic_data["input_".$title_field_id."_".$lang_id_def] = $POST['input_title'];
                    
                    // get description
                    $dynamic_data["input_".$content_short_field_id."_".$lang_id_def] = $POST['input_description'];
                    $dynamic_data["input_".$content_long_field_id."_".$lang_id_def] = $POST['input_description'];
                    
                    // prepare other fields
                    foreach($POST as $key=>$val)
                    {
                        $exp = explode('_', $key);
                        
                        if(sw_count($exp) == 2 && $exp[0]=='input' && is_numeric($exp[1]))
                        {
                            $dynamic_data["input_".$exp[1]."_".$lang_id_def] = $POST['input_'.$exp[1]];
                        }
                    }
    
                    // in wp field #2 (Type) is replaced with category in WP plugin version
                    if(isset($dynamic_data["input_2_".$lang_id_def]))
                    {
                        // Category id now in WP replacing field_2 in regular script
                        
                        $tree = $this->treefield_m->get_table_tree($lang_id_def, 1);
    
                        foreach($tree as $key=>$val)
                        {
                            if($val->value == $dynamic_data["input_2_".$lang_id_def])
                                $data['category_id'] = $val->idtreefield;
                        }
                        
                        unset($dynamic_data["input_2_".$lang_id_def]);
                    }
    
                    $this->config->set_item('multilanguage_required', 0);

                    // save basic data
                    $insert_id = $this->listing_m->save_with_lang($data, $dynamic_data, $POST['property_id'], $token->user_id);
                    
                    if(!empty($insert_id))
                    {
                        $this->uploadfiles($insert_id);
                        
                        $this->data['message'] = lang_check('Listing saved');
                        $this->data['success'] = TRUE;
                    }
                    else
                    {
                        $this->data['message'] = lang_check('Edit declined');
                    }
                }

            }
            else
            {
                // add
                
                if(empty($POST['input_description']) || empty($POST['input_title']))
                {
                    $this->data['message'] = lang_check('Please populate all fields!');
                    echo json_encode($this->data);
                    exit();
                }

                $data = array();
                $data['is_activated'] = NULL;
                $data['is_primary'] = true;
                $data['is_featured'] = false;
                $data['date_submit'] = date('Y-m-d H:i:s');
                $data['date_modified'] = date('Y-m-d H:i:s');
                $data['address'] = $POST['input_address'];
                $data['related_id'] = NULL;
                $data['rank'] = 0;

                // Create new repository
                $repository_id = $this->repository_m->save(array('model_name'=>'listing_m'));
                $data['repository_id'] = $repository_id;
                
                // fetch gps
                $this->load->library('ghelper');
                $coor = $this->ghelper->getCoordinates($data['address']);
                $data['gps'] = $coor['lat'].', '.$coor['lng'];
                
                // other dynamic data
                $dynamic_data = array();
                $dynamic_data['agent'] = $token->user_id;
                
                // get title
                $dynamic_data["input_".$title_field_id."_".$lang_id_def] = $POST['input_title'];
                
                // get description
                $dynamic_data["input_".$content_short_field_id."_".$lang_id_def] = $POST['input_description'];
                $dynamic_data["input_".$content_long_field_id."_".$lang_id_def] = $POST['input_description'];
                
                // prepare other fields
                foreach($POST as $key=>$val)
                {
                    $exp = explode('_', $key);
                    
                    if(sw_count($exp) == 2 && $exp[0]=='input' && is_numeric($exp[1]))
                    {
                        $dynamic_data["input_".$exp[1]."_".$lang_id_def] = $POST['input_'.$exp[1]];
                    }
                }
                
                // in wp field #2 (Type) is replaced with category in WP plugin version
                if(isset($dynamic_data["input_2_".$lang_id_def]))
                {
                    // Category id now in WP replacing field_2 in regular script
                    
                    $tree = $this->treefield_m->get_table_tree($lang_id_def, 1);

                    foreach($tree as $key=>$val)
                    {
                        if($val->value == $dynamic_data["input_2_".$lang_id_def])
                            $data['category_id'] = $val->idtreefield;
                    }
                    
                    unset($dynamic_data["input_2_".$lang_id_def]);
                }

                $this->config->set_item('multilanguage_required', 0);

                // save basic data
                $insert_id = $this->listing_m->save_with_lang($data, $dynamic_data, NULL, $token->user_id);
                
                if(empty($insert_id))
                {
                    echo 'EMPTY insert_id:<br />';
                    echo $this->db->last_query();
                    exit();
                }
                
                if(!empty($insert_id))
                {
                    $this->uploadfiles($insert_id);
                    
                    $this->data['message'] = lang_check('Listing added');
                    $this->data['success'] = TRUE;
                }
                else
                {
                    $this->data['message'] = lang_check('Added declined');
                }
            }
        }     
        
        echo json_encode($this->data);
        exit();
    }
    
    private function uploadfiles($listing_id)
    {
        $uploadFolder = sw_win_upload_path().'files/';

        $this->data['estate'] = $this->listing_m->get_lang($listing_id, sw_default_language_id());

        // Fetch file repository
        $repository_id = $this->data['estate']->repository_id;
        
        $this->load->library('uploadHandler', array('initialize'=>FALSE));
        
        $this->data['message_image'] = array();
        if (isset($_FILES["files"]) && is_array($_FILES["files"])) {
            $numberOfFiles = sw_count($_FILES["files"]["name"]);
            for ($i = 0; $i < $numberOfFiles; $i++) { //ignore this comment >

                $filename_db = $this->uploadhandler->get_file_name(basename($_FILES["files"]["name"][$i]), null, null, null);
            
                $uploadFile = $uploadFolder . "/" . $filename_db;
                $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        
                if (!(getimagesize($_FILES["files"]["tmp_name"][$i]) !== false)) {
                    $this->data['message_image'][$i] = lang_check("Sorry, your image is invalid");
                }
                else if ($_FILES["files"]["size"][$i] > 10000000) {
                    $this->data['message_image'][$i] = lang_check("Sorry, your file is too large.");
                }
                else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $this->data['message_image'][$i] = lang_check("Sorry, only JPG, JPEG & PNG files are allowed.");
                }
                else if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $uploadFile)) {
                    $this->data['message_image'][$i] = lang_check("Upload image successfully: ").$filename_db;

                    $this->uploadhandler->regenerate_versions($filename_db, '');
                    
                    $this->file_m->delete_cache($filename_db);
                    
                    $next_order = $this->file_m->get_max_order()+1;
                    
                    // Add file to repository
                    $file_id = $this->file_m->save(array(
                        'repository_id' => $repository_id,
                        'order' => $next_order,
                        'filename' => $filename_db,
                        'filetype' => $imageFileType
                    ));
                    
                    if(empty($file_id))
                    {
                        $this->data['errors'][] = lang_check("Insert image into DB failed.");
                    }
                    
                } else {
                    $this->data['message_image'][$i] = lang_check("Sorry, there was an error uploading your file.");
                }
            }
            
            // insert files into repository
            
            // run to resave cached image repository details
            $insert_id = $this->listing_m->save(array(), $listing_id);
        }
    }
    
    // TODO: below still need to be done

	public function searches($method='GET')
	{
        $this->data['message'] = lang_check('Hello, API here!');

        echo json_encode($this->data);
        exit();
	}
    
    /*
    
    Example call for GET:
    /index.php/tokenapi/listings/?token=784cc4bcf5fe2a183d1197128b690ef9&lang_code=en

    Example call for DELETE:
    /index.php/tokenapi/listings/DELETE/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&property_id=8
    
    1654c1115bac220aab7d599ffc75de0d
    
        
    */
	public function listings($method='GET')
	{
            
        $data_tmp['listing_uri'] = config_item('listing_uri');
        if(empty($data_tmp['listing_uri']))$data_tmp['listing_uri'] = 'property';   
            
        
        $this->load->model('listing_m');
        $this->load->model('field_m');
        $this->load->model('treefield_m');
        $this->load->model('file_m');
       
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
         $POST = array_merge($this->input->get(), $this->input->post());
        
         if(isset($POST['lang_code']))
         {
             $lang_id = sw_get_languages($POST['lang_code']);
         }
         
         if(empty($lang_id))$lang_id=sw_default_language_id();
 
         $lang_code = sw_get_languages($lang_id);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
            
        if(is_object($token))
        {
            if($method == 'GET')
            {
                $field_list = $this->field_m->get_field_list(sw_default_language_id());
                $this->db->join('sw_listing_agent', 'sw_listing_agent.listing_id = sw_listing.idlisting', 'right');
                $this->db->where('user_id', $token->user_id);
                $estates = $this->listing_m->get_by(array('lang_id'=>$lang_id));
                
                // Set website details
                $json_data = array();
                // Add listings to rss feed     
                foreach($estates as $key=>$row){
                    $estate_date = array();
                    
                    $title = _field($row, 10);
                    $url = listing_url($row);

                    $row->id = $row->idlisting;
                    $row->property_id = $row->idlisting;
                    $row->json_object = json_decode($row->json_object);
                    $row->image_repository = json_decode($row->image_repository);
                    
                    
                    $category = NULL;
                    if(empty($row->field_2))
                    {
                        $row->field_2 = '-';
                        $row->json_object->field_2 = $row->field_2;
                        $this->data['category'] = NULL;
                        
                        if(!empty($row->category_id))
                        {
                            $category = $this->treefield_m->get_lang($row->category_id);
                            
                            if(isset($category->{"value_".$lang_id}))
                            {
                                $row->field_2 = $category->{"value_".$lang_id};
                                $row->json_object->field_2 = $row->field_2;
                            }
                                
                        }
        
                    }
                    
                    if(empty($row->field_4))
                    {
                        $row->field_4 = '-';
                        $row->json_object->field_4 = $row->field_4;
                    }
        
                    if(isset($row->json_object->field_14) && !empty($row->json_object->field_14) && $row->json_object->field_14 != 'empty')
                    {
                        $row->json_object->field_6 = $row->json_object->field_14;
                    }
                    else
                    {
                        //check for category value
                        if(isset($row->field_2) && !empty($row->field_2))
                        {
                            $row->json_object->field_6 = sw_generate_slug($row->field_2, 'underscore');
                        }
                        else
                        {
                            // if nothing exists
                            $row->json_object->field_6 = 'empty';
                        }
                    }
                    
                    // prepare fields, null are not allowed by old mobile api
                    foreach($field_list as $key_f=>$row_f)
                    {
                        if(!isset($row->json_object->{"field_".$row_f->idfield}))
                        {
                            $row->{"field_".$row_f->idfield} = "";
                            $row->json_object->{"field_".$row_f->idfield} = $row->{"field_".$row_f->idfield};
                        }
                        
                        if($row_f->type == 'CHECKBOX')
                        {
                            if(isset($row->json_object->{"field_".$row_f->idfield}) && 
                                        $row->json_object->{"field_".$row_f->idfield} == '1')
                                $row->json_object->{"field_".$row_f->idfield} = 'true';
                        }
                    }
                    
                    $estate_date['url'] = $url;
                    $estate_date['listing'] = $row;
                    
                    // Add first agent/owner data
                    
                    $this->data['agents'] = $this->listing_m->get_agents($row->idlisting);
                    
                    if(isset($this->data['agents'][0]))
                    {
                        $row->name_surname = $this->data['agents'][0]->display_name;
                        $row->mail = $this->data['agents'][0]->user_email;
                        $row->phone = "-";
                        $row->agent_id = $this->data['agents'][0]->ID;
                        $row->image_user_filename = "";
                    }
                    
                    $json_data[] = $estate_date;
                }
                
                $this->data['results'] = $json_data;
                
                $this->data['message'] = lang_check('Results available');
            }
            elseif($method == 'DELETE' && isset($POST['property_id']))
            {
                $this->load->library('session');
                $property_id = $POST['property_id'];
                
                $this->data['success'] = false;
                // Check permissions
                if($this->listing_m->check_user_permission($property_id, $token->user_id)>0)
                {
                    $this->listing_m->delete($property_id);
                    
                    $this->data['message'] = lang_check('Listing removed!');
                    $this->data['success'] = true;
                }
                // Permission check failed
                else
                {
                    $this->data['message'] = lang_check('Listing doesnt exists!');
                    $this->data['success'] = true;
                }
            }
        }

        echo json_encode($this->data);
        exit();
	}
    
        
        
    /*     
    Example call for GET:
    /index.php/tokenapi/push_notifications/?token=b02ec8d9b3d7ca1bb8e9e8880245166c&lang_code=en
    */
        
    public function push_notifications($method='GET')
	{
        
        $data_tmp['listing_uri'] = config_item('listing_uri');
        if(empty($data_tmp['listing_uri']))$data_tmp['listing_uri'] = 'property';
        
        $this->load->model('language_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('enquire_m');
        $this->load->model('packages_m');
        $this->load->library('session');
       
        $this->data['message'] = lang_check('Something is wrong with request');
        $this->data['token_available'] = FALSE;
         $POST = array_merge($this->input->get(), $this->input->post());
        
        $on_last_days = 50;
        
        if(isset($POST['lang_code']))
        {
            $lang_id = $this->language_m->get_id($POST['lang_code']);
        }
        
        if(empty($lang_id))$lang_id=$this->language_m->get_default_id();
        $lang_code = $this->language_m->get_code($lang_id);
        
        $token = $this->token_m->get_token($POST);
        if(is_object($token))
            $this->data['token_available'] = TRUE;
        
        if(is_object($token))
        {
            if($method == 'GET')
            {
                $json_data= array();
                
                // Enquire  fetch
                $min_date = date('Y-m-d H:i:s', time()- $on_last_days*24*60*60);
                $enquire = $this->enquire_m->get_by(array('enquire.date >'=>$min_date), FALSE, NULL, 'enquire.id DESC');
                foreach($enquire as $key=>$row){
                    $data = array();
                    
                    $url = site_url('admin/enquire/edit/'.$row->id);
                    $data['id'] = $row->id.'-enquire';
                    $data['type'] = 'enquire';
                    $data['url'] = $url;
                    $data['date'] = $row->date;
                    $data['message'] = lang_check('You receive new message').': '.$row->message;
                    
                    $json_data[] = $data;
                }
                
                // Listings will expired fetch
                if($this->settings['listing_expiry_days'] >0)  {
                    $max_date = date('Y-m-d H:i:s', time() - $this->settings['listing_expiry_days']*86400);

                    $listings_soon_expired = $this->estate_m->get_by(array('date_modified >'=>$max_date));
                    foreach($listings_soon_expired as $key=>$row){
                        $data = array();

                        $title = $this->estate_m->get_field_from_listing($row, 10);
                        $url = site_url($data_tmp['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.url_title_cro($title));
                        $date_expired = date('Y-m-d H:i:s', strtotime($row->date_modified+$this->settings['listing_expiry_days']*86400));

                        $data['id'] = $row->id.'-listings_soon_expired';
                        $data['type'] = 'listings_soon_expired';
                        $data['url'] = $url;
                        $data['date'] =  $date_expired;
                        $data['message'] = lang_check('You receive new message').': '.lang_check('Listing').' '.$title.' ('.$row->address.'), '.lang_check('will expired at').'  '.$date_expired;

                        $json_data[] = $data;
                    }
                    // Listings expired fetch
                    $max_date = date('Y-m-d H:i:s', time() - $this->settings['listing_expiry_days']*86400);
                    $listings_expired = $this->estate_m->get_by(array('date_modified <'=>$max_date));
                    foreach($listings_expired as $key=>$row){
                        $data = array();
                        $title = $this->estate_m->get_field_from_listing($row, 10);
                        $url = site_url($data_tmp['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.url_title_cro($title));
                        $date_expired = date('Y-m-d H:i:s', strtotime($row->date_modified+$this->settings['listing_expiry_days']*86400));
                        $data['id'] = $row->id.'-listings_expired';
                        $data['type'] = 'listings_expired';
                        $data['url'] = $url;
                        $data['date'] =  $date_expired;
                        $data['message'] = lang_check('You receive new message').': '.lang_check('Listing').' '.$title.' ('.$row->address.'), '.lang_check('expired at').'  '.$date_expired;

                        $json_data[] = $data;
                    }
                }
                
                // Package expired fetch
                $user = $this->user_m->get($this->session->userdata('id'));
                $package = $this->packages_m->get($user->package_id);
                if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
                {
                    $data = array();
                    $url = site_url('frontend/myproperties/');
                    $data['id'] = $row->id.'-package_expired';
                    $data['type'] = 'package_expired';
                    $data['url'] = $url;
                    $data['date'] =  $user->package_last_payment;
                    $data['message'] = lang_check('You receive new message').': '.lang_check('Date for your package expired, please extend');
                    $json_data[] = $data;
                }
                $this->data['results'] = $json_data;
                $this->data['message'] = lang_check('Notifications');
            }
        }

        echo json_encode($this->data);
        exit();
    }
       
}
