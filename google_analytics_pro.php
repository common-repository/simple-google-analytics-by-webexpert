<?php
/**
* Plugin Name: Simple Google Analytics By WebExpert
* Plugin URI: http://www.vtdesignz.com/
* Description: Simple Google Analytics By WebExpert is very simple but effective plugin which gives you very simple configuration option and flaxibility regarding the google  analytics tracking code in you wordpress website. and you can also add multiple ga tracking codes for different post type.
* Author: WebExpert, vaptechdesigns, prabhakar.umpl
* Version: 1.1.0
* Author URI: http://www.vtdesignz.com/
*/
require_once trailingslashit(dirname(__FILE__)).'config.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('WE_GOOGLE_ANALYTICS_PRO_PLUGIN_TOKEN')) exit;
if(!defined('WE_GOOGLE_ANALYTICS_PRO_TEXT_DOMAIN')) exit;

if(!class_exists('WE_Google_Analytics_Pro')) {
	require_once( trailingslashit(dirname(__FILE__)).'classes/class-we-google-analytics-pro.php' );
	global $WE_Google_Analytics_Pro;
	$WE_Google_Analytics_Pro = new WE_Google_Analytics_Pro( __FILE__ );
	$GLOBALS['WE_Google_Analytics_Pro'] = $WE_Google_Analytics_Pro;
}
?>
