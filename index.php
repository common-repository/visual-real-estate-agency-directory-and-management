<?php

/*
Plugin Name: Visual Real Estate - Agency Directory and Management
Plugin URI: http://www.listing-themes.com/
Description: Listings Agency Directory and Management plugin with special visual customization features
Author: Sandi Winter
Author URI: http://www.swit.hr/
Version: 1.1
Text Domain: sw_win
Domain Path: /locale/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Disable free if premium plugin installed
if(plugin_basename(__FILE__) != 'SW_Win_Classified/index.php')
{
    if(file_exists(plugin_dir_path( __FILE__ ).'../SW_Win_Classified/'))
    {
        return;
    }
}

define( 'SW_WIN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SW_WIN_PLUGIN_NAME', plugin_basename(__FILE__) );
define( 'SW_WIN_FILENAME', basename(__FILE__, '.php') );
define( 'SW_WIN_SLUG', dirname(plugin_basename(__FILE__)) );

$dir_widgets = dirname(__FILE__)."/widgets/";
$dir_shortcodes = dirname(__FILE__)."/shortcodes/";
$dir_vc = dirname(__FILE__)."/vc/";
$dir_include = dirname(__FILE__)."/include/";

include dirname(__FILE__).'/config.php';
if($config['app_type'] == 'demo')
    include dirname(__FILE__).'/demo_mode.php';
include dirname(__FILE__).'/sw_win_helpers.php';
include dirname(__FILE__).'/sw_win_options.php';
include dirname(__FILE__).'/sw_win_filters.php';
include dirname(__FILE__).'/sw_win_dashwidget.php';

// Load all widget files
if (is_dir($dir_widgets)){
  if ($dh = opendir($dir_widgets)){
    while (($file = readdir($dh)) !== false){
        if(strrpos($file, ".php") !== FALSE)
            include_once($dir_widgets.$file);
    }
    closedir($dh);
  }
}

// Load all shortcode files
if (is_dir($dir_shortcodes)){
  if ($dh = opendir($dir_shortcodes)){
    while (($file = readdir($dh)) !== false){
        if(strrpos($file, ".php") !== FALSE)
            include_once($dir_shortcodes.$file);
    }
    closedir($dh);
  }
}

// Load all vc (visual composer) files
if (is_dir($dir_vc)){
  if ($dh = opendir($dir_vc)){
    while (($file = readdir($dh)) !== false){
        if(strrpos($file, ".php") !== FALSE)
            include_once($dir_vc.$file);
    }
    closedir($dh);
  }
}

// Load all include files
if (is_dir($dir_include)){
    if ($dh = opendir($dir_include)){
      while (($file = readdir($dh)) !== false){
          if(strrpos($file, ".php") !== FALSE)
              include_once($dir_include.$file);
      }
      closedir($dh);
    }
}

  include dirname(__FILE__).'/index_actions.php';

?>