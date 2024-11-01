<?php

add_shortcode('swagencies', 'register_sw_swagencies_shortcode');
function register_sw_swagencies_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'num_listings'=>9,
        'text_criteria'=>NULL,
        'search_order'=>'ID DESC'
    ), $atts);
    
    extract($atts);
    
    sw_win_generate_head_meta($output, $atts);
    
    $output='';
    
    sw_win_load_ci_function('Shortcodes', 'swagencies', array(&$output, $atts));
    
    return $output;
}

?>