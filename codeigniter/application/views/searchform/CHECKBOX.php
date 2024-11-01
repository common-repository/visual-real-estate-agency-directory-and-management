<?php
    $col=6;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
    }
    else
    {
        $placeholder = $direction;
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = _ch(${'options_prefix_'.$f_id}, '')._ch(${'options_suffix_'.$f_id}, '');
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
    
    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-6';
        
    $field_name = $field_data->field_name;
    
?>
<div class="field_search_<?php echo $f_id; ?> form-group <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label class="checkbox" for="search_option_<?php echo $f_id; ?>">
        <input rel="<?php echo $field_name; ?>" name="search_<?php echo $f_id; ?>" id="search_<?php echo $f_id; ?>" type="checkbox" value="1" <?php echo search_value($f_id, 'checked'); ?>/> <?php echo $field_name; ?>
    </label>
</div><!-- /.form-group -->