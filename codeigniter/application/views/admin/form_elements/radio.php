<div class="col-xs-12 col-sm-12">
	<div class="form-group">
		<label for="<?php echo $field['field']; ?>"><?php echo $field['label']; ?><?php if(strpos($field['rules'], 'required') !== FALSE)echo '*'; ?></label>
<?php

    $first_key='';

    if(!isset($field['values']) || !is_array($field['values']))
    {
        $field['values'] = array();
    }
    else
    {
        foreach($field['values'] as $key=>$val)
        {
            if(empty($first_key))$first_key = $key;

            $field['values'][$key] = $val;
        }
    }
        
    $data = array(
            'name'          => $field['field'],
            'id'            => $field['field'],
            //'value'         => set_value($field['field'], $field['value']),
            //'checked'       => TRUE,
            //'style'         => 'margin:10px'
    );

    foreach($field['values'] as $key=>$val)
    {
        echo '<div class="radio">';
        echo '<label>';
        echo form_radio($data, $key, set_value($field['field'], $first_key)===$key, 'id="'.$field['field'].'" class=" '.(form_error($field['field'])!=''?'error':'').'"').' '.$val;   
        echo '</label>'; 
        echo '</div>';
    }

    echo form_error($field['field'], '<div class="input-error-msg">', '</div>');
?>
    </div>
</div>