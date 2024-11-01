
<?php 

    $class="col-xs-12 col-sm-12";

    if(isset($field['class']))
    {
        $class = $field['class'];
    }

?>

<div class="<?php echo $class; ?>">
	<div class="form-group <?php _has_error($field['field']); ?>">
		<label class="control-label" for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?> (<?php echo __('Read only', 'sw_win'); ?>)</label>
		<input type="text" value="<?php echo set_value($field['field'], $field['value']); ?>" class="form-control <?php echo form_error($field['field'])!=''?'error':'';  ?>" id="<?php echo $field['field']; ?>" name="<?php echo $field['field']; ?>" placeholder="" autocomplete="off" readonly/>
        <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?>
    </div>
</div>