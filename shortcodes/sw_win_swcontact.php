<?php

add_shortcode('swcontact', 'register_sw_swcontact_shortcode');
function register_sw_swcontact_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'email'=>get_option( 'admin_email' ),
        'mode'=>'BASIC',
    ), $atts);
    
    extract($atts);
    
    SW_Win_Contactform_Widget::$widget_id++;
    $atts['widget_id'] = SW_Win_Contactform_Widget::$widget_id;
    
    sw_win_generate_head_meta($output, $atts);
    
    $output='';
    
    sw_win_load_ci_function('Shortcodes', 'swcontact', array(&$output, $atts));
    
    return $output;
}

?>