<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*


Guides:
We can't use $this->load and similar as usual in codeigniter
For this case, wordpress widgets, use for example: $this->CI->load

*/

class Dashboard extends MY_Widgetcontroller {

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
    
	public function register(&$output=NULL, $atts=array(), $instance=NULL)
	{
        $this->CI->load->model('user_m');

        $this->CI->data = array_merge($this->CI->data, $atts);

//        $options = sw_widget_options($this->data['widget_id']);
//        Dump => array(2) {
//          ["title"] => string(0) ""
//          ["receiver_email"] => string(14) "sandi@test.com"
//        }
        
        // For facebook login
        $this->data['facebook_login_url'] = '';
        $facebook_app_id = sw_settings('facebook_app_id');
        $facebook_app_secret = sw_settings('facebook_app_secret');
    
        if(!empty($facebook_app_id) && !empty($facebook_app_secret))
        if(sw_settings('facebook_login_enabled') == '1' && function_exists('sw_win_load_ci_function_facebooklogin'))
        {
            $CI = &get_instance();
            $CI->load->library('MY_Composer');

            $callback = get_site_url().'/?custom_login=facebook';
            
            $fb = sw_win_load_ci_function_facebooklogin($callback);
            
            // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
            $helper = $fb->getRedirectLoginHelper();
            //   $helper = $fb->getJavaScriptHelper();
            //   $helper = $fb->getCanvasHelper();
            //   $helper = $fb->getPageTabHelper();
    
            $permissions = ['email']; // optional
            
            $this->data['facebook_login_url'] = $helper->getLoginUrl($callback, $permissions);
        }
        
        // Process the form
        if($this->CI->input->post('widget_id') == 'register')
        {
            
            if(config_item('app_type') == 'demo')
            {
                echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Register disabled in demo.</span>";
                return;
            }
            
            $rules = $this->CI->user_m->form_register;
            
            $recaptcha_site_key = sw_settings('recaptcha_site_key');
            if(!empty($recaptcha_site_key))
                $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>__('Recaptcha', 'sw_win'), 
                                                        'rules'=>'trim|required|callback__captcha_check');
            
            $this->CI->form_validation->set_rules($rules);
            
            if($this->CI->form_validation->run() == TRUE)
            {
                
                $data = $this->CI->user_m->array_from_post($this->CI->user_m->get_post_from_rules($rules));
                
                $username = $data['username'];
                $email_address = $data['email'];
                $password = $data['password'];
                
                if( null == username_exists( $email_address ) ) {
                
                    // Generate the password and create the user
                    // $password = wp_generate_password( 12, false );
                    
                    $user_id = wp_create_user( $username, $password, $email_address );
                    
                    // Set the nickname
                    wp_update_user(
                        array(
                            'ID'          =>    $user_id,
                            'nickname'    =>    $email_address
                        )
                    );
                    
                    $available_acc_types = config_item('account_types');
                    
                    // Set the role
                    if(isset($available_acc_types[$data['account_type']]))
                    {
                        $user = new WP_User( $user_id );
                        $user->set_role($data['account_type']);
                    }
                    
                    $_POST = array();
                    $_POST['updated'] = 'true';
                    $_POST['widget_id'] = 'register';
                    
                    // Email the user
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $headers[] = 'From: '.get_bloginfo('admin_email');
                    
                    $subject = __('Welcome to our website!', 'sw_win');
                    $message = __('You are now registered to our website', 'sw_win').': '.get_site_url();
                    
                    $ret = wp_mail( $email_address, $subject, $message, $headers );
                     
                    if($ret == FALSE)
                    {
                        $_POST['updated'] = 'false';
                    }
                    
                    if(sw_settings('notify_admin_rel_new_users')){
                        // Email the user
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        $headers[] = 'From: '.get_bloginfo('admin_email');

                        $subject = __('New user on our website!', 'sw_win');
                        $message = __('New user registered on website, please check account', 'sw_win').': <a href="'. admin_url('user-edit.php?user_id='.$user_id).'">'.$username.'</a>';

                        $ret = wp_mail( get_bloginfo('admin_email'), $subject, $message, $headers );
                    }
                    
                } // end if

            }
        }
        else if($this->CI->input->post('widget_id') == 'login')
        {
            
            $rules = $this->CI->user_m->form_login;
            
//            $recaptcha_site_key = sw_settings('recaptcha_site_key');
//            if(!empty($recaptcha_site_key))
//                $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>__('Recaptcha', 'sw_win'), 
//                                                        'rules'=>'trim|required|callback__captcha_check');
            
            $this->CI->form_validation->set_rules($rules);
            
            if($this->CI->form_validation->run() == TRUE)
            {
                
                $data = $this->CI->user_m->array_from_post($this->CI->user_m->get_post_from_rules($rules));
                
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
                }
                else
                {
                    $redirect = admin_url("");
                    // Redirect to dashboard if related to plugin
                    if(sw_is_user_in_role($ret, 'AGENT') ||
                       sw_is_user_in_role($ret, 'OWNER') ||
                       sw_is_user_in_role($ret, 'AGENCY'))
                    {
                        $redirect = admin_url("admin.php?page=ownlisting_manage");
                    }
                    // Redirect to admin if user have admin roles
                    else if(sw_is_user_in_role($ret, 'administrator'))
                    {
                        $redirect = admin_url("admin.php?page=listing_manage");
                    }
                    
                    // Redirect to defined page in URI
                    if($this->CI->input->get_post('redirect_to')) {
                        $redirect = $this->CI->input->get_post('redirect_to');
                    }
                    
                    // Redirect to homepage in other case
                    wp_redirect($redirect); exit;
                }
                
                //dump($ret);
            }
        }

        $this->data['subview'] = 'dashboard/register';
        $this->print_template($output);
	}
    
    public function quicksubmission(&$output=NULL, $atts=array(), $instance=NULL)
    {
        $this->CI->load->model('user_m');
        
        $this->data['agents'] = array();
        
        // Get parameters
        $id = $this->CI->input->get('id');

        $is_new = ($id === NULL);
        
        // Set up the form
        if(empty($id))
        {
            if(!isset($_POST['repository_id']))
            {
                // Create new repository
                $repository_id = $this->CI->repository_m->save(array('model_name'=>'listing_m'));
                $_POST['repository_id'] = $repository_id;
            }
            
            $this->data['repository_id'] = $_POST['repository_id'];
        }
        else
        {
            $this->data['form_object'] = $this->CI->listing_m->get_lang($id, sw_default_language_id());
            $this->data['repository_id'] = $this->data['form_object']->repository_id;
            
            // TODO: Remove, just fore test purposes
            if(empty($this->data['repository_id']))
            {
                // Create new repository
                $repository_id = $this->CI->repository_m->save(array('model_name'=>'listing_m'));
                $_POST['repository_id'] = $repository_id;
                $this->data['repository_id'] = $_POST['repository_id'];
            }
            
            $this->data['agents'] = $this->CI->listing_m->get_agents_dropdown($id);
        }
        
        $this->data['fields_list'] = $this->CI->field_m->get_fields(sw_default_language_id());
        
        // [Rank packages]
        if(file_exists(APPPATH.'models/Packagerank_m.php')){
            $this->CI->load->model('packagerank_m');

            $this->data['rank_packages'] = $this->CI->packagerank_m->get();
        } else {
            $this->data['rank_packages'] = array();
        }
        // [/Rank packages]
        
        $rules = $this->CI->listing_m->form_agent;
        $rules_lang = $this->CI->listing_m->rules_lang;
        
        if(!is_user_logged_in())
        {
            $recaptcha_site_key = sw_settings('recaptcha_site_key');
            if(!empty($recaptcha_site_key))
                $rules['g-recaptcha-response'] = array('field'=>'g-recaptcha-response', 'label'=>__('Recaptcha', 'sw_win'), 
                                                        'rules'=>'trim|required|callback__captcha_check');
               
            if(!sw_settings('quicksubmission_no_registration')){
                $rules['email'] = array('field'=>'email', 'label'=>__('Your email', 'sw_win'), 
                                                        'rules'=>'trim|required|valid_email|callback__unique_email');   
            }
        }
                
        if(config_db_item('terms_link'))
        {
            $rules['option_agree_terms']['field'] = 'option_agree_terms';
            $rules['option_agree_terms']['label'] = __('Agree terms', 'sw_win');
            $rules['option_agree_terms']['rules'] = 'required';
        }
        
        /* minimum 1x images should be uploaded */ 
        if(true) {
            $rules['repository_id']['rules'] = 'trim|callback_min_images';
        }
        
        if($this->CI->input->get_post('widget_id') == 'quick_submission')
            $this->CI->form_validation->set_rules(array_merge($rules, $rules_lang));
        
        // Process the form
        if($this->CI->form_validation->run() == TRUE && $this->CI->input->get_post('widget_id') == 'quick_submission')
        {
            if(config_item('app_type') == 'demo')
            {
                echo "<span style=\"color:red;border:1px solid red;padding:5px;\">Action disabled in demo.</span>";
                return;
            }
            
            $package_rank = $this->CI->input->post('packagerank', true);
            
            $data = $this->CI->listing_m->array_from_post($this->CI->listing_m->get_post_from_rules($rules));
            
            $data_lang = $this->CI->listing_m->array_from_post($this->CI->listing_m->get_lang_post_fields());
            
            /* remove emoji */

            $data_lang = array_map("sw_remove_emoji", $data_lang);
            /* end remove emoji */
            
            $data['is_primary'] = 1;
                       
            $user_id = NULL;
            if(!sw_settings('quicksubmission_no_registration')){
                // [Create user by email]
                if(!is_user_logged_in())
                {
                    $email_address  = $data['email'];
                    $username       = $email_address;
                    $password = wp_generate_password( 12, false );

                    $user_id = wp_create_user( $username, $password, $email_address );

                    // Set the nickname
                    wp_update_user(
                        array(
                            'ID'          =>    $user_id,
                            'nickname'    =>    $email_address
                        )
                    );

                    $account_type = 'OWNER';

                    $available_acc_types = config_item('account_types');

                    // Set the role
                    if(isset($available_acc_types[$account_type]))
                    {
                        $user = new WP_User( $user_id );
                        $user->set_role($account_type);
                    }

                    $_POST = array();
                    $_POST['updated'] = 'true';
                    $_POST['widget_id'] = 'register';

                    // Email the user
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $headers[] = 'From: '.get_bloginfo('admin_email');

                    $subject = __('Welcome to our website!', 'sw_win');
                    $message = __('Thanks on your submission, you are now registered to our website', 'sw_win').': '.get_site_url().'<br /><br />';
                    $message.= __('Your login details', 'sw_win').'<br />';
                    $message.= __('Username', 'sw_win').': '.$username.'<br />';
                    $message.= __('Password', 'sw_win').': '.$password.'<br />';

                    $ret = wp_mail( $email_address, $subject, $message, $headers );
                }
                else
                {
                    $user_id = get_current_user_id();
                }
                // [/Create user by email]

                // [Transform subscriber to owner]
                if(sw_settings('transform_user') && sw_user_in_role('subscriber'))
                {
                    $user = new WP_User( $user_id );
                    $user->set_role('OWNER');
                }
                // [/Transform subscriber to owner]
            }
            unset($data['g-recaptcha-response'], $data['email'], $data['packagerank']);
            
            if(isset($data['option_agree_terms']))
                unset($data['option_agree_terms']);
            
            
            $id = $this->CI->listing_m->save_with_lang($data, $data_lang, $id, $user_id);
            
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
            
            $custom_uri='?';
            if(substr_count(get_permalink(sw_settings('quick_submission')), '?') > 0)
            {
                // if doesn't using custom permalink / mod_rewrite
                $custom_uri = '&';
            }
            
            // [Save invoice, ask for payment]
            if(!empty($package_rank))
            {
                // fetch package details
                $package = $this->CI->packagerank_m->get($package_rank);
                
                if(!empty($package) && $package->package_price > 0)
                {
                    $this->CI->load->model('invoice_m');
                    
                    $invoice = array();
                    $invoice['invoicenum'] = $this->CI->invoice_m->invoice_suffix($id.$user_id);
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
                    $invoice_id = $this->CI->invoice_m->save($invoice);
                    
                    // Open payment console
                    
                }

            }
            // [/Save invoice, ask for payment]
            
            wp_redirect(get_permalink(sw_settings('quick_submission')).$custom_uri.'updated=true'); exit;
        }
        
        $this->data['subview'] = 'dashboard/quicksubmission';
        $this->print_template($output);
    }

    
}
