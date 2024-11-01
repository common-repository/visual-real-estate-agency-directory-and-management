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
        clusters: L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true}),
        jpopup_customOptions:{
                                'maxWidth': 'initial',
                                'width': 'initial',
                                'className' : 'popupcustom-default'
                            },
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
        var styledMap = '';
        var address = options.obj.find('.address').text();
        
        if(address != '')
        {

            $.get('https://nominatim.openstreetmap.org/search?format=json&q='+address, function(data){
                if(data.length && typeof data[0]) {
                    options.obj.find('.latitude').text(data[0].lat);
                    options.obj.find('.longitude').text(data[0].lon);
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
        var styledMap = '';
        var address = options.obj.find('.address').text();
        var default_index = options.obj.find('.default_index').text();
        var marker_url = options.obj.find('.marker_url').text();
        var inner_html = options.obj.find('.inner_html').html() || '';
        var custom_popup_x = options.obj.find('.custom_popup_x').text() || '';
        var _custom_icons_size_y = options.obj.find('.custom_icons_size_y').text() || '';
        var _custom_icons_size_x = options.obj.find('.custom_icons_size_x').text() || '';
        var _custom_iconanchor_y = options.obj.find('.custom_icons_size_y').text() || '';
        var _custom_iconanchor_x = options.obj.find('.custom_iconanchor_x').text() || '';
        
        //CSS customizations
        options.obj.css('width', width);
        options.obj.find('.show_sw_win_map').css('height', height);
        //Hide if goes to second row
        
        options.myCenter = new L.LatLng(lat, lng);
        
        options.markers = [];
        
        
        options.map = L.map(options.obj.find('.show_sw_win_map')[0], {
            center: options.myCenter,
            zoom: zoom,
            scrollWheelZoom: false,
            dragging: !L.Browser.mobile,
            tap: !L.Browser.mobile
        });     
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
        }).addTo(options.map);

        var sw_style_open_street_ini = 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png';
        if(typeof sw_map_style_open_street != 'undefined') {
            sw_style_open_street_ini = sw_map_style_open_street;
        }

        var positron = L.tileLayer(sw_style_open_street_ini).addTo(options.map);
        var innerHTML = '<span><img src="'+marker_url+'"></span>';
        if (typeof(inner_html) !== 'undefined' && inner_html !='') {
            innerHTML = inner_html;
        }
        
        var custom_x = -1;
        if (custom_popup_x !='') {
            custom_x = parseInt(custom_popup_x);
        }
        var custom_icons_size_x = 40;
        if (_custom_icons_size_x !='') {
            custom_icons_size_x = parseInt(_custom_icons_size_x);
        }
        var custom_icons_size_y = 40;
        if (_custom_icons_size_y !='') {
            custom_icons_size_y = parseInt(_custom_icons_size_y);
        }
        
        var custom_iconanchor_x = 20;
        if (_custom_iconanchor_x !='') {
            custom_iconanchor_x = parseInt(_custom_iconanchor_x);
        }
        var custom_iconanchor_y = 42;
        if (_custom_iconanchor_y !='') {
            custom_iconanchor_y = parseInt(_custom_iconanchor_y);
        }
        
        options.mMarker = L.marker(
            options.myCenter,
            {icon: L.divIcon({
                    html: innerHTML,
                    className: 'open_steet_map_marker google_marker',
                    iconSize: [custom_icons_size_x, custom_icons_size_y],
                    popupAnchor: [custom_x, -35],
                    iconAnchor: [custom_iconanchor_x, custom_iconanchor_y],
                })
            }
        ).addTo(options.map);

        options.mMarker.bindPopup(options.obj.find('.content').html(), options.jpopup_customOptions);
     
    }
    
    function open_main_location__popup()
    {
    }

    
    function setAllMap(map) {
        
    }
    
};