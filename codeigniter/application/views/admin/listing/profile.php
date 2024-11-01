
<h1><?php echo __('Profile additional details','sw_win'); ?></h1>

<div class="bootstrap-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
        <h3 class="panel-title"><?php echo __('Profile additional data','sw_win'); ?></h3>
        </div>
        <div class="panel-body">
            <div class="box rte">
                <div class="col-xs-12 col-sm-6">
                    <?php echo build_admin_form('profile_m', 'form_index'); ?>
                </div>
                
                <div class="col-xs-12 col-sm-6">
                    <div id="map"></div>

                    <?php if(sw_count($agents) > 0): ?>
                    <br style="clear: both;" />

                    <div class="panel panel-default agent_related">
                        <div class="panel-heading"><?php echo __('Agents related','sw_win'); ?></div>
                        <div class="panel-body">
                            <?php foreach($agents as $agent): ?>

                            <div class="checkbox">
                            <label>
                                <?php echo form_checkbox('agent_'.$agent->user_id, '1', $agent->is_agency_verified, 'class=""'); ?>
                                <?php $user_info = get_userdata($agent->user_id); echo $user_info->display_name.', '.$user_info->user_email;?>
                            </label>
                            </div>

                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert"><?php echo __('Above agents request connection, you can verify it','sw_win'); ?></div>

                    <?php endif; ?>
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
jQuery(document).ready(function($) {
        $('.agent_related input').change(function(){

            // AJAX request to save changes on related agents
            
            var data_agents = {
                "page": 'listing_agentssave',
                "action": 'ci_action',
                "user_id": '<?php _che($CI->data['form_object']->user_id, ''); ?>'
            }

            // Add agents to post

            $( ".agent_related input:checked" ).each(function( index ) {
                data_agents[$(this).attr('name')] = $(this).val()
            });

            <?php if(config_item('app_type') != 'demo'):?>
            $.post(ajaxurl, data_agents, function( data ) {
                if(data.success)
                {
                    ShowStatus.show('<?php echo_js(__('Agents saved', 'sw_win')); ?>');
                }
                else
                {
                    ShowStatus.show('<?php echo_js(__('Error with request', 'sw_win')); ?>');
                }
            });
            <?php else: ?>
                ShowStatus.show('<?php echo_js(__('Disabled in demo', 'sw_win')); ?>');
            <?php endif;?>

        });

    });

</script>

<style>
  #map {
    height: 300px;
    width:100%;
    background: gray;
  }
  

</style>

