<?php


add_action( 'vc_before_init', 'Swmap_VC' );
function Swmap_VC() {
    if (class_exists('WPBakeryShortCode')) {
        vc_map( array(
        	'name' => __( 'SW Map', 'sw_win' ),
        	'base' => 'Swmap',
        //	'icon' => 'icon-wpb-ui-separator',
        	'show_settings_on_create' => true,
        	'category' => __( 'Content', 'sw_win' ),
        //"controls"	=> 'popup_delete',
        	'description' => __( 'Basic map', 'sw_win' ),
        	'params' => array(
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Address', 'sw_win' ),
        			'param_name' => 'address',
        			'description' => __( 'You can enter address or lat/lang bellow', 'sw_win' )
        		),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Latitude', 'sw_win' ),
        			'param_name' => 'latitude',
        			'description' => __( 'Latitude', 'sw_win' )
        		),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Longitude', 'sw_win' ),
        			'param_name' => 'longitude',
        			'description' => __( 'Longitude', 'sw_win' )
        		),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Height (include px)', 'sw_win' ),
        			'param_name' => 'height',
                    'value' => '300px',
        			'description' => __( 'Height (include px)', 'sw_win' )
        		),
        		array(
        			'type' => 'textarea',
        			'heading' => __( 'Content', 'sw_win' ),
        			'param_name' => 'content',
        			'description' => __( 'Wanted description/content on your Location infowindow', 'sw_win' )
        		),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Lang code', 'sw_win' ),
        			'param_name' => 'lang_code',
                    'value' => 'en',
        			'description' => __( 'Google maps language code', 'sw_win' )
        		),
        		array(
        			'type' => 'dropdown',
        			'heading' => __( 'Metric', 'sw_win' ),
        			'param_name' => 'metric',
        			'value' => array(
        				__( 'km', 'sw_win' ) => 'km',
        				__( 'miles', 'sw_win' ) => 'miles'
        			),
        			'description' => __( 'Select wanted metrics.', 'sw_win' )
        		)

        	)
        ) );
        
        class WPBakeryShortCode_Swmap extends WPBakeryShortCode {
            
            public function content( $atts, $content = null ) {
                global $wpdb, $sw_map_id;
                
                $atts = shortcode_atts(array(
                    'latitude'=>'45.8129663',
                    'longitude'=>'15.976036000000022',
                    'content'=> !empty($content)?$content:__('My location', 'sw_win'),
                    'radius'=>2000,
                    'mode'=>'WALKING',
                    'metric'=>__('km', 'sw_win'), // can be miles
                    'lang_code'=>'en',
                    'zoom'=>15,
                    'width'=>'100%',
                    'address'=>'',
                    'default_index'=>'',
                    'height'=>'400px',
                    'lang_Distance'=>__('Distance', 'sw_win'),
                    'lang_Address'=>__('Address', 'sw_win'),
                    'lang_WalkingTime'=>__('Walking time', 'sw_win'),
                    'time_metric'=>__('min', 'sw_win'),
                    'lang_Details'=>__('Details', 'sw_win')
                ), $atts);
                
                $output = '';

                sw_win_generate_head_meta($output, $atts);
                sw_show_google_map($output, $atts);

                if(isset($_GET['vc_editable']) && $sw_map_id <= 1)
                $output.='
                <script src="'.plugins_url(SW_WIN_SLUG.'/assets/js/sw_win_map_obj.js').'"></script> 
                <script src="'.plugins_url(SW_WIN_SLUG.'/assets/js/script.js').'"></script> 
                <script langauge="javascript">
                jQuery(document).ready(function() {
                    jQuery.each(jQuery(\'.sw_win_map\'), function( index, value ) {
                        
                        var options = {};
                        if (typeof sw_map_style !== \'undefined\') {
                            options = {style:sw_map_style, styledEnabled: true};
                        }
                        if (typeof sw_marker !== \'undefined\') {
                            options.markerUrl = sw_marker;
                        }
                
                        jQuery(this).Swmap(options);
                    });
                });
                </script>
                ';
                
                return $output;
            }

        }
   }
}

?>