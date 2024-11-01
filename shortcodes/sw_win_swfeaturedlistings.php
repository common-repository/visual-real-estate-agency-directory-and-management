<?php

add_shortcode('swfeaturedlistings', 'register_sw_swfeaturedlistings_shortcode');
function register_sw_swfeaturedlistings_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'num_listings'=>9,
        'text_criteria'=>NULL,
        'show_featured'=>'ONLY_FEATURED',
        'search_order'=>'idlisting DESC',
        'widget_id_short'=>''
    ), $atts);
    extract($atts);
    
    sw_win_generate_head_meta($output, $atts);
    
    $output='';
    
    sw_win_load_ci_function('Shortcodes', 'swfeaturedlistings', array(&$output, $atts));
    
    return $output;
}

?>