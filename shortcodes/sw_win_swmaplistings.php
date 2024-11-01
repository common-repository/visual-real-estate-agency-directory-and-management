<?php

$swmaplistings_counter=0;

add_shortcode('swmaplistings', 'register_sw_swmaplistings_shortcode');
function register_sw_swmaplistings_shortcode($atts, $content){
    
    global $swmaplistings_counter;
    
    if($swmaplistings_counter > 0)
    {
        $output = __('Only one results map is allowed on page', 'sw_win');
        return $output;
    }
    
    $swmaplistings_counter++;
    
    $atts = shortcode_atts(array(
        'num_listings'=>50,
        'text_criteria'=>NULL,
        'show_featured'=>'ALSO_FEATURED',
        'search_order'=>'idlisting DESC',
        'agent_id'=>NULL
    ), $atts);
    
    extract($atts);
    
    sw_win_generate_head_meta($output, $atts);
    
    $output='';
    
    sw_win_load_ci_function('Shortcodes', 'swmaplistings', array(&$output, $atts));
    
    return $output;
}

?>