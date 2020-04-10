<?php
/* Ubermenu support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('custom_made_ubermenu_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_ubermenu_theme_setup9', 9 );
	function custom_made_ubermenu_theme_setup9() {
		if (custom_made_exists_ubermenu()) {
			add_action( 'wp_enqueue_scripts', 							'custom_made_ubermenu_frontend_scripts', 1100 );
			add_filter( 'custom_made_filter_merge_styles',					'custom_made_ubermenu_merge_styles' );
			add_filter( 'custom_made_filter_get_css',						'custom_made_ubermenu_get_css', 10, 3 );
			if (!is_admin()) {
				add_filter( 'custom_made_filter_header_parts',				'custom_made_ubermenu_header_parts', 10, 2 );
				add_filter( 'custom_made_filter_menu_mobile_layout',		'custom_made_ubermenu_menu_mobile_layout' );
				add_filter( 'body_class',								'custom_made_ubermenu_add_body_classes' );
			}
		}
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',		'custom_made_ubermenu_tgmpa_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'custom_made_exists_ubermenu' ) ) {
	function custom_made_exists_ubermenu() {
		return class_exists('UberMenu');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_ubermenu_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_ubermenu_tgmpa_required_plugins');
	function custom_made_ubermenu_tgmpa_required_plugins($list=array()) {
		if (in_array('ubermenu', custom_made_storage_get('required_plugins'))) {
			$list[] = array(
					'name' 		=> esc_html__('UberMenu', 'custom-made'),
					'slug' 		=> 'ubermenu',
					'source' 	=> 'upload://ubermenu.zip',
					'required' 	=> false
			);
		}
		return $list;
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'custom_made_ubermenu_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'custom_made_ubermenu_frontend_scripts', 1100 );
	function custom_made_ubermenu_frontend_scripts() {
		if (custom_made_is_on(custom_made_get_theme_option('debug_mode')) && file_exists(custom_made_get_file_dir('plugins/ubermenu/ubermenu.css')))
			wp_enqueue_style( 'custom-made-ubermenu',  custom_made_get_file_url('plugins/ubermenu/ubermenu.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'custom_made_ubermenu_merge_styles' ) ) {
	//Handler of the add_filter('custom_made_filter_merge_styles', 'custom_made_ubermenu_merge_styles');
	function custom_made_ubermenu_merge_styles($list) {
		$list[] = 'plugins/ubermenu/ubermenu.css';
		return $list;
	}
}
	
// Disable Logo and Search in the header if the UberMenu active in the 'menu_main' theme location
if ( !function_exists( 'custom_made_ubermenu_header_parts' ) ) {
	//Handler of the add_filter( 'custom_made_filter_header_parts', 'custom_made_ubermenu_header_parts', 10, 2 );
	function custom_made_ubermenu_header_parts($parts, $loc) {
		if (custom_made_ubermenu_check_location($loc)) {
			$parts['logo'] = false;
			$parts['search'] = false;
		}
		return $parts;
	}
}
	
// Return true if theme location assigned to UberMenu
if ( !function_exists( 'custom_made_ubermenu_check_location' ) ) {
	function custom_made_ubermenu_check_location($loc) {
		$rez = false;
		if (custom_made_exists_ubermenu()) {
			$theme_loc = ubermenu_op( 'auto_theme_location', 'main' );
			$rez = !empty($theme_loc[$loc]);
		}
		return $rez;
	}
}

// Add plugin specific classes to the body
if ( !function_exists('custom_made_ubermenu_add_body_classes') ) {
	//Handler of the add_filter( 'body_class', 'custom_made_ubermenu_add_body_classes' );
	function custom_made_ubermenu_add_body_classes( $classes ) {
		if (custom_made_exists_ubermenu()) {
			$auto_theme_locations = ubermenu_op( 'auto_theme_location', 'main' );
			if( is_array( $auto_theme_locations ) ){
				foreach( $auto_theme_locations as $loc ){
					$classes[] = 'ubermenu_' . esc_attr($loc);
				}
			}
			$classes[] = 'ubermenu_responsive_' . esc_attr(ubermenu_op( 'responsive', 'main' ));
		}
		return $classes;
	}
}

// Add plugin specific classes to the mobile menu layout
if ( !function_exists('custom_made_ubermenu_menu_mobile_layout') ) {
	//Handler of the add_filter( 'custom_made_filter_menu_mobile_layout', 'custom_made_ubermenu_menu_mobile_layout' );
	function custom_made_ubermenu_menu_mobile_layout( $layout ) {
		if (custom_made_exists_ubermenu()) {
			$layout = str_replace(
					array('class="ubermenu ', 'ubermenu-nav', 'ubermenu-item-has-children', 'ubermenu'),
					array('class="menu_mobile_nav_area ', 'menu_mobile_nav', 'menu-item-has-children', 'menu_mobile'),
					$layout
					);
		}
		return $layout;
	}
}


// Add plugin's specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'custom_made_ubermenu_get_css' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_css', 'custom_made_ubermenu_get_css', 10, 3 );
	function custom_made_ubermenu_get_css($css, $colors, $fonts) {
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