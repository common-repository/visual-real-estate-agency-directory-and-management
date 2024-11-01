
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('View reservation','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('View reservation','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Resevation data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php 
    
        $CI =& get_instance();
    
    ?>


    <?php 

      $message = '';
      
      if(_fv('form_object', 'is_confirmed', 'TEXT', '', '')==0)
      {
          $message .= ' <div class="alert alert-info">'.__("Waiting for confirmation by agent/owner", "sw_win").'</div>';
      }
      elseif(_fv('form_object', 'is_payment_informed', 'TEXT', '', '') == 0)
      {
        $message .= ' <div class="alert alert-danger">'.__("Waiting for payment (please pay by instructions provided below)", "sw_win").'</div>';
      }
      elseif(_fv('form_object', 'is_payment_completed', 'TEXT', '', '') == 0)
      {
        $message .= ' <div class="alert alert-warning">'.__("Waiting for payment confirmation by agent/owner", "sw_win").'</div>';
      }
      elseif(_fv('form_object', 'is_payment_completed', 'TEXT', '', '') == 1)
      {
        $message .= ' <div class="alert alert-success">'.__("Confirmed reservation", "sw_win").'</div>';
      }
      
      echo $message;
      
      ?>   
    
  
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
      <div class="form-group <?php _has_error('idreservation'); ?> IS-INPUTBOX">
        <label for="input_idreservation" class="col-sm-2 control-label"><?php echo __('Reservation ID','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'idreservation'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_from'); ?> IS-INPUTBOX">
        <label for="input_date_from" class="col-sm-2 control-label"><?php echo __('Date from','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'date_from'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_to'); ?> IS-INPUTBOX">
      <label for="input_date_to" class="col-sm-2 control-label"><?php echo __('Date to','sw_win'); ?></label>
      <div class="col-sm-10">
        <p class="input-content"><?php echo _fv('form_object', 'date_to'); ?></p>
      </div>
    </div>
      
      <div class="form-group <?php _has_error('display_name'); ?> IS-INPUTBOX">
        <label for="input_display_name" class="col-sm-2 control-label"><?php echo __('User','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _fv('form_object', 'display_name'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('listing_id'); ?> IS-INPUTBOX">
        <label for="input_listing_id" class="col-sm-2 control-label"><?php echo __('Listing','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content">#<?php echo _fv('form_object', 'listing_id'); ?>, <?php echo _fv('form_object', 'field_10'); ?></p>
        </div>
      </div>

      <div class="form-group <?php _has_error('guests_number'); ?> IS-INPUTBOX">
        <label for="input_guests_number" class="col-sm-2 control-label"><?php echo __('Guests number','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _fv('form_object', 'guests_number', 'TEXT', '-', '-'); ?></p>
        </div>
      </div>

      <div class="form-group <?php _has_error('price'); ?> IS-INPUTBOX">
        <label for="input_price" class="col-sm-2 control-label"><?php echo __('Price','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'price'); ?></p>
        </div>
      </div>
  
      <div class="form-group <?php _has_error('total_paid'); ?> IS-INPUTBOX">
      <label for="input_total_paid" class="col-sm-2 control-label"><?php echo __('Total paid (money received)','sw_win'); ?></label>
      <div class="col-sm-10">
        <p class="input-content"><?php echo _fv('form_object', 'total_paid'); ?></p>
      </div>
    </div>

      <div class="form-group <?php _has_error('currency_code'); ?> IS-INPUTBOX">
        <label for="input_currency_code" class="col-sm-2 control-label"><?php echo __('Currency code','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo sw_settings('default_currency'); ?></p>
        </div>
      </div>

      <div class="form-group <?php _has_error('date_paid_advance'); ?> IS-INPUTBOX">
      <label for="input_date_paid_advance" class="col-sm-2 control-label"><?php echo __('Payment date for Advance','sw_win'); ?></label>
      <div class="col-sm-10">
        <p class="input-content"><?php echo _fv('form_object', 'date_paid_advance'); ?></p>
      </div>
    </div>

    <div class="form-group <?php _has_error('date_paid_total'); ?> IS-INPUTBOX">
      <label for="input_date_paid_total" class="col-sm-2 control-label"><?php echo __('Payment date for Total','sw_win'); ?></label>
      <div class="col-sm-10">
        <p class="input-content"><?php echo _fv('form_object', 'date_paid_total'); ?></p>
      </div>
    </div>

    <div class="form-group <?php _has_error('is_confirmed'); ?>">
        <label for="input_is_confirmed" class="col-sm-2 control-label"><?php echo __('Confirm availability (Owner/agent must confirm before payment)','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'is_confirmed', 'TEXT', '', '')==''?
                                                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>':
                                                '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; ?></p>
        </div>
      </div>

      <div class="form-group <?php _has_error('is_payment_informed'); ?>">
        <label for="input_is_payment_informed" class="col-sm-2 control-label"><?php echo __('Is Payment informed (by client)','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'is_payment_informed', 'TEXT', '', '')==''?
                                                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>':
                                                '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; ?></p>
        </div>
      </div>

      <div class="form-group <?php _has_error('is_payment_completed'); ?>">
        <label for="input_is_payment_completed" class="col-sm-2 control-label"><?php echo __('Is Payment completed (checked by owner/agent)','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'is_payment_completed', 'TEXT', '', '')==''?
                                                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>':
                                                '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; ?></p>
        </div>
      </div>

      <?php if(_fv('form_object', 'is_confirmed', 'TEXT', '', '') == 1): ?>
      <div class="form-group <?php _has_error('payment_details'); ?>">
        <label for="input_payment_details" class="col-sm-2 control-label"><?php echo __('Payment instruction details','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _fv('form_object', 'payment_details', 'TEXT', '-', '-'); ?></p>
        </div>
      </div>
    <?php endif; ?>

      
      </div>
      </div>
        
      <hr />
      
      <?php if(_fv('form_object', 'is_payment_informed', 'TEXT', '', '') =='' &&
               _fv('form_object', 'is_confirmed', 'TEXT', '', '') == 1): ?>
      <div class="form-group ">
        <label for="input_note" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <!-- Single button -->
            
              <a href="<?php echo admin_url("admin.php?page=owncalendars_myreservations&function=confirmpayment&id="._fv('form_object', 'idreservation')); ?>" class="btn btn-info">
                <?php echo __('Payment completed (Click here after payment transfer to inform owner/agent)','sw_win'); ?>
              </a>

        </div>
      </div>
    <?php endif; ?>

    </form>

  </div>
</div>


</div>

<?php



?>

<script>


jQuery(document).ready(function($) {

});

</script>


<style>

p.input-content
{
    padding:5px 5px 0px 5px;
    margin:0px;
}


</style>

