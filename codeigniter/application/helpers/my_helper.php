<?php

if ( ! function_exists('add_meta_title'))
{
    function add_meta_title ($string)
    {
        $CI =& get_instance();
        $CI->data['meta_title'] = e($string) . ' - ' . $CI->data['meta_title'];
    }
}

if ( ! function_exists('btn_edit'))
{
    function btn_edit($uri)
    {
        return anchor($uri, '<i class="glyphicon glyphicon-pencil"></i> '.__('Edit', 'sw_win'), array('class'=>'btn btn-success btn-xs'));
    }
}

if ( ! function_exists('btn_read'))
{
    function btn_read($uri, $title=NULL)
    {
        if(empty($title))$title=__('Read', 'sw_win');
        
        
        return anchor($uri, '<i class="glyphicon glyphicon-search"></i> '.$title, array('class'=>'btn btn-primary btn-xs'));
    }
}

if ( ! function_exists('btn_open'))
{
    function btn_open($uri)
    {
        return anchor($uri, '<i class="glyphicon glyphicon-search"></i> '.__('Open', 'sw_win'), array('class'=>'btn btn-primary btn-xs', 'target'=>'_blank'));
    }
}

if ( ! function_exists('btn_delete_noconfirm'))
{
    function btn_delete_noconfirm($uri)
    {
        return anchor($uri, '<i class="glyphicon glyphicon-remove"></i> '.__('Delete', 'sw_win'), array('class'=>'btn btn-danger btn-xs delete_button'));
    }
}

if ( ! function_exists('btn_delete'))
{
    function btn_delete($uri)
    {
        return anchor($uri, '<i class="glyphicon glyphicon-remove"></i> '.__('Delete', 'sw_win'), array('onclick' => 'return confirm(\''.__('Are you sure?', 'sw_win').'\')', 'class'=>'btn btn-danger btn-xs delete_button'));
    }
}

if ( ! function_exists('get_file_extension'))
{
    function get_file_extension($filepath)
    {
        return substr($filepath, strrpos($filepath, '.')+1);
    }
}

if ( ! function_exists('character_hard_limiter'))
{
    function character_hard_limiter($string, $max_len)
    {
        if(strlen($string)>$max_len)
        {
            return substr($string, 0, $max_len-3).'...';
        }
        
        return $string;
    }
}

if ( ! function_exists('get_numeric_val'))
{
    function get_numeric_val(&$string_val)
    {
        $val_numeric = NULL;
        $value_n = trim($string_val);
        $value_n = str_replace("'", '', $value_n);
        $value_n = str_replace("�", '', $value_n);

        //If we have both , and .
        $comma_pos = strpos($string_val, ',');
        $dot_pos = strpos($string_val, '.');
        if($dot_pos !== FALSE && $dot_pos !== FALSE)
        {
            if($dot_pos > $comma_pos)
            {
                // Example 120,000.00
                $value_n = str_replace(",", '', $value_n);
                
            }
            else
            {
                // Example 120.000,00
                $value_n = str_replace(".", '', $value_n);
                $value_n = str_replace(",", '.', $value_n);
            }
        }
        
        // Example for 100,000
        $comma_pos = strpos($value_n, ',');
        if($comma_pos < strlen($value_n)-3)
        {
            $pre_val = substr($value_n,0,-3);
            $pos_val = substr($value_n,-3);
            
            // remove , if not decimal
            $pre_val = str_replace(",", '', $pre_val);
            
            $value_n = $pre_val.$pos_val;
        }

        // Example 100000,00
        $value_n = str_replace(",", '.', $value_n);

        if( is_numeric($value_n) && strlen($value_n)<=11 )
        {
            $val_numeric = floatval($value_n);
        }
        
        return $val_numeric;
    }
}

if ( ! function_exists('price_format'))
{
    function price_format($value, $lang_id=NULL)
    {
        $CI =& get_instance();
        
        return $value;
    }
}

if ( ! function_exists('custom_number_format'))
{
    function custom_number_format($value, $lang_id=NULL)
    {
        $CI =& get_instance();
        
        $value = number_format($value, 2, '.', '');
        
        return $value;
    }
}

if ( ! function_exists('format_d'))
{
    function format_d($value)
    {
        $CI =& get_instance();
        
        $value = date("m/d/y", strtotime($value));
        
        return $value;
    }
}

if ( ! function_exists('_jse'))
{
    function _jse($content)
    {
        $output = $content;
        
        $output = str_replace("'", "\'", $output);
        $output = str_replace('"', '\"', $output);
        $output = str_replace(array("\n", "\r"), '', $output);
        
        echo $output;
    }
}

if ( ! function_exists('print_var'))
{
    function print_var($var, $var_name)
    {
        if(is_array($var))
        {
            foreach($var as $key=>$value)
            {
                echo '$'.$var_name."['$key']='$value';<br />";
            }
        }
    }
}

if ( ! function_exists('_empty'))
{
    function _empty($var)
    {
        return empty($var);
    }
}

if ( ! function_exists('search_value'))
{
    function search_value($field_id, $custom_return = NULL, $custom_default='')
    {
        $CI =& get_instance();
        
        $_MERG = array_merge($_GET, $_POST);

        if(!empty($_MERG['search_'.$field_id]))
        {        
            if($custom_return !== NULL)
                return $custom_return;
            
            return $_MERG['search_'.$field_id];
        }        
        
        return $custom_default;
    }
}

if ( ! function_exists('_ch'))
{
    function _ch(&$var, $empty = '-', $limit=NULL)
    {
        if(empty($var))
            return $empty;
            
        if($limit !== NULL)
        {
            $var = sw_character_limiter($var, $limit);
        }

        return $var;
    }
}

if ( ! function_exists('_che'))
{
    function _che(&$var = NULL, $empty = '')
    {
        if(empty($var))
            echo $empty;
            
        echo $var;
    }
}

if ( ! function_exists('flashdata_message'))
{
    function flashdata_message()
    {
        $CI =& get_instance();
        
        if($CI->session->flashdata('message'))
        {
            echo $CI->session->flashdata('message');
        }
    }
}

if ( ! function_exists('sw_is_img'))
{
    function sw_is_img($filename)
    {

        if(!file_exists($filename))
        {
            $filename = sw_win_upload_path().'files/'.$filename;
        }

        if(!file_exists($filename))return false;

        if(@is_array(getimagesize($filename))){
            return true;
        } 

        return false;
    }
}

if ( ! function_exists('_show_img'))
{
    function _show_img($filename, $dim = '640x480', $cut_enabled=false, $bkg=null)
    {
        if(!file_exists($filename))
        {
            $filename = basename($filename);
        }
        else
        {
            $uploads_pos = strpos($filename, 'uploads');
            $filename = substr($filename, $uploads_pos);
        }
            

        $filename = str_replace('%20', ' ', $filename);
        $filename_encode = rawurlencode($filename);
        
        if(file_exists(sw_win_upload_path().'files/strict_cache/'.$dim.$filename))
        {
            return sw_win_upload_dir().'/files/strict_cache/'.$dim.$filename_encode;
        }
        else if(file_exists(sw_win_upload_path().'files/strict_cache/'.$dim.basename($filename)))
        {
            $filename = basename($filename);
            $filename = str_replace('%20', ' ', $filename);
            $filename_encode = rawurlencode($filename);

            return sw_win_upload_dir().'/files/strict_cache/'.$dim.$filename_encode;
        }

        if(!file_exists(WP_CONTENT_DIR . '/'.$filename) &&
          (!file_exists(sw_win_upload_path().'files/'.$filename) || empty($filename_encode)))
        {
            $filename_encode = '../assets/img/no-photo.png';
        }
        
        if($cut_enabled === true)
        {
            return plugins_url(SW_WIN_SLUG.'' )."/strict_image.php?d=$dim&f=$filename_encode&cut=true&bkg=$bkg";
        }
        
        return plugins_url(SW_WIN_SLUG.'' )."/strict_image.php?d=$dim&f=$filename_encode&bkg=$bkg";
    }
}

