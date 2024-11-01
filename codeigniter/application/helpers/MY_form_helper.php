<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// ------------------------------------------------------------------------

/**
 * Text Input Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_number'))
{
	function form_number($data = '', $value = '', $extra = '')
	{
		$defaults = array('type' => 'number', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

		return "<input "._parse_form_attributes($data, $defaults).$extra." />";
	}
}

// ------------------------------------------------------------------------

/**
 * Form Value
 *
 * Grabs a value from the POST array for the specified field so you can
 * re-populate an input field or textarea.  If Form Validation
 * is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @return	mixed
 */
if ( ! function_exists('set_value_GET'))
{
	function set_value_GET($field = '', $default = '', $skip_valdation = FALSE)
	{
		if (FALSE === ($OBJ =& _get_validation_object()))
		{
			if ( ! isset($_GET[$field]))
			{
				return $default;
			}

			return form_prep($_GET[$field], $field);
		}
        
        if($skip_valdation)
        {
			if (!empty($_GET[$field]))
			{
			    $CI =& get_instance(); 
				return $CI->input->get($field);
			}
        }
        
		return form_prep($OBJ->set_value($field, $default), $field);
	}
}

if ( ! function_exists('regenerate_query_string'))
{
	function regenerate_query_string($enable_fields = array())
	{
		$CI =& get_instance();
        $check_fields = (sw_count($enable_fields) > 0);
        $_GET_clone = $_GET;

        $gen_text = '';
        if(sw_count($_GET_clone) > 0) foreach($_GET_clone as $field=>$value)
        {
            if($check_fields && !in_array($field, $enabled_fields))
            {
                continue;
            }
            
            $s_value = $CI->input->get($field);
            
            if(!empty($s_value))
            {
                $gen_text.=$field.'='.$s_value.'&amp;';
            }
        }
        
        if(!empty($gen_text))
            $gen_text = substr($gen_text, 0, strlen($gen_text)-5);

        return $gen_text;
	}
}

if ( ! function_exists('sw_upload_media_element'))
{
    function sw_upload_media_element($elem_id, $field_id, $field_name, $your_img_id)
    {
        static $media_element_counter = 0;
        
        $media_element_counter++;
        
        $elem_id.='_'.$media_element_counter;
        
        wp_enqueue_media();

        ?>
        <div id="<?php echo esc_attr($elem_id); ?>meta-box-id" class="postbox-upload">
        <?php
        // Get WordPress' media upload URL
        $upload_link = '#';
        
        // Get the image src
        $your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );

        // For convenience, see if the array is valid
        $you_have_img = is_array( $your_img_src );
        ?>
        
        <!-- Your image container, which can be manipulated with js -->
        <div class="custom-img-container">
            <?php if ( $you_have_img ) : ?>
                <img src="<?php echo esc_html($your_img_src[0]); ?>" alt="..." style="max-width:100%;" />
            <?php endif; ?>
        </div>
        
        <?php if(sw_user_in_role('administrator')): ?>
        <!-- Your add & remove image links -->
        <p class="hide-if-no-js">
            <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
            href="<?php echo esc_url($upload_link) ?>">
                <?php echo esc_html__('Set custom image','nexos') ?>
            </a>
            <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
            href="#">
                <?php echo esc_html__('Remove this image','nexos') ?>
            </a>
        </p>
        <?php endif; ?>
        
        <!-- A hidden input to set and post the chosen image id -->
        <input class="logo_image_id" type="hidden" id="<?php echo esc_html(esc_html($field_id)); ?>" name="<?php echo esc_html($field_name); ?>" value="<?php echo esc_html($your_img_id); ?>" />
        </div>
        
        <?php
        $custom_js ='';
        $custom_js .=" jQuery(function($) {
                            if( typeof jQuery.fn.wpMediaElement == 'function')
                                $('#".esc_html($elem_id)."meta-box-id.postbox-upload').wpMediaElement();
                        });";
        
        echo "<script>".$custom_js."</script>";

        ?>

        <?php
    }
}


