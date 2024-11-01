<div class="form-group col-sm-7">
    <label><?php echo __('Location', 'sw_win'); ?></label>
    <input id="search_option_smart" value="<?php _che($search_query); ?>" type="text"  class="form-control" placeholder="<?php echo __('City', 'sw_win'); ?>" />
</div><!-- /.form-group -->
<div class="form-group col-sm-5">
    <label><?php echo __('Radius', 'sw_win'); ?></label>

    <div class="select-wrapper-1">
        <select id="search_radius" name="search_radius" class="form-control">
<?php
    $sel_values = array(0,50,100,200,500);
    $suffix = __('km', 'sw_win');
    $curr_value=NULL;
    
    if(isset($_GET['search']))$search_json = json_decode($_GET['search']);
    if(isset($search_json->v_search_radius))
    {
        $curr_value=$search_json->v_search_radius;
    }
    
    foreach($sel_values as $key=>$val)
    {
        if($curr_value == $val)
        {
            echo "<option value=\"$val\" selected>$val$suffix</option>\r\n";
        }
        else
        {
            echo "<option value=\"$val\">$val$suffix</option>\r\n";
        }
    }
?>
        </select>
    </div><!-- /.select-wrapper -->

</div><!-- /.form-group -->