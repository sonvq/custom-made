<?php
/* Tribe Events Calendar support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('custom_made_tribe_events_theme_setup1')) {
	add_action( 'after_setup_theme', 'custom_made_tribe_events_theme_setup1', 1 );
	function custom_made_tribe_events_theme_setup1() {
		add_filter( 'custom_made_filter_list_sidebars', 'custom_made_tribe_events_list_sidebars' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('custom_made_tribe_events_theme_setup3')) {
	add_action( 'after_setup_theme', 'custom_made_tribe_events_theme_setup3', 3 );
	function custom_made_tribe_events_theme_setup3() {
		if (custom_made_exists_tribe_events()) {
			custom_made_storage_merge_array('options', '', array(
				// Section 'Tribe Events' - settings for show pages
				'events' => array(
					"title" => esc_html__('Events', 'custom-made'),
					"desc" => wp_kses_data( __('Select parameters to display the events pages', 'custom-made') ),
					"type" => "section"
					),
				'expand_content_events' => array(
					"title" => esc_html__('Expand content', 'custom-made'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'custom-made') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'header_widgets_events' => array(
					"title" => esc_html__('Header widgets', 'custom-made'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the events pages', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'sidebar_widgets_events' => array(
					"title" => esc_html__('Sidebar widgets', 'custom-made'),
					"desc" => wp_kses_data( __('Select sidebar to show on the events pages', 'custom-made') ),
					"std" => 'tribe_events_widgets',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'sidebar_position_events' => array(
					"title" => esc_html__('Sidebar position', 'custom-made'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the events pages', 'custom-made') ),
					"refresh" => false,
					"std" => 'left',
					"options" => custom_made_get_list_sidebars_positions(),
					"type" => "select"
					),
				'widgets_above_page_events' => array(
					"title" => esc_html__('Widgets above the page', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_above_content_events' => array(
					"title" => esc_html__('Widgets above the content', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_below_content_events' => array(
					"title" => esc_html__('Widgets below the content', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_below_page_events' => array(
					"title" => esc_html__('Widgets below the page', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'footer_scheme_events' => array(
					"title" => esc_html__('Footer Color Scheme', 'custom-made'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'custom-made') ),
					"std" => 'dark',
					"options" => custom_made_get_list_schemes(true),
					"type" => "select"
					),
				'footer_widgets_events' => array(
					"title" => esc_html__('Footer widgets', 'custom-made'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'custom-made') ),
					"std" => 'footer_widgets',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'footer_columns_events' => array(
					"title" => esc_html__('Footer columns', 'custom-made'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'custom-made') ),
					"dependency" => array(
						'footer_widgets_events' => array('^hide')
					),
					"std" => 0,
					"options" => custom_made_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_events' => array(
					"title" => esc_html__('Footer fullwide', 'custom-made'),
					"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'custom-made') ),
					"std" => 0,
					"type" => "checkbox"
					)
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('custom_made_tribe_events_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_tribe_events_theme_setup9', 9 );
	function custom_made_tribe_events_theme_setup9() {
		
		if (custom_made_exists_tribe_events()) {
			add_action( 'wp_enqueue_scripts', 								'custom_made_tribe_events_frontend_scripts', 1100 );
			add_filter( 'custom_made_filter_merge_styles',						'custom_made_tribe_events_merge_styles' );
			add_filter( 'custom_made_filter_get_css',							'custom_made_tribe_events_get_css', 10, 3 );
			add_filter( 'custom_made_filter_post_type_taxonomy',				'custom_made_tribe_events_post_type_taxonomy', 10, 2 );
			if (!is_admin()) {
				add_filter( 'custom_made_filter_detect_blog_mode',				'custom_made_tribe_events_detect_blog_mode' );
				add_filter( 'custom_made_filter_get_blog_all_posts_link', 		'custom_made_tribe_events_get_blog_all_posts_link');
				add_filter( 'custom_made_filter_get_blog_title', 				'custom_made_tribe_events_get_blog_title');
				add_filter( 'custom_made_filter_need_page_title', 				'custom_made_tribe_events_need_page_title');
				add_filter( 'custom_made_filter_get_post_categories', 			'custom_made_tribe_events_get_post_categories');
				add_filter( 'custom_made_filter_get_post_date',		 			'custom_made_tribe_events_get_post_date');
			} else {
				add_action( 'admin_enqueue_scripts',						'custom_made_tribe_events_admin_scripts' );
			}
		}
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',			'custom_made_tribe_events_tgmpa_required_plugins' );
		}

	}
}



// Check if Tribe Events is installed and activated
if ( !function_exists( 'custom_made_exists_tribe_events' ) ) {
	function custom_made_exists_tribe_events() {
		return class_exists( 'Tribe__Events__Main' );
	}
}

// Return true, if current page is any tribe_events page
if ( !function_exists( 'custom_made_is_tribe_events_page' ) ) {
	function custom_made_is_tribe_events_page() {
		$rez = false;
		if (custom_made_exists_tribe_events())
			if (!is_search()) $rez = tribe_is_event() || tribe_is_event_query() || tribe_is_event_category() || tribe_is_event_venue() || tribe_is_event_organizer();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'custom_made_tribe_events_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'custom_made_filter_detect_blog_mode', 'custom_made_tribe_events_detect_blog_mode' );
	function custom_made_tribe_events_detect_blog_mode($mode='') {
		if (custom_made_is_tribe_events_page())
			$mode = 'events';
		return $mode;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'custom_made_tribe_events_post_type_taxonomy' ) ) {
	//Handler of the add_filter( 'custom_made_filter_post_type_taxonomy',	'custom_made_tribe_events_post_type_taxonomy', 10, 2 );
	function custom_made_tribe_events_post_type_taxonomy($tax='', $post_type='') {
		if (custom_made_exists_tribe_events() && $post_type == Tribe__Events__Main::POSTTYPE)
			$tax = Tribe__Events__Main::TAXONOMY;
		return $tax;
	}
}

// Return current page title
if ( !function_exists( 'custom_made_tribe_events_get_blog_title' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_blog_title', 'custom_made_tribe_events_get_blog_title');
	function custom_made_tribe_events_get_blog_title($title='') {
		if (custom_made_is_tribe_events_page() ) {
			if (is_archive())
				$title = apply_filters( 'tribe_events_title', tribe_get_events_title( false ) );
			else {
				global $wp_query;
				if (!empty($wp_query->post)) {
					$title = $wp_query->post->post_title;
				}
			}
		}
		return $title;
	}
}

// Return link to the main events page for the breadcrumbs
if ( !function_exists( 'custom_made_tribe_events_get_blog_all_posts_link' ) ) {
	//Handler of the add_filter('custom_made_filter_get_blog_all_posts_link', 'custom_made_tribe_events_get_blog_all_posts_link');
	function custom_made_tribe_events_get_blog_all_posts_link($link='') {
		if ($link=='' && custom_made_is_tribe_events_page() && (!is_archive() || tribe_is_event_category()))
			$link = '<a href="'.esc_url(tribe_get_events_link()).'">'.esc_html__('Events', 'custom-made').'</a>';
		return $link;
	}
}

// Detect if page title must be showed
if ( !function_exists( 'custom_made_tribe_events_show_page_title' ) ) {
	//Handler of the add_filter('custom_made_filter_show_page_title', 'custom_made_tribe_events_show_page_title');
	function custom_made_tribe_events_need_page_title($need=false) {
		if (!$need)
			$need = custom_made_is_tribe_events_page();
		return $need;
	}
}

// Show categories of the current event
if ( !function_exists( 'custom_made_tribe_events_get_post_categories' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_post_categories', 		'custom_made_tribe_events_get_post_categories');
	function custom_made_tribe_events_get_post_categories($cats='') {
		if (get_post_type()==Tribe__Events__Main::POSTTYPE) {
			$cats = custom_made_get_post_terms(', ', get_the_ID(), Tribe__Events__Main::TAXONOMY);
		}
		return $cats;
	}
}

// Return date of the current event
if ( !function_exists( 'custom_made_tribe_events_get_post_date' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_post_date',		 		'custom_made_tribe_events_get_post_date');
	function custom_made_tribe_events_get_post_date($dt='') {
		if (get_post_type()==Tribe__Events__Main::POSTTYPE) {
			$dt = tribe_get_start_date(null, true, 'Y-m-d');
			$dt = sprintf($dt < date('Y-m-d') 
								? esc_html__('Started on %s', 'custom-made') 
								: esc_html__('Starting %s', 'custom-made'),
								date(get_option('date_format'), strtotime($dt)));
		}
		return $dt;
	}
}
	
// Enqueue Tribe Events admin scripts and styles
if ( !function_exists( 'custom_made_tribe_events_admin_scripts' ) ) {
	//Handler of the add_action( 'admin_enqueue_scripts', 'custom_made_tribe_events_admin_scripts' );
	function custom_made_tribe_events_admin_scripts() {
		wp_deregister_style('tribe-jquery-ui-theme');
	}
}

// Enqueue Tribe Events custom scripts and styles
if ( !function_exists( 'custom_made_tribe_events_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'custom_made_tribe_events_frontend_scripts', 1100 );
	function custom_made_tribe_events_frontend_scripts() {
		if (custom_made_is_tribe_events_page()) {
			wp_deregister_style('tribe-events-custom-jquery-styles');
			if (custom_made_is_on(custom_made_get_theme_option('debug_mode')) && file_exists(custom_made_get_file_dir('plugins/the-events-calendar/the-events-calendar.css')))
				wp_enqueue_style( 'custom-made-the-events-calendar',  custom_made_get_file_url('plugins/the-events-calendar/the-events-calendar.css'), array(), null );
		}
	}
}

// Merge custom styles
if ( !function_exists( 'custom_made_tribe_events_merge_styles' ) ) {
	//Handler of the add_filter('custom_made_filter_merge_styles', 'custom_made_tribe_events_merge_styles');
	function custom_made_tribe_events_merge_styles($list) {
		$list[] = 'plugins/the-events-calendar/the-events-calendar.css';
		return $list;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_tribe_events_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_tribe_events_tgmpa_required_plugins');
	function custom_made_tribe_events_tgmpa_required_plugins($list=array()) {
		if (in_array('the-events-calendar', custom_made_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Tribe Events Calendar', 'custom-made'),
					'slug' 		=> 'the-events-calendar',
					'required' 	=> false
				);
		return $list;
	}
}



// Add Tribe Events specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'custom_made_tribe_events_list_sidebars' ) ) {
	//Handler of the add_filter( 'custom_made_filter_list_sidebars', 'custom_made_tribe_events_list_sidebars' );
	function custom_made_tribe_events_list_sidebars($list=array()) {
		$list['tribe_events_widgets'] = esc_html__('Tribe Events Widgets', 'custom-made');
		return $list;
	}
}



// Add Tribe Events specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'custom_made_tribe_events_get_css' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_css', 'custom_made_tribe_events_get_css', 10, 3 );
	function custom_made_tribe_events_get_css($css, $colors, $fonts) {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
			
.tribe-events-list .tribe-events-list-event-title {
	{$fonts['h3_font-family']}
}

.tribe-events-list .tribe-events-list-separator-month,
.tribe-events-calendar thead th,
.tribe-events-schedule, .tribe-events-schedule h2,
.tribe-events-read-more,
#tribe-events .tribe-events-button, .tribe-events-button, .tribe-events-cal-links a, .tribe-events-sub-nav li a,
#tribe-bar-form button, #tribe-bar-form a {
	{$fonts['h5_font-family']}
}
#tribe-bar-form input, #tribe-events-content.tribe-events-month,
#tribe-events-content .tribe-events-calendar div[id*="tribe-events-event-"] h3.tribe-events-month-event-title,
#tribe-mobile-container .type-tribe_events,
.tribe-events-list-widget ol li .tribe-event-title {
	{$fonts['p_font-family']}
}
.tribe-events-loop .tribe-event-schedule-details,
.single-tribe_events #tribe-events-content .tribe-events-event-meta dt,
#tribe-mobile-container .type-tribe_events .tribe-event-date-start {
	{$fonts['info_font-family']};
}

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Buttons */
#tribe-bar-form .tribe-bar-submit input[type="submit"],
#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"],
#tribe-events .tribe-events-button,
.tribe-events-button,
.tribe-events-cal-links a,
.tribe-events-sub-nav li a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
#tribe-bar-form .tribe-bar-submit input[type="submit"]:hover,
#tribe-bar-form .tribe-bar-submit input[type="submit"]:focus,
#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"]:focus,
#tribe-bar-form.tribe-bar-mini .tribe-bar-submit input[type="submit"]:focus,
#tribe-events .tribe-events-button:hover,
.tribe-events-button:hover,
.tribe-events-cal-links a:hover,
.tribe-events-sub-nav li a:hover {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}

