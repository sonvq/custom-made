<?php
/* Custom Made Donations support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('custom_made_trx_donations_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_trx_donations_theme_setup9', 9 );
	function custom_made_trx_donations_theme_setup9() {
		
		if (custom_made_exists_trx_donations()) {
			add_action( 'wp_enqueue_scripts', 								'custom_made_trx_donations_frontend_scripts', 1100 );
			add_filter( 'custom_made_filter_merge_styles',						'custom_made_trx_donations_merge_styles' );
			add_filter( 'custom_made_filter_get_css',							'custom_made_trx_donations_get_css', 10, 3 );
			add_filter( 'custom_made_filter_get_post_info',		 				'custom_made_trx_donations_get_post_info');
			add_filter( 'custom_made_filter_post_type_taxonomy',				'custom_made_trx_donations_post_type_taxonomy', 10, 2 );
			if (!is_admin()) {
				add_filter( 'custom_made_filter_detect_blog_mode',				'custom_made_trx_donations_detect_blog_mode' );
				add_filter( 'custom_made_filter_get_blog_all_posts_link', 		'custom_made_trx_donations_get_blog_all_posts_link');
				add_filter( 'custom_made_filter_get_blog_title', 				'custom_made_trx_donations_get_blog_title');
				add_filter( 'custom_made_filter_need_page_title', 				'custom_made_trx_donations_need_page_title');
				add_filter( 'custom_made_filter_sidebar_present',				'custom_made_trx_donations_sidebar_present' );
				add_filter( 'custom_made_filter_get_post_categories', 			'custom_made_trx_donations_get_post_categories');
				add_action( 'custom_made_action_before_post_meta',				'custom_made_trx_donations_action_before_post_meta');
			}
		}
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',			'custom_made_trx_donations_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_trx_donations_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_trx_donations_tgmpa_required_plugins');
	function custom_made_trx_donations_tgmpa_required_plugins($list=array()) {
		if (in_array('trx_donations', custom_made_storage_get('required_plugins'))) {
			$list[] = array(
					'name' 		=> esc_html__('Custom Made Donations', 'custom-made'),
					'slug' 		=> 'trx_donations',
					'version'	=> '1.3',
					'source'	=> 'upload://trx_donations.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}



// Check if trx_donations installed and activated
if ( !function_exists( 'custom_made_exists_trx_donations' ) ) {
	function custom_made_exists_trx_donations() {
		return class_exists('TRX_DONATIONS');
	}
}

// Return true, if current page is any trx_donations page
if ( !function_exists( 'custom_made_is_trx_donations_page' ) ) {
	function custom_made_is_trx_donations_page() {
		$rez = false;
		if (custom_made_exists_trx_donations()) {
			$rez = (is_single() && get_query_var('post_type') == TRX_DONATIONS::POST_TYPE) 
					|| is_post_type_archive(TRX_DONATIONS::POST_TYPE) 
					|| is_tax(TRX_DONATIONS::TAXONOMY);
		}
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'custom_made_trx_donations_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'custom_made_filter_detect_blog_mode', 'custom_made_trx_donations_detect_blog_mode' );
	function custom_made_trx_donations_detect_blog_mode($mode='') {
		if (custom_made_is_trx_donations_page())
			$mode = 'donations';
		return $mode;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'custom_made_trx_donations_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'custom_made_filter_post_type_taxonomy',	'custom_made_trx_donations_post_type_taxonomy', 10, 2 );
	function custom_made_trx_donations_post_type_taxonomy($tax='', $post_type='') {
		if (custom_made_exists_trx_donations() && $post_type == TRX_DONATIONS::POST_TYPE)
			$tax = TRX_DONATIONS::TAXONOMY;
		return $tax;
	}
}

// Return current page title
if ( !function_exists( 'custom_made_trx_donations_get_blog_title' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_blog_title', 'custom_made_trx_donations_get_blog_title');
	function custom_made_trx_donations_get_blog_title($title='') {
		if ( is_post_type_archive(TRX_DONATIONS::POST_TYPE) )
			$title = esc_html__('All Donations', 'custom-made');
		return $title;
	}
}

// Return link to the all donations page for the breadcrumbs
if ( !function_exists( 'custom_made_trx_donations_get_blog_all_posts_link' ) ) {
	//Handler of the add_filter('custom_made_filter_get_blog_all_posts_link', 'custom_made_trx_donations_get_blog_all_posts_link');
	function custom_made_trx_donations_get_blog_all_posts_link($link='') {
		if ($link=='') {
			if (custom_made_is_trx_donations_page() && !is_post_type_archive(TRX_DONATIONS::POST_TYPE))
				$link = '<a href="'.esc_url(get_post_type_archive_link( TRX_DONATIONS::POST_TYPE )).'">'.esc_html__('All Donations', 'custom-made').'</a>';
		}
		return $link;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'custom_made_trx_donations_need_page_title' ) ) {
	//Handler of the add_filter('custom_made_filter_need_page_title', 'custom_made_trx_donations_need_page_title');
	function custom_made_trx_donations_need_page_title($need=false) {
		if (!$need)
			$need = custom_made_is_trx_donations_page();
		return $need;
	}
}

// Show categories of the current product
if ( !function_exists( 'custom_made_trx_donations_get_post_categories' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_post_categories', 		'custom_made_trx_donations_get_post_categories');
	function custom_made_trx_donations_get_post_categories($cats='') {
		if ( custom_made_exists_trx_donations() && get_post_type()==TRX_DONATIONS::POST_TYPE ) {
			$cats = custom_made_get_post_terms(', ', get_the_ID(), TRX_DONATIONS::TAXONOMY);
		}
		return $cats;
	}
}

// Show price of the current product in the widgets and search results
if ( !function_exists( 'custom_made_trx_donations_get_post_info' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_post_info', 'custom_made_trx_donations_get_post_info');
	function custom_made_trx_donations_get_post_info($post_info='') {
		if (custom_made_exists_trx_donations()) {
			if (get_post_type()==TRX_DONATIONS::POST_TYPE) {
				// Goal and raised
				$goal = get_post_meta( get_the_ID(), 'trx_donations_goal', true );
				if (!empty($goal)) {
					$raised = get_post_meta( get_the_ID(), 'trx_donations_raised', true );
					if (empty($raised)) $raised = 0;
					$manual = get_post_meta( get_the_ID(), 'trx_donations_manual', true );
					$plugin = TRX_DONATIONS::get_instance();
					$post_info .= '<div class="post_info post_meta post_donation_info">'
										. '<span class="post_info_item post_meta_item post_donation_item post_donation_goal">'
											. '<span class="post_info_label post_meta_label post_donation_label">' . esc_html__('Group goal:', 'custom-made') . '</span>'
											. ' ' 
											. '<span class="post_info_number post_meta_number post_donation_number">' . trim($plugin->get_money($goal)) . '</span>'
										. '</span>'
										. '<span class="post_info_item post_meta_item post_donation_item post_donation_raised">'
											. '<span class="post_info_label post_meta_label post_donation_label">' . esc_html__('Raised:', 'custom-made') . '</span>'
											. ' '
											. '<span class="post_info_number post_meta_number post_donation_number">' . trim($plugin->get_money($raised+$manual)) . ' (' . round(($raised+$manual)*100/$goal, 2) . '%)' . '</span>'
										. '</span>'
									. '</div>';
				}
			}
		}
		return $post_info;
	}
}

// Show price of the current product in the search results streampage
if ( !function_exists( 'custom_made_trx_donations_action_before_post_meta' ) ) {
	//Handler of the add_action( 'custom_made_action_before_post_meta', 'custom_made_trx_donations_action_before_post_meta');
	function custom_made_trx_donations_action_before_post_meta() {
		custom_made_show_layout(custom_made_trx_donations_get_post_info());
	}
}
	
// Enqueue trx_donations custom styles
if ( !function_exists( 'custom_made_trx_donations_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'custom_made_trx_donations_frontend_scripts', 1100 );
	function custom_made_trx_donations_frontend_scripts() {
		if (custom_made_is_on(custom_made_get_theme_option('debug_mode')) && file_exists(custom_made_get_file_dir('plugins/trx_donations/trx_donations.css')))
			wp_enqueue_style( 'custom-made-trx-donations',  custom_made_get_file_url('plugins/trx_donations/trx_donations.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'custom_made_trx_donations_merge_styles' ) ) {
	//Handler of the add_filter('custom_made_filter_merge_styles', 'custom_made_trx_donations_merge_styles');
	function custom_made_trx_donations_merge_styles($list) {
		$list[] = 'plugins/trx_donations/trx_donations.css';
		return $list;
	}
}



// Decorate trx_donations output: Single product
//------------------------------------------------------------------------

// Hide sidebar on the single products and pages
if ( !function_exists( 'custom_made_trx_donations_sidebar_present' ) ) {
	//Handler of the add_action( 'custom_made_filter_sidebar_present', 'custom_made_trx_donations_sidebar_present' );
	function custom_made_trx_donations_sidebar_present($present) {
		return is_single() && custom_made_is_trx_donations_page() ? false : $present;
	}
}



// Add trx_donations specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'custom_made_trx_donations_get_css' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_css', 'custom_made_trx_donations_get_css', 10, 3 );
	function custom_made_trx_donations_get_css($css, $colors, $fonts) {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

CSS;
		}
		
		return $css;
	}
}
?>