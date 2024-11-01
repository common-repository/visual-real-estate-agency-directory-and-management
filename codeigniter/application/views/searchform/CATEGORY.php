<?php

    $field_name = __('Category', 'sw_win');

    $col=12;
    $f_id = 'category';
    $placeholder = __('Search keyword', 'sw_win');

    $class_add = $field->class;
    if(empty($class_add))
        $class_add = ' col-sm-'.$col;

?>
<?php if(sw_settings('show_categories')): ?>

<div class="form-group group_category_id search_field <?php echo $class_add; ?>" style="<?php _che($field->style); ?>">
    <label><?php echo $field_name; ?></label>
    <?php echo form_treefield('search_category', 'treefield_m', search_value($f_id), 'value', sw_current_language_id(), 'field_search_', true, __('All Categories', 'sw_win'));?>
</div><!-- /.form-group -->

<?php endif; ?>