<div class="col-xs-12 col-sm-12">
	<div class="form-group">
		<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
<?php
    if(!isset($field['values']) || !is_array($field['values']))
    {
        $field['values'] = array();
    }
    else
    {
        foreach($field['values'] as $key=>$val)
        {
            $field['values'][$key] = $val;
        }
    }
        
    
    echo form_dropdown($field['field'], $field['values'], set_value($field['field'], $field['value']), 'id="'.$field['field'].'" class="select2 js-select2 form-control '.(form_error($field['field'])!=''?'error':'').'"');
    ?>

    <?php if(!empty($field['hint'])): ?>
    <p><em><?php echo $field['hint']; ?></em></p>
    <?php endif; ?>

    <?php
    echo form_error($field['field'], '<div class="input-error-msg">', '</div>');
?>
	</div>
</div>