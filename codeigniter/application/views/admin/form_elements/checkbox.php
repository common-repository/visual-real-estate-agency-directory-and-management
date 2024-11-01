<?php 

    $class="col-xs-12 col-sm-12";

    if(isset($field['class']))
    {
        $class = $field['class'];
    }

?>

<div class="<?php echo $class; ?>">
	<div class="form-group checkboxes <?php _has_error($field['field']); ?>">
		<label class="control-label">
			<input type="checkbox" name="<?php echo $field['field']; ?>" value="1" <?php echo (set_value($field['field'], $field['value'])==1?'checked':''); ?>/>
			<span><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></span>
            <?php if(!empty($field['hint'])): ?>
            <p><em><?php echo $field['hint']; ?></em></p>
            <?php endif; ?>
	        <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?> 
        </label>
	</div>
</div>