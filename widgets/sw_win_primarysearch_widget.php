<?php


class SW_Win_Primarysearch_Widget extends WP_Widget{
    
    public static $multiple_instance=false;
    
    function __construct()
    {
        $options = array(
            'description' => __('Display primary search form', 'sw_win'),
            'name' => __('Primary search', 'sw_win'),
        );
        
        $options['name'] = 'SW '.$options['name'];
        
        parent::__construct('SW_Win_Primarysearch_Widget', $options['name'], $options);
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
        //static $x=1;
        
        if(self::$multiple_instance === true)return;

        extract($args);
        extract($instance);
        $atts = array_merge($instance, $args);

        $atts = shortcode_atts(array(
            'lang_Search'=>__('Search', 'sw_win'),
            'lang_Savesearch'=>__('Save Search', 'sw_win'),
            'lang_Cancel'=>__('Cancel', 'sw_win'),
            'id'=>NULL,
            'widget_name'=>NULL
        ), $atts);
        
        sw_win_generate_head_meta($output, $atts);
        
        if(empty($title))$title=__('Primary search', 'sw_win');
//        if(empty($atts['show_featured']))$atts['show_featured'] = 'ALSO_FEATURED';
//        if(empty($atts['num_listings']))$atts['num_listings'] = 5;
//        if(empty($atts['text_criteria']))$atts['text_criteria'] = '';
        
        $output = '';
        //sw_win_show_latest_listings($output, array_merge($instance, $atts));
        
        sw_win_load_ci_function('Widgets', 'Primarysearch', array(&$output, $atts, $instance));
        
        echo $before_widget;
            echo $before_title.$title.$after_title;
            echo "$output";
        echo $after_widget;
        
        //echo $x++;
        
        self::$multiple_instance = true;
    }

    
}

// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "SW_Win_Primarysearch_Widget" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'SW_Win_Primarysearch_Widget' );});

