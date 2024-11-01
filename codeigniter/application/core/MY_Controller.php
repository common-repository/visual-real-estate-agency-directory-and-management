<?php

class MY_Controller extends CI_Controller {
    
    public $data = array();
    
	public function __construct(){
		parent::__construct();

        $this->data['errors'] = array();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible" role="alert">', 
                                                     '</div>');

        if( 
            is_admin() && isset($_GET['page']) && $_GET['page'] != 'install_index' && 
            (!sw_win_table_exists('sw_invoice') || sw_win_classified_version() > sw_win_classified_version_db()) 
             )
        {
            wp_redirect(admin_url("tools.php?page=install_index&not_installed=true")); exit;
        }

	}
    
    public function print_template(&$output = NULL)
    {
        if(empty($this->data['subview']))
            $this->data['subview'] = 'errors/html/error404';
            
        // [Load custom view from template]

        if(is_child_theme() && file_exists(get_stylesheet_directory().'/SW_Win_Classified/views/'.$this->data['subview'].'.php'))
        {
            $this->load->add_package_path(get_stylesheet_directory().'/SW_Win_Classified/');
        } 
        else if(file_exists(get_template_directory().'/SW_Win_Classified/views/'.$this->data['subview'].'.php')) 
        {
            $this->load->add_package_path(get_template_directory().'/SW_Win_Classified/');
        }
        
        
        // [/Load custom view from template]
        
        $output_t = $this->load->view('_layout_widget', $this->data, TRUE);
//        $output =  str_replace('assets/', c_base_url('assets/admin/assets/'), $output);
//        $output =  str_replace('img/', c_base_url('assets/admin/img/'), $output);
//        $output =  str_replace('tmp/', c_base_url('assets/admin/tmp/'), $output);
//        
//        if(config_item('litecache_enabled') === TRUE)
//        {
//            $this->litecache->save_cache($output);
//        }
        
        
        if(is_null($output))
        {
            echo $output_t;
        }
        elseif(strpos( $output,'[swcontent]') === FALSE)
        {
            $output.=$output_t;
        }
        else
        {
            $output= str_replace ('[swcontent]', $output_t, $output);
        }
    }
    
    public function _values_correction(&$str)
    {
        $str = str_replace(', ', ',', $str);
        
        return TRUE;
    }

    public function _check_subscription(&$str)
	{
        $this->load->model('profile_m');
        $this->load->model('subscriptions_m');

        // prepare user details

        $user_id_logged = get_current_user_id();

        

        //$user_data = $this->user_m->get_by(array('ID'=>$user_id_logged), TRUE);

        $profile_data = $this->profile_m->get_by(array('user_id'=>$user_id_logged), TRUE);

        // check if packages enabled or admin logged in

        if(!function_exists('sw_win_load_ci_function_subscriptions'))
            return TRUE;

        if(sw_user_in_role('administrator') || empty($user_id_logged))
            return TRUE;

        // set default package subscription if used don't have

        if(empty($profile_data->package_id))
        {
            $subscription = $this->subscriptions_m->get_by(array('is_default'=>1), TRUE);
            if(!is_object($subscription))
            {
                $this->form_validation->set_message('_check_subscription', __('Default package not set, you don\'t have activated package', 'sw_win'));
                return FALSE;
            }
            else
            {
                $data_update = array();
                $data_update['package_id'] = $subscription->idsubscriptions;
                $data_update['package_expire'] = date('Y-m-d H:i:s', time()+$subscription->days_limit*86400);

                $this->profile_m->save($data_update, $profile_data->idprofile);

                $profile_data = $this->profile_m->get_by(array('user_id'=>$user_id_logged), TRUE);
            }
        }

        // get package related

        if(strtotime($profile_data->package_expire) < time())
        {
            // get default package subscription

            $subscription = $this->subscriptions_m->get_by(array('is_default'=>1), TRUE);
            if(!is_object($subscription))
            {
                $this->form_validation->set_message('_check_subscription', __('Default subscription not set, you don\'t have activated subscription', 'sw_win'));
                return FALSE;
            }
        }
        else
        {
            $subscription = $this->subscriptions_m->get($profile_data->package_id);
            if(!is_object($subscription))
            {
                // package not exists, so try to set default

                $subscription = $this->subscriptions_m->get_by(array('is_default'=>1), TRUE);
                
                if(!is_object($subscription))
                {
                    $this->form_validation->set_message('_check_subscription', __('Default subscription not set, you don\'t have activated subscription', 'sw_win'));
                    return FALSE;
                }
            }
        }

        // $subscription now contain active subscription, check if package is expired

        if(empty($profile_data->package_expire))
        {

        }
        else if(strtotime($profile_data->package_expire) < time())
        {
            $this->form_validation->set_message('_check_subscription', __('Your subscription expired, ', 'sw_win').' <a target="_blank" href="'.admin_url("admin.php?page=ownlisting_subscriptions").'">'.__('please extend', 'sw_win').'</a>');
            return FALSE;
        }

        // check number of user listings

       $listings_count = $this->profile_m->related_listings_count($user_id_logged);

        if(isset($_GET['id']) && $listings_count<=$subscription->listing_limit)
        {
            // edit mode, so possible if package not expired

            return TRUE;
        }

        if(!empty($subscription->listing_limit) && $listings_count>=$subscription->listing_limit)
        {
            $this->form_validation->set_message('_check_subscription', __('Purchase larger subscription, you exceed listings limitation', 'sw_win').' <a target="_blank" href="'.admin_url("admin.php?page=ownlisting_subscriptions").'">'.__('manage subscription', 'sw_win').'</a>');
            return FALSE;
        }
        
        return TRUE;
    }

    public function _agency_email_to_id(&$str)
    {
        $user = NULL;

        if(empty($str))
        {
            return TRUE;
        }
        else if(is_numeric($str))
        {
            $user = $this->user_m->get_by(array('ID'=>$str), TRUE);
        }
        else
        {
            $user = $this->user_m->get_by(array('user_email'=>$str), TRUE);
        }

        if(is_object($user))
        {
            $user = get_userdata( $user->ID );

            if(sw_is_user_in_role( $user, 'AGENCY' ))
            {
                $str = $user->ID;
                return TRUE;
            }
        }

        $this->form_validation->set_message('_agency_email_to_id', __('Agency not found', 'sw_win'));
        return FALSE;
    }
    
    public function _values_dropdown_check(&$str)
    {
        static $comma_count = -1;
        
        foreach(sw_get_languages() as $lang)
        {
            $values_post = $this->input->post("values_".$lang['id']);
            
            if($str == $values_post)
            {
                $comma_cur_count = substr_count($values_post, ',');
                
                if($comma_count == -1)$comma_count = $comma_cur_count;

                if($comma_count != $comma_cur_count)
                {
                    $this->form_validation->set_message('_values_dropdown_check', __('Values number must be same in all languages', 'sw_win'));
                    return FALSE;
                }
            }
        }

        return true;
    }
    
    public function _value_dropdown_check(&$str)
    {
        static $comma_count = -1;
        
        foreach(sw_get_languages() as $lang)
        {
            $values_post = $this->input->post("value_".$lang['id']);
            
            if($str == $values_post)
            {
                $comma_cur_count = substr_count($values_post, ',');
                
                if($comma_count == -1)$comma_count = $comma_cur_count;

                if($comma_count != $comma_cur_count)
                {
                    $this->form_validation->set_message('_value_dropdown_check', __('Values number must be same in all languages', 'sw_win'));
                    return FALSE;
                }
            }
        }

        return true;
    }
    
	public function _captcha_check($str)
	{
        if(sw_settings('recaptcha_site_key') !== FALSE)
        {
            if(valid_recaptcha() === TRUE)
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('_captcha_check', __('Robot verification failed', 'sw_win'));
                return FALSE;
            }
        }
       
		return TRUE;
    }
    
    public function _unique_calendar($str)
    {
        if(isset($_GET['id']))
            $id = $_GET['id'];

        $this->db->where('listing_id', $this->input->post('listing_id'));
        
        if(!empty($id))
            $this->db->where('idcalendar !=', $id);
        
        $calendar = $this->calendar_m->get();
        
        if(sw_count($calendar))
        {
            $this->form_validation->set_message('_unique_calendar', __('Calendar for this listing already defined', 'sw_win'));
            return FALSE;
        }

        return TRUE;
    }

    public function _calendar_exists($str)
    {
        if(empty($str))
        {
            return TRUE;
        }

        if(!empty($str) && !is_numeric($str))
        {
            $this->form_validation->set_message('_calendar_exists', __('Listing ID must be numeric value', 'sw_win'));
            return FALSE;
        }

        $id = $str;

        $this->db->where('sw_calendar.listing_id', $id);
        
        $calendar = $this->calendar_m->get();
        
        if(sw_count($calendar) == 0)
        {
            $this->form_validation->set_message('_calendar_exists', __('Calendar for this listing missing, add first', 'sw_win'));
            return FALSE;
        }

        return TRUE;
    }

    public function _check_date($str)
    {
        if(empty($str))
        {
            return TRUE;
        }

        $id=NULL;
        if(isset($_GET['id']))
            $id = $_GET['id'];

        $date_from = date('Y-m-d H:i:s', strtotime($_POST['date_from']));
            
        $date_to = date('Y-m-d H:i:s', strtotime($str));
            

        if(isset($date_from))
        {
            if(strtotime($date_from) < time())
            {
                $this->form_validation->set_message('_check_date', __('Date from to old', 'sw_win'));
                return FALSE;
            }

            if(strtotime($date_from) >= strtotime($date_to))
            {
                $this->form_validation->set_message('_check_date', __('Date FROM should be before Date TO', 'sw_win'));
                return FALSE;
            }

            $is_defined = $this->rates_m->is_defined($_POST['listing_id'], $date_from, $date_to, $id);
            
            if(count($is_defined) > 0)
            {
                $this->form_validation->set_message('_check_date', __('Date already defined or overlaped', 'sw_win'));
                return FALSE;
            }


        }

        return TRUE;
    }

    public function _check_available($str)
    {
        if(empty($str))
        {
            return TRUE;
        }

        $id=NULL;
        if(isset($_GET['id']))
            $id = $_GET['id'];

        if(sw_is_page(sw_settings('listing_preview_page')))
        {
            if(isset($this->data['listing']))
                $_POST['listing_id'] = $this->data['listing']->idlisting;
        }

        $date_from = date('Y-m-d H:i:s', strtotime($_POST['date_from']));

        $date_to = date('Y-m-d H:i:s', strtotime($str));

        if(isset($date_from))
        {
            if(strtotime($date_from) < time())
            {
                $this->form_validation->set_message('_check_available', __('Date from to old', 'sw_win'));
                return FALSE;
            }

            if(strtotime($date_from) >= strtotime($date_to))
            {
                $this->form_validation->set_message('_check_available', __('Date FROM should be before Date TO', 'sw_win'));
                return FALSE;
            }

            if(strtotime($date_to)-strtotime($date_from) < 3600)
            {
                $this->form_validation->set_message('_check_available', __('Reservation time below 1h is not possible', 'sw_win'));
                return FALSE;
            }

            // check if rate exists

            $is_available = $this->rates_m->is_available($_POST['listing_id'], $date_from, $date_to, NULL);
            
            if(sw_count($is_available) == 0)
            {
                $this->form_validation->set_message('_check_available', __('Rates not defined in this period', 'sw_win'));
                return FALSE;
            }
            elseif(isset($is_available[0]))
            {
                // check for changeover day
                if(!empty($is_available[0]->changeover_day))
                {
                    $days = array(''=>'','0'=>__('Monday', 'sw_win'), 
                    '1'=>__('Tuesday', 'sw_win'),'2'=>__('Wednesday', 'sw_win'),
                    '3'=>__('Thursday', 'sw_win'),'4'=>__('Friday', 'sw_win'),
                    '5'=>__('Saturday', 'sw_win'),'6'=>__('Sunday', 'sw_win'));

                    $w = date("w", strtotime($date_from))-1; // you must add 1 to for Sunday
                    if($w==-1)$w=6;

                    $hours = (strtotime($date_to)-strtotime($date_from)) / 3600;
                    if($w != $is_available[0]->changeover_day)
                    {
                        $this->form_validation->set_message('_check_available', __('Wrong from changeover day, must be: ', 'sw_win').$days[$is_available[0]->changeover_day]);
                        return FALSE;
                    }
                }

                // check for min stay
                if(!empty($is_available[0]->min_stay_days))
                {
                    $hours = (strtotime($date_to)-strtotime($date_from)) / 3600;
                    if($is_available[0]->min_stay_days*24 > $hours)
                    {
                        $this->form_validation->set_message('_check_available', __('Period is to short, min days: ', 'sw_win').$is_available[0]->min_stay_days);
                        return FALSE;
                    }
                }
            }

            // check if reservation available/empty

            $is_defined = $this->reservation_m->is_defined($_POST['listing_id'], $date_from, $date_to, $id);
            
            if(sw_count($is_defined) > 0)
            {
                $this->form_validation->set_message('_check_available', __('Already reserved and confirmed', 'sw_win'));
                //.$this->db->last_query()
                return FALSE;
            }

        }

        return TRUE;
    }


    public function _unique_username($str)
    {
        // Do NOT validate if username alredy exists
        // UNLESS it's the username for the current user
        //$id = $this->session->userdata('id');
        $this->db->where('user_login', $this->input->post('username'));
        
        if(!empty($id))
            $this->db->where('ID !=', $id);
        
        $user = $this->user_m->get();
        
        if(sw_count($user))
        {
            $this->form_validation->set_message('_unique_username', '%s '.__('already exists in database', 'sw_win'));
            return FALSE;
        }

        return TRUE;
    }
    
    public function _unique_email($str)
    {
        // Do NOT validate if username alredy exists
        // UNLESS it's the username for the current user
        //$id = $this->session->userdata('id');
        $this->db->where('user_email', $this->input->post('email'));
        
        if(!empty($id))
            $this->db->where('ID !=', $id);
        
        $user = $this->user_m->get();
        
        if(sw_count($user))
        {
            $this->form_validation->set_message('_unique_email', '%s '.__('already exists in database', 'sw_win'));
            return FALSE;
        }

        return TRUE;
    }
          
    public function disable_demo(){
        
        if(config_item('app_type') == 'demo')
        {
            $this->form_validation->set_message('disable_demo', __('Disable in demo', 'sw_win'));
            return FALSE;
        }
        return TRUE;
        
    }  
    
    public function min_images($str){
        
        $this->load->model('file_m');
        
        
        $files = $this->file_m->get_repository($str);
        
        if(sw_count($files) == 0)
        {
            $this->form_validation->set_message('min_images', __('Min one image should be exists, please upload some images', 'sw_win'));
            return FALSE;
        }
        
        return TRUE;
    }  
        
}