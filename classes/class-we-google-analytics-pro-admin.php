<?php
class WE_Google_Analytics_Pro_Admin {
  
  public $settings;

	public function __construct() {
		//admin script and style
		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));
		$this->load_class('settings');
		$this->settings = new WE_Google_Analytics_Pro_Settings();
	}

	function load_class($class_name = '') {
	  global $WE_Google_Analytics_Pro;
		if ('' != $class_name) {
			require_once ($WE_Google_Analytics_Pro->plugin_path . '/admin/class-' . esc_attr($WE_Google_Analytics_Pro->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}// End load_class()
	



	public function enqueue_admin_script() {
		global $WE_Google_Analytics_Pro;
		$screen = get_current_screen();		
		// Enqueue admin script and stylesheet from here
		if (in_array( $screen->id, array( 'toplevel_page_we-google-analytics-pro-setting-admin' ))) :   
		  $WE_Google_Analytics_Pro->library->load_qtip_lib();		  
		  wp_enqueue_script('admin_js', $WE_Google_Analytics_Pro->plugin_url.'assets/admin/js/admin.js', array('jquery'), $WE_Google_Analytics_Pro->version, true);
		  wp_enqueue_style('admin_css',  $WE_Google_Analytics_Pro->plugin_url.'assets/admin/css/admin.css', array(), $WE_Google_Analytics_Pro->version);
	  endif;
	}
}