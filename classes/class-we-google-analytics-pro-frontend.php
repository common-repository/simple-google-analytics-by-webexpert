<?php
class WE_Google_Analytics_Pro_Frontend {

	public function __construct() {
		$settings = get_option('we_we_google_analytics_pro_general_settings_name');
		if(isset($settings['is_enable']) &&  $settings['is_enable'] == "Enable") {			
			add_action('wp_head',array($this,'add_ga_code'),100);
			add_action('wp_head',array($this,'add_ga_code_multiple'),101);
		}
	}
	public function  add_ga_code(){
		global $post;
		$settings = get_option('we_we_google_analytics_pro_general_settings_name');
		$show =1;
		
		$exclusion_list_cat = $settings['mycategory_list'];
		$exclusion_list_post = $settings['mypost_list'];
		$exclusion_list_page = $settings['mypage_list'];		
		$category_id = is_category( $category );
		if($category_id ) {
			if(isset($exclusion_list_cat) && is_array($exclusion_list_cat) && (!empty($exclusion_list_cat))) {
				if(in_array($category_id,$exclusion_list_cat)) {
					$show = 0;
				}				
			}		
		}
		if(is_single()) {
			$posttype = get_post_type( );
			if($posttype == "post") {
				$post_id = $post->ID;
				if($post_id ) {
					if(isset($exclusion_list_post) && is_array($exclusion_list_post) && (!empty($exclusion_list_post))) {
						if(in_array($post_id,$exclusion_list_post)) {
							$show = 0;
						}				
					}		
				}
				
			}			
		}
		
		if(is_page()) {
			$posttype = get_post_type( );
			if($posttype == "page") {
				$page_id = $post->ID;
				if($page_id ) {
					if(isset($exclusion_list_page) && is_array($exclusion_list_page) && (!empty($exclusion_list_page))) {
						if(in_array($page_id,$exclusion_list_page)) {
							$show = 0;
						}				
					}		
				}				
			}			
		}
		
		
				
		if($show == 1) {
			if(isset($settings['ga_code']) && (!empty($settings['ga_code']))){
		?>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');		
		  ga('create', '<?php echo $settings['ga_code']; ?>', 'auto');
		  ga('send', 'pageview');		
		</script>		
		<?php } } }
		
		public function add_ga_code_multiple() {
			global $post;
			$settings = get_option('we_we_google_analytics_pro_gacode_settings_name');
			
			if(isset($settings['is_enable']) &&  $settings['is_enable'] == "Enable") {
				if(isset($settings['ga_tracking']) &&  is_array($settings['ga_tracking']) && !empty($settings['ga_tracking'])) {
				  $ga_tracking_arr = $settings['ga_tracking'];
				  foreach( $ga_tracking_arr as $ga_tracking ) {
					 $geocode = $ga_tracking['gacode'];
					 if(isset($post->post_type) && $post->post_type == $ga_tracking['post_types']){
						 ?>
						<script>
						  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
						  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
						  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
						  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');		
						  ga('create', '<?php echo $geocode; ?>', 'auto');
						  ga('send', 'pageview');		
						</script>		
						<?php
						 
					 }
				  }
				}
				
			}
			
		}
	
}
