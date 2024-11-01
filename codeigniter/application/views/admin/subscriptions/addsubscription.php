
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit subscription','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('Add subscription','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Subscription data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
      <div class="form-group <?php _has_error('subscription_name'); ?> IS-INPUTBOX">
        <label for="input_subscription_name" class="col-sm-2 control-label"><?php echo __('Subscription name','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="subscription_name" value="<?php echo _fv('form_object', 'subscription_name'); ?>" type="text" id="input_subscription_name" class="form-control" placeholder="<?php echo __('Subscription name','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('days_limit'); ?> IS-INPUTBOX">
        <label for="input_days_limit" class="col-sm-2 control-label"><?php echo __('Days limit','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="days_limit" value="<?php echo _fv('form_object', 'days_limit'); ?>" type="text" id="input_days_limit" class="form-control" placeholder="<?php echo __('Days limit','sw_win'); ?>"/>
        </div>
      </div>

      
      <div class="form-group <?php _has_error('listing_limit'); ?> IS-INPUTBOX">
        <label for="input_listing_limit" class="col-sm-2 control-label"><?php echo __('Listings limit','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="listing_limit" value="<?php echo _fv('form_object', 'listing_limit'); ?>" type="text" id="input_listing_limit" class="form-control" placeholder="<?php echo __('Listings limit','sw_win'); ?>"/>
        </div>
      </div>
      
      <?php if(false): ?>
      <div class="form-group <?php _has_error('featured_limit'); ?> IS-INPUTBOX">
        <label for="input_featured_limit" class="col-sm-2 control-label"><?php echo __('Featured limit','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="featured_limit" value="<?php echo _fv('form_object', 'featured_limit'); ?>" type="text" id="input_featured_limit" class="form-control" placeholder="<?php echo __('Featured limit','sw_win'); ?>"/>
        </div>
      </div>
      <?php endif; ?>

      <div class="form-group <?php _has_error('set_activated'); ?>">
        <label for="input_set_activated" class="col-sm-2 control-label"><?php echo __('Set activated','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="set_activated" value="1" type="checkbox" <?php echo _fv('form_object', 'set_activated', 'CHECKBOX'); ?>/>
        </div>
      </div>

      <?php if(false): ?>
      <div class="form-group <?php _has_error('set_private'); ?>">
        <label for="input_set_private" class="col-sm-2 control-label"><?php echo __('Set private','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="set_private" value="1" type="checkbox" <?php echo _fv('form_object', 'set_private', 'CHECKBOX'); ?>/>
        </div>
      </div>
      <?php endif; ?>

      <div class="form-group <?php _has_error('user_type'); ?> IS-INPUTBOX">
        <label for="input_user_type" class="col-sm-2 control-label"><?php echo __('User type','sw_win'); ?></label>
        <div class="col-sm-10">
            <?php echo form_dropdown('user_type', config_item('account_types'), _fv('form_object', 'user_type'), 'class="form-control"')?>
        </div>
      </div>

      <div class="form-group <?php _has_error('is_default'); ?>">
        <label for="input_is_default" class="col-sm-2 control-label"><?php echo __('Is default','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_default" value="1" type="checkbox" <?php echo _fv('form_object', 'is_default', 'CHECKBOX'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('subscription_price'); ?> IS-INPUTBOX">
        <label for="input_subscription_price" class="col-sm-2 control-label"><?php echo __('Price','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="subscription_price" value="<?php echo _fv('form_object', 'subscription_price'); ?>" type="text" id="input_subscription_price" class="form-control" placeholder="<?php echo __('Price','sw_win'); ?>"/>
        </div>
      </div>
    
      <div class="form-group <?php _has_error('currency_code'); ?> IS-INPUTBOX">
        <label for="input_currency_code" class="col-sm-2 control-label"><?php echo __('Currency code','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="currency_code" value="<?php echo sw_settings('default_currency'); ?>" type="text" id="input_currency_code" class="form-control" placeholder="<?php echo __('Currency code','sw_win'); ?>" readonly/>
        </div>
      </div>

      <div class="form-group <?php _has_error('woo_item_id'); ?> IS-INPUTBOX">
        <label for="input_woo_item_id" class="col-sm-2 control-label"><?php echo __('Woo Subscrptio Item','sw_win'); ?></label>
        <div class="col-sm-10">
            <?php echo form_dropdown('woo_item_id', $woo_items, _fv('form_object', 'woo_item_id'), 'class="form-control"')?>
        </div>
      </div>
      
      </div>
      </div>
        
      <hr />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>


</div>


<style>



</style>

