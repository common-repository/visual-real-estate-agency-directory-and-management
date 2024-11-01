<?php
$CI = &get_instance();
$field_data = $CI->field_m->get_field_data(36);

$suf = $field_data->suffix;
$pre = $field_data->prefix;

?>
<div class="row">
    <div class="col-sm-6">
        <div class="field_search_36 form-group">
            <label><?php echo $pre.esc_html__('Price From','sw_win').$suf;?></label>
            <input id="search_36_from" name="search_36_from" type="text" class="form-control" placeholder="<?php echo esc_html__('Price From','sw_win');?>" value="<?php echo esc_html__('Price From','sw_win');?>" />
        </div><!-- /.form-group -->
    </div>
    <div class="col-sm-6">
        <div class="field_search_36 form-group">
            <label><?php echo $pre.esc_html__('Price To','sw_win').$suf;?></label>
            <input id="search_36_from" name="search_36_from" type="text" class="form-control" placeholder="<?php echo esc_html__('Price From','sw_win');?>" value="<?php echo esc_html__('Price From','sw_win');?>" />
        </div><!-- /.form-group -->
    </div>
</div>
