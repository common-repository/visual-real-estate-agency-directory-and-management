
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('View subscription','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('View subscription','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Subscription data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php 
    
        $CI =& get_instance();
    
    ?>
    
    <?php if(isset($_GET['paid'])): ?>
    <div class="alert alert-success" role="alert"><?php echo __('Thank you very much on payment!','sw_win').' '.__('Admin will check bank account and activate related services','sw_win'); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_GET['listingsaved'])): ?>
    <div class="alert alert-success" role="alert"><?php echo __('Thank you on submission, please pay for subscription.','sw_win'); ?></div>
    <?php endif; ?>
    
  
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    

      
      <div class="form-group <?php _has_error('subscription_name'); ?> IS-INPUTBOX">
      <label for="input_subscription_name" class="col-sm-2 control-label"><?php echo __('Subscription name','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'subscription_name'); ?></p>
      </div>
    </div>
    
    <div class="form-group <?php _has_error('days_limit'); ?> IS-INPUTBOX">
      <label for="input_days_limit" class="col-sm-2 control-label"><?php echo __('Days limit','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'days_limit'); ?></p>
      </div>
    </div>

    <div class="form-group <?php _has_error('listing_limit'); ?> IS-INPUTBOX">
      <label for="input_listing_limit" class="col-sm-2 control-label"><?php echo __('Listings limit','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'listing_limit'); ?></p>
      </div>
    </div>
    
    <?php if(false): ?>
    <div class="form-group <?php _has_error('featured_limit'); ?> IS-INPUTBOX">
      <label for="input_featured_limit" class="col-sm-2 control-label"><?php echo __('Featured limit','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'featured_limit'); ?></p>
      </div>
    </div>
    <?php endif; ?>

    <div class="form-group <?php _has_error('set_activated'); ?>">
      <label for="input_set_activated" class="col-sm-2 control-label"><?php echo __('Set activated','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'set_activated', 'TEXT', '', '')==''?
                                                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>':
                                                '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; ?></p>
      </div>
    </div>

    <?php if(false): ?>
    <div class="form-group <?php _has_error('set_private'); ?>">
      <label for="input_set_private" class="col-sm-2 control-label"><?php echo __('Set private','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'set_private', 'TEXT', '', '')==''?
                                                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>':
                                                '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; ?></p>
      </div>
    </div>
    <?php endif; ?>

    <div class="form-group <?php _has_error('user_type'); ?> IS-INPUTBOX">
      <label for="input_user_type" class="col-sm-2 control-label"><?php echo __('User type','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'user_type', 'TEXT', 'Any', 'Any'); ?></p>
      </div>
    </div>
    
    <div class="form-group <?php _has_error('subscription_price'); ?> IS-INPUTBOX">
      <label for="input_subscription_price" class="col-sm-2 control-label"><?php echo __('Price','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo _fv('form_object', 'subscription_price'); ?></p>
      </div>
    </div>
  
    <div class="form-group <?php _has_error('currency_code'); ?> IS-INPUTBOX">
      <label for="input_currency_code" class="col-sm-2 control-label"><?php echo __('Currency code','sw_win'); ?></label>
      <div class="col-sm-10">
      <p class="input-content"><?php echo sw_settings('default_currency'); ?></p>
      </div>
    </div>

<?php

$user_package_id = NULL;
$user_package_expire = NULL;
$user_package_details = NULL;
$is_free_package_user = false;
if(sw_is_logged_user())
{
    $user = wp_get_current_user();
    $user_package_id = profile_data($user, 'package_id');
    $user_package_expire = profile_data($user, 'package_expire');

    $user_package_details = $this->subscriptions_m->get($user_package_id);
    if(is_object($user_package_details) && $user_package_details->subscription_price == 0)
    {
      $is_free_package_user = true;
    }
}

$days_expire = intval((strtotime($user_package_expire)-time())/86400);

?>

<?php if($user_package_id == _fv('form_object', 'idsubscriptions') && $is_free_package_user): ?>

<div class="form-group IS-INPUTBOX">
    <label for="input_currency_code" class="col-sm-2 control-label"> </label>
    <div class="col-sm-10">
    
      <div class="alert alert-info" role="alert">
        <?php echo __('This is your current free subscription, feel free to purchase another to get more features.','sw_win'); ?>
      </div>

    </div>
  </div>

<?php elseif($user_package_id == _fv('form_object', 'idsubscriptions')): ?>
    <div class="form-group IS-INPUTBOX">
      <label for="input_currency_code" class="col-sm-2 control-label"> </label>
      <div class="col-sm-10">
      
        <div class="alert alert-info" role="alert">
          <?php echo __('This is your current subscription.','sw_win'); ?>
          <?php if($days_expire >= 0): ?>
          <?php echo __('Expire in','sw_win').' '.$days_expire.' '.__('days','sw_win').', '.__('you can pay for extend','sw_win'); ?>
          <?php endif; ?>
        </div>

      </div>
    </div>

<?php if($days_expire < 0): ?>
    <div class="form-group IS-INPUTBOX">
      <label for="input_currency_code" class="col-sm-2 control-label"> </label>
      <div class="col-sm-10">
      
        <div class="alert alert-danger" role="alert">
          <?php echo __('Your subscription expired and need to be extended or changed, you can pay for extend','sw_win'); ?>
        </div>

      </div>
    </div>
<?php endif; ?>

<?php endif; ?>



      </div>
      </div>
        
      <hr />

<?php if(_fv('form_object', 'is_default') && false): ?>

<div class="form-group IS-INPUTBOX">
<label for="input_currency_code" class="col-sm-2 control-label"> </label>
<div class="col-sm-10">

  <div class="alert alert-warning" role="alert">
    <?php echo __('This is default subscription package assigned automatically for new user or if other package expire','sw_win'); ?>
  </div>

</div>

</div>

<?php elseif(strtotime($user_package_expire) > time() && $user_package_id != _fv('form_object', 'idsubscriptions') && !$is_free_package_user): ?>

<div class="form-group IS-INPUTBOX">
<label for="input_currency_code" class="col-sm-2 control-label"> </label>
<div class="col-sm-10">

  <div class="alert alert-warning" role="alert">
    <?php echo __('You are subscribed for other subscription package, because of that you are not able to purchase this one until current expire','sw_win'); ?>
    <a href="<?php menu_page_url( 'ownlisting_subscriptions', true ); ?>&function=viewsubscription&id=<?php echo $user_package_id; ?>"><?php echo __('Link to current package','sw_win'); ?></a>
  </div>

</div>

</div>
<?php elseif(_fv('form_object', 'subscription_price') > 0): ?>

<?php if(_fv('form_object', 'woo_item_id') > 0): ?>

<?php

$cart_url = apply_filters( 'woocommerce_get_cart_url', wc_get_page_permalink( 'cart' ) );
$woo_direct_url = $cart_url.'?add-to-cart='._fv('form_object', 'woo_item_id');

?>

<div class="form-group ">
  <label for="input_note" class="col-sm-2 control-label"></label>
  <div class="col-sm-10">
      <!-- Single button -->
        <a href="<?php echo $woo_direct_url; ?>" class="btn btn-info">
          <?php echo __('Purchase now via WooCommerce','sw_win'); ?>
        </a>
  </div>
</div>

<?php else: ?>

<div class="form-group ">
  <label for="input_note" class="col-sm-2 control-label"></label>
  <div class="col-sm-10">
      <!-- Single button -->
        <a href="<?php echo admin_url("admin.php?page=ownlisting_subscriptions&function=purchasesubs&id=".$_GET['id']);?>" class="btn btn-info">
          <?php echo __('Purchase now','sw_win'); ?>
        </a>
  </div>
</div>

<?php endif; ?>

<?php endif; ?>

    </form>

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