if ( ! function_exists('upload_field_admin'))
{
	function upload_field_admin($field_name, $label, $label_size=2, $value, $addition_message='')
	{
?>
                                            <div class="form-group UPLOAD-FIELD-TYPE">
                                              <label class="col-lg-<?php echo $label_size; ?> control-label">
                                              <?php echo _($label); ?>
                                              <div class="ajax_loading"> </div>
                                              </label>
                                              <div class="col-lg-<?php echo intval(12-$label_size); ?>">
<div class="field-row hidden">
<?php echo form_input($field_name, set_value($field_name, isset($value)?$value:'SKIP_ON_EMPTY'), 'class="form-control skip-input" id="'.$field_name.'" placeholder="'.$label.'"')?>
</div>
<?php if( empty($value) ): ?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else: ?>
<!-- Button to select & upload files -->
<span class="btn btn-success fileinput-button">
    <span>Select files...</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload_<?php echo $field_name; ?>" class="FILE_UPLOAD file_<?php echo $field_name; ?>" type="file" name="files[]" multiple>
</span>
<?php 
$_addition_message='';
if(isset($value)){
    $rep_id = $value;
    $CI =& get_instance();
    //Fetch repository
    $file_rep = $CI->file_m->get_by(array('repository_id'=>$rep_id));
    if(sw_count($file_rep)) 
        $_addition_message = $addition_message;
}
?>
<span id="additional-messag_<?php echo $field_name; ?>" class="fileupload-additional-message">
    <span><?php echo $_addition_message;?></span>
</span> 

<br style="clear: both;" />
<!-- The global progress bar -->
<p>Upload progress</p>
<div id="progress_<?php echo $field_name; ?>" class="progress progress-success progress-striped">
    <div class="bar"></div>
</div>
<!-- The list of files uploaded -->
<p>Files uploaded:</p>
<ul id="files_<?php echo $field_name; ?>">
<?php 
if(isset($value)){
    $rep_id = $value;
    $CI =& get_instance();
    
    //Fetch repository
    $file_rep = $CI->file_m->get_by(array('repository_id'=>$rep_id));
    if(sw_count($file_rep)) foreach($file_rep as $file_r)
    {
        $delete_url = site_url_q('files/upload/rep_'.$file_r->repository_id, '_method=DELETE&amp;file='.rawurlencode($file_r->filename));
        
        echo "<li><a target=\"_blank\" href=\"".base_url('files/'.$file_r->filename)."\">$file_r->filename</a>".
             '&nbsp;&nbsp;<button class="btn btn-xs btn-danger" data-type="POST" data-url='.$delete_url.'><i class="icon-trash icon-white"></i></button></li>';
    }
}
?>
</ul>

<!-- JavaScript used to call the fileupload widget to upload files -->
<script>
// When the server is ready...
$( document ).ready(function() {
    
    // Define the url to send the image data to
    var url_<?php echo $field_name; ?> = '<?php echo site_url('files/upload_settings/'.$field_name);?>';
    
    // Call the fileupload widget and set some parameters
    $('#fileupload_<?php echo $field_name; ?>').fileupload({
        url: url_<?php echo $field_name; ?>,
        autoUpload: true,
        dropZone: $('#fileupload_<?php echo $field_name; ?>'),
        dataType: 'json',
        done: function (e, data) {
            // Add each uploaded file name to the #files list
            $.each(data.result.files, function (index, file) {
                if(!file.hasOwnProperty("error"))
                {
                    $('#files_<?php echo $field_name; ?>').append('<li><a href="'+file.url+'" target="_blank">'+file.name+'</a>&nbsp;&nbsp;<button class="btn btn-xs btn-danger" data-type="POST" data-url='+file.delete_url+'><i class="icon-trash icon-white"></i></button></li>');
                    added=true;
                    if($('#additional-messag_<?php echo $field_name; ?>').length) {
                        $('#additional-messag_<?php echo $field_name; ?> > span').html("<?php echo $addition_message;?>");
                    }
                }
                else
                {
                    ShowStatus.show(file.error);
                }

            });
            
            //console.log(data.result.repository_id);
            //console.log('<?php echo '#'.$field_name; ?>');
            $('<?php echo '#'.$field_name; ?>').attr('value', data.result.repository_id);
            
            reset_events_<?php echo $field_name; ?>();
        },
        destroyed: function (e, data) {
            $.fn.endLoading();
            <?php if(config_item('app_type') != 'demo'):?>
            if(data.success)
            {
                ShowStatus.show('<?php echo _('Disabled in demo'); ?>');
            }
            else
            {
                ShowStatus.show('<?php echo _('Unsuccessful, possible permission problems or file not exists'); ?>');
            }
            <?php endif;?>
            return false;
        },
        <?php if(config_item('app_type') == 'demo'):?>
        added: function (e, data) {
            $.fn.endLoading();
            return false;
        },
        <?php endif;?>
        progressall: function (e, data) {
            // Update the progress bar while files are being uploaded
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress_<?php echo $field_name; ?> .bar').css(
                'width',
                progress + '%'
            );
        }
    });
    
    reset_events_<?php echo $field_name; ?>();
});

function reset_events_<?php echo $field_name; ?>(){
    $("#files_<?php echo $field_name; ?> li button").unbind();
    $("#files_<?php echo $field_name; ?> li button.btn-danger").click(function(){
        var image_el = $(this);
        
        <?php if(config_item('app_type') == 'demo'):?>
        if(true)
        {
            $.fn.endLoading();
            return false;
        }
        <?php endif;?>
        
        $.post($(this).attr('data-url'), function( data ) {
            var obj = jQuery.parseJSON(data);
            
            if(obj.success)
            {
                image_el.parent().remove();
            }
            else
            {
                ShowStatus.show('<?php echo lang_check('Unsuccessful, possible permission problems or file not exists'); ?>');
            }
            //console.log("Data Loaded: " + obj.success );
        });
        
        return false;
    });
}

</script>
<?php endif; ?>
                                              </div>
                                            </div>

<?php 
    }
}

if ( ! function_exists('form_dropdown'))
{
	function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
	{
		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (sw_count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$name]))
			{
				$selected = array($_POST[$name]);
			}
		}

		if ($extra != '') $extra = ' '.$extra;

		$multiple = (sw_count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select name="'.$name.'"'.$extra.$multiple." title='".__('Nothing selected','sw_win')."'>\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
                    
                    //$optgroup_key =  str_replace('"', '&quot;', $optgroup_key);
                    //echo '$optgroup_key: '.$optgroup_key.'<br />';
					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
			else
			{
                if(isset($selected[0]))
                $selected[0] = str_replace('&quot;', '"', $selected[0]);

                $sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
                
                $key = str_replace('"', '&quot;', $key);

				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}

		$form .= '</select>';

		return $form;
	}
}

