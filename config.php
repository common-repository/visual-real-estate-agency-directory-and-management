<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $config;

$config['account_types'] = array(''=>'',
                                 'VISITOR'=>__('Visitor', 'sw_win'),
                                 'OWNER'=>__('Owner', 'sw_win'),
                                 'AGENT'=>__('Agent', 'sw_win'),
                                 'AGENCY'=>__('Agency', 'sw_win'));

// re means real estate
$config['purpose_type'] = 're';

// cms|demo
$config['app_type'] = 'cms';


/* load optimization 
 * 
 * minify in-line js code in themes
 * use local cdn for open street map
 * 
 */
$config['load_optimization'] = false;





?>