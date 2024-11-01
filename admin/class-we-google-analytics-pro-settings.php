<?php
class WE_Google_Analytics_Pro_Settings {
  
  private $tabs = array();  
  private $options;
  
  /**
   * Start up
   */
  public function __construct() {
    // Admin menu
    add_action( 'admin_menu', array( $this, 'add_settings_page' ), 100 );
    add_action( 'admin_init', array( $this, 'settings_page_init' ) );
    
    // Settings tabs
    add_action('settings_page_we_google_analytics_pro_general_tab_init', array(&$this, 'general_tab_init'), 10, 1);
	add_action('settings_page_we_google_analytics_pro_gacode_tab_init', array(&$this, 'gacode_tab_init'), 10, 1);
  }
  
  /**
   * Add options page
   */
  public function add_settings_page() {
    global $WE_Google_Analytics_Pro;
    
    add_menu_page(
        __('Google Analytics Pro Settings', $WE_Google_Analytics_Pro->text_domain), 
        __('Analytics Settings', $WE_Google_Analytics_Pro->text_domain), 
        'manage_options', 
        'we-google-analytics-pro-setting-admin', 
        array( $this, 'create_we_google_analytics_pro_settings' ),
        $WE_Google_Analytics_Pro->plugin_url . 'assets/images/welogos.png'
    );
    
    $this->tabs = $this->get_we_settings_tabs();
  }
  
  function get_we_settings_tabs() {
    global $WE_Google_Analytics_Pro;
    $tabs = apply_filters('we_google_analytics_pro_tabs', array(
      'we_google_analytics_pro_general' => __('Google Analytics Pro General', $WE_Google_Analytics_Pro->text_domain),
	  'we_google_analytics_pro_gacode' => __('Google Analytics Pro Multiple Analytics Code', $WE_Google_Analytics_Pro->text_domain)
    ));
    return $tabs;
  }
  
  function we_settings_tabs( $current = 'we_google_analytics_pro_general' ) {
    if ( isset ( $_GET['tab'] ) ) :
      $current = $_GET['tab'];
    else:
      $current = 'we_google_analytics_pro_general';
    endif;
    
    $links = array();
    foreach( $this->tabs as $tab => $name ) :
      if ( $tab == $current ) :
        $links[] = "<a class='nav-tab nav-tab-active' href='?page=we-google-analytics-pro-setting-admin&tab=$tab'>$name</a>";
      else :
        $links[] = "<a class='nav-tab' href='?page=we-google-analytics-pro-setting-admin&tab=$tab'>$name</a>";
      endif;
    endforeach;
    echo '<div class="icon32" id="webexpert_menu_ico"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ( $links as $link )
      echo $link;
    echo '</h2>';
    
    foreach( $this->tabs as $tab => $name ) :
      if ( $tab == $current ) :
        echo "<h2>$name Settings</h2>";
      endif;
    endforeach;
  }

  /**
   * Options page callback
   */
  public function create_we_google_analytics_pro_settings() {
    global $WE_Google_Analytics_Pro;
    ?>
    <div class="wrap">
      <?php $this->we_settings_tabs(); ?>
      <?php
      $tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'we_google_analytics_pro_general' );
      $this->options = get_option( "we_{$tab}_settings_name" );
      //print_r($this->options);
      
