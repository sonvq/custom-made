<?php
/* Envato WordPress Toolkit support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('custom_made_envato_wordpress_toolkit_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_envato_wordpress_toolkit_theme_setup9', 9 );
	function custom_made_envato_wordpress_toolkit_theme_setup9() {
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',		'custom_made_envato_wordpress_toolkit_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'custom_made_exists_envato_wordpress_toolkit' ) ) {
	function custom_made_exists_envato_wordpress_toolkit() {
		return class_exists('Envato_WP_Toolkit');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_envato_wordpress_toolkit_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_envato_wordpress_toolkit_tgmpa_required_plugins');
	function custom_made_envato_wordpress_toolkit_tgmpa_required_plugins($list=array()) {
		if (in_array('envato-wordpress-toolkit', custom_made_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Envato WordPress Toolkit', 'custom-made'),
					'slug' 		=> 'envato-wordpress-toolkit',
					'source' 	=> 'upload://envato-wordpress-toolkit.zip',
					'required' 	=> false
			);
		return $list;
	}
}
?>