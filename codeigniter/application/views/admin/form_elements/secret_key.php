<div class="col-xs-12 col-sm-6">
	<div class="form-group">
		<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?> (<?php echo __('Read only', 'sw_win'); ?>)</label>
		<input type="text" value="<?php echo set_value($field['field'], $field['value']); ?>" class="form-control <?php echo form_error($field['field'])!=''?'error':'';  ?>" id="<?php echo $field['field']; ?>" name="<?php echo $field['field']; ?>" placeholder="" autocomplete="off" readonly/>
        <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?>
    </div>
</div>
<div class="col-xs-12 col-sm-6">
	<div class="form-group">
		<label>&nbsp;</label>
		<button type="button" id="generate_new" class="btn btn-primary btn-lg bg-brown col-xs-12 col-sm-12"><?php echo __('Generate new', 'sw_win'); ?></button>
    </div>
</div>

<script>

$(document).ready(function() {
    $('#generate_new').click(function()
    {
        var device_key = $('#device_key').val();
        
        $.post( "<?php echo c_site_url('admin/device/generate_key'); ?>", { 'device_key': device_key})
          .done(function( data ) {
            var json_data = JSON.parse(data)
            $('#secret_key').val(json_data.new_key);
          });
    });
    
});

</script>


