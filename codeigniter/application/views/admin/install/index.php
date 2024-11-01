
<h1><?php echo __('Listing install','sw_win'); ?></h1>

<div class="bootstrap-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Install data','sw_win'); ?></h3>
        </div>
        <div class="panel-body">
            <div class="box rte">
                <div class="col-xs-12 col-sm-12">

                    <div class="col-xs-12 col-sm-12">
                    
                        <?php if(!sw_win_table_exists('sw_invoice')): ?>
                        <div class="alert alert-info" role="alert"><?php echo __('Plugin tables not installed, please install','sw_win'); ?></div>
                        <?php elseif(sw_win_classified_version() > sw_win_classified_version_db()): ?>
                        <div class="alert alert-info" role="alert"><?php echo __('Plugin tables not updated, please update','sw_win'); ?></div>
                        <?php else: ?>
                        <div class="alert alert-success alert-dismissible"><?php echo __('Plugin is ready to use', 'sw_win'); ?> <a href="<?php echo home_url( '/' ); ?>"><?php echo __('Open website here', 'sw_win'); ?></a></div>
                        <?php endif; ?>

                        <?php echo $install_log; ?>
                        
                        <?php if (!empty($pre_requirements)): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $pre_requirements; ?></div>
                        <?php endif; ?>
                    </div>
                            
                    <br style="clear: both;" />
                    <?php if (empty($pre_requirements)): ?>
                    
                    <?php 
                    $theme = sw_get_compatible_theme();
                    $plugins = sw_get_compatible_plugins();

                    if( (  (!empty($theme) && !isset($_GET['skiptheme'])) ||  (sw_count($plugins) > 0 && !isset($_GET['skiptheme']))  ) && sw_classified_installed() == FALSE)
                    {
                        echo build_admin_form('install_m', 'form_theme', __('Continue to next step','sw_win'));
                    }
                    else
                    {
                        if(ini_get('max_execution_time')<300){
                            echo '<div class="col-xs-12 col-sm-12">';
                                echo '<div class="alert alert-danger" role="alert">'. __("Minimum execution time should be 300 seconds","sw_win").' <a href="http://php.net/manual/en/info.configuration.php#ini.max-execution-time">'. __("Open php.net", "sw_win").'</div>';
                            echo '</div>';
                        } else {
                            echo build_admin_form('install_m', 'form_index', __('Install','sw_win'), 
                            __('Plugin installed/updated successfully', 'sw_win').' <a href="'.home_url( '/' ).'">'.__('Open website here', 'sw_win').'</a>');
                        }
                        
                    }

                    ?>
                    <?php endif; ?>
                </div>
                
                <br style="clear: both;" />
            </div>
        </div>
    </div>
</div>

<style>
  #map {
    height: 300px;
    width:100%;
    background: gray;
  }
  

</style>

<script>
jQuery(document).ready(function($) {
    $('.box.rte form button[type="submit"]').on('click', function(){
        var self = $(this);
        setTimeout(function(){
            self.delay(2000).attr("disabled", true)
        },0)
    })
    
})
</script>


