<?php

    $field_name = __('What?', 'sw_win');

    $col=12;
    $f_id = 'what';
    $placeholder = __('Search keyword', 'sw_win');

    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-'.$col;

?>
<div class="form-group <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php echo $field_name; ?></label>
    <input id="search_<?php echo $f_id; ?>" name="search_<?php echo $f_id; ?>" type="text" class="form-control" placeholder="<?php echo $placeholder ?>" value="<?php echo search_value($f_id); ?>" />
</div><!-- /.form-group -->