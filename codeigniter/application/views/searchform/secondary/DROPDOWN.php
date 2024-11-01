<?php
    $col=6;

    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
    }
    
    $f_id = $field->id;
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-12';
        
        
    $field_name = $field_data->field_name;

    $values_available = explode(',', $field_data->values);
    $values_available = array_combine($values_available, $values_available);
    $values_available[''] = $field_name;
    
?>
<div class="field_search_<?php echo $f_id; ?> form-group <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php _che($field_name); ?></label>

    <div class="select-wrapper-1">
        <?php echo form_dropdown('search_'.$field_data->idfield, $values_available, '', 'class="form-control"')?>
    </div><!-- /.select-wrapper -->
</div><!-- /.form-group -->
