<?php
if(config_item('field_dropdown_multiple_enabled') === FALSE) return false;

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
    
?>
<div class="field_search_<?php echo $f_id; ?> form-group selectpicker <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php _che(${'options_name_'.$f_id}); ?></label>
    <div class="select-wrapper-1">
        <select id="search_option_<?php echo $f_id; ?>_multi" multiple="multiple"  class="form-control" size="3">
            <?php if(isset(${'options_values_arr_'.$f_id}) && !empty(${'options_values_arr_'.$f_id}))
                    foreach (${'options_values_arr_'.$f_id} as $key => $value):?>
                    <option value="<?php _che($value);?>"><?php _che($value);?></option>
            <?php endforeach;?>
        </select>
    </div><!-- /.select-wrapper -->
</div><!-- /.form-group -->

