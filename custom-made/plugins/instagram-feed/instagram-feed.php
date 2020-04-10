<?php
/* Custom Feeds for Instagram support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('custom_made_instagram_feed_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_instagram_feed_theme_setup9', 9 );
	function custom_made_instagram_feed_theme_setup9() {
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',		'custom_made_instagram_feed_tgmpa_required_plugins' );
		}
	}
}

// Check if Custom Feeds for Instagram installed and activated
if ( !function_exists( 'custom_made_exists_instagram_feed' ) ) {
	function custom_made_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_instagram_feed_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_instagram_feed_tgmpa_required_plugins');
	function custom_made_instagram_feed_tgmpa_required_plugins($list=array()) {
		if (in_array('instagram-feed', custom_made_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Custom Feeds for Instagram', 'custom-made'),
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}
?>