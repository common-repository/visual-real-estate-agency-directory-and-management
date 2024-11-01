<?php

add_shortcode('swprimarysearch', 'register_sw_swprimarysearch_shortcode');
function register_sw_swprimarysearch_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'id'=>NULL,
        'mode'=>'BASIC',
        'subfolder'=>NULL
    ), $atts);


    extract($atts);
    
    sw_win_generate_head_meta($output, $atts);
    
    $output='';
    
    if(SW_Win_Primarysearch_Widget::$multiple_instance === true)return;

    sw_win_load_ci_function('Shortcodes', 'swprimarysearch', array(&$output, $atts));
    
    SW_Win_Primarysearch_Widget::$multiple_instance=true;
    
    return $output;
}

?>