<?php


add_action( 'vc_before_init', 'Swagents_VC' );
function Swagents_VC() {
    if (class_exists('WPBakeryShortCode')) {
        vc_map( array(
        	'name' => __( 'SW Agents', 'sw_win' ),
        	'base' => 'Swagents',
        //	'icon' => 'icon-wpb-ui-separator',
        	'show_settings_on_create' => true,
        	'category' => __( 'Content', 'sw_win' ),
        //"controls"	=> 'popup_delete',
        	'description' => __( 'Agents listing', 'sw_win' ),
        	'params' => array(
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Search criteria', 'sw_win' ),
        			'param_name' => 'text_criteria',
        			'description' => __( 'You can enter searh criteria', 'sw_win' )
        		),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Num agents', 'sw_win' ),
        			'param_name' => 'num_listings',
        			'description' => __( 'You can enter num agents limit', 'sw_win' )
        		),

        	)
        ) );
        
        class WPBakeryShortCode_Swagents extends WPBakeryShortCode {
            
            public function content( $atts, $content = null ) {
                global $wpdb, $sw_map_id;
                
                $atts = shortcode_atts(array(
                    'text_criteria'=>NULL,
                    'show_featured'=>NULL,
                    'num_listings'=>NULL,
                ), $atts);
                
                //generate_head_meta($output, $atts);
                
//                if(empty($title))$title=__('Walker widget', 'sw_win');
//                if(empty($atts['height']))$atts['height'] = '400px';
//                if(empty($atts['latitude']))$atts['latitude'] = '45.8129663';
//                if(empty($atts['longitude']))$atts['longitude'] = '15.976036000000022';
//                if(empty($atts['content']))$atts['content'] = __('My location', 'sw_win');
//                if(empty($atts['lang_code']))$atts['lang_code'] = 'en';
//                if(empty($atts['metric']))$atts['metric'] = __('km', 'sw_win');
//                if(empty($atts['zoom']))$atts['zoom'] = 15;
//                if(empty($atts['radius']))$atts['radius'] = 2000;
                
                $output = '';
                //show_google_map($output, $atts);
                
                $output.= do_shortcode('[swagents text_criteria="'.$atts['text_criteria'].'" show_featured="'.$atts['show_featured'].'" num_listings="'.$atts['num_listings'].'" ]');


//                if(isset($_GET['vc_editable']) && $sw_map_id <= 1)
//                $output.='
//                <script src="'.plugins_url('js/near_places_obj.js', __FILE__).'"></script> 
//                <script src="'.plugins_url('js/script.js', __FILE__).'"></script> 
//                <script langauge="javascript">
//                jQuery(document).ready(function() {
//                    jQuery.each(jQuery(\'.show_walker\'), function( index, value ) {
//                        
//                        var options = {};
//                        if (typeof sw_map_style !== \'undefined\') {
//                            options = {style:sw_map_style, styledEnabled: true};
//                        }
//                        if (typeof sw_marker !== \'undefined\') {
//                            options.markerUrl = sw_marker;
//                        }
//                
//                        jQuery(this).NearPlaces(options);
//                    });
//                });
//                </script>
//                ';
                
                return $output;
            }

        }
   }
}

?>