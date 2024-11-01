<?php


add_action( 'vc_before_init', 'Swfeaturedlistings_VC' );
function Swfeaturedlistings_VC() {
    if (class_exists('WPBakeryShortCode')) {
        vc_map( array(
        	'name' => __( 'SW Featured Listings', 'sw_win' ),
        	'base' => 'Swfeaturedlistings',
        //	'icon' => 'icon-wpb-ui-separator',
        	'show_settings_on_create' => true,
        	'category' => __( 'Content', 'sw_win' ),
        //"controls"	=> 'popup_delete',
        	'description' => __( 'Featured listings', 'sw_win' ),
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

        	)
        ) );
        
        class WPBakeryShortCode_Swfeaturedlistings extends WPBakeryShortCode {
            
            public function content( $atts, $content = null ) {
                global $wpdb, $sw_map_id;
                
                $atts = shortcode_atts(array(
                    'text_criteria'=>NULL,
                    'show_featured'=>NULL,
                    'num_listings'=>9,
                    'show_featured'=>'ONLY_FEATURED',
                    'search_order'=>'idlisting DESC'
                ), $atts);
                

                
                $output = '';

                $output.= do_shortcode('[swfeaturedlistings text_criteria="'.$atts['text_criteria'].'" show_featured="'.$atts['show_featured'].'" num_listings="'.$atts['num_listings'].'" ]');

                
                return $output;
            }

        }
   }
}

?>