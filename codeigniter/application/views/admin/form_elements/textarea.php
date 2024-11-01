<div class="col-xs-12 col-sm-12">
	<div class="form-group">
		<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
		<textarea id="<?php echo $field['field']; ?>" name="<?php echo $field['field']; ?>" class="form-control <?php echo form_error($field['field'])!=''?'error':'';  ?>" rows="8"><?php echo set_value($field['field'], $field['value']); ?></textarea>
	    <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?>
    </div>
</div>