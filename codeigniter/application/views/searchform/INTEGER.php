<?php

    $field_name = $field_data->field_name;
    
    $for_translate = __('FROM', 'sw_win');
    $for_translate = __('TO', 'sw_win');
    
    $col=6;
    $f_id = $field->id;
    $placeholder = $field_name;
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
    }
    else
    {
        $placeholder = __($direction, 'sw_win');
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = $field_data->prefix.$field_data->suffix;
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
        
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-'.$col;

    $values_available = explode(',', $field_data->values);
    $values_available = array_combine($values_available, $values_available);
    if(isset($values_available['']))
    {
        $values_available[''] = __($field->direction, 'sw_win');
    }

?>
<div class="field_search_<?php echo $f_id; ?> form-group <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php echo $field_name; ?><?php echo $suf_pre; ?></label>
    <?php if(sw_count($values_available) > 1): ?>
    <?php echo form_dropdown('search_'.$f_id.$direction, $values_available, search_value($f_id.$direction), 'class="form-control"')?>
    <?php else: ?>
    <input id="search_<?php echo $f_id.$direction; ?>" name="search_<?php echo $f_id.$direction; ?>" type="text" class="form-control" placeholder="<?php echo $placeholder ?><?php echo $suf_pre; ?>" value="<?php echo search_value($f_id.$direction); ?>" />
    <?php endif; ?>
</div><!-- /.form-group -->