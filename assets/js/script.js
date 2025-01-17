
jQuery(document).ready(function() {
    if (typeof google === 'undefined') {
        // variable is undefined
        initialize_sw_win();
    } 
    else
    {
        google.maps.event.addDomListener(window, 'load', initialize_sw_win); 
    } 
});

function initialize_sw_win()
{

    jQuery.each(jQuery('.sw_win_map'), function( index, value ) {
        
        var options = {};
        if (typeof sw_map_style !== 'undefined') {
            options = {style:sw_map_style, styledEnabled: true};
        }
        if (typeof sw_marker !== 'undefined') {
            options.markerUrl = sw_marker;
        }

        jQuery(this).Swmap(options);
    });
    
}

function sw_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function sw_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