if ( ! function_exists('_generate_results_item'))
{
    function _generate_results_item($estate_data, $json_output = false)
    {
        $CI =& get_instance();
        
        //Get template settings
        $template_name = $CI->data['settings']['template'];
        
        //Load view
        if(file_exists(FCPATH.'templates/'.$template_name.'/widgets/results_item.php'))
        {
            $output = $CI->load->view($template_name.'/widgets/results_item.php', $estate_data, true);
            
            if($json_output)
            {
                $output = str_replace('"', '\"', $output);
                $output = str_replace(array("\n", "\r"), '', $output);
                return $output;
            }
            
            echo $output;
        }
        else
        {
            echo 'NOT FOUND: results_item.php';
        }
    }
}


if ( ! function_exists('limit_to_numwords'))
{
    function limit_to_numwords($string, $numwords){
        $excerpt = explode(' ', $string, $numwords + 1);
        if (sw_count($excerpt) >= $numwords) {
            array_pop($excerpt);
        }
        $excerpt = implode(' ', $excerpt);
        return $excerpt;
    }
}

if ( ! function_exists('e'))
{
    function e($string){
        return htmlentities($string);
    }
}

/**
* Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
* @author Joost van Veen
* @version 1.0
*/
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        
        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
        
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}



if (!function_exists('dump_basic'))
{
    function dump_basic ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        print_r($var);
        $output = ob_get_clean();
        
        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre>' . $output . '</pre>';
        
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}
 
 
if (!function_exists('dump_exit'))
{
    function dump_exit($var, $label = 'Dump', $echo = TRUE)
    {
        dump ($var, $label, $echo);
        exit;
    }
}

if (!function_exists('check_set'))
{
    function check_set($test, $default)
    {
        if(isset($test))
            return $test;
            
        return $default;
    }
}

if (!function_exists('check_combine_set'))
{
    function check_combine_set($main, $test, $default)
    {
        if(sw_count(explode(',', $main)) == sw_count(explode(',', $test)) && 
        sw_count(explode(',', $main)) > 0 && sw_count(explode(',', $test)) > 0)
        {
            return $main;
        }

        return $default;
    }
}

/**
* Returns the specified config item
*
* @access	public
* @return	mixed
*/
if ( ! function_exists('config_db_item'))
{
	function config_db_item($item)
	{
		static $_config_item = array();
        static $_db_settings = array();

		if ( ! isset($_config_item[$item]))
		{
			$config =& get_config();
            
            // [check-database]
            if(sw_count($_db_settings) == 0)
            {
                $CI =& get_instance();
                $CI->load->model('settings_m');
                $_db_settings = $CI->settings_m->get_fields();
            }

            if(isset($_db_settings[$item]))
            {
                $_config_item[$item] = $_db_settings[$item];
                return $_config_item[$item];
            }
            // [/check-database]
            
			if ( ! isset($config[$item]))
			{
				return FALSE;
			}
			$_config_item[$item] = $config[$item];
		}

		return $_config_item[$item];
	}
}

