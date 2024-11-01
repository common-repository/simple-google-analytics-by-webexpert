<?php
class WE_Google_Analytics_Pro_Library {
  
  public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $jquery_lib_path;
  
  public $jquery_lib_url;

	public function __construct() {
	  global $WE_Google_Analytics_Pro;
	  
	  $this->lib_path = $WE_Google_Analytics_Pro->plugin_path . 'lib/';

    $this->lib_url = $WE_Google_Analytics_Pro->plugin_url . 'lib/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->jquery_lib_path = $this->lib_path . 'jquery/';
    
    $this->jquery_lib_url = $this->lib_url . 'jquery/';
	}
	
	/**
	 * PHP WP fields Library
	 */
	public function load_wp_fields() {
	  global $WE_Google_Analytics_Pro;
	  if ( ! class_exists( 'WE_WP_Fields' ) )
	    require_once ($this->php_lib_path . 'class-we-wp-fields.php');
	  $WE_WP_Fields = new WE_WP_Fields(); 
	  return $WE_WP_Fields;
	}
	
	/**
	 * Jquery qTip library
	 */
	public function load_qtip_lib() {
	  global $WE_Google_Analytics_Pro;
	  wp_enqueue_script('qtip_js', $this->jquery_lib_url . 'qtip/qtip.js', array('jquery'), $WE_Google_Analytics_Pro->version, true);
		wp_enqueue_style('qtip_css',  $this->jquery_lib_url . 'qtip/qtip.css', array(), $WE_Google_Analytics_Pro->version);
	}
	
	/**
	 * WP Media library
	 */
	public function load_upload_lib() {
	  global $WE_Google_Analytics_Pro;
	  wp_enqueue_media();
	  wp_enqueue_script('upload_js', $this->jquery_lib_url . 'upload/media-upload.js', array('jquery'), $WE_Google_Analytics_Pro->version, true);
	  wp_enqueue_style('upload_css',  $this->jquery_lib_url . 'upload/media-upload.css', array(), $WE_Google_Analytics_Pro->version);
	}
	
	/**
	 * WP ColorPicker library
	 */
	public function load_colorpicker_lib() {
	  global $WE_Google_Analytics_Pro;
	  wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'colorpicker_init', $this->jquery_lib_url . 'colorpicker/colorpicker.js', array( 'jquery', 'wp-color-picker' ), $WE_Google_Analytics_Pro->version, true );
    wp_enqueue_style( 'wp-color-picker' );
	}
	
	/**
	 * WP DatePicker library
	 */
	public function load_datepicker_lib() {
	  global $WE_Google_Analytics_Pro;
	  wp_enqueue_script('jquery-ui-datepicker');
	  wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}
}
