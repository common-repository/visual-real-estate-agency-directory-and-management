<?php

    $field_name = __('Location', 'sw_win');

    $col=12;
    $f_id = 'location';
    $placeholder = __('Search keyword', 'sw_win');

    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-'.$col;

?>
<?php if(sw_settings('show_locations')): ?>

<div class="form-group group_location_id search_field <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php echo $field_name; ?></label>
    <?php echo form_treefield('search_location', 'treefield_m', search_value($f_id), 'value', sw_current_language_id(), 'field_search_', true, __('All Locations', 'sw_win'), 2);?>
</div><!-- /.form-group -->

<?php endif; ?>