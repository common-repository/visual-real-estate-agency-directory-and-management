
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit currency','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('Add currency','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Currency data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
      <div class="form-group <?php _has_error('currency_code'); ?> IS-INPUTBOX">
        <label for="input_currency_code" class="col-sm-2 control-label"><?php echo __('Currency code','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="currency_code" value="<?php echo _fv('form_object', 'currency_code'); ?>" type="text" id="input_currency_code" class="form-control" placeholder="<?php echo __('Currency code','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('currency_symbol'); ?> IS-INPUTBOX">
        <label for="input_currency_symbol" class="col-sm-2 control-label"><?php echo __('Currency symbol','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="currency_symbol" value="<?php echo _fv('form_object', 'currency_symbol'); ?>" type="text" id="input_currency_symbol" class="form-control" placeholder="<?php echo __('Currency symbol','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('rate_index'); ?> IS-INPUTBOX">
        <label for="input_rate_index" class="col-sm-2 control-label"><?php echo __('Rate index','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="rate_index" value="<?php echo _fv('form_object', 'rate_index'); ?>" type="text" id="input_rate_index" class="form-control" placeholder="<?php echo __('Rate index','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_activated'); ?>">
        <label for="input_is_activated" class="col-sm-2 control-label"><?php echo __('Is activated','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_activated" value="1" type="checkbox" <?php echo _fv('form_object', 'is_activated', 'CHECKBOX'); ?>/>
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

