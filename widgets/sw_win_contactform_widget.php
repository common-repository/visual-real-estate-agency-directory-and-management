<?php


class SW_Win_Contactform_Widget extends WP_Widget{
    
    public static $multiple_instance=false;
    
    public static $widget_id=0;
    
    function __construct()
    {
        $options = array(
            'description' => __('Display contact form', 'sw_win'),
            'name' => __('Contact form', 'sw_win'),
        );
        
        $options['name'] = 'SW '.$options['name'];
        
        parent::__construct('SW_Win_Contactform_Widget', $options['name'], $options);
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
            <label for="<?php echo $this->get_field_id('receiver_email'); ?>"><?php echo __('Receiver email', 'sw_win'); ?>: </label>
            <input 
                class="widefat"
                type="text"
                id="<?php echo $this->get_field_id('receiver_email'); ?>"
                name="<?php echo $this->get_field_name('receiver_email'); ?>"
                value="<?php if(isset($receiver_email))echo esc_attr($receiver_email); ?>"
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
            'receiver_email'=>get_option('admin_email'),
            'lang_Contactform'=>__('Contact form', 'sw_win'),
            'lang_Send'=>__('Send', 'sw_win')
        ), $atts);

        self::$widget_id++;

        if(empty($args['id']))$args['id'] = self::$widget_id;
        if(empty($args['name']))$args['name'] = '';

        $atts['widget_id'] = $args['widget_id'];
        $atts['widget_id_short'] = $args['id'];
        $atts['widget_name'] = $args['name'];
        
        //dump($args);

        sw_win_generate_head_meta($output, $atts);
        
        if(empty($title))$title=__('Contact form', 'sw_win');
        if(empty($atts['receiver_email']))$atts['receiver_email'] = get_option('admin_email');
        
        $output = '';
        //sw_win_show_latest_listings($output, array_merge($instance, $atts));
        
        sw_win_load_ci_function('Widgets', 'contactform', array(&$output, $atts, $instance));
        
        echo $before_widget;
            echo $before_title.$title.$after_title;
            echo "$output";
        echo $after_widget;
        
        self::$multiple_instance = true;
    }

    
}

// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "SW_Win_Contactform_Widget" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'SW_Win_Contactform_Widget' );});
