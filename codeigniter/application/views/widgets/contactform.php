<?php $CI =& get_instance(); ?>

<?php _form_messages(__('Message sent successfully', 'sw_win'), NULL, $widget_id); ?>

<form id="sw_contactform_<?php echo $widget_id?>" method="post" action="#sw_contactform_<?php echo $widget_id?>" class="box">
    
    <?php if(function_exists('sw_win_load_ci_function_calendar') && 
             sw_is_page(sw_settings('listing_preview_page'))): ?>

    <?php

        $CI->load->model('calendar_m');
        $listing = $CI->data['listing'];

        $readonly ='';
        if( !is_user_logged_in())
            $readonly ='readonly="readonly"';
        
        $calendar = $CI->calendar_m->get_by(array('sw_calendar.listing_id'=>$listing->idlisting), true);
        if(sw_count($calendar)):

    ?>
    
    <?php if( !is_user_logged_in()):?>
    <div class="alert alert-info">
        <?php echo esc_html__('For booking, please', 'sw_win'); ?> <a href="<?php echo esc_url(get_permalink(sw_settings('register_page'))); ?>" class="<?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>"><?php echo esc_html__('login', 'yordy'); ?></a>
    </div>
    <?php endif;?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Date from', 'sw_win'); ?></label>
                <div id="datetimepicker-<?php echo $widget_id?>" class="input-group date">
                    <input value="<?php echo _fv('form_widget', 'date_from'); ?>" id="date_from_<?php echo $widget_id?>" name="date_from" type="text" class="form-control"  <?php echo esc_html($readonly);?> placeholder="<?php echo __('Date from', 'sw_win'); ?>">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div><!-- /.form-group -->
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Date to', 'sw_win'); ?></label>
                <div id="datetimepicker-<?php echo $widget_id?>-2" class="input-group date">
                <input value="<?php echo _fv('form_widget', 'date_to'); ?>" id="date_to_<?php echo $widget_id?>" name="date_to" type="text" class="form-control"  <?php echo esc_html($readonly);?> placeholder="<?php echo __('Date to', 'sw_win'); ?>">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            </div><!-- /.form-group -->
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label><?php echo __('Guests', 'sw_win'); ?></label>
                <?php echo form_dropdown('guests_number', array(''=>'', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5+'=>'5+'), _fv('form_widget', 'guests_number', 'TEXT','1'), 'class="form-control" '.$readonly)?>
            </div><!-- /.form-group -->
        </div>
    </div><!-- /.row -->

<?php

$CI->load->model('rates_m');
$CI->load->model('reservation_m');


$listing = $CI->data['listing'];

$dates_enabled = $CI->reservation_m->get_enabled_dates($listing->idlisting);


//dump($dates_enabled);

?>
    <script>

    jQuery(document).ready(function() {
        if (jQuery('#datetimepicker-<?php echo $widget_id; ?>').length) {
            jQuery('#datetimepicker-<?php echo $widget_id; ?>').datetimepicker({
                <?php if($calendar->calendar_type == 'DAY'): ?>
                format: '<?php echo substr(config_db_item('date_format_js'), 0, strpos(config_db_item('date_format_js'), ' ')); ?>',
                <?php else: ?>
                format: '<?php echo config_db_item('date_format_js'); ?>',
                <?php endif; ?>
                useCurrent: false,
                minDate: '<?php echo date('Y-m-d H:i:s'); ?>',
                enabledDates: [<?php echo join(',', $dates_enabled); ?>],
                //hour : '12',
                stepping: 30,
                debug: false
            });
        }

        if (jQuery('#datetimepicker-<?php echo $widget_id; ?>-2').length) {
            jQuery('#datetimepicker-<?php echo $widget_id; ?>-2').datetimepicker({
                <?php if($calendar->calendar_type == 'DAY'): ?>
                format: '<?php echo substr(config_db_item('date_format_js'), 0, strpos(config_db_item('date_format_js'), ' ')); ?>',
                <?php else: ?>
                format: '<?php echo config_db_item('date_format_js'); ?>',
                <?php endif; ?>
                useCurrent: false,
                enabledDates: [<?php echo join(',', $dates_enabled); ?>],
                //hour : '12',
                stepping: 30,
                debug: false
            });

            jQuery('#datetimepicker-<?php echo $widget_id; ?>').on("dp.change", function (e) {
                jQuery('#datetimepicker-<?php echo $widget_id; ?>-2').data("DateTimePicker").minDate(e.date);
                jQuery('#datetimepicker-<?php echo $widget_id; ?>-2').datetimepicker('show');
                jQuery(this).datetimepicker('hide');
            });
            jQuery('#datetimepicker-<?php echo $widget_id; ?>-2').on("dp.change", function (e) {
                jQuery('#datetimepicker-<?php echo $widget_id; ?>').data("DateTimePicker").maxDate(e.date);
                jQuery(this).datetimepicker('hide');
            });

        }
    });

    </script>

    <style>

        #sw_contactform_<?php echo $widget_id?> {
            overflow: visible;
        }

        td.day.disabled{
            text-decoration: line-through;
            background: #f7f7f7 !important;
        }

        td.day:not(.disabled){
            /*background: green;*/
        }

    </style>

    <br style="clear: both;" />

    <?php

    wp_enqueue_script( 'datetime-picker-moment' );
    wp_enqueue_script( 'datetime-picker-bootstrap' );
    wp_enqueue_style( 'datetime-picker-css' );

    ?>

    <?php endif;endif; ?>
    
    
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Full name', 'sw_win'); ?></label>
                <input class="form-control" id="fullname_<?php echo $widget_id?>" name="fullname" type="text" value="<?php echo _fv('form_widget', 'fullname'); ?>" placeholder="<?php echo __('Full name', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Your email', 'sw_win'); ?></label>
                <input class="form-control" id="email_<?php echo $widget_id?>" name="email" type="text" value="<?php echo _fv('form_widget', 'email'); ?>" placeholder="<?php echo __('Your email', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>
    </div><!-- /.row -->
    
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Phone number', 'sw_win'); ?></label>
                <input class="form-control" id="phone_<?php echo $widget_id?>" name="phone" type="text" value="<?php echo _fv('form_widget', 'phone'); ?>" placeholder="<?php echo __('Phone number', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo __('Subject', 'sw_win'); ?></label>
                <input class="form-control" id="subject_<?php echo $widget_id?>" name="subject" type="text" value="<?php echo _fv('form_widget', 'subject'); ?>" placeholder="<?php echo __('Subject', 'sw_win'); ?>" />
            </div><!-- /.form-group -->
        </div>

    </div><!-- /.row -->

    <div class="form-group">
        <label><?php echo __('Message', 'sw_win'); ?></label>
        <textarea id="message_<?php echo $widget_id?>" name="message" rows="4" class="form-control" type="text"><?php echo _fv('form_widget', 'message'); ?></textarea>
    </div><!-- /.form-group -->
    
    <input class="hidden" id="widget_id" name="widget_id" type="text" value="<?php echo $widget_id?>" />

    <?php echo _recaptcha(strpos($widget_name, 'sidebar')!==FALSE); ?>

    <div class="form-group">
        <input type="submit" value="<?php echo __('Send', 'sw_win'); ?>" class="btn btn-primary btn-inversed">
    </div><!-- /.form-group -->
</form>