if ( ! function_exists('form_table'))
{
	function form_table($name = '', $options = array(), $value ='', $extra = 'deprecated')
	{
                    $columns = $options;
                    
                    $json_string =  $value;
                    $json_string=str_replace('&quot;', '"', $json_string);
                    $obj = json_decode($json_string);
                    
                ?>

                <div id="talbe_<?php echo $name; ?>" class="form-sw_table_edit">
                    
                 <table id="editable_table_<?php echo $name; ?>" class="table table-striped table-bordered table-hover sw_table_edit" style="border-left: 1px solid #CCC !important;border-top: 1px solid #CCC !important;">
                     <thead>
                     <tr>
                         <th><?php echo 'id'; ?></th>
                     <?php foreach($columns as $col_val): ?>

                         <?php
                         $to = strpos($col_val, '[');
                         if($to !== FALSE)$col_val =substr($col_val, 0, $to);
                         ?>
                         <th><?php echo $col_val; ?></th>
                     <?php endforeach; ?>
                     </tr>
                     </thead>
                     <tbody>
                     <?php  $line_added = false; $i=1; 
                         if(!empty($obj))foreach ($obj as $obj_key => $obj_value):?>
                         <?php if(empty($obj_value)) continue;?>
                         <tr>
                             <td style="width: 35px;"><?php echo $i;?></td>
                             <?php foreach ($obj_value as $obj_v):?>
                                 <td><?php echo $obj_v;?></td>
                             <?php $line_added = true; endforeach;?>
                         </tr>
                     <?php $i++; endforeach;?>
                     <?php if(!$line_added):?>
                         <tr>
                         <td>1</td>
                         <?php foreach($columns as $col_key => $col_val): ?>
                             <td></td>
                         <?php endforeach; ?>
                         </tr>
                     <?php endif;?>
                     </tbody>
                 </table>
                <input name="<?php echo $name; ?>" type="text" value="<?php echo $value; ?>" class="form-control hidden" id="<?php echo $name; ?>">
                </div>
                <script>
                 jQuery(document).ready(function($){ 
                    $('#editable_table_<?php echo $name; ?>').Tabledit({
                    buttons: {
                              edit: {
                                  class: 'btn btn-xs btn-default',
                                  html: '<span class="glyphicon glyphicon-pencil"></span>',
                                  action: 'edit'
                              },
                              add: {
                                  class: 'btn btn-xs btn-default',
                                  html: '<span class="fa fa-plus"></span> <?php echo esc_html__('Add', 'sw_win');?>',
                                  action: 'add'
                              },
                              delete: {
                                  class: 'btn btn-xs btn-default',
                                  html: '<span class="glyphicon glyphicon-trash"></span>',
                                  action: 'delete'
                              },
                              save: {
                                  class: 'btn btn-xs btn-default',
                                  html: '<span class="fa fa-check"></span>'
                              },
                              cancel: {
                                  class: 'btn btn-xs btn-default',
                                  html: '<span class="fa fa-times"></span>'
                              },
                              restore: {
                                  class: 'btn btn-xs btn-warning',
                                  html: '<?php echo esc_html__('Restore', 'sw_win');?>',
                                  action: 'restore'
                              },
                              confirm: {
                                  class: 'btn btn-xs btn-danger',
                                  html: '<?php echo esc_html__('Confirm', 'sw_win');?>'
                              }
                          },
                      columns: {
                          identifier: [0, 'id'],
                          editable: [
                              <?php foreach($columns as $k=>$col_val): ?>
                                  <?php
                                  $to = strpos($col_val, '[');
                                  $col_val_title = $col_val;
                                  if($to !== FALSE)$col_val_title =substr($col_val, 0, $to);
                                  ?>

                                  [<?php echo ++$k;?>, '<?php echo $col_val_title; ?>'

                                   <?php
                                      $from = strpos($col_val, '[');
                                      $to = strpos($col_val, ']');
                                      if($from !== FALSE)
                                      {
                                          $col_list =substr($col_val, $from+1, $to-$from-1);
                                          $col_list_explode = explode('|',$col_list);
                                          echo ',\'{';
                                          foreach($col_list_explode as $val_opt)
                                          {
                                              if(end($col_list_explode) == $val_opt)
                                                  echo ('"'.$val_opt.'":"'.$val_opt.'"');
                                              else
                                                  echo ('"'.$val_opt.'":"'.$val_opt.'",');

                                          }
                                          echo '}\'';
                                      }
                                   ?>

                                  ],

                              <?php endforeach; ?>
                          ]
                      },
                          // executed whenever there is an ajax request
                          onAlways: function() { 
                              //console.log(html2json('editable_table_<?php echo $name; ?>'));
                              $('#<?php echo $name; ?>').val(html2json('editable_table_<?php echo $name; ?>'));
                          },
                  });
              })       

                function html2json(selector) {
                  var json = '{';
                  var otArr = [];
                  var tbl2 = $('#'+selector+' tr').each(function(i) {        
                     x = $(this).find('td:not(:first-child) .tabledit-span');
                     var itArr = [];
                     x.each(function() {
                        itArr.push('"' + $(this).text() + '"');
                     });
                     otArr.push('"' + i + '": [' + itArr.join(',') + ']');
                  })
                  json += otArr.join(",") + '}'

                  return json;
               }

               </script>
                
                <?php
                
	}
}

if ( ! function_exists('_recaptcha'))
{
    function _recaptcha($is_compact=false, $style="", $load_script=true)
    {
        static $called = 0;
        static $recaptcha_array = array();
        
        if(sw_settings('recaptcha_site_key') !== FALSE && sw_settings('recaptcha_site_key') !== NULL)
        {
            if($load_script && $called===0)
            {
                echo "<script src='https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&amp;render=explicit'></script>";
            }
            $called++;
            
            $compact_tag='';
            $size_tag='';
            if($is_compact)
            {
                $compact_tag='data-size="compact"';
                $size_tag='compact';
            }

            $recaptcha_array[$called] = array('size'=>$size_tag);
                    
            echo '<div id="recaptcha_called_'.$called.'" class="g-recaptcha" style="'.$style.'"  '.$compact_tag.' data-sitekey="'.sw_settings('recaptcha_site_key').'"></div>';
    ?>

    <script>

    <?php if($called===1)echo 'var ';?>CaptchaCallback = function(){
    <?php for($j=1;$j<=$called;$j++): ?>
        grecaptcha.render(document.getElementById('recaptcha_called_<?php echo $j;?>'), {'size' : '<?php echo $recaptcha_array[$j]['size']; ?>',  'sitekey' : '<?php echo sw_settings('recaptcha_site_key'); ?>'});
    <?php endfor; ?>
    };

    </script>

    <?php
        }
    }
}

