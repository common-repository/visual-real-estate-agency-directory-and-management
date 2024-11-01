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
    //$values_available[''] = __('Any', 'sw_win');
    unset($values_available['']);
    
?>
<div class="field_search_<?php echo $f_id; ?> form-group selectpicker <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php _che($field_name); ?></label>
    <div class="select-wrapper-1">
        <?php echo form_multiselect('search_'.$field_data->idfield, $values_available, search_value($f_id), 'class="form-control"')?>
        
        <?php if(false): ?>
        <select id="search_option_<?php echo $f_id; ?>_multi" multiple="multiple"  class="form-control" size="3">
            <?php if(isset(${'options_values_arr_'.$f_id}) && !empty(${'options_values_arr_'.$f_id}))
                    foreach (${'options_values_arr_'.$f_id} as $key => $value):?>
                    <option value="<?php _che($value);?>"><?php _che($value);?></option>
            <?php endforeach;?>
        </select>
        <?php endif; ?>
    </div><!-- /.select-wrapper -->
</div><!-- /.form-group -->

