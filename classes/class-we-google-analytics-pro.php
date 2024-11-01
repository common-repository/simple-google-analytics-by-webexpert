<?php
class WE_Google_Analytics_Pro {

	public $plugin_url;
	public $plugin_path;
	public $version;
	public $token;	
	public $text_domain;	
	public $library;
	public $admin;
	public $frontend;
	public $ajax;
	private $file;	
	public $settings;	
	public $we_wp_fields;

	public function __construct($file) {
		$this->file = $file;
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = WE_GOOGLE_ANALYTICS_PRO_PLUGIN_TOKEN;
		$this->text_domain = WE_GOOGLE_ANALYTICS_PRO_TEXT_DOMAIN;
		$this->version = WE_GOOGLE_ANALYTICS_PRO_PLUGIN_VERSION;		
		add_action('init', array(&$this, 'init'), 0);
	}
	
	
	function init() {		
		$this->load_plugin_textdomain();		
		$this->load_class('library');
		$this->library = new WE_Google_Analytics_Pro_Library();
		if (is_admin()) {
			$this->load_class('admin');
			$this->admin = new WE_Google_Analytics_Pro_Admin();
		}
		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('frontend');
			$this->frontend = new WE_Google_Analytics_Pro_Frontend();      
		}		
		$this->we_wp_fields = $this->library->load_wp_fields();
	}
	
	
  public function load_plugin_textdomain() {
    $locale = apply_filters( 'plugin_locale', get_locale(), $this->token );

    load_textdomain( $this->text_domain, WP_LANG_DIR . "/we-google-analytics-pro/we-google-analytics-pro-$locale.mo" );
    load_textdomain( $this->text_domain, $this->plugin_path . "/languages/we-google-analytics-pro-$locale.mo" );
  }

	public function load_class($class_name = '') {
		if ('' != $class_name && '' != $this->token) {
			require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
		} 
	}
	
	
	function nocache() {
		if (!defined('DONOTCACHEPAGE'))
			define("DONOTCACHEPAGE", "true");
		
	}

}
