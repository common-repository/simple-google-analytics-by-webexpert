<?php
class WE_Google_Analytics_Pro_Settings_Gacode {
 
  private $options;  
  private $tab; 
  public $post_type = array();  
  public function __construct($tab) {
    $this->tab = $tab;
	
	$this->get_post_type();
    $this->options = get_option( "we_{$this->tab}_settings_name" );
    $this->settings_page_init();
  }
  public function get_post_type(){
	  $args = array(
		   'public'   => true,
		   '_builtin' => false
		);
	  
			$output = 'names'; // names or objects, note names is the default
			$operator = 'and'; // 'and' or 'or'			
			$this->post_type = $post_types = get_post_types( $args, $output, $operator ); 
			$this->post_type['page'] = "Page";
			$this->post_type['post'] = "Post";
				
			
	  
  }
  
  public function get_pages() {
	   $args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'page',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'	   => '',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts_array = get_posts( $args );
		foreach ( $posts_array as $mypost ) {
			$this->all_pages[$mypost->ID] = $mypost->post_title;			
		}  
  }
  
  public function get_posts() {
	   $args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'author'	   => '',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts_array = get_posts( $args );
		foreach ( $posts_array as $mypost ) {
			$this->all_post[$mypost->ID] = $mypost->post_title;			
		}  
  }
  
  
  public function settings_page_init() {
    global $WE_Google_Analytics_Pro;    
    $settings_tab_options = array("tab" => "{$this->tab}",
                                  "ref" => &$this,
                                  "sections" => array(
                                                      "main_settings_section" => array("title" =>  __('Multiple Google Analytics Settings', $WE_Google_Analytics_Pro->text_domain), 
                                                                                         "fields" => array(  
																						 "is_enable" => array('title' => __('Enable', $WE_Google_Analytics_Pro->text_domain), 'type' => 'checkbox', 'id' => 'is_enable', 'label_for' => 'is_enable', 'name' => 'is_enable', 'value' => 'Enable'),                                            
                                                                                                           "ga_tracking" => array('title' => __('GA Tracking Code', $WE_Google_Analytics_Pro->text_domain) , 'type' => 'multiinput', 'id' => 'ga_tracking', 'label_for' => 'ga_tracking', 'name' => 'ga_tracking', 'options' => array(
                                                                                                               "title" => array('label' => __('Title', $WE_Google_Analytics_Pro->text_domain) , 'type' => 'text', 'label_for' => 'title', 'name' => 'title', 'class' => 'regular-text'),
																											   "gacode" => array('label' => __('GA Code', $WE_Google_Analytics_Pro->text_domain) , 'type' => 'text', 'label_for' => 'gacode', 'name' => 'gacode', 'class' => 'regular-text'),
                                                                                                               "post_types" => array('label' => __('Choose post type where given google analytics will work', $WE_Google_Analytics_Pro->text_domain),  'type' => 'select', 'id' => 'post_types', 'label_for' => 'post_types', 'name' => 'post_types', 'desc' => __('Choose post types where given google analytics will work', $WE_Google_Analytics_Pro->text_domain), 'hints' => __('Choose post types where given google analytics will work.', $WE_Google_Analytics_Pro->text_domain),  'options'=>$this->post_type),
                                                                                                               
                                                                                                               
                                                                                                               )
                                                                                                             ), 
																										                                                                             
                                                                                                          
                                                                                                           )
                                                                                         ) 
                                                    
                                                      )
                                  );
    
    $WE_Google_Analytics_Pro->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
  }

 
  public function we_we_google_analytics_pro_gacode_settings_sanitize( $input ) {
    global $WE_Google_Analytics_Pro;
    $new_input = array();    
    $hasError = false; 

    if( isset( $input['is_enable'] ) )
      $new_input['is_enable'] = sanitize_text_field( $input['is_enable'] );
	if( isset( $input['ga_tracking'] ) )
      $new_input['ga_tracking'] =  $input['ga_tracking']; 
	
	  
	      
    if(!$hasError) {
      add_settings_error(
        "we_{$this->tab}_settings_name",
        esc_attr( "we_{$this->tab}_settings_admin_updated" ),
        __('Settings updated', $WE_Google_Analytics_Pro->text_domain),
        'updated'
      );
    }

    return $new_input;
  }

  
  public function main_settings_section_info() {
    global $WE_Google_Analytics_Pro;
    _e('Enter your settings below', $WE_Google_Analytics_Pro->text_domain);
  } 
  
}