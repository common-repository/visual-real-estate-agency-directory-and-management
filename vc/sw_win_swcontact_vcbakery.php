<?php


add_action( 'vc_before_init', 'Swcontact_VC' );
function Swcontact_VC() {
    if (class_exists('WPBakeryShortCode')) {
        vc_map( array(
        	'name' => __( 'SW Contact', 'sw_win' ),
        	'base' => 'Swcontact',
        //	'icon' => 'icon-wpb-ui-separator',
        	'show_settings_on_create' => true,
        	'category' => __( 'Content', 'sw_win' ),
        //"controls"	=> 'popup_delete',
        	'description' => __( 'Basic contact form', 'sw_win' ),
        	'params' => array(
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Receiver email', 'sw_win' ),
        			'param_name' => 'email',
        			'description' => __( 'You can enter address receiver email', 'sw_win' )
        		)
        	)
        ) );
        
        class WPBakeryShortCode_Swcontact extends WPBakeryShortCode {
            
            public function content( $atts, $content = null ) {
                global $wpdb, $sw_map_id;
                
                $atts = shortcode_atts(array(
                    'email'=>get_option( 'admin_email' ),
                    'content'=> !empty($content)?$content:__('My location', 'sw_win'),
                    'lang_Distance'=>__('Distance', 'sw_win'),
                    'lang_Address'=>__('Address', 'sw_win'),
                    'lang_WalkingTime'=>__('Walking time', 'sw_win'),
                    'time_metric'=>__('min', 'sw_win'),
                    'lang_Details'=>__('Details', 'sw_win')
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
                
                $output.= do_shortcode('[swcontact email="'.$atts['email'].'" ]');


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