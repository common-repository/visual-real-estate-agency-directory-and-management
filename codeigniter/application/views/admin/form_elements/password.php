<div class="col-xs-12 col-sm-12">
	<div class="form-group">
		<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
	    <input class="js-pwstrength form-control m-b-10 <?php echo form_error($field['field'])!=''?'error':'';  ?>" id="<?php echo $field['field']; ?>" name="<?php echo $field['field']; ?>" placeholder="" type="password" autocomplete="off" />
        <?php echo form_error($field['field'], '<div class="input-error-msg">', '</div>');  ?>
    </div>
</div>