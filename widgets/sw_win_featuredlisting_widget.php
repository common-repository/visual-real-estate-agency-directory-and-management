<?php


class SW_Win_Featuredlisting_Widget extends WP_Widget{
    
    public static $multiple_instance=false;
    
    function __construct()
    {
        $options = array(
            'description' => __('Display featured listings', 'sw_win'),
            'name' => __('Featured listings', 'sw_win'),
        );
        
        $options['name'] = 'SW '.$options['name'];
        
        parent::__construct('SW_Win_Featuredlisting_Widget', $options['name'], $options);
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
        
        <p>
            <label for="<?php echo $this->get_field_id('text_criteria'); ?>"><?php echo __('Text criteria', 'sw_win'); ?>: </label>
            <input 
                class="widefat"
                type="text"
                id="<?php echo $this->get_field_id('text_criteria'); ?>"
                name="<?php echo $this->get_field_name('text_criteria'); ?>"
                value="<?php if(isset($text_criteria))echo esc_attr($text_criteria); ?>"
            />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('num_listings'); ?>"><?php echo __('Number of listings', 'sw_win'); ?>: </label>
            <input 
                class="widefat"
                type="text"
                id="<?php echo $this->get_field_id('num_listings'); ?>"
                name="<?php echo $this->get_field_name('num_listings'); ?>"
                value="<?php if(isset($num_listings))echo esc_attr($num_listings); ?>"
            />
        </p>

        <?php
    }
    
    public function widget($args, $instance)
    {
        if(self::$multiple_instance === true)return;
        
        extract($args);
        extract($instance);
        $atts = array_merge($instance, $args);
        
        $atts = shortcode_atts(array(
            'num_listings'=>5,
            'show_featured'=>NULL,
            'text_criteria'=>'',
            'lang_Featuredlistings'=>__('Featured listings', 'sw_win'),
            'lang_Address'=>__('Address', 'sw_win'),
            'lang_Details'=>__('Details', 'sw_win')
        ), $atts);
        
        $atts['widget_id'] = $args['widget_id'];
        $atts['widget_id_short'] = $args['id'];
        $atts['widget_name'] = $args['name'];
        
        sw_win_generate_head_meta($output, $atts);
        
        if(empty($title))$title=__('Featured listings', 'sw_win');
        if(empty($atts['num_listings']))$atts['num_listings'] = 5;
        if(empty($atts['text_criteria']))$atts['text_criteria'] = '';
        
        $output = '';
        //sw_win_show_latest_listings($output, array_merge($instance, $atts));
        $output = '';
        
        $output.= do_shortcode('[swfeaturedlistings widget_id_short="'.$atts['widget_id_short'].'" text_criteria="'.$atts['text_criteria'].'" show_featured="'.$atts['show_featured'].'" num_listings="'.$atts['num_listings'].'" ]');

        echo $before_widget;
            echo $before_title.$title.$after_title;
            echo "$output";
        echo $after_widget;
        
        self::$multiple_instance = true;
    }

    
}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "SW_Win_Featuredlisting_Widget" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'SW_Win_Featuredlisting_Widget' );});

