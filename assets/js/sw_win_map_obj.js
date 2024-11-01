/*
Item Name: Basic SwWin map
Author: sanljiljan
Author URI: http://codecanyon.net/user/sanljiljan
Version: 1.0
*/
jQuery.fn.Swmap = function (options) 
{
    var defaults = {
        map: null,
        markers: [],
        generic_icon: null,
        directionsDisplay: null,
        directionsService: null,
        placesService: null,
        default_index: null,
        //default_types: 'food,bar,cafe,restourant,store',
        obj: null,
        myCenter: null,
        styledEnabled: null,
        marker_url: null,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, 'map_style'],
        style: [
                {
                  stylers: [
                    { hue: "#00ffe6" },
                    { saturation: -20 }
                  ]
                },{
                  featureType: "road",
                  elementType: "geometry",
                  stylers: [
                    { lightness: 100 },
                    { visibility: "simplified" }
                  ]
                },{
                  featureType: "road",
                  elementType: "labels",
                  stylers: [
                    { visibility: "off" }
                  ]
                }
              ]
    };
    
    var options = jQuery.extend(defaults, options);
    
    
    /* Public API */
    this.getCurrent = function()
    {
        return options.obj;
    }
        
    return this.each (function () 
    {
        options.obj = jQuery(this);

        var lat = options.obj.find('.latitude').text();
        var lng = options.obj.find('.longitude').text();
        var zoom = parseInt(options.obj.find('.zoom').text()); 
        var width = options.obj.find('.width').text();
        var height = options.obj.find('.height').text();
        var styledMap = new google.maps.StyledMapType(options.style, {name: "Styled"});
        var address = options.obj.find('.address').text();
        
        if(address != '')
        {
            geocoder = new google.maps.Geocoder();
            //In this case it gets the address from an element on the page, but obviously you  could just pass it to the method instead
            geocoder.geocode({ 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    lat = results[0].geometry.location.lat();
                    lng = results[0].geometry.location.lng();
                    options.obj.find('.latitude').text(lat);
                    options.obj.find('.longitude').text(lng);
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
                init_start();
            });
        }
        else
        {
            init_start();
        }

        return this;
    });
    
    function init_start()
    {
        var lat = options.obj.find('.latitude').text();
        var lng = options.obj.find('.longitude').text();
        var zoom = parseInt(options.obj.find('.zoom').text()); 
        var width = options.obj.find('.width').text();
        var height = options.obj.find('.height').text();
        var styledMap = new google.maps.StyledMapType(options.style, {name: "Styled"});
        var address = options.obj.find('.address').text();
        var default_index = options.obj.find('.default_index').text();
        var marker_url = options.obj.find('.marker_url').text();
        var _inner_html = options.obj.find('.inner_html').html() || '';
        var _custom_popup_x = options.obj.find('.custom_popup_x').text() || '';
        var _custom_icons_size_y = options.obj.find('.custom_icons_size_y').text() || '';
        var _custom_icons_size_x = options.obj.find('.custom_icons_size_x').text() || '';
        var _custom_iconanchor_y = options.obj.find('.custom_icons_size_y').text() || '';
        var _custom_iconanchor_x = options.obj.find('.custom_iconanchor_x').text() || '';
        var HTMLmarker_offset_x = options.obj.find('.htmlmarker_offset_x').text() || '';
        var HTMLmarker_offset_y = options.obj.find('.htmlmarker_offset_y').text() || '';
        
        //CSS customizations
        options.obj.css('width', width);
        options.obj.find('.show_sw_win_map').css('height', height);
        //Hide if goes to second row
        jQuery(window).on('resize', function(){

        });
        
        options.myCenter = new google.maps.LatLng(lat, lng);
        options.markers = [];
        
        var mapProp = {
            center: options.myCenter,
            zoom: zoom,
            mapTypeControlOptions: {
                mapTypeIds: options.mapTypeIds
            }
        };
    
        options.map = new google.maps.Map(options.obj.find('.show_sw_win_map')[0], mapProp);
        options.map.mapTypes.set('map_style', styledMap);
        if(options.styledEnabled)
        {
            options.map.setMapTypeId('map_style');
        }
        
        options.infowindow = new google.maps.InfoWindow({
    class: 'contentString'
  });
       
        if(typeof enableHTMLmarkers !== 'undefined' && enableHTMLmarkers == true){
            var CustomMarker = function (latlng, map, marker_inner, callback) {
                    this.latlng = latlng;	
                    this.marker_inner = marker_inner;	
                    this.callback = callback;
                    this.position = latlng;	
                    this.setMap(map);
                    return this;
            }

            CustomMarker.prototype = new google.maps.OverlayView();

            CustomMarker.prototype.draw = function() {

                    var self = this;
                    var div = this.div;
                    if (!div) {

                            div = this.div = document.createElement('div');
                            div.className = "google_marker";

                            div.style.position = 'absolute';
                            div.style.cursor = 'pointer';

                            if (typeof(self.marker_inner) !== 'undefined') {
                                div.innerHTML = self.marker_inner;
                            }

                            google.maps.event.addDomListener(div, "click", function(event) {
                                if (typeof(self.callback.click) !== 'undefined') {
                                    self.callback.click(self.map, self);	
                                }
                            });

                            var panes = this.getPanes();
                            panes.overlayImage.appendChild(div);
                    }
                    var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
                    if (point) {
                        var offset_x = 17;
                        if(typeof HTMLmarker_offset_x !== '')
                            offset_x = HTMLmarker_offset_x;
                        div.style.left = (point.x - offset_x) + 'px';
                        var offset_y = 10;
                        if(typeof HTMLmarker_offset_y !== '')
                            offset_y = HTMLmarker_offset_y;
                        div.style.top = (point.y - offset_y) + 'px';
                    }
            };

            CustomMarker.prototype.remove = function() {
                    if (this.div) {
                            this.div.parentNode.removeChild(this.div);
                            this.div = null;
                    }	
                    this.setMap(null);
            };

            CustomMarker.prototype.getPosition = function() {
                    return this.latlng;	
            };

            CustomMarker.prototype.getDraggable = function() {
                return false;
            };


            var callback = {
                'click': function(map, e){
                    options.infowindow.setContent(options.obj.find('.content').html());
                    options.infowindow.open(map, e);
                }
            };
            
            var marker_inner ='<span><img src="'+marker_url+'"></span>';
            
            if(marker_url=='' && typeof default_marker_url !=='undefined')
                marker_inner = '<img class="default-pin" src="'+default_marker_url+'">'; 
            
            if(_inner_html != '') {
                marker_inner = _inner_html;
            }

            var marker = new CustomMarker(options.myCenter,options.map,marker_inner,callback);
        } else {
            options.mMarker = new google.maps.Marker({
                position: options.myCenter,
                //animation: google.maps.Animation.BOUNCE
            });

            google.maps.event.addListener(options.mMarker, 'click', open_main_location__popup);

            options.mMarker.setMap(options.map);
        }
    }
    
    function open_main_location__popup()
    {
        //open popup infowindow
        options.infowindow.setContent(options.obj.find('.content').html());
        options.infowindow.open(options.map, options.mMarker);
    }

    
    function setAllMap(map) {
      for (var i = 0; i < options.markers.length; i++) {
        options.markers[i].setMap(map);
      }
    }


    
};