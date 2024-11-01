
<form class="sw_search_primary">

<?php _search_form_primary(1); ?>


<div class="form-group col-sm-12" style="">
    <div class="button-wrapper-1">
        <button id="search-start-primary" type="submit" class="sw-search-start btn btn-primary btn-inversed btn-block">&nbsp;&nbsp;<?php echo __('Search', 'sw_win'); ?>&nbsp;<i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i>&nbsp;</button>
        
    </div><!-- /.select-wrapper -->
</div><!-- /.form-group -->
                               
</form>




<script>


jQuery(document).ready(function($) {
    
    $('.sw-search-start').click(function(){
        search_result(0, false, false, true);
        return false;
    });

    $('#search_where').typeahead({
        minLength: 2,
        source: function(query, process) {
            var data = { q: query, limit: 8 };
            
            $.extend( data, {
                "page": 'frontendajax_locationautocomplete',
                "action": 'ci_action'
            });
            
            $.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data, function(data) {
                //console.log(data); // data contains array
                process(data);
            });
        }
    });
    
    reloadElements();

    function reloadElements()
    {
        $('#results .view-type').click(function () { 
          $(this).parent().find('.view-type').removeClass("active");
          $(this).addClass("active");
          return false;
        });
        
        $('#results a.view-type:not(.active)').click(function(){
            search_result(0, false, false, false);
            return false;
        });
        
        $('#results #search_order').change(function(){
            search_result(0, false, false, true);
            return false;
        });
        
        $('#results .pagination a').click(function () { 
            
            var href = $(this).attr('href');
            
            var offset = getParameterByName('offset', href);
            
            search_result(offset, true, false, false);

            return false;
        });
        
    }

    function search_result(results_offset, scroll_enabled, save_only, load_map)
    {
        var selectorResults = '#results_top';
        
        // Order ASC/DESC
        var results_order = $('#results #search_order').val();
        
        if (results_order === undefined || results_order === null) {
            results_order = 'idlisting DESC';
        }
        
        // View List/Grid
        var results_view = $('.view-type.active').attr('ref');  
                
        if (results_view === undefined || results_view === null) {
            results_view = 'grid';
        }
        
        //Define default data values for search
        var data = {
            search_order: results_order,
            search_view: results_view,
            offset: results_offset
        };
        
        // Add custom data values, automatically by fields inside search-form
        $('form.sw_search_primary input, form.sw_search_primary select, '+
          'form.sw_search_secondary input, form.sw_search_secondary select').each(function (i) {
            
            if($(this).attr('type') == 'checkbox')
            {
                if ($(this).attr('checked'))
                {
                    data[$(this).attr('name')] = $(this).val();
                }
            }
            else if($(this).val() != '' && $(this).val() != 0&& $(this).val() != null)
            {
                data[$(this).attr('name')] = $(this).val();
            }
            
        });
        
        // scroll_enabled is used only on pagination, and then we don't need to refresh map results
        if(load_map &&  $('#sw_map_results').length>0)
        {
            data['map_num_listings'] = $('#sw_map_results').attr('num_listings');
        }
        
        //console.log(data);
        
        <?php
            $page_link = '';
            
            // get results page ID
            $results_page_id = sw_settings('results_page');
            if(!empty($results_page_id))
            {
                // get results page link
                $page_link = get_page_link($results_page_id);
            }
            
            if(sw_settings('enable_multiple_results_page') == 1 && strpos(get_page_template(), 'results') !== FALSE)
            {
                $page_link = get_page_link(get_the_ID());
            }
            
            
        ?>
        
        var gen_url = generateUrl("<?php echo $page_link; ?>", data)+"#header-search";
        
        <?php if(sw_is_page(sw_settings('results_page')) || 
                (sw_settings('enable_multiple_results_page') == 1 && strpos(get_page_template(), 'results') !== FALSE)): ?>
        
        $.extend( data, {
            "page": 'frontendajax_resultslisting',
            "action": 'ci_action'
        });
        
        $(".fa-ajax-indicator").show();
        $.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data,
        function(data){

            $(selectorResults).parent().parent().html(data.html);
            reloadElements();
            
            $(".fa-ajax-indicator").hide();
            if( scroll_enabled != false && !$(selectorResults).isInViewport() )
                $(document).scrollTop( $(selectorResults).offset().top );
            
            if ('history' in window && 'pushState' in history)
                history.pushState(null, null, gen_url);

            // populate map
            if(data.hasOwnProperty("listings_map") && typeof map !== 'undefined')
            {
                
            <?php if(sw_settings('open_street_map_enabled')):
        
                $custom_js ="
                    
                //Loop through all the markers and remove
                for (var i in markers) {
                    clusters.removeLayer(markers[i]);
                }
                markers = [];
 
                $.each( data.listings_map, function( key, obj ) {
                    
                    if(!sw_win_isNumeric(obj.lat))return;
                    
                    var marker = L.marker(
                        [parseFloat(obj.lat), parseFloat(obj.lng)],
                        {icon: L.divIcon({
                                html: '<span><img src=\"'+obj.pin_icon+'\"></span>',
                                className: 'open_steet_map_marker google_marker',
                                iconSize: [40, 40],
                                popupAnchor: [-4, -35],
                                iconAnchor: [20, 42],
                            })
                        }
                    );

                    marker.bindPopup(obj.infowindow, jpopup_customOptions); 
                      
                    clusters.addLayer(marker);
                    markers[obj.idlisting] = marker;

                });";
    
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
                echo $custom_js;
            else:?>
                
                
                
                markerCluster.clearMarkers();
                deleteMarkers();
                
                $.each( data.listings_map, function( key, obj ) {
                    
                    if(!sw_win_isNumeric(obj.lat))return;
                    
                    var marker = new google.maps.Marker({
                      draggable: false,
                      position: {lat: parseFloat(obj.lat), lng: parseFloat(obj.lng)},
                      map: map,
                      title: obj.address,
                      icon: obj.pin_icon
                    });
                
                    marker.addListener('click', function() {
                         infowindow.setContent(obj.infowindow);
                         infowindow.open(map, this);
                    });
                    
                    markers.push(marker);

                });

                markerCluster = new MarkerClusterer(map, markers, clustererOptions);
                
                autoCenter();
                
                <?php endif; ?>
            }
            
        }, "json");
        
        <?php else: ?>
        $(".fa-ajax-indicator").show();
        window.location = gen_url;
        <?php endif; ?>

    }
    
    $.fn.isInViewport = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
    
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
    
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };


});

function generateUrl(url, params) {
    var i = 0, key;
    for (key in params) {
        if (i === 0 && url.indexOf("?")===-1) {
            url += "?";
        } else {
            url += "&";
        }
        url += key;
        url += '=';
        url += params[key];
        i++;
    }
    return url;
}

function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function sw_win_isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}



</script>

<style>



</style>