<?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
    <div class="form-group col-sm-6">
        <label><?php echo __('Fromdate', 'sw_win'); ?></label>
        <input id="booking_date_from" type="text"  class="form-control" placeholder="<?php echo __('Fromdate', 'sw_win'); ?>" value="<?php echo search_value('date_from'); ?>" />
    </div><!-- /.form-group -->
    
    <div class="form-group col-sm-6">
        <label><?php echo __('Todate', 'sw_win'); ?></label>
        <input id="booking_date_to" type="text"  class="form-control" placeholder="<?php echo __('Todate', 'sw_win'); ?>" value="<?php echo search_value('date_to'); ?>" />
    </div><!-- /.form-group -->
<?php endif; ?>