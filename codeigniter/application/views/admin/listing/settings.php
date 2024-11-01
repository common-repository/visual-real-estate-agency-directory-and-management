
<h1><?php echo __('Listing settings','sw_win'); ?></h1>

<div class="bootstrap-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Settings data','sw_win'); ?></h3>
        </div>
        <div class="panel-body">
            <div class="box rte">
                <div class="col-xs-12 col-sm-6">

                <?php
                $url = site_url();
                $domain = parse_url($url)['host'];
                $string =  sw_settings('noreply');
                $prefix = substr($string, strrpos($string, '@')+1);
                if($prefix!=$domain):?>               
                <script> 
                jQuery(document).ready(function($){
                   $('#noreply').parent().append('<div class="input-error-msg" style="margin-top: 5px"><?php echo esc_html__('Domain different to server domain possible issues with sending email','sw_win');?></div>') ;
                })
                </script>
                <?php endif;?>
                    <?php
                    $custom_success_message ='';
                    if($this->input->get_post('sitemap_generated') == 'true') {
                        $custom_success_message=esc_html__("Sitemap generated","sw_win").' <a href="'.site_url('/sitemap_listings.xml').'" target="_blank">'.esc_html__("Open sitemap","sw_win").'</a>';
                    }
                    if($this->input->get_post('remove_all_cache_images') == 'true') {
                        $custom_success_message=esc_html__("Cache images removed","sw_win");
                    }
                    if($this->input->get_post('remove_all_listings') == 'true') {
                        $custom_success_message=esc_html__("All listings removed","sw_win");
                    }
                    if($this->input->get_post('copy_to_all_languages') == 'true') {
                        $custom_success_message= str_replace("+", ' ',$this->input->get_post('message'));
                    }
                    if($this->input->get_post('generated_all_translations') == 'true') {
                        $custom_success_message= str_replace("+", ' ',$this->input->get_post('message'));
                    }
                    ?>
                    
                    <?php echo build_admin_form('settings_m', 'form_index',NULL, $custom_success_message); ?>
                </div>
                
                <div class="col-xs-12 col-sm-6">
                    <div id="map"></div>
                    <br /><br />
                    <a href="//geniuscript.com/replugin/docs/documentation/#!/shortcodes" target="_blank"><?php echo __('Shortcodes and other documentation can be found here', 'sw_win'); ?></a>
                    <br /><br />
                    <a href="http://iwinter.com.hr/support/?p=17200" target="_blank"><?php echo __('Google Maps doesn\'t work? You must enter your own API key', 'sw_win'); ?></a>
                    <br /><br />
                    <div class="clearfix">
                        <a href="<?php echo admin_url("admin.php?page=listing_settings&function=remove_all_listings"); ?>" onclick="return confirm('<?php echo __('Are you sure?', 'sw_win');?>')" class="btn btn-default add_button pull-right" style="margin: 0 0 5px 5px;"><?php echo __('Remove all listings', 'sw_win'); ?></a>
                        <a href="<?php echo admin_url("admin.php?page=listing_settings&function=remove_all_cache_images"); ?>" onclick="return confirm('<?php echo __('Are you sure?', 'sw_win');?>')" class="btn btn-default add_button pull-right" style="margin: 0 0 5px 5px;"><?php echo __('Clear all cache images', 'sw_win'); ?></a>
                        <a href="<?php echo admin_url("admin.php?page=listing_settings&function=sitemap_generate"); ?>" class="btn btn-default add_button pull-right" style="margin: 0 0 5px 5px;"><?php echo __('Sitemap generate', 'sw_win'); ?></a>
                        <a href="<?php echo admin_url("admin.php?page=listing_settings&function=generated_all_translations"); ?>" class="btn btn-default add_button pull-right" style="margin: 0 0 5px 5px;"><?php echo __('Download all translation', 'sw_win'); ?></a>
                        <?php if(sw_count(sw_get_languages())>1):?>
                            <a href="<?php echo admin_url("admin.php?page=listing_settings&function=copy_to_all_languages"); ?>" onclick="return confirm('<?php echo __('Are you sure?', 'sw_win');?>')" class="btn btn-default add_button pull-right" style="margin: 0 0 5px 5px;"><?php echo __('Copy to all languages', 'sw_win'); ?></a>
                        <?php endif;?>
                    </div>
                </div>
                
                <br style="clear: both;" />
            </div>
        </div>
    </div>
</div>

<?php

if(sw_settings('open_street_map_enabled')) {
    wp_enqueue_script('leaflet-maps-api');
    wp_enqueue_script('leaflet-maps-api-cluster');
} else {
    wp_enqueue_script('google-maps-api-w');
}

?>

<?php
    $CI =& get_instance();
    
    $lat = $lng = 0;
    
    if(!empty($CI->data['form_object']->lat))
        $lat = $CI->data['form_object']->lat;
    
    if(!empty($CI->data['form_object']->lng))
        $lng = $CI->data['form_object']->lng;
?>
         
<script>


    
    var map
    
    <?php if(sw_settings('open_street_map_enabled')):?>
    jQuery(document).ready(function($) {    
        map = L.map('map', {
          center: [<?php echo $lat; ?>, <?php echo $lng; ?>],
          zoom: 4,
      });     
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(map);
    var map_marker = L.marker(
        [<?php echo $lat; ?>, <?php echo $lng; ?>],
        {draggable: true}
    ).addTo(map);

    map_marker.on('dragend', function(event){
        var marker = event.target;
        var location = marker.getLatLng();
        var lat = location.lat;
        var lon = location.lng;
        $('#lat').val(lat);
        $('#lng').val(lon);
        //retrieved the position
      });
    });  
    <?php else:?>
    function initMap() {

      var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};

      map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: myLatlng
      });

      var marker = new google.maps.Marker({
        draggable: true,
        position: myLatlng,
        map: map,
        title: '<?php echo_js(__('Your Location', 'sw_win')); ?>'
      });

      google.maps.event.addListener(marker, 'dragend', function(event) {
          document.getElementById("lat").value = event.latLng.lat();
          document.getElementById("lng").value = event.latLng.lng();
      });

      google.maps.event.addListener(map, 'click', function(event) {
          document.getElementById("lat").value = event.latLng.lat();
          document.getElementById("lng").value = event.latLng.lng();
          marker.setPosition(event.latLng);
      });

    }
  
    jQuery(document).ready(function($) {
        initMap();
        timerMap = setTimeout(function () {
            google.maps.event.trigger(map, 'resize');
            
            var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
            map.setCenter(myLatlng);
        }, 2000);
    });
    <?php endif;?>

</script>

<style>
  #map {
    height: 300px;
    width:100%;
    background: gray;
  }
  

</style>

