<div class="col-xs-12 col-sm-12">
	<div class="form-group <?php _has_error($field['field']); ?>">
		<label for="<?php echo $field['field']; ?>" class="control-label" ><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
		<input type="text" value="<?php echo set_value($field['field'], $field['value']); ?>" class="form-control <?php echo form_error($field['field'])!=''?'error':'';  ?>" id="<?php echo $field['field']; ?>" name="<?php echo $field['field']; ?>" placeholder="" autocomplete="off" />
        <?php if(!empty($field['hint'])): ?>
        <p><em><?php echo $field['hint']; ?></em></p>
        <?php endif; ?>
        <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?>
    </div>
</div>