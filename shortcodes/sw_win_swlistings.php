<?php

add_shortcode('swlistings', 'register_sw_swlistings_shortcode');
function register_sw_swlistings_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'num_listings'=>9,
        'text_criteria'=>NULL,
        'show_featured'=>'ALSO_FEATURED',
        'search_order'=>'idlisting DESC',
        'agent_id'=>NULL
    ), $atts);
    
    extract($atts);
    
    sw_win_generate_head_meta($output, $atts);
    
    $output='';
    
    sw_win_load_ci_function('Shortcodes', 'swlistings', array(&$output, $atts));
    
    return $output;
}

?>