if ( ! function_exists('valid_recaptcha'))
{
    function valid_recaptcha()
    {
        
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
            //your site secret key
            $secret = sw_settings('recaptcha_secret_key');

            if(valid_recaptcha_curl($_POST['g-recaptcha-response']))
            {
                return TRUE;
            }
        }
        
        return FALSE;
    }
}

if ( ! function_exists('valid_recaptcha_curl'))
{
    function valid_recaptcha_curl($g_recaptcha_response) {
        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL            => "https://www.google.com/recaptcha/api/siteverify",
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 200,
            CURLOPT_TIMEOUT => 200,
            CURLOPT_POSTFIELDS     => array(
                'secret' => sw_settings('recaptcha_secret_key'),
                'response' => $g_recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );
        curl_setopt_array($ch, $curlConfig);
        if($result = curl_exec($ch)){
            curl_close($ch);
            $response = json_decode($result);
            return $response->success;
        }else{
            //dump(curl_error($ch)); // this for debug remove after you test it
            //exit();

            // If connection to google api failed, just send form without verification
            return true;
        }
    }
}

if ( ! function_exists('profile_cf_single'))
{
    function profile_cf_single($rel_find, $print_label=TRUE, $container = NULL)
    {
        $CI =& get_instance(); 
        
        $json_decoded = NULL;
        if($container === NULL)
        {
            $json_decoded = json_decode($CI->data['agent_profile']['custom_fields']);
        }
        else
        {
            if(isset($container['custom_fields']))
            {
                $json_decoded = json_decode($container['custom_fields']);
            }
            elseif(isset($container['agent_profile']['custom_fields']))
            {
                $json_decoded = json_decode($container['agent_profile']['custom_fields']);
            }
        }

        $custom_fields_code = $CI->settings_m->get_field('custom_fields_code');
        $obj_widgets = json_decode($custom_fields_code);
        $custom_fields = $json_decoded;
        $content_language_id = $CI->data['lang_id'];
        
        if(is_object($obj_widgets->PRIMARY))
        {
            foreach($obj_widgets->PRIMARY as $key=>$obj)
            {
                $title = '';
                $rel = $obj->rel;
                $class_color = $obj->type;
                $label = $obj->{"label_$content_language_id"};
        
                if($obj->rel == $rel_find && !empty($obj->type) && !empty($custom_fields->{'cinput_'.$rel}))
                {
                    if($obj->type === 'INPUTBOX')
                    {
                        if($print_label)
                            echo '<strong>'.$label.':</strong> '.$custom_fields->{'cinput_'.$rel};
                        else
                            return $custom_fields->{'cinput_'.$rel};
                    }
                    else if($obj->type === 'TEXTAREA')
                    {
                        if($print_label)
                            echo '<strong>'.$label.':</strong> '.$custom_fields->{'cinput_'.$rel};
                        else
                            return $custom_fields->{'cinput_'.$rel};
                    }
                    else if($obj->type === 'CHECKBOX')
                    {
                        $print = '<i class="fa fa-check"></i>';
                        if($custom_fields->{'cinput_'.$rel} == '1')
                            $print = '<i class="fa fa-check ok"></i>';
                        
                        if($print_label)
                            echo '<strong>'.$label.':</strong> '.$print;
                        else
                            return $custom_fields->{'cinput_'.$rel};
                    }
                }
            }
        }
        
        return FALSE;
    }
}

if ( ! function_exists('profile_cf_li'))
{
    function profile_cf_li($container = NULL)
    {
        $CI =& get_instance(); 
        
        if($container === NULL)
        {
            $custom_fields_code = $CI->settings_m->get_field('custom_fields_code');
            $obj_widgets = json_decode($custom_fields_code);
            $custom_fields = json_decode($CI->data['agent_profile']['custom_fields']);
            $content_language_id = $CI->data['lang_id'];
            
            if(is_object($obj_widgets) && is_object($obj_widgets->PRIMARY))
            {
                echo '<ul class="profile_custom_fields">';
                foreach($obj_widgets->PRIMARY as $key=>$obj)
                {
                    $title = '';
                    $rel = $obj->rel;
                    $class_color = $obj->type;
                    $label = $obj->{"label_$content_language_id"};
            
                    if(!empty($obj->type) && !empty($custom_fields->{'cinput_'.$rel}))
                    {
                        if($obj->type === 'INPUTBOX')
                        {
                            echo '<li>';
                            echo '<strong>'.$label.':</strong> '.$custom_fields->{'cinput_'.$rel};
                            echo '</li>';
                        }
                        else if($obj->type === 'TEXTAREA')
                        {
                            echo '<li>';
                            echo '<strong>'.$label.':</strong> '.$custom_fields->{'cinput_'.$rel};
                            echo '</li>';
                        }
                        else if($obj->type === 'CHECKBOX')
                        {
                            $print = '<i class="fa fa-check"></i>';
                            if($custom_fields->{'cinput_'.$rel} == '1')
                                $print = '<i class="fa fa-check ok"></i>';
                            
                            echo '<li>';
                            echo '<strong>'.$label.':</strong> '.$print;
                            echo '</li>';
                        }
                    }
                }
                echo '</ul>';
            }

        }
    }
}

