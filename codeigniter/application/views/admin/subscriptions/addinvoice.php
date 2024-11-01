
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit invoice','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('Add invoice','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Invoice data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
      <div class="form-group <?php _has_error('invoicenum'); ?> IS-INPUTBOX">
        <label for="input_invoicenum" class="col-sm-2 control-label"><?php echo __('Num','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="invoicenum" value="<?php echo _fv('form_object', 'invoicenum'); ?>" readonly="" type="text" id="input_invoicenum" class="form-control" placeholder="<?php echo __('Num','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_created'); ?> IS-INPUTBOX">
        <label for="input_date_created" class="col-sm-2 control-label"><?php echo __('Date created','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_created" value="<?php echo _fv('form_object', 'date_created'); ?>" readonly="" type="text" id="input_date_created" class="form-control" placeholder="<?php echo __('Date created','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_paid'); ?> IS-INPUTBOX">
        <label for="input_date_paid" class="col-sm-2 control-label"><?php echo __('Date paid','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_paid" value="<?php echo _fv('form_object', 'date_paid'); ?>" type="text" id="input_date_paid" class="form-control datetimepicker_1" placeholder="<?php echo __('Date paid','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('user_id'); ?> IS-INPUTBOX">
        <label for="input_user_id" class="col-sm-2 control-label"><?php echo __('User','sw_win'); ?></label>
        <div class="col-sm-10">
            <?php echo form_dropdown('user_id', $users, _fv('form_object', 'user_id'), 'class="form-control"')?>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('listing_id'); ?> IS-INPUTBOX">
        <label for="input_listing_id" class="col-sm-2 control-label"><?php echo __('Listing','sw_win'); ?></label>
        <div class="col-sm-10">
            <?php echo form_dropdown('listing_id', $listings, _fv('form_object', 'listing_id'), 'class="form-control"')?>
        </div>
      </div>

      <div class="form-group <?php _has_error('subscription_id'); ?> IS-INPUTBOX">
        <label for="input_subscription_id" class="col-sm-2 control-label"><?php echo __('Subscription','sw_win'); ?></label>
        <div class="col-sm-10">
            <?php echo form_dropdown('subscription_id', $subscriptions, _fv('form_object', 'subscription_id'), 'class="form-control"')?>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_activated'); ?>">
        <label for="input_is_activated" class="col-sm-2 control-label"><?php echo __('Is activated','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_activated" value="1" type="checkbox" <?php echo _fv('form_object', 'is_activated', 'CHECKBOX'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_disabled'); ?>">
        <label for="input_is_disabled" class="col-sm-2 control-label"><?php echo __('Is disabled','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_disabled" value="1" type="checkbox" <?php echo _fv('form_object', 'is_disabled', 'CHECKBOX'); ?>/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('vat_percentage'); ?> IS-INPUTBOX">
        <label for="input_vat_percentage" class="col-sm-2 control-label"><?php echo __('VAT percentage','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="vat_percentage" value="<?php echo _fv('form_object', 'vat_percentage'); ?>" type="text" id="input_vat_percentage" class="form-control" placeholder="<?php echo __('VAT percentage','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('price'); ?> IS-INPUTBOX">
        <label for="input_price" class="col-sm-2 control-label"><?php echo __('Price','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="price" value="<?php echo _fv('form_object', 'price'); ?>" type="text" id="input_price" class="form-control" placeholder="<?php echo __('Price','sw_win'); ?>"/>
        </div>
      </div>
    
      <div class="form-group <?php _has_error('currency_code'); ?> IS-INPUTBOX">
        <label for="input_currency_code" class="col-sm-2 control-label"><?php echo __('Currency code','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="currency_code" value="<?php echo sw_settings('default_currency'); ?>" type="text" id="input_currency_code" class="form-control" placeholder="<?php echo __('Currency code','sw_win'); ?>" readonly/>
        </div>
      </div>

      <div class="form-group <?php _has_error('paid_via'); ?> IS-INPUTBOX">
        <label for="input_paid_via" class="col-sm-2 control-label"><?php echo __('Paid via','sw_win'); ?></label>
        <div class="col-sm-10">
            <?php echo form_dropdown('paid_via', $this->invoice_m->paid_via, _fv('form_object', 'paid_via'), 'class="form-control"')?>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('company_details'); ?>">
        <label for="input_company_details" class="col-sm-2 control-label"><?php echo __('Company details','sw_win'); ?></label>
        <div class="col-sm-10">
            <textarea name="company_details" id="input_company_details" class="form-control" placeholder="<?php echo __('Company details','sw_win'); ?>"><?php echo _fv('form_object', 'company_details'); ?></textarea>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('note'); ?>">
        <label for="input_note" class="col-sm-2 control-label"><?php echo __('Note','sw_win'); ?></label>
        <div class="col-sm-10">
            <textarea name="note" id="input_note" class="form-control" placeholder="<?php echo __('Note','sw_win'); ?>"><?php echo _fv('form_object', 'note'); ?></textarea>
        </div>
      </div>
      
      <?php $json_data = json_decode(_fv('form_object', 'data_json')); ?> 
      
      <?php if(isset($json_data->item->package_name)): ?>
      
      <div class="form-group ">
        <label for="input_note" class="col-sm-2 control-label"><?php echo __('Package','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo $json_data->item->package_name; ?></p>
        </div>
      </div>
      
      <?php endif; ?>
      
      </div>
      </div>
        
      <hr />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
    
    <div>
        <label><?php echo __('Transaction details','sw_win'); ?></label>
        <?php dump_basic(json_decode(_fv('form_object', 'data_json'))); ?>    
    </div>

  </div>
</div>


</div>

<?php

wp_enqueue_script( 'jquery' );
wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-widget', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, false);

wp_enqueue_script( 'datetime-picker-moment' );
wp_enqueue_script( 'datetime-picker-bootstrap' );

wp_enqueue_style( 'datetime-picker-css');

?>

<script>


jQuery(document).ready(function($) {
    $('.datetimepicker_1').datetimepicker({format:'YYYY-MM-DD HH:mm:ss', useCurrent:false});
});

</script>


<style>

p.input-content
{
    padding:5px 5px 0px 5px;
    margin:0px;
}

</style>

