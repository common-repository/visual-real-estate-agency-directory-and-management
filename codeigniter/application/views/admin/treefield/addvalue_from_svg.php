
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit treefield value','sw_win'); ?> <a href="<?php echo admin_url("admin.php?page=$wp_page&function=addvalue&field_id="._fv('form_object', 'field_id')); ?>" class="page-title-action"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo __('Add New','sw_win')?></a></h1>
<?php else: ?>
<h1><?php echo __('Add treefield value','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Generate map','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
    <?php if(!empty($errors_svg)) foreach ($errors_svg as $key => $value): ?>
      <?php _che($value);?>
    <?php endforeach;?>
    <?php if(!empty($error)):?>
        <p class="alert alert-danger alert-dismissible"> <?php echo $error; ?> </p>
    <?php endif;?>
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
           <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo __('Map','sw_win'); ?></label>
            <div class="col-lg-10">
              <?php echo form_dropdown('geo_map', $geo_map_prepared, $this->input->post('geo_map'), 'class="form-control" id="inputgeo_map"')?>
            </div>
          </div>

          <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo __('Current map will be replaced with new on','sw_win')?></label>
            <div class="col-lg-10">
            <?php echo form_checkbox('accept_generate', 1, false, '')?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label"><?php echo __('Random locations for existing listings','sw_win')?></label>
            <div class="col-lg-10">
            <?php echo form_checkbox('random_locations', 1, false, '')?>
            </div>
          </div>

          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?php echo __('Current map with all related categories will be removed/replaced with new map','sw_win'); ?>
            </div>
          </div>
        <div class="preview-svg">
            <img src="<?php echo plugins_url( SW_WIN_SLUG.'_Geomap/svg_maps' );?>/ad.svg" style="max-height: 350px;display: block;margin-left: 150px;margin-bottom: 25px; margin-top: 25px;">
        </div>
          <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
              <?php echo form_submit('submit', __('Generate','sw_win'), 'class="btn btn-primary" onclick="return confirm(\' All values will be removed, are you sure?\')"')?>
              <a href="" class="btn btn-default" type="button"><?php echo __('Cancel','sw_win')?></a>
            </div>
          </div>
    </form>
  </div>
</div>

</div>
    
<script>

jQuery(document).ready(function($){
    
    $('#inputgeo_map').on('change', function(){
        $('.preview-svg img').attr('src', '<?php echo plugins_url( SW_WIN_SLUG.'_Geomap/svg_maps' );?>/'+$(this).val())
        
    })
    
    
})

</script>