if ( ! function_exists('custom_fields_print'))
{
    function custom_fields_print($settings_field)
    {
        $CI =& get_instance(); 
        
        $custom_fields = $CI->data['custom_fields'];
        $content_language_id = $CI->data['content_language_id'];

        $custom_fields_code = $CI->settings_m->get_field($settings_field);
        $obj_widgets = json_decode($custom_fields_code);
        
        if(is_object($obj_widgets->PRIMARY))
        foreach($obj_widgets->PRIMARY as $key=>$obj)
        {
            $title = '';
            $rel = $obj->rel;
            $class_color = $obj->type;
            $label = $obj->{"label_$content_language_id"};

            if(!empty($obj->type))
            {
                if($obj->type === 'INPUTBOX')
                {
        ?>
        
            <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo $label; ?></label>
            <div class="col-lg-10">
                <?php echo form_input('cinput_'.$rel, set_value('cinput_'.$rel, _ch($custom_fields->{'cinput_'.$rel}, '')), 'class="form-control" id="input_facebook_link" placeholder="'.$label.'"')?>
            </div>
            </div>
        
        <?php
                }
                else if($obj->type === 'TEXTAREA')
                {
        ?>
        
            <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo $label; ?></label>
            <div class="col-lg-10">
                <?php echo form_textarea('cinput_'.$rel, set_value('cinput_'.$rel, _ch($custom_fields->{'cinput_'.$rel}, '')), 'class="form-control" id="input_payment_details" placeholder="'.$label.'"')?>
            </div>
            </div>
        
        <?php
                }
                else if($obj->type === 'CHECKBOX')
                {
        ?>
        
            <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo $label; ?></label>
            <div class="col-lg-10">
                <?php echo form_checkbox('cinput_'.$rel, '1', set_value('cinput_'.$rel, _ch($custom_fields->{'cinput_'.$rel}, '')), 'id="input_alerts_email"')?>
            </div>
            </div>
        
        <?php
                }
            }
        }
    }
}

if ( ! function_exists('custom_fields_print'))
{
    function custom_fields_print_f($settings_field)
    {
        $CI =& get_instance(); 
        
        $custom_fields = $CI->data['custom_fields'];
        $content_language_id = $CI->data['content_language_id'];

        $custom_fields_code = $CI->settings_m->get_field($settings_field);
        $obj_widgets = json_decode($custom_fields_code);
        
        if(is_object($obj_widgets) && is_object($obj_widgets->PRIMARY))
        foreach($obj_widgets->PRIMARY as $key=>$obj)
        {
            $title = '';
            $rel = $obj->rel;
            $class_color = $obj->type;
            $label = $obj->{"label_$content_language_id"};

            if(!empty($obj->type))
            {
                if($obj->type === 'INPUTBOX')
                {
        ?>
        
            <div class="control-group">
            <label class="control-label"><?php echo $label; ?></label>
            <div class="controls">
                <?php echo form_input('cinput_'.$rel, set_value('cinput_'.$rel, _ch($custom_fields->{'cinput_'.$rel}, '')), 'class="form-control" id="input_facebook_link" placeholder="'.$label.'"')?>
            </div>
            </div>
        
        <?php
                }
                else if($obj->type === 'TEXTAREA')
                {
        ?>
        
            <div class="control-group">
            <label class="control-label"><?php echo $label; ?></label>
            <div class="controls">
                <?php echo form_textarea('cinput_'.$rel, set_value('cinput_'.$rel, _ch($custom_fields->{'cinput_'.$rel}, '')), 'class="form-control" id="input_payment_details" placeholder="'.$label.'"')?>
            </div>
            </div>
        
        <?php
                }
                else if($obj->type === 'CHECKBOX')
                {
        ?>
        
            <div class="control-group">
            <label class="control-label"><?php echo $label; ?></label>
            <div class="controls">
                <?php echo form_checkbox('cinput_'.$rel, '1', set_value('cinput_'.$rel, _ch($custom_fields->{'cinput_'.$rel}, '')), 'id="input_alerts_email"')?>
            </div>
            </div>
        
        <?php
                }
            }
        }
    }
}

if ( ! function_exists('custom_fields_print'))
{
    function custom_fields_load(&$data, $fields_json)
    {           
        $data['custom_fields'] = json_decode($fields_json);
    }
}

if ( ! function_exists('custom_fields_print'))
{
    function custom_fields_save(&$data, $settings_field)
    {
        $CI =& get_instance(); 
        
        $custom_fields_code = $CI->settings_m->get_field($settings_field);
        $obj_widgets = json_decode($custom_fields_code);
        $custom_fields_json = array();
        
        if(is_object($obj_widgets->PRIMARY))
        foreach($obj_widgets->PRIMARY as $key=>$obj)
        {
            $input_submittion = $CI->input->post("cinput_$obj->rel");
            if(!empty($input_submittion))
            {
                $custom_fields_json["cinput_$obj->rel"] = $input_submittion;
            }
        }
        
        $data['custom_fields'] = json_encode($custom_fields_json);
    }
}

if ( ! function_exists('form_dropdown_ajax'))
{
	function form_dropdown_ajax($name = '', $table, $selected = NULL, $column = 'address', $language_id=NULL)
	{
        $CI =& get_instance();

	    static $called = 0;
        
        $model_name = $table;
        $table_name = str_replace('_m', '', $table);
        
        $attribute_id = 'id'.$table_name;
        
        if(substr($model_name, -2, 2) == '_m')
        {
            $CI->load->model($model_name);
            
            $attribute_id = $CI->$model_name->_primary_key;
        }
        
		if(empty($selected))
            $selected='';
        
		$form = '<input name="'.$name.'" type="text" value="'.$selected.'" id="winelem_'.$called.'" readonly/>';
        
        $skip_id = '';
        if(isset($_GET['id']))
        {
            $skip_id = $_GET['id'];
        }
        
        //load javascript library
        if($called==0)
        {
            echo '<script src="'.plugins_url(SW_WIN_SLUG.'/assets/js/winter_dropdown/winter.js').'"></script>';
            echo '<link rel="stylesheet" href="'.plugins_url(SW_WIN_SLUG.'/assets/js/winter_dropdown/winter.css').'"> </script>';
        }
        
        ?>
<script>
jQuery(document).ready(function($) {
    $('#winelem_<?php echo $called;?>').winterDropdown({
        ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
        ajax_param: { 
                      "page": 'frontendajax_relatedid',
                      "action": 'ci_action',
                      "table": '<?php echo $table; ?>'
                    },
        attribute_id: '<?php echo $attribute_id; ?>',
        language_id: '<?php echo $language_id; ?>',
        attribute_value: '<?php echo $column; ?>',
        skip_id: '<?php echo $skip_id; ?>',
        text_search: '<?php esc_html_e('Search term', 'sw_win');?>',
        text_no_results: '<?php esc_html_e('No results found', 'sw_win');?>',
    });
});
</script>
        <?php
        $called++;
		return $form;
	}
}

