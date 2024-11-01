<div class="form-group col-sm-7">
    <label><?php echo __('Location', 'sw_win'); ?></label>
    <input id="search_where" name="search_where" value="<?php echo search_value('where'); ?>" type="text"  class="form-control" placeholder="<?php echo __('City', 'sw_win'); ?>" />
</div><!-- /.form-group -->
<div class="form-group col-sm-5">
    <label><?php echo __('Radius', 'sw_win'); ?></label>

    <div class="select-wrapper-1">
        <select id="search_radius" name="search_radius" class="form-control">
<?php
    $sel_values = array(0=>'-',50=>50,100=>100,200=>200,500=>500);
    $suffix = __('km', 'sw_win');
    $curr_value = search_value('radius');
    
    foreach($sel_values as $key=>$val)
    {
        if($curr_value == $val)
        {
            echo "<option value=\"$key\" selected>$val$suffix</option>\r\n";
        }
        else
        {
            echo "<option value=\"$key\">$val$suffix</option>\r\n";
        }
    }
?>
        </select>
    </div><!-- /.select-wrapper -->

</div><!-- /.form-group -->