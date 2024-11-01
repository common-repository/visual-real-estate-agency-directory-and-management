<?php

add_shortcode('swlisting', 'register_sw_swlisting_shortcode');
function register_sw_swlisting_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'id'=>NULL,
        'mode'=>'BASIC',
    ), $atts);
    
    extract($atts);
    
    sw_win_generate_head_meta($output, $atts);
    
    $output='';
    
    sw_win_load_ci_function('Shortcodes', 'swlisting', array(&$output, $atts));
    
    return $output;
}

?>