if ( ! function_exists('form_treefield'))
{
	function form_treefield($name = '', $table, $selected = NULL, 
                            $column = 'value', $language_id=NULL, $field_prefix = 'field_',
                            $clear_dependent_fields=false, $empty_value='', $field_id=1)
	{
        $CI =& get_instance();

	    static $called = 0;
        
        $model_name = $table;
        $table_name = str_replace('_m', '', $table);
        
        $attribute_id = 'id'.$table_name;
        
        if(substr($model_name, -2, 2) == '_m')
        {
            $CI->load->model($model_name);
            
            $attribute_id = $CI->$model_name->_primary_key;
        }
        
		if(empty($selected))
            $selected='';
        
		$form = '<input name="'.$name.'" value="'.$selected.'" type="text" id="wintreeelem_'.$called.'" readonly/>';
        
        $skip_id = '';
//        if(isset($_GET['id']))
//        {
//            $skip_id = $_GET['id'];
//        }
        
        //load javascript library
        if($called==0)
        {
            wp_enqueue_script( 'winter_treefield', plugins_url(SW_WIN_SLUG.'/assets/js/winter_treefield/winter.js'), array( 'jquery' ), false, false );

            wp_register_style('winter_treefield', plugins_url(SW_WIN_SLUG.'/assets/js/winter_treefield/winter.css'), false, '1.0.0' );
            wp_enqueue_style('winter_treefield', plugins_url(SW_WIN_SLUG.'/assets/js/winter_treefield/winter.css'));


            //echo '<script src="'.plugins_url(SW_WIN_SLUG.'/assets/js/winter_treefield/winter.js').'"></script>';
            //echo '<link rel="stylesheet" href="'.plugins_url(SW_WIN_SLUG.'/assets/js/winter_treefield/winter.css').'"> </script>';
        }
        
        $CI->load->model('dependentfield_m');
        $all_fields = $CI->dependentfield_m->get_by(array('field_id'=>$field_id));
        
        $gen_js_array = array();
        
        foreach($all_fields as $key=>$field)
        {
            $gen_js_array[$field->treefield_id] = explode(',', $field->hidden_fields_list);
        }
        
        $dependent_fields_json = json_encode($gen_js_array);
        ?>
<script>

var dp_fields_<?php echo $field_id;?> = <?php echo $dependent_fields_json; ?>

<?php if(!function_exists('show_dependent')): ?>
var dp_fields_<?php echo $field_id;?>  = [];
<?php endif; ?>

jQuery(document).ready(function($) {
    $('#wintreeelem_<?php echo $called;?>').winterTreefield({
        ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
        ajax_param: { 
                      "page": 'frontendajax_treefieldid',
                      "action": 'ci_action',
                      "table": '<?php echo $table; ?>',
                      "field_id": '<?php echo $field_id; ?>',
                      "empty_value": '<?php _jse($empty_value); ?>'
                    },
        attribute_id: '<?php echo $attribute_id; ?>',
        language_id: '<?php echo $language_id; ?>',
        attribute_value: '<?php echo $column; ?>',
        skip_id: '<?php echo $skip_id; ?>',
        empty_value: ' - ',
        text_search: '<?php esc_html_e('Search term', 'sw_win');?>',
        text_no_results: '<?php esc_html_e('No results found', 'sw_win');?>',
        callback_selected: function(key) {
            $('#wintreeelem_<?php echo $called;?>').trigger("change");

            <?php if($field_id == 1): // only for category, show fields ?>
            $('*[class^="<?php echo $field_prefix; ?>"]').show();
            <?php endif; ?>

            if(dp_fields_<?php echo $field_id;?> [key])
            {
                $.each( dp_fields_<?php echo $field_id;?> [key], function( key, value ) {
                    
                    // Hide all dependent fields
                    $('.<?php echo $field_prefix; ?>'+value).hide();
                    
                    <?php if($clear_dependent_fields): ?>
                    $('.<?php echo $field_prefix; ?>'+value).find('input:not([type="checkbox"])').val(null);
                    $('.<?php echo $field_prefix; ?>'+value).find('input[type="checkbox"]').prop( 'checked', false );
                    $('.<?php echo $field_prefix; ?>'+value).find('input:checked').removeAttr('checked');
                    $('.<?php echo $field_prefix; ?>'+value).find($('option')).attr('selected',false)
                    //tinymce.execCommand( 'mceAddEditor', false, 'input_'+value+'_1' );
                    <?php endif; ?>
                        
                });
            }
        }
    });
});
</script>
        <?php
        $called++;
		return $form;
	}
}


