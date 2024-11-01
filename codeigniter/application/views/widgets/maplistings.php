
<div id="sw_map_results" num_listings="<?php echo $num_listings; ?>" ></div>

<?php
    
    $CI =& get_instance();
    
    $lat = $lng = 0;

    if($lat == 0)
    {
        $lat = config_item('lat');
        $lng = config_item('lng');
    }
    
    
if(!isset($zoom_index))
    $zoom_index = 10;

?>

<?php if(!sw_settings('open_street_map_enabled')):?>
<script src="<?php echo plugins_url(SW_WIN_SLUG.'/assets/js/markerclusterer.js', SW_WIN_PLUGIN_PATH); ?>"></script>
<?php endif;?>

<script>

<?php if(sw_settings('open_street_map_enabled')):

$custom_js ="
    var geocoder;
    var map;
    var markers = [];
    var clustererOptions;
    var infowindow;
    var markerCluster; 
    
    var clusters ='';
    var jpopup_customOptions =
    {
    'maxWidth': 'initial',
    'width': 'initial',
    'className' : 'popupCustom'
    }
    
    jQuery(document).ready(function($) {
        if(clusters=='')
            clusters = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
        
        map = L.map('sw_map_results', {
            center: [". esc_html($lat).",".esc_html($lng)."],
            zoom: ".$zoom_index.",
            scrollWheelZoom: false,
            dragging: !L.Browser.mobile,
            tap: !L.Browser.mobile
        });     
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
        }).addTo(map);

        var positron = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png').addTo(map);
  ";  


        foreach($listings as $key=>$listing): 
        if(!is_numeric($listing->lat))continue;
        $custom_js .="
        var image = null;
        ";

        $pin_icon = "'".plugins_url( SW_WIN_SLUG.'/assets/img/markers/empty.png')."'";

        // check for version with field_id = 14
        if(file_exists(SW_WIN_PLUGIN_PATH.'assets/img/markers/'._field($listing, 14).'.png'))
        {
            $pin_icon = "'".plugins_url(SW_WIN_SLUG.'/assets/img/markers/'._field($listing, 14).'.png')."'";
        }

        // check for version with category related marker
        $category = get_listing_category($listing);

        if(isset($category->marker_icon_id))
        {
            $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
            if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
            {
                $pin_icon = "'".$img[0]."'";
            }
        }

        $custom_js .="
        image = ".($pin_icon).";
            

        var marker = L.marker(
            [".esc_html($listing->lat).", ".esc_html($listing->lng)."],
            {icon: L.divIcon({
                    html: '<span><img src=\"'+image+'\"></span>',
                    className: 'open_steet_map_marker google_marker',
                    iconSize: [40, 40],
                    popupAnchor: [-4, -35],
                    iconAnchor: [20, 42],
                })
            }
        );

        marker.bindPopup('"._js(_infowindow_content($listing))."', jpopup_customOptions);

        clusters.addLayer(marker);
        markers[".esc_html($listing->idlisting)."] = marker;

    ";
endforeach;
    
$custom_js .=" map.addLayer(clusters);";

            if(!sw_settings('map_fixed_position')):
            $custom_js .=" 
                /* set center */
                if(markers.length){
                    var limits_center = [];
                    for (var i in markers) {
                        var latLngs = [ markers[i].getLatLng() ];
                        limits_center.push(latLngs)
                    };
                    var bounds = L.latLngBounds(limits_center);
                    map.fitBounds(bounds);
                }
            ";
            endif;
            
            if(sw_settings('auto_set_zoom_disabled')):
            $custom_js .="setTimeout(function(){
                    if($('#sw_map_results').attr('data-zoom_index'))
                       map.setZoom($('#sw_map_results').attr('data-zoom_index'));
                }, 1000);";
            endif;
            
$custom_js .="
            /* end set center */
";

$custom_js .=" })";

echo $custom_js;

else: ?>

var geocoder;
var map;
var markers = [];
var clustererOptions;
var infowindow;
var markerCluster;

jQuery(document).ready(function($) {
    
    
    initMap();
    

});

    function autoCenter(){
        
        if(markers.length == 0)return;
        
        var limits = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            limits.extend(markers[i].position);
        };
        
        google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
            if(markers.length == 1)
            {
                map.setZoom(12);
            }
            else
            {
                map.setZoom(map.getZoom()-1);
            }
        });
        
        map.fitBounds(limits);

    }

    function deleteMarkers() {
        //Loop through all the markers and remove
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    };

function initMap() {

    clustererOptions = {
        imagePath: '<?php echo plugins_url( SW_WIN_SLUG.'/assets').'/img/clusters/m'; ?>'
    };
    
    var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
    
    geocoder = new google.maps.Geocoder();
    
    map = new google.maps.Map(document.getElementById('sw_map_results'), {
      zoom: 4,
      center: myLatlng
    });
    
    infowindow = new google.maps.InfoWindow({
        content: '<?php echo_js(__('Loading...', 'sw_win')); ?>'
    });
    
<?php 
        foreach($listings as $key=>$listing): 
        if(!is_numeric($listing->lat))continue;
?>
    
    var image = null;
    
    <?php
    
        $pin_icon = "'".plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/empty.png'."'";
        
        // check for version with field_id = 14
        if(file_exists(SW_WIN_PLUGIN_PATH.'assets/img/markers/'._field($listing, 14).'.png'))
        {
            $pin_icon = "'".plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/'._field($listing, 14).'.png'."'";
        }
        
        // check for version with category related marker
        $category = get_listing_category($listing);

        if(isset($category->marker_icon_id))
        {
            $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
            if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
            {
                $pin_icon = "'".$img[0]."'";
            }
        }

    ?>

    image = <?php echo $pin_icon; ?>;
    
    var marker = new google.maps.Marker({
      draggable: false,
      position: {lat: <?php echo $listing->lat; ?>, lng: <?php echo $listing->lng; ?>},
      map: map,
      title: '<?php echo_js($listing->address); ?>',
      icon: image
    });

    marker.addListener('click', function() {
         infowindow.setContent('<?php echo_js(_infowindow_content($listing)); ?>');
         infowindow.open(map, this);
    });
    
    markers.push(marker);

    

<?php endforeach; ?>

    markerCluster = new MarkerClusterer(map, markers, clustererOptions);

    autoCenter();
}

<?php endif; ?>
</script>

<style>

.gm-style-iw {
    height: 150px;
    width: 125px;
}

</style>














