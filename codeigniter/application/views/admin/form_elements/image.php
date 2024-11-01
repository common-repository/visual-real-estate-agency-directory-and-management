<div class="col-xs-12 col-sm-12">
	<div class="form-group <?php _has_error($field['field']); ?>">
		<label for="<?php echo $field['field']; ?>" class="control-label" ><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
		
        <?php
        
        sw_upload_media_element($field['field'], $field['field'], $field['field'], set_value($field['field'], $field['value']));

        ?>

        <?php if(!empty($field['hint'])): ?>
        <p><em><?php echo $field['hint']; ?></em></p>
        <?php endif; ?>
        <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?>
    </div>
</div>