if ( ! function_exists('_show_items'))
{
    function _show_items($listing, $form_id=2, $subfolder='', $lang_id=NULL)
    {
        $CI =& get_instance();
        $items_limit = 10;
        
        if($lang_id === NULL)
            $lang_id = sw_current_language_id();
        
        $CI->load->model('searchform_m');  
        $form = $CI->searchform_m->get($form_id);

        $CI->load->model('field_m');
        $CI->fields = $CI->field_m->get_field_list($lang_id);
        
        if(!is_object($form))
        {
            echo('<pre>ELEMENT MISSING</pre>');
            return;
        }
        
        $fields_value_json_1 = $form->fields_order;
        $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);
        
        $obj_widgets = json_decode($fields_value_json_1);
        
        if(is_object($obj_widgets->PRIMARY))
        foreach($obj_widgets->PRIMARY as $key=>$obj)
        {
            if($items_limit-- <= 0)break;
            
            $class = $obj->class;
            $style = $obj->style;
            $field_id = $obj->id;
            $type = $obj->type;
            $title = _field($listing, $field_id);
            
            // check for version with related marker
            $image = '';
            $field_data = $CI->field_m->get_field_data($field_id, $lang_id);
            if(isset($field_data->image_id))
            {
                $img = wp_get_attachment_image_src($field_data->image_id, '', true, '' );
                if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
                {
                    $image = $img[0];
                }
            }
            
            
            if(!empty($title) && $title != '-')
            if($type == 'CHECKBOX' && $title == '1')
            {
                echo '<span class="property-card-value field_id_'.$field_id.'" style="'.$style.'">';
                
                if(!empty($image))
                    echo '<img class="field_icon" src="'.$image.'"></img>';
                elseif($title == '1')
                {
                    if(!empty($class))
                        echo '<i class="'.$class.'"></i>';
                    else
                        echo '<i class="fa fa-check"></i>';
                }
                    
                if(isset($field_data->field_name))
                    echo $field_data->field_name;
                
                
                echo '</span>';
            }
            elseif($type == 'INPUTBOX' && !empty($title))
            {
                echo '<span class="property-card-value '.$class.' field_id_'.$field_id.'" style="'.$style.'">';

                if(isset($field_data->field_name))
                {
                    echo '<span class="field_name before hidden"> ';
                    echo $field_data->field_name.'';
                    echo ' </span>';
                }

                echo '<span class="item" style="'.$style.'"> ';
                if(!empty($class))
                    echo '<i class="'.$class.'"></i> '.$title;
                else
                    echo $title;
                
                echo ' </span>';
                
                if(isset($field_data->field_name))
                {
                    echo '<span class="field_name after hidden"> ';
                    echo $field_data->field_name.'';
                    echo ' </span>';
                }
                
                echo '</span>';
            }
            elseif($type == 'INTEGER' && !empty($title))
            {
                echo '<span class="property-card-value '.$class.' field_id_'.$field_id.'" style="'.$style.'">';

                if(isset($field_data->field_name))
                {
                    echo '<span class="field_name before hidden"> ';
                    echo $field_data->field_name.'';
                    echo ' </span>';
                }
                $title = ' <span class="prefix">'.$field_data->prefix.'</span> '.intval($title).' <span class="suffix"> '.$field_data->suffix.' </span>';
                
                echo '<span class="item" style="'.$style.'"> ';
                if(!empty($class))
                    echo '<i class="'.$class.'"></i> '.$title;
                else
                    echo $title;
                
                echo ' </span>';
                
                if(isset($field_data->field_name))
                {
                    echo '<span class="field_name after hidden"> ';
                    echo $field_data->field_name.'';
                    echo ' </span>';
                }
                
                echo '</span>';
            }
            else
            {
                echo '<span class="property-card-value '.$class.'  field_id_'.$field_id.'" style="'.$style.'">';
                
                if(isset($field_data->field_name))
                {
                    echo '<span class="field_name before hidden">';
                    echo $field_data->field_name.': ';
                    echo '</span>';
                }
                
                if(!empty($image))
                    echo '<img class="field_icon" src="'.$image.'"></img>';
                
                elseif(!empty($class))
                    echo '<i class="'.$class.'"></i>';

                echo _field($listing, $field_id);
                
                if(isset($field_data->field_name))
                    echo ' <span class="hidden field_name after">'.$field_data->field_name.'</span>';
                
                echo '</span>';
            }
            

        }
    }
}


