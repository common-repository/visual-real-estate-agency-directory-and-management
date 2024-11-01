<?php if(function_exists('sw_pluginsLoaded_calendar')): ?>

<?php

    $col=6;
    $f_id = 'booking';
    $placeholder = __('Booking', 'sw_win');

    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-'.$col;

?>

<?php if(false): ?>
<div class="form-group <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php echo $field_name; ?></label>
    <input id="search_<?php echo $f_id; ?>" name="search_<?php echo $f_id; ?>_from" type="text" class="form-control" placeholder="<?php echo $placeholder ?>" value="<?php echo search_value($f_id); ?>" />
</div><!-- /.form-group -->
<?php endif; ?>


<div class="row" id="search_<?php echo $f_id; ?>" style="<?php _che($field->style); ?>">
    <div class="<?php echo $class_add; ?>">
        <div class="form-group">
            <label><?php echo __('Date from', 'sw_win'); ?></label>
            <div id="date_<?php echo $f_id; ?>_from" class="input-group date">
                <input value="<?php echo search_value($f_id.'_from'); ?>" name="search_<?php echo $f_id; ?>_from" id="search_<?php echo $f_id; ?>_from" type="text" class="form-control"  placeholder="<?php echo __('Date from', 'sw_win'); ?>">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div><!-- /.form-group -->
    </div>

    <div class="<?php echo $class_add; ?>">
        <div class="form-group">
            <label><?php echo __('Date to', 'sw_win'); ?></label>
            <div id="date_<?php echo $f_id; ?>_to" class="input-group date">
            <input value="<?php echo search_value($f_id.'_to'); ?>" name="search_<?php echo $f_id; ?>_to" id="search_<?php echo $f_id; ?>_to" type="text" class="form-control"  placeholder="<?php echo __('Date to', 'sw_win'); ?>">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
        </div><!-- /.form-group -->
    </div>

</div><!-- /.row -->

<script>

jQuery(document).ready(function() {
    if (jQuery('#date_<?php echo $f_id; ?>_from').length) {
        jQuery('#date_<?php echo $f_id; ?>_from').datetimepicker({
            format: '<?php echo config_db_item('date_format_js'); ?>',
            useCurrent: false,
            minDate: '<?php echo date('Y-m-d H:i:s'); ?>',
            //hour : '12',
            stepping: 30,
            debug: false
        });
    }

    if (jQuery('#date_<?php echo $f_id; ?>_to').length) {
        jQuery('#date_<?php echo $f_id; ?>_to').datetimepicker({
            format: '<?php echo config_db_item('date_format_js'); ?>',
            useCurrent: false,
            //hour : '12',
            stepping: 30,
            debug: false
        });

        jQuery('#date_<?php echo $f_id; ?>_from').on("dp.change", function (e) 
        {
            jQuery('#date_<?php echo $f_id; ?>_to').data("DateTimePicker").minDate(e.date);
            jQuery('#date_<?php echo $f_id; ?>_to').datetimepicker('show');
            jQuery(this).datetimepicker('hide');
        });
        jQuery('#date_<?php echo $f_id; ?>_to').on("dp.change", function (e) 
        {
            jQuery('#date_<?php echo $f_id; ?>_from').data("DateTimePicker").maxDate(e.date);
            jQuery(this).datetimepicker('hide');
        });

    }
});

</script>

<?php

wp_enqueue_script( 'datetime-picker-moment' );
wp_enqueue_script( 'datetime-picker-bootstrap' );
wp_enqueue_style( 'datetime-picker-css' );

?>

<?PHP else: ?>
<?php echo __('BOOKING PLUGIN MISSING', 'sw_win'); ?>
<?php endif; ?>