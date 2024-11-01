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
            $values[] = $row->{$field['index']}.', '.$row->{$field['display']};
        }
    }
    $columns = $values;
    echo form_table($field['field'], $columns, set_value($field['field'], $field['value']));
    
    echo form_error($field['field'], '<div class="input-error-msg">', '</div>');
?>
	</div>
</div>