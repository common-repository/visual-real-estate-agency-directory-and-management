
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit calendar','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('Add calendar','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Calendar data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php echo build_admin_form('calendar_m', 'form_admin', NULL, NULL); ?>
  </div>
</div>


</div>


<style>



</style>

