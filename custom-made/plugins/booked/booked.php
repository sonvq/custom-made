<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('custom_made_booked_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_booked_theme_setup9', 9 );
	function custom_made_booked_theme_setup9() {
		if (custom_made_exists_booked()) {
			add_action( 'wp_enqueue_scripts', 							'custom_made_booked_frontend_scripts', 1100 );
			add_filter( 'custom_made_filter_merge_styles',					'custom_made_booked_merge_styles' );
			add_filter( 'custom_made_filter_get_css',						'custom_made_booked_get_css', 10, 3 );
		}
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',		'custom_made_booked_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'custom_made_exists_booked' ) ) {
	function custom_made_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_booked_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_booked_tgmpa_required_plugins');
	function custom_made_booked_tgmpa_required_plugins($list=array()) {
		if (in_array('booked', custom_made_storage_get('required_plugins'))) {
			$list[] = array(
					'name' 		=> esc_html__('Booked Appointments', 'custom-made'),
					'slug' 		=> 'booked',
					'source' 	=> 'upload://booked.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'custom_made_booked_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'custom_made_booked_frontend_scripts', 1100 );
	function custom_made_booked_frontend_scripts() {
		if (custom_made_is_on(custom_made_get_theme_option('debug_mode')) && file_exists(custom_made_get_file_dir('plugins/booked/booked.css')))
			wp_enqueue_style( 'custom-made-booked',  custom_made_get_file_url('plugins/booked/booked.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'custom_made_booked_merge_styles' ) ) {
	//Handler of the add_filter('custom_made_filter_merge_styles', 'custom_made_booked_merge_styles');
	function custom_made_booked_merge_styles($list) {
		$list[] = 'plugins/booked/booked.css';
		return $list;
	}
}



// Add plugin's specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'custom_made_booked_get_css' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_css', 'custom_made_booked_get_css', 10, 3 );
	function custom_made_booked_get_css($css, $colors, $fonts) {
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