if ( ! function_exists('_search_form_primary'))
{
    function _search_form_primary($form_id, $subfolder='', $lang_id=NULL)
    {
        $CI =& get_instance();
        
        if($lang_id === NULL)
            $lang_id = sw_current_language_id();
        
        $CI->load->model('searchform_m');  
        $form = $CI->searchform_m->get($form_id);
        
        $CI->load->model('field_m');
        $CI->fields = $CI->field_m->get_field_list($lang_id);
        
        if(!is_object($form))
        {
            echo('<pre>FORM MISSING</pre>');
            return;
        }
        
        //dump($form);
        
        $fields_value_json_1 = $form->fields_order;
        $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);

        $obj_widgets = json_decode($fields_value_json_1);

        if(is_object($obj_widgets->PRIMARY))
        foreach($obj_widgets->PRIMARY as $key=>$obj)
        {
            $title = '';
            $rel = $obj->type;
            $direction = 'NONE';
            if($obj->id != 'NONE')
            {
                if(isset($CI->fields[$obj->id]))
                {
                    $title.='#'.$obj->id.', ';
                    $title.=$CI->fields[$obj->id]->field_name;
                    $rel = $CI->fields[$obj->id]->type.'_'.$obj->id;
                    
                    if($obj->direction != 'NONE')
                    {
                        $direction = $obj->direction;
                        $title.=', '.$direction;
                        $rel.='_'.$obj->direction;
                    }
                }
            }
            else
            {
                $title.=$obj->type;
            }
        
            if(!empty($title))
            {
                if($obj->type == 'C_PRICE_RANGE' || $obj->type == 'C_PURPOSE' || $obj->type == 'SMART_SEARCH' || $obj->type == 'WHAT_SEARCH' || 
                $obj->type == 'WHERE_SEARCH' || $obj->type == 'DATE_RANGE' || $obj->type == 'BREAKLINE'  || 
                $obj->type == 'CATEGORY' || $obj->type == 'LOCATION' || $obj->type == 'C_BOOKING')
                {
                    if(!empty($subfolder)&&sw_file_exists(APPPATH.'views/searchform/'.$subfolder.$obj->type.'.php')){
                        echo $CI->load->view('searchform/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                    } elseif(sw_file_exists(APPPATH.'/views/searchform/'.$obj->type.'.php')){
                        echo $CI->load->view('searchform/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                    }
                    else
                    {
                        echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                    }
                }
                else
                {
                    
                    if(!empty($subfolder)&&sw_file_exists(APPPATH.'views/searchform/'.$subfolder.$obj->type.'.php')){
                        echo $CI->load->view('searchform/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                    }
                    elseif(sw_file_exists(APPPATH.'views/searchform/'.$obj->type.'.php'))
                    {
                        echo $CI->load->view('searchform/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                    }
                    else
                    {
                        echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                    }
                }
            }
        }
    }
}

if ( ! function_exists('sw_file_exists'))
{
    function sw_file_exists($relative_path)
    {

        $relative_path = str_replace(APPPATH, '', $relative_path);
        
        if(is_child_theme() && file_exists(get_stylesheet_directory().'/SW_Win_Classified/'.$relative_path))
        {
            return true;
        }
        else if(file_exists(get_template_directory().'/SW_Win_Classified/'.$relative_path))
        {
            return true;
        }
        else if(file_exists(APPPATH.$relative_path))
        {
            return true;
        }
        else if(file_exists($relative_path))
        {
            return true;
        }

        return false;
    }
}

if ( ! function_exists('_search_form_secondary'))
{
    function _search_form_secondary($form_id, $subfolder='secondary/', $lang_id=NULL)
    {
        $CI =& get_instance();
        
        if($lang_id === NULL)
            $lang_id = sw_current_language_id();
        
        $CI->load->model('searchform_m');  
        $form = $CI->searchform_m->get($form_id);
        
        $CI->load->model('field_m');
        $CI->fields = $CI->field_m->get_field_list($lang_id);
        
        if(!is_object($form))
        {
            echo('<pre>FORM MISSING</pre>');
            return;
        }
        
        //dump($form);
        
        $fields_value_json_1 = $form->fields_order;
        $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);

        $obj_widgets = json_decode($fields_value_json_1);

        if(is_object($obj_widgets->SECONDARY))
        foreach($obj_widgets->SECONDARY as $key=>$obj)
        {
            $title = '';
            $rel = $obj->type;
            $direction = 'NONE';
            if($obj->id != 'NONE')
            {
                if(isset($CI->fields[$obj->id]))
                {
                    $title.='#'.$obj->id.', ';
                    $title.=$CI->fields[$obj->id]->field_name;
                    $rel = $CI->fields[$obj->id]->type.'_'.$obj->id;
                    
                    if($obj->direction != 'NONE')
                    {
                        $direction = $obj->direction;
                        $title.=', '.$direction;
                        $rel.='_'.$obj->direction;
                    }
                }
            }
            else
            {
                $title.=$obj->type;
            }
        
            if(!empty($title))
            {
                if($obj->type == 'C_PURPOSE' || $obj->type == 'SMART_SEARCH' || $obj->type == 'DATE_RANGE' || $obj->type == 'BREAKLINE')
                {
                    if(!empty($subfolder)&&sw_file_exists(APPPATH.'views/searchform/'.$subfolder.$obj->type.'.php')){
                        echo $CI->load->view('searchform/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                    } elseif(sw_file_exists(APPPATH.'/views/searchform/'.$subfolder.$obj->type.'.php')){
                        echo $CI->load->view('searchform/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                    }
                    else
                    {
                        echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                    }
                }
                else
                {
                    
                    if(!empty($subfolder)&&sw_file_exists(APPPATH.'views/searchform/'.$subfolder.$obj->type.'.php')){
                        echo $CI->load->view('searchform/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                    }
                    elseif(sw_file_exists(APPPATH.'views/searchform/'.$subfolder.$obj->type.'.php'))
                    {
                        echo $CI->load->view('searchform/'.$subfolder.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                    }
                    else
                    {
                        echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                    }
                }
            }
        }
    }
}

if ( ! function_exists('build_admin_form'))
{
    function build_admin_form($model, $form_fields, $button_title = NULL, $custom_success_message=NULL)
    {
        $CI =& get_instance();
        $CI->load->model($model);
        
        $all_fields = $CI->$model->$form_fields;
        
        // fetch values
        if(!empty($CI->$model->form_id) && !isset($CI->data['form_object']))
        {
            $CI->data['form_object'] = $CI->$model->get($CI->$model->form_id);
        }
        
        // dump($all_fields);
        // echo validation_errors();
        
    //    if(validation_errors() != '')
    //    {
    //        $output = $CI->load->view('admin/form_elements/validation_errors', array_merge($CI->data, array('validation_errors'=>validation_errors())), TRUE);
    //        echo $output;
    //    }
        
    //    if($CI->session->flashdata('message') !== NULL)
    //    {
    //        $output = $CI->load->view('admin/form_elements/message', array_merge($CI->data, array('message'=>$CI->session->flashdata('message'))), TRUE);
    //        echo $output;
    //    }
        
        echo '<form action="" method="post" class="" role="form" autocomplete="off" >';
        
        echo '<div class="col-xs-12 col-sm-12">';
        _form_messages($custom_success_message);
        echo '</div>';
        
        foreach($all_fields as $key=>$field)
        {
            //check for error in rules
            if(substr($field['rules'], -1, 1) == '|')
                show_error('issue in validation rule for field: '.$field['field']);
                
            if(isset($all_fields[$key]['value']))
            {
                $field['value'] = $all_fields[$key]['value'];
            }

            if(isset($CI->data['form_object']->{$field['field']}))
            {
                $field['value'] = $CI->data['form_object']->{$field['field']};
            }
            elseif(isset($all_fields[$key]['value']))
            {
                //$field['value'] = $all_fields[$key]['value']; - no need to se, already set
            }
            else
            {
                $field['value'] = '';
            }

            // print field view
            $output = $CI->load->view('admin/form_elements/'.$field['design'], array_merge($CI->data, array('field'=>$field)), TRUE);
            echo $output;
        }
        
        $output = $CI->load->view('admin/form_elements/button_submit', array_merge($CI->data, array('field'=>$field, 'button_title'=>$button_title)), TRUE);
        echo $output;
        
        echo form_close();
        
    }
}


/* End of file form_helper.php */
