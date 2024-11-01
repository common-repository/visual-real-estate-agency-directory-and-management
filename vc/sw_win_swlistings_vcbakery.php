<?php


add_action( 'vc_before_init', 'Swlistings_VC' );
function Swlistings_VC() {
    if (class_exists('WPBakeryShortCode')) {
        vc_map( array(
        	'name' => __( 'SW Listings', 'sw_win' ),
        	'base' => 'Swlistings',
        //	'icon' => 'icon-wpb-ui-separator',
        	'show_settings_on_create' => true,
        	'category' => __( 'Content', 'sw_win' ),
        //"controls"	=> 'popup_delete',
        	'description' => __( 'listings details', 'sw_win' ),
        	'params' => array(
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Search criteria', 'sw_win' ),
        			'param_name' => 'text_criteria',
        			'description' => __( 'You can enter searh criteria', 'sw_win' )
        		),
        		array(
        			'type' => 'dropdown',
        			'heading' => __( 'Show features', 'sw_win' ),
        			'param_name' => 'show_featured',
        			'value' => array(__('Also featured', 'sw_win')=>'ALSO_FEATURED', 
                                     __('Only featured', 'sw_win')=>'ONLY_FEATURED',
                                     __('No featured', 'sw_win')=>'NO_FEATURED'),
        			'description' => __( 'Select featured criteria.', 'sw_win' )
        		),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Num listings', 'sw_win' ),
        			'param_name' => 'num_listings',
        			'description' => __( 'You can enter num listings limit', 'sw_win' )
        		),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Agent id', 'sw_win' ),
        			'param_name' => 'agent_id',
        			'description' => __( 'You can enter user ID or email', 'sw_win' )
        		),
        	)
        ) );
        
        class WPBakeryShortCode_Swlistings extends WPBakeryShortCode {
            
            public function content( $atts, $content = null ) {
                global $wpdb, $sw_map_id;
                
                $atts = shortcode_atts(array(
                    'text_criteria'=>NULL,
                    'show_featured'=>NULL,
                    'num_listings'=>NULL,
                    'agent_id'=>NULL,
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
                
                $output.= do_shortcode('[swlistings text_criteria="'.$atts['text_criteria'].'" show_featured="'.$atts['show_featured'].'" agent_id="'.$atts['agent_id'].'" num_listings="'.$atts['num_listings'].'" ]');


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