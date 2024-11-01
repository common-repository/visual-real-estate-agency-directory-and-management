<div class="col-xs-12 col-sm-4">
	<!-- Datetimepickers -->
	<div class="form-group">
		<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
		<div id="datetimepicker-<?php echo $field['field']; ?>" class="input-group date">
			<input value="<?php 
            
            if(!empty($field['value']))
                $field['value'] = date(config_db_item('date_format_php'), strtotime($field['value']));
            
            echo set_value($field['field'], $field['value']); 
            
            ?>" id="<?php echo $field['field']; ?>" name="<?php echo $field['field']; ?>" type="text" class="form-control" placeholder="<?php echo __('Select date & time', 'sw_win');?>">
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
		</div>
        <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?>
	</div>
</div>

<script>

jQuery(document).ready(function() {
	if (jQuery('#datetimepicker-<?php echo $field['field']; ?>').length) {
		jQuery('#datetimepicker-<?php echo $field['field']; ?>').datetimepicker({
			format: '<?php echo config_db_item('date_format_js'); ?>',
			useCurrent: 'hour',
			//hour : '12',
			stepping: 30
		});

	}
});


</script>

<br style="clear: both;" />

<?php

wp_enqueue_script( 'datetime-picker-moment' );
wp_enqueue_script( 'datetime-picker-bootstrap' );
wp_enqueue_style( 'datetime-picker-css' );

?>