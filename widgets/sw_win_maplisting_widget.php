<?php


class SW_Win_Maplisting_Widget extends WP_Widget{
    
    public static $multiple_instance=false;
    
    function __construct()
    {
        $options = array(
            'description' => __('Display listings on map', 'sw_win'),
            'name' => __('Listings on map', 'sw_win'),
        );
        
        $options['name'] = 'SW '.$options['name'];
        
        parent::__construct('SW_Win_Maplisting_Widget', $options['name'], $options);
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
        
        $sw_zoom = sw_settings('zoom_index');
        
        if(isset($zoom_index) && !empty($zoom_index)){
            $zoom_index = $zoom_index;
        } elseif(!empty($sw_zoom)) {
            $zoom_index = $sw_zoom;
        } else {
            $zoom_index = 10;
        }
        ?>
        
        <?php if(sw_settings('auto_set_zoom_disabled')): ?>
        <p>
            <label for="<?php echo $this->get_field_id('zoom_index'); ?>"><?php echo __('Zoom index', 'sw_win'); ?>: </label>
            <input 
                class="widefat"
                type="text"
                id="<?php echo $this->get_field_id('zoom_index'); ?>"
                name="<?php echo $this->get_field_name('zoom_index'); ?>"
                value="<?php echo esc_attr($zoom_index); ?>"
            />
        </p>
        <?php endif;?>
        <p>
            <label for="<?php echo $this->get_field_id('show_featured'); ?>"><?php echo __('Show featured', 'sw_win'); ?>: </label>
<?php

$items = array('ALSO_FEATURED'=>__('Also featured', 'sw_win'), 'ONLY_FEATURED'=>__('Only featured', 'sw_win'), 'NO_FEATURED'=>__('No featured', 'sw_win'));
echo "<select name='".$this->get_field_name('show_featured')."' class='widefat' id='".$this->get_field_id('show_featured')."'>";
foreach($items as $item)
{
    $selected = $show_featured===$item?'selected="selected"':'';;
    echo '<option value="'.$item.'" '.$selected.'>'.$item.'</option>';
}
echo "</select>";

?>
        </p>

        <?php
    }
    
    public function widget($args, $instance)
    {
        if(self::$multiple_instance === true)return;

        global $swmaplistings_counter;
        
        if($swmaplistings_counter > 0)
        {
            echo __('Only one results map is allowed on page', 'sw_win');
            return;
        }

        $swmaplistings_counter++;

        extract($args);
        extract($instance);
        $atts = array_merge($instance, $args);

        $atts = shortcode_atts(array(
            'show_featured'=>'ALSO_FEATURED',
            'num_listings'=>50,
            'text_criteria'=>'',
            'lang_Maplistings'=>__('Map listings', 'sw_win'),
            'lang_Address'=>__('Address', 'sw_win'),
            'lang_Details'=>__('Details', 'sw_win')
        ), $atts);
        
        sw_win_generate_head_meta($output, $atts);
        
        if(empty($title))$title=__('Map listings', 'sw_win');
        if(empty($atts['show_featured']))$atts['show_featured'] = 'ALSO_FEATURED';
        if(empty($atts['num_listings']))$atts['num_listings'] = 100;
        if(empty($atts['text_criteria']))$atts['text_criteria'] = '';
        
        $sw_zoom = sw_settings('zoom_index');
        if(isset($instance['zoom_index']) && !empty($instance['zoom_index'])){
            $atts['zoom_index'] = $instance['zoom_index'];
        } elseif(!empty($sw_zoom)) {
            $atts['zoom_index'] = $sw_zoom;
        } else {
            $atts['zoom_index'] = 10;
        }
        
        $output = '';
        //sw_win_show_Map_listings($output, array_merge($instance, $atts));

        sw_win_load_ci_function('Widgets', 'maplistings', array(&$output, $atts, $instance));
        
        echo $before_widget;
            echo $before_title.$title.$after_title;
            echo "$output";
        echo $after_widget;
        
        self::$multiple_instance = true;
    }

    
}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "SW_Win_Maplisting_Widget" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'SW_Win_Maplisting_Widget' );});

