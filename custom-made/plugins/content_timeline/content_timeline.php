<?php
/* Content Timeline support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('custom_made_content_timeline_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_content_timeline_theme_setup9', 9 );
	function custom_made_content_timeline_theme_setup9() {
		
		if (custom_made_exists_content_timeline()) {
			add_action( 'wp_enqueue_scripts', 										'custom_made_content_timeline_frontend_scripts', 1100 );
			add_filter( 'custom_made_filter_merge_styles',						'custom_made_content_timeline_merge_styles' );
			add_filter( 'custom_made_filter_get_css',							'custom_made_content_timeline_get_css', 10, 3 );
		}
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',			'custom_made_content_timeline_tgmpa_required_plugins' );
		}

	}
}

// Check if plugin is installed and activated
if ( !function_exists( 'custom_made_exists_content_timeline' ) ) {
	function custom_made_exists_content_timeline() {
		return class_exists( 'ContentTimelineAdmin' );
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'custom_made_content_timeline_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'custom_made_content_timeline_frontend_scripts', 1100 );
	function custom_made_content_timeline_frontend_scripts() {
		if ( custom_made_is_on(custom_made_get_theme_option('debug_mode')) && file_exists(custom_made_get_file_dir('plugins/content_timeline/content_timeline.css')))
			wp_enqueue_style( 'custom-made-content-timeline',  custom_made_get_file_url('plugins/content_timeline/content_timeline.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'custom_made_content_timeline_merge_styles' ) ) {
	//Handler of the add_filter('custom_made_filter_merge_styles', 'custom_made_content_timeline_merge_styles');
	function custom_made_content_timeline_merge_styles($list) {
		$list[] = 'plugins/content_timeline/content_timeline.css';
		return $list;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_content_timeline_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_content_timeline_tgmpa_required_plugins');
	function custom_made_content_timeline_tgmpa_required_plugins($list=array()) {
		if (in_array('content_timeline', custom_made_storage_get('required_plugins'))) {
			$list[] = array(
						'name' 		=> esc_html__('Content Timeline', 'custom-made'),
						'slug' 		=> 'content_timeline',
						'source'	=> 'upload://content_timeline.zip',
						'required' 	=> false
				);
		}
		return $list;
	}
}


// Add plugin's specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'custom_made_content_timeline_get_css' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_css', 'custom_made_content_timeline_get_css', 10, 3 );
	function custom_made_content_timeline_get_css($css, $colors, $fonts) {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
			
.timeline.my_style_style_4 .t_line_month,
.timeline.my_style_style_4 a.t_line_node,
.timeline.my_style_style_4 .item .my_timeline_content .read_more {
	{$fonts['p_font-family']}
}

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Timeline */
.timeline.my_style_style_4 .timeline_line {
	background-color: {$colors['text_dark']};
	color: {$colors['text']};
}
.timeline.my_style_style_4 .t_line_month {
	color: {$colors['bg_color']};
}
.timeline.my_style_style_4 .t_line_wrapper:after {
	border-bottom-color: {$colors['text_link']} !important;
}
#tl1.timeline.my_style_style_4 .t_line_node {
	color: {$colors['text']} !important;
}
#tl1.timeline.my_style_style_4 .t_line_node.active {
	color: {$colors['bg_color']} !important;
}
#tl1.timeline.my_style_style_4 .t_line_node:after {
	background: {$colors['text_link']} !important;
}
#tl1.timeline.my_style_style_4 .t_line_node.active:after {
	border-color: {$colors['text_link']} !important;
	background: {$colors['text_dark']} !important;
}
#tl1.timeline.my_style_style_4 #t_line_left, 
#tl1.timeline.my_style_style_4 #t_line_right {
	color: {$colors['text_link']} !important;
}
#tl1.timeline.my_style_style_4 #t_line_left:hover, 
#tl1.timeline.my_style_style_4 #t_line_right:hover {
	color: {$colors['bg_color']} !important;
}
.timeline.my_style_style_4 .t_node_desc {
	color: {$colors['bg_color']};
}
.timeline.my_style_style_4 .t_node_desc span {
	background: {$colors['text_link']} !important;
}
#tl1.timeline.my_style_style_4 .t_node_desc span:after {
	border-top-color: {$colors['text_link']};
}

/* Items */
.timeline.my_style_style_4 .item {
	background: {$colors['bg_color']} !important;
}
.timeline.my_style_style_4 .timeline_items_wrapper .item h2 {
	color: {$colors['text_dark']} !important;
}
.timeline.my_style_style_4 .my_post_date {
	color: {$colors['text_light']} !important;
}
.timeline.my_style_style_4 .my_timeline_content span {
	color: {$colors['text']} !important;
}
#tl1.timeline.my_style_style_4 .my_timeline_content .read_more {
	color: {$colors['text_link']} !important;
}
#tl1.timeline.my_style_style_4 .my_timeline_content .read_more:hover {
	color: {$colors['text_dark']} !important;
}
#tl1.timeline.my_style_style_4 .item_node_hover:before {
	background-color: {$colors['text_link']} !important;
}
#tl1.timeline.my_style_style_4 .item_node_hover:after {
	border-top-color: {$colors['text_link']} !important;
}

CSS;
		}
		
		return $css;
	}
}
?>