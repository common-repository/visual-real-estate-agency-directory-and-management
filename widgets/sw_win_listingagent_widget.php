<?php


class SW_Win_Listingagent_Widget extends WP_Widget{
    
    public static $multiple_instance=false;
    
    function __construct()
    {
        $options = array(
            'description' => __('Display listing agents', 'sw_win'),
            'name' => __('Listing agent', 'sw_win'),
        );
        
        $options['name'] = 'SW '.$options['name'];
        
        parent::__construct('SW_Win_Listingagent_Widget', $options['name'], $options);
    }
    
    function form($instance)
    {
        //print_r($instance);
        extract($instance);
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'sw_win'); ?>: </label>
            <input 
                class="widefat"
                type="text"
                id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php if(isset($title))echo esc_attr($title); ?>"
            />
        </p>

        <?php
    }
    
    public function widget($args, $instance)
    {
        if(self::$multiple_instance === true)return;
        
        // Only show on listing preview page
        if(!sw_is_page(sw_settings('listing_preview_page')))
            return;
        
        extract($args);
        extract($instance);
        $atts = array_merge($instance, $args);
        
        $atts = shortcode_atts(array(
            'text_criteria'=>'',
            'lang_Listingagent'=>__('Agents', 'sw_win'),
            'lang_Address'=>__('Address', 'sw_win'),
            'lang_Details'=>__('Details', 'sw_win')
        ), $atts);
        
        sw_win_generate_head_meta($output, $atts);
        
        //if(empty($title))$title=__('Agents', 'sw_win');
        
        $output = '';
        //sw_win_show_latest_listings($output, array_merge($instance, $atts));
        
        sw_win_load_ci_function('Widgets', 'listingagent', array(&$output, $atts, $instance));
        
        // Hide complete widget if no content available
        if(empty($output))return;
        
        echo $before_widget;
            if(!empty($title))echo $before_title.$title.$after_title;
            echo "$output";
        echo $after_widget;
        
        self::$multiple_instance = true;
    }
}

// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "SW_Win_Listingagent_Widget" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'SW_Win_Listingagent_Widget' );});