      // This prints out all hidden setting errors
      settings_errors("we_{$tab}_settings_name");
      ?>
      <form method="post" action="options.php">
      <?php
        // This prints out all hidden setting fields
        settings_fields( "we_{$tab}_settings_group" );   
        do_settings_sections( "dc-{$tab}-settings-admin" );
        submit_button(); 
      ?>
      </form>
    </div>
    <?php
   
  }

  /**
   * Register and add settings
   */
  public function settings_page_init() { 
    do_action('befor_settings_page_init');
    
    // Register each tab settings
    foreach( $this->tabs as $tab => $name ) :
      do_action("settings_page_{$tab}_tab_init", $tab);
    endforeach;
    
    do_action('after_settings_page_init');
  }
  
  /**
   * Register and add settings fields
   */
  public function settings_field_init($tab_options) {
    global $WE_Google_Analytics_Pro;
    
    if(!empty($tab_options) && isset($tab_options['tab']) && isset($tab_options['ref']) && isset($tab_options['sections'])) {
      // Register tab options
      register_setting(
        "we_{$tab_options['tab']}_settings_group", // Option group
        "we_{$tab_options['tab']}_settings_name", // Option name
        array( $tab_options['ref'], "we_{$tab_options['tab']}_settings_sanitize" ) // Sanitize
      );
      
      foreach($tab_options['sections'] as $sectionID => $section) {
        // Register section
        add_settings_section(
          $sectionID, // ID
          $section['title'], // Title
          array( $tab_options['ref'], "{$sectionID}_info" ), // Callback
          "dc-{$tab_options['tab']}-settings-admin" // Page
        );
        
        // Register fields
        if(isset($section['fields'])) {
          foreach($section['fields'] as $fieldID => $field) {
            if(isset($field['type'])) {
              $field = $WE_Google_Analytics_Pro->we_wp_fields->check_field_id_name($fieldID, $field);
              $field['tab'] = $tab_options['tab'];
              $callbak = $this->get_field_callback_type($field['type']);
              if(!empty($callbak)) {
                add_settings_field(
                  $fieldID,
                  $field['title'],
                  array( $this, $callbak ),
                  "dc-{$tab_options['tab']}-settings-admin",
                  $sectionID,
                  $field
                );
              }
            }
          }
        }
      }
    }
  }
  
  function general_tab_init($tab) {
    global $WE_Google_Analytics_Pro;
    $WE_Google_Analytics_Pro->admin->load_class("settings-{$tab}", $WE_Google_Analytics_Pro->plugin_path, $WE_Google_Analytics_Pro->token);
    new WE_Google_Analytics_Pro_Settings_Gneral($tab);
  }
  function gacode_tab_init($tab) {
    global $WE_Google_Analytics_Pro;
    $WE_Google_Analytics_Pro->admin->load_class("settings-{$tab}", $WE_Google_Analytics_Pro->plugin_path, $WE_Google_Analytics_Pro->token);
    new WE_Google_Analytics_Pro_Settings_Gacode($tab);
  }
  
  function get_field_callback_type($fieldType) {
    $callBack = '';
    switch($fieldType) {
      case 'input':
      case 'text':
      case 'email':
      case 'number':
      case 'file':
      case 'url':
        $callBack = 'text_field_callback';
        break;
        
      case 'hidden':
        $callBack = 'hidden_field_callback';
        break;
        
      case 'textarea':
        $callBack = 'textarea_field_callback';
        break;
        
      case 'wpeditor':
        $callBack = 'wpeditor_field_callback';
        break;
        
      case 'checkbox':
        $callBack = 'checkbox_field_callback';
        break;
        
      case 'radio':
        $callBack = 'radio_field_callback';
        break;
        
      case 'select':
        $callBack = 'select_field_callback';
        break;
        
      case 'upload':
        $callBack = 'upload_field_callback';
        break;
        
      case 'colorpicker':
        $callBack = 'colorpicker_field_callback';
        break;
        
      case 'datepicker':
        $callBack = 'datepicker_field_callback';
        break;
        
      case 'multiinput':
        $callBack = 'multiinput_callback';
        break;
	  case 'multiselect':
		$callBack = 'multiselect_callback';
		break;
        
      default:
        $callBack = '';
        break;
    }
    
    return $callBack;
  }
  
  /** 
   * Get the hidden field display
   */
  public function hidden_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_attr( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->hidden_input($field);
  }
  
  /** 
   * Get the text field display
   */
  public function text_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_attr( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->text_input($field);
  }
  
  /** 
   * Get the text area display
   */
  public function textarea_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_textarea( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_textarea( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->textarea_input($field);
  }
  
  /** 
   * Get the wpeditor display
   */
  public function wpeditor_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? ( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? ( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->wpeditor_input($field);
  }
  
  /** 
   * Get the checkbox field display
   */
  public function checkbox_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_attr( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : $field['value'];
    $field['dfvalue'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : '';
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->checkbox_input($field);
  }
  
  /** 
   * Get the checkbox field display
   */
  public function radio_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_attr( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->radio_input($field);
  }
  
  /** 
   * Get the select field display
   */
  public function select_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_textarea( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_textarea( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->select_input($field);
  }
  
   /**
   *
   *
   *Get the multiselect field display
   */
  public function multiselect_callback($field) {
  	global $WE_Google_Analytics_Pro;
  	$field['value'] = isset( $field['value'] ) ? $field['value'] : array();
    $field['value'] = isset( $this->options[$field['name']] ) ? $this->options[$field['name']] : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->multiselect_input($field);  	
  }
  
  /** 
   * Get the upload field display
   */
  public function upload_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_attr( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->upload_input($field);
  }
  
  /** 
   * Get the multiinput field display
   */
  public function multiinput_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? $field['value'] : array();
    $field['value'] = isset( $this->options[$field['name']] ) ? $this->options[$field['name']] : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->multi_input($field);
  }
  
  /** 
   * Get the colorpicker field display
   */
  public function colorpicker_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_attr( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->colorpicker_input($field);
  }
  
  /** 
   * Get the datepicker field display
   */
  public function datepicker_field_callback($field) {
    global $WE_Google_Analytics_Pro;
    $field['value'] = isset( $field['value'] ) ? esc_attr( $field['value'] ) : '';
    $field['value'] = isset( $this->options[$field['name']] ) ? esc_attr( $this->options[$field['name']] ) : $field['value'];
    $field['name'] = "we_{$field['tab']}_settings_name[{$field['name']}]";
    $WE_Google_Analytics_Pro->we_wp_fields->datepicker_input($field);
  }
  
}