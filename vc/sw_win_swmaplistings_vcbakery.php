<?php


add_action( 'vc_before_init', 'Swmaplistings_VC' );
function Swmaplistings_VC() {
    if (class_exists('WPBakeryShortCode')) {
        vc_map( array(
        	'name' => __( 'SW Map Listings', 'sw_win' ),
        	'base' => 'Swmaplistings',
        //	'icon' => 'icon-wpb-ui-separator',
        	'show_settings_on_create' => true,
        	'category' => __( 'Content', 'sw_win' ),
        //"controls"	=> 'popup_delete',
        	'description' => __( 'Map with listings (Only one allowed)', 'sw_win' ),
        	'params' => array(
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Search criteria', 'sw_win' ),
        			'param_name' => 'text_criteria',
        			'description' => __( 'You can enter searh criteria', 'sw_win' )
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
        
        class WPBakeryShortCode_Swmaplistings extends WPBakeryShortCode {
            
            public function content( $atts, $content = null ) {
                global $wpdb, $sw_map_id;
                
                $atts = shortcode_atts(array(
                    'text_criteria'=>NULL,
                    'show_featured'=>NULL,
                    'num_listings'=>NULL,
                    'agent_id'=>NULL,
                ), $atts);

                $output = '';

                $output.= do_shortcode('[swmaplistings text_criteria="'.$atts['text_criteria'].'" show_featured="'.$atts['show_featured'].'" agent_id="'.$atts['agent_id'].'" num_listings="'.$atts['num_listings'].'" ]');
                
                return $output;
            }

        }
   }
}

?>