/* Filters bar */
#tribe-bar-form {
	color: {$colors['text_dark']};
}
#tribe-bar-form input[type="text"] {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}

#tribe-bar-views li.tribe-bar-views-option a,
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option.tribe-bar-active a {
	color: {$colors['inverse_text']};
	background: {$colors['text_link']};
}
#tribe-bar-views li.tribe-bar-views-option a:hover,
#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option.tribe-bar-active a:hover {
	color: {$colors['bg_color']};
	background: {$colors['text_dark']};
}
.datepicker thead tr:first-child th:hover, .datepicker tfoot tr th:hover {
	color: {$colors['text_link']};
	background: {$colors['text_dark']};
}

/* Content */
.tribe-events-calendar thead th {
	color: {$colors['bg_color']};
	background: {$colors['text_dark']} !important;
	border-color: {$colors['text_dark']} !important;
}
.tribe-events-calendar thead th + th:before {
	background: {$colors['bg_color']};
}
#tribe-events-content .tribe-events-calendar td {
	border-color: {$colors['bd_color']} !important;
}
.tribe-events-calendar td div[id*="tribe-events-daynum-"],
.tribe-events-calendar td div[id*="tribe-events-daynum-"] > a {
	color: {$colors['text_dark']};
}
.tribe-events-calendar td.tribe-events-othermonth {
	color: {$colors['alter_light']};
	background: {$colors['alter_bg_color']} !important;
}
.tribe-events-calendar td.tribe-events-othermonth div[id*="tribe-events-daynum-"],
.tribe-events-calendar td.tribe-events-othermonth div[id*="tribe-events-daynum-"] > a {
	color: {$colors['alter_light']};
}
.tribe-events-calendar td.tribe-events-past div[id*="tribe-events-daynum-"], .tribe-events-calendar td.tribe-events-past div[id*="tribe-events-daynum-"] > a {
	color: {$colors['text_light']};
}
.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"],
.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"] > a {
	color: {$colors['text_link']};
}
.tribe-events-calendar td.tribe-events-present:before {
	border-color: {$colors['text_link']};
}
.tribe-events-calendar .tribe-events-has-events:after {
	background-color: {$colors['text']};
}
.tribe-events-calendar .mobile-active.tribe-events-has-events:after {
	background-color: {$colors['bg_color']};
}
#tribe-events-content .tribe-events-calendar td,
#tribe-events-content .tribe-events-calendar div[id*="tribe-events-event-"] h3.tribe-events-month-event-title a {
	color: {$colors['text_dark']};
}
#tribe-events-content .tribe-events-calendar div[id*="tribe-events-event-"] h3.tribe-events-month-event-title a:hover {
	color: {$colors['text_link']};
}
#tribe-events-content .tribe-events-calendar td.mobile-active,
#tribe-events-content .tribe-events-calendar td.mobile-active:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
#tribe-events-content .tribe-events-calendar td.mobile-active div[id*="tribe-events-daynum-"] {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}
#tribe-events-content .tribe-events-calendar td.tribe-events-othermonth.mobile-active div[id*="tribe-events-daynum-"] a,
.tribe-events-calendar .mobile-active div[id*="tribe-events-daynum-"] a {
	background-color: transparent;
	color: {$colors['bg_color']};
}

