<div class="col-xs-12 col-sm-12">
	<div class="form-group">
		<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
<?php

    $values=array();

    if(!isset($field['table']) || !isset($field['index']) || !isset($field['display']))
    {
        $values = array();
    }
    else
    {
        $users = $this->user_m->get();
        foreach($users as $key=>$row)
        {
            $values[$row->{$field['index']}] = $row->{$field['index']}.', '.$row->{$field['display']};
        }
    }
        
    
    echo form_dropdown($field['field'], $values, set_value($field['field'], $field['value']), 'id="'.$field['field'].'" class="select2 js-select2 form-control '.(form_error($field['field'])!=''?'error':'').'"');
    
    echo form_error($field['field'], '<div class="input-error-msg">', '</div>');
?>
	</div>
</div>