if ( ! function_exists('get_ol'))
{
    function get_ol ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (sw_count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="option_sortable">' : '<ol>';
    		
    		foreach ($array as $item) {
    		  
                if($child == FALSE){
                    $item_children = null;
                    if(isset($item['children']))$item_children = $item['children'];
                    $item = $item['parent'];
                    if(isset($item_children))$item['children'] = $item_children;
                }
              
                $visible = '';
                if($item['is_table_visible'] == 1)
                    $visible = '<i class="glyphicon glyphicon-th-large"></i>';
                
                $locked='';
                if($item['is_hardlocked'])
                    $locked = '<i class="glyphicon glyphicon-lock" style="color:red;"></i>';
                else if($item['is_locked'] == 1)
                    $locked = '<i class="glyphicon glyphicon-lock"></i>';
                    
                $frontend='';
                if($item['is_submission_visible'] == 0)
                    $frontend = '<i class="glyphicon glyphicon-eye-close"></i>';
                    
                $frontend='';
                if($item['is_translatable'] == 1)
                    $frontend = '<i class="glyphicon glyphicon-random"></i>';
                    
                $required='';
                if($item['is_required'] == 1)
                    $required = '*';
                
                $icon = '';
                $CI =& get_instance();
//                $template_name = $CI->data['settings']['template'];
//                if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png'))
//                {
//                    $icon = '<img class="results-icon" src="'.base_url('templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png').'" alt="'.$item['option'].'"/>&nbsp;&nbsp;';
//                }
                
    			$str .= '<li id="list_' . $item['idfield'] .'">';
    			$str .= '<div class="" alt="'.$item['idfield'].'" >#'.$item['idfield'].'&nbsp;&nbsp;&nbsp;'.$icon.$required.$item['field_name'].'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-'.$item['color'].'">'.$item['type'].'</span>&nbsp;&nbsp;'.$visible.'&nbsp;&nbsp;'.$locked.'&nbsp;&nbsp;'.$frontend.'<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.admin_url("admin.php?page=listing_addfield&id=".$item['idfield']).'"><i class="glyphicon glyphicon-pencil"></i></a>'.
                              ($item['is_locked']||$item['is_hardlocked']?'':'<a onclick="return confirm(\''.__('Are you sure?', 'sw_win').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.__('Loading...', 'sw_win').'" href="'.admin_url("admin.php?page=listing_fields&function=remfield&id=".$item['idfield']).'"><i class="glyphicon glyphicon-remove"></i></a>')
                            .'</div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && sw_count($item['children'])) {
    				$str .= get_ol($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}



if ( ! function_exists('sw_translate'))
{
    function sw_translate($word, $from, $to)
    {
        $CI =& get_instance();
        
        if(sw_settings('google_translate_api_key') == '')
        {
            // Use mymemory
            $CI->load->library('mymemorytranslation');
            $word = $CI->mymemorytranslation->translate($word, $from, $to);
        }
        else
        {
            // Use google translate api
            $CI->load->library('gtranslation');
            $word = $CI->gtranslation->translate($word, $from, $to);
        }
        
        return $word;
    }
}

if (!function_exists('_form_messages'))
{
    function _form_messages($custom_message = NULL, $custom_message_failed = NULL, $form_id=NULL)
    {
        $CI =& get_instance();

        if($form_id !== NULL)
        {
            if($CI->input->get_post('widget_id') != $form_id)
                return;
        }
        
        if($CI->input->get_post('updated') == 'true')
        {
            if(empty($custom_message))
                $custom_message = __('Changes saved', 'sw_win');
            
            echo '<div class="alert alert-success alert-dismissible">'.$custom_message.'</div>';
        }
        elseif($CI->input->get_post('updated') == 'false')
        {
            if(empty($custom_message_failed))
                $custom_message_failed = __('Failed from unknown reason', 'sw_win');
            
            echo '<div class="alert alert-danger alert-dismissible">'.$custom_message_failed.'</div>';
        }
        
        echo validation_errors();
    }
}

if (!function_exists('_has_error'))
{
    function _has_error($field_name)
    {
        if(form_error($field_name) != '')
            echo 'has-error';
    }
}

if (!function_exists('_fv'))
{
    function _fv($form_object, $form_field, $type = 'TEXT', $default='', $update_default='')
    {
        $CI =& get_instance();
        
        if($type == 'CHECKBOX')
        {
            if($CI->input->post($form_field) == '1')
            {
                return 'checked';
            }
            else if(isset($CI->data[$form_object]->$form_field))
            {
                if($CI->data[$form_object]->$form_field == 1)
                    return 'checked';
            }
            
            // if default is 1 and we are on add new, not update
            if($default == '1' && !isset($CI->data[$form_object]))
            {
                return 'checked';
            }
            
            return '';
        }
        else if($type == 'MULTISELECT')
        {
            if($CI->input->post($form_field) != '')
            {
                return $CI->input->post($form_field);
            }
        
            if(isset($CI->data[$form_object]->$form_field))
            {
                return $CI->data[$form_object]->$form_field;
            }    
            
            return $default;
        }

        if($CI->input->post($form_field) != '')
        {
            $value = $CI->input->post($form_field);
            $value = str_replace( '"','&quot;', $value );
            $value = str_replace( "'",'&#039;', $value );
            return $value;
        }

        if(isset($CI->data[$form_object]->$form_field))
        {
            $value = $CI->data[$form_object]->$form_field;
            $value = str_replace( '"','&quot;', $value );
            $value = str_replace( "'",'&#039;', $value );
            return $value;
        }    

        // if we are on add new, not update
        if(!isset($CI->data[$form_object])){
            $value = $default;
            $value = str_replace( '"','&quot;', $value );
            $value = str_replace( "'",'&#039;', $value );
            return $value;
        }
        return $update_default;
    }
}


if ( ! function_exists('prepare_search_query_GET'))
{
	function prepare_search_query_GET($columns = array(), $model_name = NULL, $external_columns = array())
	{
		$CI =& get_instance();
        $_GET_clone = array_merge($_GET, $_POST);
        
        $smart_search = '';
        if(isset($_GET_clone['search']))
            $smart_search = $_GET_clone['search']['value'];
            
        $available_fields = $CI->$model_name->get_available_fields();
        
        //$table_name = substr($model_name, 0, -2);  
        
        $columns_original = array();
        foreach($columns as $key=>$val)
        {
            $columns_original[$val] = $val;
            
            // if column contain also "table_name.*"
            $splited = explode('.', $val);
            if(sw_count($splited) == 2)
                $val = $splited[1];
            
            if(isset($available_fields[$val]))
            {
                
            }
            else
            {
                if(!in_array($columns[$key], $external_columns))
                {
                    unset($columns[$key]);
                }
            }
        }

        if(sw_count($_GET_clone) > 0)
        {
            unset($_GET_clone['search']);
            
            // For quick/smart search
            if(sw_count($columns) > 0 && !empty($smart_search))
            {
                $gen_q = '';
                foreach($columns as $key=>$value)
                {
                    if(substr_count($value, 'id') > 0 && is_numeric($smart_search))
                    {
                        $gen_q.="$value = $smart_search OR ";
                    }
                    else if(substr_count($value, 'date') > 0)
                    {
                        $gen_search = sw_generate_slug($smart_search, ' ');
                        
                        $gen_q.="$value LIKE '%$gen_search%' OR ";
                    }
                    else
                    {
                        $gen_q.="$value LIKE '%$smart_search%' OR ";
                    }
                }
                $gen_q = substr($gen_q, 0, -4);
                
                if(!empty($gen_q))
                    $CI->db->where("($gen_q)");
            }
            
            // For column search
            if(isset($_GET_clone['columns']))
            {
                $gen_q = '';
                
                //var_dump($_GET_clone['columns']);
                
                foreach($_GET_clone['columns'] as $key=>$row)
                {
                    if(!empty($row['search']['value']))
                    if(isset($columns[$key]))
                    {
                        $col_name = $columns[$key];
                        
                        if(substr_count($row['data'], 'id') > 0 && is_numeric($row['search']['value']))
                        {
                            // ID is always numeric
                            
                            $gen_q.=$col_name." = ".$row['search']['value']." AND ";
                        }
                        else if(substr_count($row['data'], 'date') > 0)
                        {
                            // DATE VALUES
                            
                            $gen_search = sw_generate_slug($row['search']['value'], ' ');
                            
                            $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                        }
                        else if(substr_count($row['data'], 'is_') > 0)
                        {
                            // CHECKBOXES
                            
                            if($row['search']['value']=='on')
                            {
                                $gen_search = 1;
                                $gen_q.=$col_name." LIKE '%".$gen_search."%' AND ";
                            }
                            else if($row['search']['value']=='off')
                            {
                                $gen_q.=$col_name." IS NULL AND ";
                            }
                        }
                        else
                        {
                            $gen_q.=$col_name." LIKE '%".$row['search']['value']."%' AND ";
                        }
                    }
                    elseif(isset($columns_original[$row['data']]) && !isset($available_fields[$row['data']]) && isset($available_fields['json_object']))
                    {
                        $gen_q.= " json_object LIKE '%\"".$row['data']."\":\"".trim($row['search']['value'])."\"%' AND ";
                    }
                }
                
                $gen_q = substr($gen_q, 0, -5);
                
                if(!empty($gen_q))
                    $CI->db->where("($gen_q)");
            }
        }
	}
}


if ( ! function_exists('prepare_frontend_search_query_GET'))
{
	function prepare_frontend_search_query_GET($model_name = 'listing_m', $custom_vars = array('search_is_activated'=>1), $order_by = array())
	{
		$CI =& get_instance();
        
        $all_qs = array_merge($_POST, $_GET);
        
        // Special situation for properties available for rent
        if(isset($all_qs['search_4']) && !empty($all_qs['search_4']) && sw_count($all_qs['search_4']) == 1 && isset($all_qs['search_4'][0])){
            if($all_qs['search_4'][0] == esc_html__('For Rent', 'nexos')) {
                
                if(isset($all_qs['search_36_from']))
                    $_POST['search_37_from'] = $all_qs['search_37_from'] = $all_qs['search_36_from'];
                
                if(isset($all_qs['search_36_to']))
                    $_POST['search_37_to'] = $all_qs['search_37_to'] = $all_qs['search_36_to'];
                
                if(isset($all_qs['search_36']))
                    $_POST['search_37'] = $all_qs['search_37'] = $all_qs['search_36'];
                
                unset($all_qs['search_36_from'], $all_qs['search_36_to'], $all_qs['search_36']);
                
            }
        }

        // hide expired listings
        if(sw_settings('expire_days') > 0)
            $custom_vars['search_date_modified_from'] = date('Y-m-d H:i:s', time()-sw_settings('expire_days')*86400);

        if(is_array($custom_vars))
            $all_qs = array_merge($all_qs, $custom_vars);

        $CI->load->model($model_name);
        
        $available_fields = $CI->$model_name->get_available_fields();
        
        $all_where = array();
        $booking_added=false;
        $enable_multiple_treefield=false;
        
        $parameters = array();
        foreach($all_qs as $key => $value){
            if(substr($key,0,7)=='search_')
            {
                $value_curr = $CI->input->get_post($key, TRUE);
                
                if(isset($custom_vars[$key]))
                    $value_curr = $custom_vars[$key];

                $parameters[str_replace('search_', 'field_', $key)] = $value_curr;
                
                if($key == 'search_radius' && isset($parameters['field_where']))
                {
                    $CI->load->library('ghelper');

                    $coordinates_center = $CI->ghelper->getCoordinates($parameters['field_where']);
                    $search_radius = $parameters['field_radius'];
                    
                    if(sw_count($coordinates_center) >= 2 && $coordinates_center['lat'] != 0 && is_numeric($search_radius))
                    {
                        $distance_unit = 'km';
                        if(__('km', 'sw_win') == 'm')
                        {
                            $distance_unit = 'm';
                        }
                        
                        // calculate rectangle
                        $rectangle_ne = $CI->ghelper->getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 45, $search_radius, $distance_unit);
                        $rectangle_sw = $CI->ghelper->getDueCoords($coordinates_center['lat'], $coordinates_center['lng'], 225, $search_radius, $distance_unit);
                        
                        $gps_ne = explode(', ', $rectangle_ne);
                        $gps_sw = explode(', ', $rectangle_sw);
//                        $CI->db->where("(listing.lat < '$gps_ne[0]' AND listing.lat > '$gps_sw[0]' AND 
//                                           listing.lng < '$gps_ne[1]' AND listing.lng > '$gps_sw[1]')");
                        
                        $all_where["(sw_listing.lat < '$gps_ne[0]' AND sw_listing.lat > '$gps_sw[0]' AND 
                                           sw_listing.lng < '$gps_ne[1]' AND sw_listing.lng > '$gps_sw[1]')"] = NULL;
                        
                        // Now search is surely by radius/coordinates
                        unset($parameters['field_where']);
                    }
                }
                else if(($key == 'search_booking_from' || $key == 'search_booking_to') && !$booking_added)
                {
                    // algorithm to search free listings

                    /*
                    
                    SELECT * FROM `wp_sw_listing` JOIN `wp_sw_rates` ON `wp_sw_rates`.`listing_id` = `wp_sw_listing`.`idlisting` 
                    LEFT JOIN `wp_sw_reservation` ON `wp_sw_reservation`.`listing_id` = `wp_sw_listing`.`idlisting` 
                    JOIN `wp_sw_listing_lang` ON `wp_sw_listing`.`idlisting`= `wp_sw_listing_lang`.`listing_id` 
                    WHERE 
                    (`wp_sw_rates`.`date_from` < '2019-04-27 16:30:00' 
                    AND `wp_sw_rates`.`date_to` > '2019-04-29 16:30:00' 
                        AND (`wp_sw_reservation`.`date_from` IS NULL 
                        OR NOT 
                            ( `wp_sw_reservation`.`date_from` < '2019-04-29 16:30:00' 
                            AND `wp_sw_reservation`.`date_to` > '2019-04-27 16:30:00' 
                            AND `wp_sw_reservation`.`is_confirmed` = 1 
                        ) ) ) 
                    AND `is_activated` = 1 
                    AND `lang_id` = 1 
                    ORDER BY `idlisting` DESC, `wp_sw_listing`.`idlisting` DESC LIMIT 50

                    */

                    $query_temp_rat = array();
                    $query_temp_res = array();

                    if(!isset($all_qs['search_booking_from']))
                    {
                        $all_qs['search_booking_from'] = date("Y-m-d H:i:s", strtotime($all_qs['search_booking_to'] . ' -1 day'));
                    }

                    if(!isset($all_qs['search_booking_to']))
                    {
                        $all_qs['search_booking_to'] = date("Y-m-d H:i:s", strtotime($all_qs['search_booking_from'] . ' +1 day'));
                    }

                    $query_temp_rat[] = "sw_rates.date_from < '".date("Y-m-d H:i:s", strtotime($all_qs['search_booking_from']))."' ";
                    $query_temp_rat[] = "sw_rates.date_to > '".date("Y-m-d H:i:s", strtotime($all_qs['search_booking_to']))."' ";
                    
                    $query_temp_res[] = "`".$GLOBALS['table_prefix']."sw_reservation`.`date_from` < '".date("Y-m-d H:i:s", strtotime($all_qs['search_booking_to']))."' ";
                    $query_temp_res[] = "`".$GLOBALS['table_prefix']."sw_reservation`.`date_to` > '".date("Y-m-d H:i:s", strtotime($all_qs['search_booking_from']))."' ";
                    $query_temp_res[] = "`".$GLOBALS['table_prefix']."sw_reservation`.`is_confirmed` = 1";


                    $all_where["( ".join(' AND ', $query_temp_rat)." AND (`".$GLOBALS['table_prefix']."sw_reservation`.`date_from` IS NULL OR NOT ( ".join(' AND ', $query_temp_res)." )))"] = NULL;

                    $booking_added=true;
                }
                else if($key == 'search_what' && isset($parameters['field_what']))
                {
                    $q = " (json_object LIKE '%".trim($parameters['field_what'])."%' OR address LIKE '%".trim($parameters['field_what'])."%') ";
                    
                    if(is_numeric(trim($parameters['field_what'])))
                    {
                        $q = " (idlisting = '".trim($parameters['field_what'])."') ";
                    }

                    //$CI->db->where($q);
                    $all_where[$q] = NULL;
                }
                else if($key == 'search_rectangle' && isset($parameters['field_rectangle']))
                { // Added for old mobile api compatibility
                    $gps_coo = explode(', ', $parameters['field_rectangle']);
                    
                    if(sw_count($gps_coo) == 4)
                    {
                        $all_where["(sw_listing.lat < '$gps_coo[2]' AND sw_listing.lat > '$gps_coo[0]' AND 
                                           sw_listing.lng < '$gps_coo[3]' AND sw_listing.lng > '$gps_coo[1]')"] = NULL;
                    }
                    
                    unset($parameters['field_rectangle']);
                }
                else if($key == 'search_category' && is_numeric($value_curr))
                {
                    if(sw_settings('recursive_search') && !sw_settings('enable_multiple_treefield'))
                    {
                        $CI->load->model('treefield_m');
                        
                        // Fetch all child categories
                        $childs = array();
                        $childs[intval($value_curr)] = intval($value_curr);
                        $CI->treefield_m->get_all_childs($value_curr, $childs);
                        
                        // search for all child categories
                        if(is_array($childs))
                        {
                            $all_where['sw_listing.category_id'] = $childs;
                        }
                            
                    }
                    else
                    {
                        if(sw_settings('enable_multiple_treefield'))
                        {
                            $all_where["( sw_listing.category_id = $value_curr OR sw_treefield_listing.treefield_id = $value_curr )"] = NULL;
                            $enable_multiple_treefield=true;
                        }
                        else
                        {
                            $all_where["( sw_listing.category_id = $value_curr )"] = NULL;
                        }
                        
                    }
                }
                else if($key == 'search_location' && is_numeric($value_curr))
                {
                    if(sw_settings('recursive_search') && !sw_settings('enable_multiple_treefield'))
                    {
                        $CI->load->model('treefield_m');
                        
                        // Fetch all child categories
                        $childs = array();
                        $childs[intval($value_curr)] = intval($value_curr);
                        $CI->treefield_m->get_all_childs($value_curr, $childs);
                        
                        // search for all child categories
                        if(is_array($childs))
                        {
                            //$CI->db->where_in('listing.location_id', $childs);
                            $all_where['sw_listing.location_id'] = $childs;
                        }
                            
                    }
                    else
                    {
                        if(sw_settings('enable_multiple_treefield'))
                        {
                            $all_where["( sw_listing.location_id = $value_curr OR sw_treefield_listing.treefield_id = $value_curr )"] = NULL;
                            $enable_multiple_treefield=true;
                        }
                        else
                        {
                            $all_where["( sw_listing.location_id = $value_curr )"] = NULL;
                        }
                    }
                }
            }
        }
        
        unset($parameters['field_view'], $parameters['map_num_listings'], $parameters['field_radius'], 
              $parameters['field_category'], $parameters['field_what'], $parameters['field_here'], 
              $parameters['field_location'], $parameters['field_booking_from'], $parameters['field_booking_to']);
        
        //var_dump($parameters);
        
        foreach($parameters as $key=>$val)
        {
            if($val == '')continue;
            
            if(substr($key, -3) == '_to')
            {
                $col = substr($key, 0, -3);
                
                if(isset($available_fields[$col.'_int']))
                {
                    
                    if((bool)strtotime($val)) {
                        $val = strtotime($val);
                    }
                    
                    //$CI->db->where($col.'_int <', $val);
                    $all_where[$col.'_int <='] = $val;
                }
                elseif(strpos($col, 'date') !== FALSE)
                {
                    $col_cus = str_replace('field_', '', $col);
                    $all_where[$col_cus.' <='] = $val;
                }
                else
                {
                    $all_where[$col.' <='] = $val;
                }
                
                continue;
            }
            else if(substr($key, -5) == '_from')
            {
                $col = substr($key, 0, -5);
                
                if(isset($available_fields[$col.'_int']))
                {
                    if((bool)strtotime($val)) {
                        $val = strtotime($val);
                    }
                    
                    //$CI->db->where($col.'_int >', $val);
                    $all_where[$col.'_int >='] = $val;
                }
                elseif(strpos($col, 'date') !== FALSE)
                {
                    $col_cus = str_replace('field_', '', $col);
                    $all_where[$col_cus.' >='] = $val;
                }
                else
                {
                    $col_cus = str_replace('field_', '', $col);
                    $all_where[$col.' >='] = $val;
                }
                continue;
            }
            
            if(isset($available_fields[$key.'_int']))
            {
                //$CI->db->where($key.'_int', $val);
                $all_where[$key.'_int'] = $val;
            }
            elseif(isset($available_fields[$key]))
            {
                
                if(is_array($val))
                {
                    $gen_or = '';
                    foreach($val as $subval)
                    {
                        if($subval == 'IS NULL')
                        {
                            $gen_or.= $key.' IS NULL OR ';
                        }
                        else
                        {
                            $gen_or.= $key.'=\''.$subval.'\' OR ';
                        }
                    }
                    $gen_or = substr($gen_or,0,-3);
                    
                    //$CI->db->where(' ( '.$gen_or.' ) ');
                    $all_where[' ( '.$gen_or.' ) '] = NULL;
                }
                else
                {
                    //$CI->db->where($key, $val);
                    $all_where[$key] = $val;
                }
            }
            elseif(isset($available_fields[substr($key, 6)]))
            {
                if($val == 'IS NULL')
                {
                    //$CI->db->where(substr($key, 6).' IS NULL', NULL);
                    $all_where[substr($key, 6).' IS NULL'] = NULL;
                }
                else
                {
                    //$CI->db->where(substr($key, 6), $val);
                    $all_where[substr($key, 6)] = $val;
                }
            }
            elseif(strpos($key, 'radius') !== FALSE)
            {
                
            }
            elseif(strpos($key, 'order') !== FALSE)
            {
                if(strpos(trim($val), 'field') === FALSE)
                {
                    $order_by[] = trim($val);
                }
                else
                {
                    $order_by = array(trim($val));
                }
                
            }
            elseif(isset($available_fields['json_object']))
            {
                if(is_array($val))
                {
                    $gen_or = '';
                    foreach($val as $subval)
                    {
                        $gen_or.= ' json_object LIKE \'%'.trim($subval).'%\' OR address LIKE \'%'.trim($subval).'%\' OR ';
                    }
                    $gen_or = substr($gen_or,0,-3);
                    
                    //$CI->db->where(' ( '.$gen_or.' ) ');
                    $all_where[' ( '.$gen_or.' ) '] = NULL;
                }
                else
                {
                    if($val == 'true' || $val == '1') // for checkboxes support both values, true|1
                    {
                        $val = '1';
                        $val = '"'.$key.'":"'.$val.'"';
                    }
                    
                    $q = " (json_object LIKE '%".trim($val)."%' OR address LIKE '%".trim($val)."%') ";
                    
                    //$CI->db->where($q);
                    $all_where[$q] = NULL;
                }
            }
        }

        if($booking_added === true)
        {
            $CI->db->join('sw_rates', 'sw_rates.listing_id = sw_listing.idlisting');
            $CI->db->join('sw_reservation', 'sw_reservation.listing_id = sw_listing.idlisting', 'left');
        }

        if($enable_multiple_treefield === true)
        {
            $CI->db->join('sw_treefield_listing', 'sw_treefield_listing.listing_id = sw_listing.idlisting', 'left');
        }
        
        if(is_array($all_where))
        foreach($all_where as $key=>$val)
        {
            if(is_array($val))
            {
                $CI->db->where_in($key, $val);
            }
            else
            {
                $CI->db->where($key, $val);
            }
        }
        
        if(sw_count($order_by) > 0) {
            $order_str = join(', ', $order_by);
            if(isset($all_qs['search_4']) && !empty($all_qs['search_4']) && sw_count($all_qs['search_4']) == 1 && isset($all_qs['search_4'][0])){
                if($all_qs['search_4'][0] == esc_html__('For Rent', 'nexos')) {
                    $order_str = str_replace("field_36_int", "field_37_int", $order_str);
                }
            } elseif (isset($all_qs['search_4']) && !empty($all_qs['search_4']) && is_string($all_qs['search_4'])) {
                if($all_qs['search_4'] == esc_html__('For Rent', 'nexos')) {
                    $order_str = str_replace("field_36_int", "field_37_int", $order_str);
                }
            }
            
            $CI->db->order_by($order_str);
        }
        
        return;

	}
}

if ( ! function_exists('_field'))
{
    function _field($listing, $field_id, $limit=NULL)
    {
        if(!is_object($listing))
            return '-';
            
        $prefix = '';
        $suffix = '';
        
        $CI =& get_instance();

        $CI->load->model('field_m');
        $field_data = $CI->field_m->get_field_data($field_id, sw_current_language_id());
        if(!empty($field_data))
        {
            $prefix = $field_data->prefix;
            $suffix = $field_data->suffix;
        }
        
        if(sw_settings('number_format_i18n_enabled') && ($field_id == 36 || $field_id == 37) && isset($listing->{$field_id}))
        {
            $value = (int)str_replace('.','',$listing->{$field_id});
            $value = number_format_i18n($value);
            return sw_character_limiter($prefix.$value.$suffix, $limit);
        } else if(sw_settings('number_format_i18n_enabled') && ($field_id == 36 || $field_id == 37) && isset($listing->{'field_'.$field_id})) {
            $value = (int)str_replace('.','',$listing->{'field_'.$field_id});
            $value = number_format_i18n($value);
            return sw_character_limiter($prefix.$value.$suffix, $limit);
        }
        
        if(!is_numeric($field_id) && isset($listing->{$field_id}))
        {
            return sw_character_limiter($prefix.$listing->{$field_id}.$suffix, $limit);
        }
        
        if(isset($listing->{'field_'.$field_id}))
        {
            return sw_character_limiter($prefix.$listing->{'field_'.$field_id}.$suffix, $limit);
        }
        elseif(isset($listing->json_object))
        {                
            //$json_string = sw_win_prepare_json_basic($listing->json_object);
            $json_string = $listing->json_object;


            $obj = json_decode($json_string);

            if(json_last_error()) {

                // This will remove unwanted characters.
                // Check http://www.php.net/chr for details
                for ($i = 0; $i <= 31; ++$i) { 
                    $json_string = str_replace(chr($i), "", $json_string); 
                }
                $json_string = str_replace(chr(127), "", $json_string);

                // This is the most common part
                // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
                // here we detect it and we remove it, basically it's the first 3 characters 
                if (0 === strpos(bin2hex($json_string), 'efbbbf')) {
                   $json_string = substr($json_string, 3);
                }

                $obj = json_decode($json_string); 
            }
            
            if(json_last_error()) {
                $json_string = stripslashes($json_string); 
                $obj = json_decode($json_string);
            }
                       
            if (json_last_error()) {
                /*
                echo '<pre>';
                echo $json_string;
                echo '</pre>';
                */

                return '-JSON-';

                switch (json_last_error()) {
                    case JSON_ERROR_NONE:
                    return ' - No errors';
                    break;
                    case JSON_ERROR_DEPTH:
                    return ' - Maximum stack depth exceeded';
                    break;
                    case JSON_ERROR_STATE_MISMATCH:
                    return ' - Underflow or the modes mismatch';
                    break;
                    case JSON_ERROR_CTRL_CHAR:
                    return ' - Unexpected control character found';
                    break;
                    case JSON_ERROR_SYNTAX:
                    return ' - Syntax error, malformed JSON';
                    break;
                    case JSON_ERROR_UTF8:
                    return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                    default:
                    return ' - Unknown error';
                    break;
                }
            }
            

            if(sw_settings('number_format_i18n_enabled') && ($field_id == 36 || $field_id == 37) && isset($obj->{'field_'.$field_id}))
            {
                $field_val = $obj->{'field_'.$field_id};
                $field_val= str_replace( '&quot;','"', $field_val );
                $field_val= str_replace( '""','"', $field_val );
                $value = (int)str_replace('.','',$field_val);
                $value = number_format_i18n($value);
                return sw_character_limiter($prefix.$value.$suffix, $limit);
            }
            
            if(isset($obj->{'field_'.$field_id}))
            {
                $field_val = $obj->{'field_'.$field_id};
                $field_val= str_replace( '&quot;','"', $field_val );
                $field_val= str_replace( '""','"', $field_val );
                
                return sw_character_limiter($prefix.$field_val.$suffix, $limit);
            }
        }
        
        return '-';
    }
}


if ( ! function_exists('sw_character_limiter'))
{
    function sw_character_limiter($text, $length=NULL)
    {
        $CI =& get_instance();
        $CI->load->helper('text');

        if($length != NULL)
        {
            return character_limiter(strip_tags($text), $length);
        }

        return $text;
    }
}

if ( ! function_exists('_field_name'))
{
    function _field_name($field_id)
    {   
        $CI =& get_instance();
        
        $CI->load->model('field_m');
        $field_data = $CI->field_m->get_field_data($field_id, sw_current_language_id());

        if(is_object($field_data))
        {
            return $field_data->field_name;
        }
        
        return '-';
    }
}

if ( ! function_exists('get_listing_category'))
{
    function get_listing_category($listing)
    {
        static $categories = NULL;
        
        $CI =& get_instance();

        if(is_numeric($listing))
        {
            if(!is_array($categories))
            {
                $CI->load->model('treefield_m');
                $categories = $CI->treefield_m->get_all_list();
            }

            if(isset($categories[$listing]))
                return $categories[$listing];
        }
        
        if(isset($listing->category_id) && is_numeric($listing->category_id))
        {
            if(!is_array($categories))
            {
                $CI->load->model('treefield_m');
                $categories = $CI->treefield_m->get_all_list();
            }

            if(isset($categories[$listing->category_id]))
                return $categories[$listing->category_id];
        }
        
        return NULL;
    }
}

if ( ! function_exists('escapeJavaScriptText'))
{
    function escapeJavaScriptText($string)
    {

        $string = str_replace("'", "\'", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace("\r", "", $string);
        
        return $string;
    }
}

if ( ! function_exists('_infowindow_content'))
{
    function _infowindow_content($listing, $custom_data = array())
    {
        $CI =& get_instance();
        
        $output = '';
        
        if(!isset($custom_data['show_details']))$custom_data['show_details'] = true;

        $output = $CI->load->view('frontend/infowindow.php', array_merge($CI->data, array('listing'=>$listing), $custom_data), true);
        
        return escapeJavaScriptText($output);
    }
}

if ( ! function_exists('get_treefield_value'))
{
    function get_treefield_value($treefield_id, $prefix="", $empty="")
    {
        $CI =& get_instance();
        $CI->load->model('treefield_m');
        
        $value = "";
        
        if(is_numeric($treefield_id))
            $value = $CI->treefield_m->get_value($treefield_id);
        
        if($value != '-' && !empty($value))
        {
            return $prefix.$value;
        }
        
        return $empty;
    }
}

if ( ! function_exists('listing_url'))
{
    function listing_url($listing)
    {
        $listing_uri = $listing->idlisting;
        
        if(!empty($listing->slug))
            $listing_uri = $listing->slug;

        $listing_preview_page = sw_settings('listing_preview_page');
    
        // for polylang to detect translated page version
        if(function_exists('pll_get_post'))
            $listing_preview_page = pll_get_post($listing_preview_page);

        $custom_uri='';
        if(substr_count(get_permalink($listing_preview_page), '?') > 0)
        {
            // if doesn't using custom permalink / mod_rewrite
            $custom_uri = '&slug=';
        }
        elseif(substr(get_permalink($listing_preview_page), -1) != '/')
        {
            $custom_uri='/';
        }

        return get_permalink($listing_preview_page).$custom_uri.$listing_uri;
    }
}

if ( ! function_exists('search_url'))
{
    function search_url($query_string)
    {        

        if(!empty($query_string))
        {
            $query_string = '?'.$query_string;
        }

        $results_page = sw_settings('results_page');

        // for polylang to detect translated page version
        if(function_exists('pll_get_post'))
            $results_page = pll_get_post($results_page);

        return get_permalink($results_page).$query_string;
    }
}

if ( ! function_exists('profile_data'))
{
    function profile_data($user, $column_name)
    {
        if(!isset($user->ID))
            return '-';

        if(isset($user->{$column_name}) && !empty($user->{$column_name}))
            return $user->{$column_name};

        $CI =& get_instance();
        $CI->load->model('profile_m');

        $data = $CI->profile_m->get_by(array('user_id'=>$user->ID), TRUE);

        if(isset($data->{$column_name}) && !empty($data->{$column_name}))
            return $data->{$column_name};

        return '-';
    }
}

if ( ! function_exists('agent_url'))
{
    function agent_url($user)
    {
        $listing_uri = $user->ID;
        
        if(!empty($user->user_nicename))
            $listing_uri = $user->user_nicename;

        $user_profile_page = sw_settings('user_profile_page');

        // for polylang to detect translated page version
        if(function_exists('pll_get_post'))
            $user_profile_page = pll_get_post($user_profile_page);
        
        $custom_uri='';
        if(substr_count(get_permalink($user_profile_page), '?') > 0)
        {
            // if doesn't using custom permalink / mod_rewrite
            $custom_uri = '&slug=';
        }
        
        return get_permalink($user_profile_page).$custom_uri.$listing_uri;
    }
}

if ( ! function_exists('check_access'))
{
	function check_access($model, $object_id, $method='edit')
	{
        $CI =& get_instance();
        $user_id = get_current_user_id();
        
        if(sw_user_in_role('administrator'))
        {
            return true;
        }
        
        if(substr($model,-2,2) == '_m')
        {
            // its model
            $CI->load->model($model);
    
            if( sw_user_in_role('AGENT') ||
                sw_user_in_role('OWNER') ||
                sw_user_in_role('AGENCY')||
                (sw_user_in_role('VISITOR') && $model =='myreservation_m')||
                in_array($model, array('repository_m', 'file_m')))
            {
                
                if($CI->$model->is_related($object_id, $user_id, $method) || $object_id === NULL)
                {
                    // User is related
                    return true;
                }
                else
                {
                    //echo $CI->db->last_query();

                    exit('Access denied ROLES RELATED');
                }
            }
        }
        else
        {
            // it's table
        }
        
        //var_dump($model, $object_id, $method);
        
        exit('Access denied ROLES');
    }
}

if ( ! function_exists('allow_submit_listing'))
{
    function allow_submit_listing()
    {
        if(sw_user_in_role('administrator') || sw_user_in_role('AGENT') ||
        sw_user_in_role('OWNER') || sw_user_in_role('AGENCY') || !is_user_logged_in() ||
        (sw_settings('transform_user') && sw_user_in_role('subscriber')))
        {
            return true;
        }
        
        return false;
    }
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */

if ( ! function_exists('get_gravatar'))
{
    function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }
}

if ( ! function_exists('sw_profile_image'))
{
    function sw_profile_image( $user, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() )
    {
        $email = $user->user_email;

        //Try to fetch alternative user profile image

        //Assuming $post is in scope
        if (function_exists ( 'mt_profile_img' ) ) {
            $author_id=$user->ID;
            $src = mt_profile_img( $author_id, array(
                'size' => 'thumbnail',
                'attr' => array( 'alt' => 'Alternative Text' ),
                'echo' => false )
            );

            if(!empty($src))return $src;
        }

        $CI =& get_instance();

        $image_id = profile_data($user, 'profile_image');

        if(!empty($image_id) && $image_id != '-')
        {
            $image_filename = sw_scaled_image_path($image_id);

            return _show_img($image_filename, $s.'x'.$s, false);
        }

        return get_gravatar( $email, $s, $d, $r, $img, $atts);
    }
}

if ( ! function_exists('sw_scaled_image_path'))
{
    function sw_scaled_image_path($attachment_id, $size = 'full') {
        $file = get_attached_file($attachment_id, true);
        if (empty($size) || $size === 'full') {
            // for the original size get_attached_file is fine
            return realpath($file);
        }
        if (! wp_attachment_is_image($attachment_id) ) {
            return false; // the id is not referring to a media
        }
        $info = image_get_intermediate_size($attachment_id, $size);
        if (!is_array($info) || ! isset($info['file'])) {
            return false; // probably a bad size argument
        }

        return realpath(str_replace(wp_basename($file), $info['file'], $file));
    }
}

if ( ! function_exists('sw_update_page'))
{
    function sw_update_page($post_ID, $post_content, $post_template)
    {
        
        if(!is_numeric($post_ID))
        {
            $post = get_page_by_title($post_ID, 'OBJECT', 'page' );
            
            if(!empty($post))
            $post_ID   = $post->ID;
        }
        
        
        $my_post = array(
            'ID'           => $post_ID,
            'page_template'=> $post_template,
            'post_content' => $post_content,
        );
    
        // Update the post into the database
        $post_insert = wp_update_post( $my_post );
        
        if (is_wp_error($post_ID)) {
            $errors = $post_ID->get_error_messages();
            foreach ($errors as $error) {
                echo $error;
            }
        }
        
        return $post_insert;
    }
}

if ( ! function_exists('sw_create_page'))
{
    function sw_create_page($post_title, $post_content = '', $post_template = NULL, $post_parent=0)
    {
        //$post_title = __('Register / Login', 'sw_win');
        $post      = get_page_by_title($post_title, 'OBJECT', 'page' );
        
        $post_id = NULL;
        
        // Delete posts and rebuild
        if(!empty($post))
        {
            wp_delete_post($post->ID, true);
            $post=NULL;
        }
        
        if(!empty($post))
        $post_id   = $post->ID;

        if(empty($post_id))
        {
            $error_obj = NULL;
            $post_insert = array(
                'post_title'    => wp_strip_all_tags( $post_title ),
                'post_content'  => $post_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id(),
                'post_category' => array(1,2),
                'page_template' => $post_template,
                'post_parent'   => $post_parent
            );
            $post_id = wp_insert_post( $post_insert, $error_obj );
        }

        $post_insert = get_page( $post_id );
        
        return $post_insert;
    }
}

if ( ! function_exists('sw_get_menu_item_by_title'))
{
    function sw_get_menu_item_by_title($menu_name, $title)
    {        
        $array_menu = wp_get_nav_menu_items($menu_name);
        
        $menu = array();
        foreach ($array_menu as $m) {
            if (empty($m->menu_item_parent)) {
                $menu[$m->ID] = array();
                $menu[$m->ID]['ID']          =   $m->ID;
                $menu[$m->ID]['title']       =   $m->title;
                $menu[$m->ID]['url']         =   $m->url;
                $menu[$m->ID]['children']    =   array();
                
                if(isset($menu[$m->ID]['title']) && $menu[$m->ID]['title'] == $title)
                    return $m;
            }
        }
        $submenu = array();
        foreach ($array_menu as $m) {
            if ($m->menu_item_parent) {
                $submenu[$m->ID] = array();
                $submenu[$m->ID]['ID']       =   $m->ID;
                $submenu[$m->ID]['title']    =   $m->title;
                $submenu[$m->ID]['url']      =   $m->url;
                $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
                
                if(isset($menu[$m->ID]['title']) && $menu[$m->ID]['title'] == $title)
                    return $m;
            }
        }

        return NULL;
    }
}

if ( ! function_exists('echo_js'))
{
    function echo_js($str)
    {
        $str = str_replace("'", "\'", trim($str));
        $str = str_replace('"', '\"', $str);
        
        echo $str;
    }
}
if ( ! function_exists('_js'))
{
    function _js($str)
    {
        $str = str_replace("\\", "", trim($str));
        $str = str_replace("'", "\'", trim($str));
        $str = str_replace('"', '\"', $str);
        
        return $str;
    }
}

if ( ! function_exists('lang_wp'))
{
    function lang_wp($string)
    {
        return $string;
    }
}

if(!function_exists('get_center_location_data'))
{
    function get_center_location_data($listings = array())
    {
        global $wp;
         /* calculateCenter */
        $minlat = false;
        $minlng = false;
        $maxlat = false;
        $maxlng = false;
        $calculateCenter_lon = false;
        $calculateCenter_lat = false;
        foreach ($listings as $estate) {
            $geolocation = array();
            $gps_string_explode = array();
            if(is_array($estate))
            {
               $gps_string_explode = explode(', ', $estate['gps']);
            }
            else
            {
               $gps_string_explode = explode(', ', $estate->gps);
            }
            if(!isset($gps_string_explode[1]) && isset($estate->lat))
            {
               $gps_string_explode[0] = $estate->lat;
               $gps_string_explode[1] = $estate->lng;
            }
            if(sw_count($gps_string_explode)>1)
            {
                $geolocation['lat'] = $gps_string_explode[0];
                $geolocation['lon'] = $gps_string_explode[1];

                if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
                if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
                if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
                if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
            }
        }
        // Calculate the center
        if($maxlat && $minlat && $maxlng && $minlng){
            $calculateCenter_lat = $maxlat - (($maxlat - $minlat) / 2);
            $calculateCenter_lon = $maxlng - (($maxlng - $minlng) / 2);
            $calculateCenter = $calculateCenter_lat.', '.$calculateCenter_lon;
        }
        /* end calculateCenter */
        
        /* CURL */
        $data ='';
        $resp ='';
        if($calculateCenter_lat && $calculateCenter_lon){
            $url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='.$calculateCenter_lat.'&lon='.$calculateCenter_lon;
            $args = array(
                'user-agent'  =>  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8) AppleWebKit/535.6.2 (KHTML, like Gecko) Version/5.2 Safari/535.6.2',
            ); 
            $tmp = wp_remote_get(esc_url_raw( $url ), $args);
            $tmp = wp_remote_retrieve_body($tmp);
            if ($tmp != false && !empty($tmp)){
                $data = $tmp;
            }
        }
        /* end CURL */
        
        if (!empty($data)){
            $resp = json_decode($data, true);
        }
        return $resp;
    }
    
}

if(!function_exists('sw_remove_emoji'))
{
    function sw_remove_emoji($text){
        return preg_replace('/[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0077}\x{E006C}\x{E0073}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0073}\x{E0063}\x{E0074}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0065}\x{E006E}\x{E0067}\x{E007F})|[\x{1F3F4}](?:\x{200D}\x{2620}\x{FE0F})|[\x{1F3F3}](?:\x{FE0F}\x{200D}\x{1F308})|[\x{0023}\x{002A}\x{0030}\x{0031}\x{0032}\x{0033}\x{0034}\x{0035}\x{0036}\x{0037}\x{0038}\x{0039}](?:\x{FE0F}\x{20E3})|[\x{1F441}](?:\x{FE0F}\x{200D}\x{1F5E8}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F468})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F468})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B0})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2640}\x{FE0F})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FF}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FE}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FD}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FC}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FB}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F9B8}\x{1F9B9}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FF}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FE}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FD}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FC}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FB}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F9B8}\x{1F9B9}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{200D}\x{2642}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2695}\x{FE0F})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FF})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FE})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FD})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FC})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FB})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FA}](?:\x{1F1FF})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1FA}](?:\x{1F1FE})|[\x{1F1E6}\x{1F1E8}\x{1F1F2}\x{1F1F8}](?:\x{1F1FD})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F9}\x{1F1FF}](?:\x{1F1FC})|[\x{1F1E7}\x{1F1E8}\x{1F1F1}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1FB})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1FB}](?:\x{1F1FA})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FE}](?:\x{1F1F9})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FA}\x{1F1FC}](?:\x{1F1F8})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F7})|[\x{1F1E6}\x{1F1E7}\x{1F1EC}\x{1F1EE}\x{1F1F2}](?:\x{1F1F6})|[\x{1F1E8}\x{1F1EC}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}](?:\x{1F1F5})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EE}\x{1F1EF}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1F8}\x{1F1F9}](?:\x{1F1F4})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1F3})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F4}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FF}](?:\x{1F1F2})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F1})|[\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FD}](?:\x{1F1F0})|[\x{1F1E7}\x{1F1E9}\x{1F1EB}\x{1F1F8}\x{1F1F9}](?:\x{1F1EF})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EB}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F3}\x{1F1F8}\x{1F1FB}](?:\x{1F1EE})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1ED})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1EC})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F9}\x{1F1FC}](?:\x{1F1EB})|[\x{1F1E6}\x{1F1E7}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FB}\x{1F1FE}](?:\x{1F1EA})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1E9})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FB}](?:\x{1F1E8})|[\x{1F1E7}\x{1F1EC}\x{1F1F1}\x{1F1F8}](?:\x{1F1E7})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F6}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}\x{1F1FF}](?:\x{1F1E6})|[\x{00A9}\x{00AE}\x{203C}\x{2049}\x{2122}\x{2139}\x{2194}-\x{2199}\x{21A9}-\x{21AA}\x{231A}-\x{231B}\x{2328}\x{23CF}\x{23E9}-\x{23F3}\x{23F8}-\x{23FA}\x{24C2}\x{25AA}-\x{25AB}\x{25B6}\x{25C0}\x{25FB}-\x{25FE}\x{2600}-\x{2604}\x{260E}\x{2611}\x{2614}-\x{2615}\x{2618}\x{261D}\x{2620}\x{2622}-\x{2623}\x{2626}\x{262A}\x{262E}-\x{262F}\x{2638}-\x{263A}\x{2640}\x{2642}\x{2648}-\x{2653}\x{2660}\x{2663}\x{2665}-\x{2666}\x{2668}\x{267B}\x{267E}-\x{267F}\x{2692}-\x{2697}\x{2699}\x{269B}-\x{269C}\x{26A0}-\x{26A1}\x{26AA}-\x{26AB}\x{26B0}-\x{26B1}\x{26BD}-\x{26BE}\x{26C4}-\x{26C5}\x{26C8}\x{26CE}-\x{26CF}\x{26D1}\x{26D3}-\x{26D4}\x{26E9}-\x{26EA}\x{26F0}-\x{26F5}\x{26F7}-\x{26FA}\x{26FD}\x{2702}\x{2705}\x{2708}-\x{270D}\x{270F}\x{2712}\x{2714}\x{2716}\x{271D}\x{2721}\x{2728}\x{2733}-\x{2734}\x{2744}\x{2747}\x{274C}\x{274E}\x{2753}-\x{2755}\x{2757}\x{2763}-\x{2764}\x{2795}-\x{2797}\x{27A1}\x{27B0}\x{27BF}\x{2934}-\x{2935}\x{2B05}-\x{2B07}\x{2B1B}-\x{2B1C}\x{2B50}\x{2B55}\x{3030}\x{303D}\x{3297}\x{3299}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F171}\x{1F17E}-\x{1F17F}\x{1F18E}\x{1F191}-\x{1F19A}\x{1F201}-\x{1F202}\x{1F21A}\x{1F22F}\x{1F232}-\x{1F23A}\x{1F250}-\x{1F251}\x{1F300}-\x{1F321}\x{1F324}-\x{1F393}\x{1F396}-\x{1F397}\x{1F399}-\x{1F39B}\x{1F39E}-\x{1F3F0}\x{1F3F3}-\x{1F3F5}\x{1F3F7}-\x{1F3FA}\x{1F400}-\x{1F4FD}\x{1F4FF}-\x{1F53D}\x{1F549}-\x{1F54E}\x{1F550}-\x{1F567}\x{1F56F}-\x{1F570}\x{1F573}-\x{1F57A}\x{1F587}\x{1F58A}-\x{1F58D}\x{1F590}\x{1F595}-\x{1F596}\x{1F5A4}-\x{1F5A5}\x{1F5A8}\x{1F5B1}-\x{1F5B2}\x{1F5BC}\x{1F5C2}-\x{1F5C4}\x{1F5D1}-\x{1F5D3}\x{1F5DC}-\x{1F5DE}\x{1F5E1}\x{1F5E3}\x{1F5E8}\x{1F5EF}\x{1F5F3}\x{1F5FA}-\x{1F64F}\x{1F680}-\x{1F6C5}\x{1F6CB}-\x{1F6D2}\x{1F6E0}-\x{1F6E5}\x{1F6E9}\x{1F6EB}-\x{1F6EC}\x{1F6F0}\x{1F6F3}-\x{1F6F9}\x{1F910}-\x{1F93A}\x{1F93C}-\x{1F93E}\x{1F940}-\x{1F945}\x{1F947}-\x{1F970}\x{1F973}-\x{1F976}\x{1F97A}\x{1F97C}-\x{1F9A2}\x{1F9B0}-\x{1F9B9}\x{1F9C0}-\x{1F9C2}\x{1F9D0}-\x{1F9FF}]/u', '', $text);
    }
}


if(!function_exists('sw_listing_preview'))
{
    function sw_listing_preview(){
        sw_win_load_ci_frontend();
        $CI = &get_instance();
        $listing_id_slug = get_query_var( 'slug' );
        if(empty($listing_id_slug))
        {
            return false;
        }
        if(is_numeric($listing_id_slug))
            $conditions = array('search_idlisting'=>$listing_id_slug, 'search_is_activated'=>1);
        else
        {
            $CI->load->model('slug_m');
            $table_id = $CI->slug_m->getid($listing_id_slug);

            $conditions = array('search_idlisting'=>$table_id, 'search_is_activated'=>1);
        }
        prepare_frontend_search_query_GET('listing_m', $conditions);
        $CI->load->model('listing_m');
        $listings = $CI->listing_m->get_pagination_lang(1, 0, sw_current_language_id());
        
        if(empty($listings))
        {
            return false;
        }
        $listing = $listings[0];
        
        return $listing;
    }
}

if(!function_exists('sw_listing_content'))
{
    function sw_listing_content(){
        $listing = sw_listing_preview();
        if($listing)
            return _field($listing, 13);
        else
            return false;
    }
}

if(!function_exists('sw_listing_description'))
{
    function sw_listing_description(){
        $listing = sw_listing_preview();
        if($listing)
            return _field($listing, 8);
        else
            return false;
    }
}

if(!function_exists('sw_listing_keywords'))
{
    function sw_listing_keywords(){
        $listing = sw_listing_preview();
        if($listing)
            return _field($listing, 78);
        else
            return false;
    }
}

if(!function_exists('sw_listing_preview_image'))
{
    function sw_listing_preview_image(){
        $listing = sw_listing_preview();
        if($listing)
            return _show_img($listing->image_filename, '1040x660', false);
        else
            return false;
    }
}


if(!function_exists('sw_featured_image')){
    function sw_featured_image() {
            $url ='';
            
            $id = get_queried_object_id ();
            if(!$id)
                return '';
            
            $galleries = get_post_galleries_images($id);
            // Check if the post/page has featured image
            if ( has_post_thumbnail( $id ) ) {

                // Change thumbnail size, but I guess full is what you'll need
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );

                $url = $image[0];

            } elseif( !empty($galleries) && isset($galleries[0]) && isset($galleries[0][0])) {
                $url = $galleries[0][0];
            } else {
                //Set a default image if Featured Image isn't set
                $url = '';
            }
        return $url;
    }
}

if(!function_exists('sw_featured_excerpt')){
    function sw_featured_excerpt() {
            $exc ='';
            $id = get_queried_object_id ();
            if(!$id)
                return '';
            // Check if the post/page has featured image
            $post = get_post($id); 
            
            if(!is_object($post))
                return $exc;
            
            $excerpt = $post->post_excerpt;
            $content = $post->post_content;
            $elementor_page = get_post_meta( $id, '_elementor_edit_mode', true );
            if ( !$elementor_page ) {
                if(!empty($excerpt)) {
                    $exc = wp_trim_words(strip_shortcodes(strip_tags(wpautop($excerpt))), 25, '...');
                } elseif(!empty($content)) {
                    $exc = wp_trim_words(strip_shortcodes(strip_tags(wpautop($content))), 25, '...');
                }
            }
        return $exc;
    }
}
