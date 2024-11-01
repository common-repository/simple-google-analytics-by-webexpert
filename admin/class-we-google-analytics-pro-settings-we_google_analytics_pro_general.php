<?php
class WE_Google_Analytics_Pro_Settings_Gneral {
 
  private $options;  
  private $tab; 
  public $all_cat = array(); 
  public $all_pages = array(); 
  public $all_post = array();
  public function __construct($tab) {
    $this->tab = $tab;
	$this->get_category();
	$this->get_pages();
	$this->get_posts();
    $this->options = get_option( "we_{$this->tab}_settings_name" );
    $this->settings_page_init();
  }
  public function get_category(){
	  $taxonomies = array( 
			'category',			
		);		
		$args = array(
			'orderby'           => 'name', 
			'order'             => 'ASC',
			'hide_empty'        => true, 
			'exclude'           => array(), 
			'exclude_tree'      => array(), 
			'include'           => array(),
			'number'            => '', 
			'fields'            => 'all', 
			'slug'              => '',
			'parent'            => '',
			'hierarchical'      => true, 
			'child_of'          => 0,
			'childless'         => false,
			'get'               => '', 
			'name__like'        => '',
			'description__like' => '',
			'pad_counts'        => false, 
			'offset'            => '', 
			'search'            => '', 
			'cache_domain'      => 'core'
		); 		
		$terms = get_terms($taxonomies, $args);
		foreach ( $terms as $term ) {
			$this->all_cat[$term->term_id] = $term->name;			
		}	  
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
                                                      "main_settings_section" => array("title" =>  __('Google Analytics Settings', $WE_Google_Analytics_Pro->text_domain), 
                                                                                         "fields" => array(                                              
                                                                                                           "is_enable" => array('title' => __('Enable', $WE_Google_Analytics_Pro->text_domain), 'type' => 'checkbox', 'id' => 'is_enable', 'label_for' => 'is_enable', 'name' => 'is_enable', 'value' => 'Enable'), 
																										    "ga_code" => array('title' => __('Global GA Tracking Code', $WE_Google_Analytics_Pro->text_domain), 'type' => 'text', 'id' => 'ga_code', 'label_for' => 'ga_code', 'name' => 'ga_code', 'placeholder'=>'UA-41954408-1
', 'hints' => __('Enter your GA Tracking Code here.', $WE_Google_Analytics_Pro->text_domain), 'desc' => __('This is GA Tracking Code you will find in your GA Account.', $WE_Google_Analytics_Pro->text_domain)),

"mycategory_list" => array('title' => __('Choose categories Excluded from GA', $WE_Google_Analytics_Pro->text_domain),  'type' => 'multiselect', 'id' => 'mycategory_list', 'label_for' => 'mycategory_list', 'name' => 'mycategory_list', 'desc' => __('select the category will be excluded from the GA tracking', $WE_Google_Analytics_Pro->text_domain), 'hints' => __('select the category will be excluded from the GA tracking.', $WE_Google_Analytics_Pro->text_domain),  'options'=>$this->all_cat),

"mypage_list" => array('title' => __('Choose pages Excluded from GA', $WE_Google_Analytics_Pro->text_domain),  'type' => 'multiselect', 'id' => 'mypage_list', 'label_for' => 'mypage_list', 'name' => 'mypage_list', 'desc' => __('select the pages will be excluded from the GA tracking', $WE_Google_Analytics_Pro->text_domain), 'hints' => __('select the pages will be excluded from the GA tracking.', $WE_Google_Analytics_Pro->text_domain),  'options'=>$this->all_pages), 

"mypost_list" => array('title' => __('Choose posts Excluded from GA', $WE_Google_Analytics_Pro->text_domain),  'type' => 'multiselect', 'id' => 'mypost_list', 'label_for' => 'mypost_list', 'name' => 'mypost_list', 'desc' => __('select the posts will be excluded from the GA tracking', $WE_Google_Analytics_Pro->text_domain), 'hints' => __('select the posts will be excluded from the GA tracking.', $WE_Google_Analytics_Pro->text_domain),  'options'=>$this->all_post),                                                                          
                                                                                                          
                                                                                                           )
                                                                                         ) 
                                                    
                                                      )
                                  );
    
    $WE_Google_Analytics_Pro->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
  }

 
  public function we_we_google_analytics_pro_general_settings_sanitize( $input ) {
    global $WE_Google_Analytics_Pro;
    $new_input = array();    
    $hasError = false; 

    if( isset( $input['is_enable'] ) )
      $new_input['is_enable'] = sanitize_text_field( $input['is_enable'] );
	if( isset( $input['mycategory_list'] ) )
      $new_input['mycategory_list'] =  $input['mycategory_list']; 
	if( isset( $input['mypage_list'] ) )
      $new_input['mypage_list'] = $input['mypage_list'];
	if( isset( $input['mypost_list'] ) )
      $new_input['mypost_list'] = $input['mypost_list'];    
    if( isset( $input['ga_code'] ) )
      $new_input['ga_code'] = ( $input['ga_code'] );
	  
	      
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