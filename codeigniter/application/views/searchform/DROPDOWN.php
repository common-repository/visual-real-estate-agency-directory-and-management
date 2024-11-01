<?php
    $col=6;
    $trans = array();
    $trans['FROM'] = esc_html__('FROM', 'sw_win' );
    $trans['TO'] = esc_html__('TO', 'sw_win' );
    $trans['NONE'] = '';
    $trans[''] = '';
    
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
    } 
    else
    {
        $placeholder = $trans[$field->direction];
        $direction=strtolower('_'.$direction);
    }
    
    $f_id = $field->id;
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-12';
        
        
    $field_name = $field_data->field_name;

    $values_available = explode(',', $field_data->values);
    $values_available = array_combine($values_available, $values_available);
    $values_available[''] = __('Any', 'sw_win');
    
?>
<div class="field_search_<?php echo $f_id; ?> form-group <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php _che($field_name); ?></label>

    <div class="select-wrapper-1">
        <?php echo form_dropdown('search_'.$field_data->idfield.$direction, $values_available, search_value($f_id.$direction), 'class="form-control"')?>
    </div><!-- /.select-wrapper -->
</div><!-- /.form-group -->
