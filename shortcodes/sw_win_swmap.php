<?php

add_shortcode('swmap', 'register_sw_swmap_shortcode');
function register_sw_swmap_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'latitude'=>'45.8129663',
        'longitude'=>'15.976036000000022',
        'content'=> !empty($content)?$content:__('My location', 'sw_win'),
        'radius'=>2000,
        'mode'=>'WALKING',
        'metric'=>__('km', 'sw_win'), // can be miles
        'lang_code'=>'en',
        'zoom'=>15,
        'width'=>'100%',
        'address'=>'',
        'default_index'=>'',
        'height'=>'400px',
        'marker_url'=>null,
        'inner_html'=>'',
        'custom_popup_x'=>'',
        'custom_icons_size_x'=>'',
        'custom_icons_size_y'=>'',
        'custom_iconanchor_x'=>'',
        'custom_iconanchor_y'=>'',
        'htmlmarker_offset_x'=>'',
        'htmlmarker_offset_y'=>'',
        'lang_Distance'=>__('Distance', 'sw_win'),
        'lang_Address'=>__('Address', 'sw_win'),
        'lang_WalkingTime'=>__('Walking time', 'sw_win'),
        'time_metric'=>__('min', 'sw_win'),
        'lang_Details'=>__('Details', 'sw_win')
    ), $atts);
    
    extract($atts);
    
    $output='';
    sw_win_generate_head_meta($output, $atts);
    sw_show_google_map($output, $atts);
    
    sw_win_load_ci_function('Shortcodes', 'swmap', array(&$output, $atts));
    
    return $output;
}

?>