/* Tooltip */
.recurring-info-tooltip,
.tribe-events-calendar .tribe-events-tooltip,
.tribe-events-week .tribe-events-tooltip,
.tribe-events-tooltip .tribe-events-arrow {
	color: {$colors['alter_text']};
	background: {$colors['alter_bg_color']};
}
#tribe-events-content .tribe-events-tooltip h4 { 
	color: {$colors['text_link']};
	background: {$colors['text_dark']};
}
.tribe-events-tooltip .tribe-event-duration {
	color: {$colors['inverse_light']};
}

/* Events list */
.tribe-events-list-separator-month {
	color: {$colors['text_dark']};
}
.tribe-events-list-separator-month:after {
	border-color: {$colors['bd_color']};
}
.tribe-events-list .type-tribe_events + .type-tribe_events {
	border-color: {$colors['bd_color']};
}
.tribe-events-list .tribe-events-event-cost span {
	color: {$colors['inverse_text']};
	border-color: {$colors['text_dark']};
	background: {$colors['text_dark']};
}
.tribe-mobile .tribe-events-loop .tribe-events-event-meta {
	color: {$colors['alter_text']};
	border-color: {$colors['alter_bd_color']};
	background-color: {$colors['alter_bg_color']};
}
.tribe-mobile .tribe-events-loop .tribe-events-event-meta a {
	color: {$colors['alter_link']};
}
.tribe-mobile .tribe-events-loop .tribe-events-event-meta a:hover {
	color: {$colors['alter_hover']};
}
.tribe-mobile .tribe-events-list .tribe-events-venue-details {
	border-color: {$colors['alter_bd_color']};
}

/* Events day */
.tribe-events-day .tribe-events-day-time-slot h5 {
	color: {$colors['inverse_text']};
	background: {$colors['text_dark']};
}

/* Single Event */
.single-tribe_events .tribe-events-single-section {
	border-color: {$colors['bd_color']};
}
.single-tribe_events .tribe-events-venue-map {
	color: {$colors['alter_text']};
	border-color: {$colors['alter_bd_hover']};
	background: {$colors['alter_bg_hover']};
}
.single-tribe_events .tribe-events-schedule .tribe-events-cost {
	color: {$colors['text_dark']};
}


CSS;
		}
		
		return $css;
	}
}
?>