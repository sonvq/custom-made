<?php
/* BBPress and BuddyPress support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('custom_made_bbpress_theme_setup1')) {
	add_action( 'after_setup_theme', 'custom_made_bbpress_theme_setup1', 1 );
	function custom_made_bbpress_theme_setup1() {
		add_filter( 'custom_made_filter_list_sidebars', 'custom_made_bbpress_list_sidebars' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('custom_made_bbpress_theme_setup3')) {
	add_action( 'after_setup_theme', 'custom_made_bbpress_theme_setup3', 3 );
	function custom_made_bbpress_theme_setup3() {
		if (custom_made_exists_bbpress()) {
			custom_made_storage_merge_array('options', '', array(
				// Section 'BBPress and BuddyPress' - settings for show pages
				'bbpress' => array(
					"title" => esc_html__('BB(Buddy) Press', 'custom-made'),
					"desc" => wp_kses_data( __('Select parameters to display the community pages', 'custom-made') ),
					"type" => "section"
					),
				'expand_content_bbpress' => array(
					"title" => esc_html__('Expand content', 'custom-made'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'custom-made') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'header_widgets_bbpress' => array(
					"title" => esc_html__('Header widgets', 'custom-made'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the community pages', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'sidebar_widgets_bbpress' => array(
					"title" => esc_html__('Sidebar widgets', 'custom-made'),
					"desc" => wp_kses_data( __('Select sidebar to show on the community pages', 'custom-made') ),
					"std" => 'bbpress_widgets',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'sidebar_position_bbpress' => array(
					"title" => esc_html__('Sidebar position', 'custom-made'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the community pages', 'custom-made') ),
					"refresh" => false,
					"std" => 'left',
					"options" => custom_made_get_list_sidebars_positions(),
					"type" => "select"
					),
				'widgets_above_page_bbpress' => array(
					"title" => esc_html__('Widgets above the page', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_above_content_bbpress' => array(
					"title" => esc_html__('Widgets above the content', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_below_content_bbpress' => array(
					"title" => esc_html__('Widgets below the content', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_below_page_bbpress' => array(
					"title" => esc_html__('Widgets below the page', 'custom-made'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'custom-made') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'footer_scheme_bbpress' => array(
					"title" => esc_html__('Footer Color Scheme', 'custom-made'),
					"desc" => wp_kses_data( __('Select color scheme to decorate footer area', 'custom-made') ),
					"std" => 'dark',
					"options" => custom_made_get_list_schemes(true),
					"type" => "select"
					),
				'footer_widgets_bbpress' => array(
					"title" => esc_html__('Footer widgets', 'custom-made'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'custom-made') ),
					"std" => 'footer_widgets',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'custom-made')), custom_made_get_list_sidebars()),
					"type" => "select"
					),
				'footer_columns_bbpress' => array(
					"title" => esc_html__('Footer columns', 'custom-made'),
					"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'custom-made') ),
					"dependency" => array(
						'footer_widgets_bbpress' => array('^hide')
					),
					"std" => 0,
					"options" => custom_made_get_list_range(0,6),
					"type" => "select"
					),
				'footer_wide_bbpress' => array(
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
if (!function_exists('custom_made_bbpress_theme_setup9')) {
	add_action( 'after_setup_theme', 'custom_made_bbpress_theme_setup9', 9 );
	function custom_made_bbpress_theme_setup9() {
		
		if (custom_made_exists_bbpress()) {
			add_action( 'wp_enqueue_scripts', 								'custom_made_bbpress_frontend_scripts', 1100 );
			add_filter( 'custom_made_filter_merge_styles',						'custom_made_bbpress_merge_styles' );
			add_filter( 'custom_made_filter_get_css',							'custom_made_bbpress_get_css', 10, 3 );
			if (!is_admin()) {
				add_filter( 'custom_made_filter_detect_blog_mode',				'custom_made_bbpress_detect_blog_mode' );
				add_filter( 'custom_made_filter_get_blog_all_posts_link', 		'custom_made_bbpress_get_blog_all_posts_link');
				add_filter( 'custom_made_filter_get_blog_title', 				'custom_made_bbpress_get_blog_title');
				add_filter( 'custom_made_filter_need_page_title', 				'custom_made_bbpress_need_page_title');
			}
		}
		if (is_admin()) {
			add_filter( 'custom_made_filter_tgmpa_required_plugins',			'custom_made_bbpress_tgmpa_required_plugins' );
		}

	}
}



// Check if BBPress and BuddyPress is installed and activated
if ( !function_exists( 'custom_made_exists_bbpress' ) ) {
	function custom_made_exists_bbpress() {
		return class_exists( 'BuddyPress' ) || class_exists( 'bbPress' );
	}
}

// Return true, if current page is any bbpress page
if ( !function_exists( 'custom_made_is_bbpress_page' ) ) {
	function custom_made_is_bbpress_page() {
		$rez = false;
		if (custom_made_exists_bbpress())
			if (!is_search()) $rez = (function_exists('is_buddypress') && is_buddypress()) || (function_exists('is_bbpress') && is_bbpress());
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'custom_made_bbpress_detect_blog_mode' ) ) {
	//Handler of the add_filter( 'custom_made_filter_detect_blog_mode', 'custom_made_bbpress_detect_blog_mode' );
	function custom_made_bbpress_detect_blog_mode($mode='') {
		if (custom_made_is_bbpress_page())
			$mode = 'bbpress';
		return $mode;
	}
}

// Return current page title
if ( !function_exists( 'custom_made_bbpress_get_blog_title' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_blog_title', 'custom_made_bbpress_get_blog_title');
	function custom_made_bbpress_get_blog_title($title='') {
		if (custom_made_is_bbpress_page() ) {
		}
		return $title;
	}
}

// Return link to the main bbpress page for the breadcrumbs
if ( !function_exists( 'custom_made_bbpress_get_blog_all_posts_link' ) ) {
	//Handler of the add_filter('custom_made_filter_get_blog_all_posts_link', 'custom_made_bbpress_get_blog_all_posts_link');
	function custom_made_bbpress_get_blog_all_posts_link($link='') {
		if ($link=='' && custom_made_is_bbpress_page()) {
			// Page exists at root slug path, so use its permalink
			$page = bbp_get_page_by_path( bbp_get_root_slug() );
			if ( !empty( $page ) )
				$url = get_permalink( $page->ID );
			else
				$url = get_post_type_archive_link( bbp_get_forum_post_type() );
			$link = '<a href="'.esc_url($url).'">'.esc_html__('Forums', 'custom-made').'</a>';
		}
		return $link;
	}
}

// Detect if page title must be showed
if ( !function_exists( 'custom_made_bbpress_show_page_title' ) ) {
	//Handler of the add_filter('custom_made_filter_show_page_title', 'custom_made_bbpress_show_page_title');
	function custom_made_bbpress_need_page_title($need=false) {
		if (!$need)
			$need = custom_made_is_bbpress_page();
		return $need;
	}
}
	
// Enqueue BBPress and BuddyPress custom styles
if ( !function_exists( 'custom_made_bbpress_frontend_scripts' ) ) {
	//Handler of the add_action( 'wp_enqueue_scripts', 'custom_made_bbpress_frontend_scripts', 1100 );
	function custom_made_bbpress_frontend_scripts() {
		if (custom_made_is_bbpress_page()) {
			if (custom_made_is_on(custom_made_get_theme_option('debug_mode')) && file_exists(custom_made_get_file_dir('plugins/bbpress/bbpress.css')))
				wp_enqueue_style( 'custom-made-bbpress',  custom_made_get_file_url('plugins/bbpress/bbpress.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'custom_made_bbpress_merge_styles' ) ) {
	//Handler of the add_filter('custom_made_filter_merge_styles', 'custom_made_bbpress_merge_styles');
	function custom_made_bbpress_merge_styles($list) {
		$list[] = 'plugins/bbpress/bbpress.css';
		return $list;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'custom_made_bbpress_tgmpa_required_plugins' ) ) {
	//Handler of the add_filter('custom_made_filter_tgmpa_required_plugins',	'custom_made_bbpress_tgmpa_required_plugins');
	function custom_made_bbpress_tgmpa_required_plugins($list=array()) {
		if (in_array('the-events-calendar', custom_made_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('BBPress', 'custom-made'),
					'slug' 		=> 'bbpress',
					'required' 	=> false
				);
			$list[] = array(
					'name' 		=> esc_html__('BuddyPress', 'custom-made'),
					'slug' 		=> 'buddypress',
					'required' 	=> false
				);
		return $list;
	}
}



// Add BBPress and BuddyPress specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'custom_made_bbpress_list_sidebars' ) ) {
	//Handler of the add_filter( 'custom_made_filter_list_sidebars', 'custom_made_bbpress_list_sidebars' );
	function custom_made_bbpress_list_sidebars($list=array()) {
		$list['bbpress_widgets'] = esc_html__('BBPress and BuddyPress Widgets', 'custom-made');
		return $list;
	}
}



// Add BBPress and BuddyPress specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'custom_made_bbpress_get_css' ) ) {
	//Handler of the add_filter( 'custom_made_filter_get_css', 'custom_made_bbpress_get_css', 10, 3 );
	function custom_made_bbpress_get_css($css, $colors, $fonts) {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS

#buddypress .comment-reply-link,
#buddypress .generic-button a,
#buddypress a.button,
#buddypress button,
#buddypress input[type="button"],
#buddypress input[type="reset"],
#buddypress input[type="submit"],
#buddypress ul.button-nav li a,
a.bp-title-button,
#bbpress-forums li.bbp-header,
#bbpress-forums li.bbp-footer,
.bbp-forums .bbp-forum-title,
#bbpress-forums div.bbp-forum-author a.bbp-author-name,
#bbpress-forums div.bbp-topic-author a.bbp-author-name,
#bbpress-forums div.bbp-reply-author a.bbp-author-name,
li.bbp-topic-title .bbp-topic-permalink,
#buddypress #item-nav ul li {
	{$fonts['h5_font-family']}
}
.bbp-meta .bbp-reply-post-date,
#buddypress table.profile-fields tr td.data,
#buddypress .current-visibility-level,
#buddypress div#item-header div#item-meta,
#buddypress .activity-list .activity-content .activity-inner  {
	{$fonts['info_font-family']}
}
#buddypress .dir-search input[type="search"],
#buddypress .dir-search input[type="text"],
#buddypress .groups-members-search input[type="search"],
#buddypress .groups-members-search input[type="text"],
#buddypress .standard-form input[type="color"],
#buddypress .standard-form input[type="date"],
#buddypress .standard-form input[type="datetime-local"],
#buddypress .standard-form input[type="datetime"],
#buddypress .standard-form input[type="email"],
#buddypress .standard-form input[type="month"],
#buddypress .standard-form input[type="number"],
#buddypress .standard-form input[type="password"],
#buddypress .standard-form input[type="range"],
#buddypress .standard-form input[type="search"],
#buddypress .standard-form input[type="tel"],
#buddypress .standard-form input[type="text"],
#buddypress .standard-form input[type="time"],
#buddypress .standard-form input[type="url"],
#buddypress .standard-form input[type="week"],
#buddypress .standard-form select,
#buddypress .standard-form textarea {
	{$fonts['input_font-family']}
}

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* BBPress
---------------------------------------------------- */
#bbpress-forums ul.bbp-lead-topic,
#bbpress-forums ul.bbp-topics,
#bbpress-forums ul.bbp-forums,
#bbpress-forums ul.bbp-replies,
#bbpress-forums ul.bbp-search-results {
	border-color: {$colors['bd_color']};
}
#bbpress-forums li.bbp-header,
#bbpress-forums li.bbp-footer {
	background: {$colors['alter_bd_hover']};
	border-color: {$colors['bd_color']};
	color: {$colors['alter_dark']};
}
#bbpress-forums li.bbp-body ul.forum,
#bbpress-forums li.bbp-body ul.topic {
    border-color: {$colors['bg_color']};
}
#bbpress-forums div.odd,
#bbpress-forums ul.odd {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
}
#bbpress-forums div.even,
#bbpress-forums ul.even {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_color']};
}
#bbpress-forums div.bbp-forum-header,
#bbpress-forums div.bbp-topic-header{
	color: {$colors['alter_text']};
	border-color: {$colors['bd_color']};
	background-color: {$colors['alter_bg_color']};
}
#bbpress-forums div.bbp-reply-header {
	color: {$colors['alter_text']};
	background-color: {$colors['alter_bg_hover']};
	border-color: {$colors['bg_color']};
}
span.bbp-admin-links {
	color: {$colors['alter_text']};
}
.bbp-forum-header a.bbp-forum-permalink,
.bbp-topic-header a.bbp-topic-permalink,
.bbp-reply-header a.bbp-reply-permalink {
	color: {$colors['alter_link']};
}
.bbp-forum-header a.bbp-forum-permalink:hover,
.bbp-topic-header a.bbp-topic-permalink:hover,
.bbp-reply-header a.bbp-reply-permalink:hover {
	color: {$colors['alter_hover']};
}

#bbpress-forums fieldset.bbp-form {
	border-color: {$colors['bd_color']};
}
.quicktags-toolbar {
    background: {$colors['alter_bg_hover']};
    border-color: {$colors['alter_bd_hover']};
}


/* Buddy Press
---------------------------------------------------- */

/* Buttons */
#buddypress .comment-reply-link,
#buddypress .generic-button a,
#buddypress a.button,
#buddypress button,
#buddypress input[type="button"],
#buddypress input[type="reset"],
#buddypress input[type="submit"],
#buddypress ul.button-nav li a,
a.bp-title-button {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
#buddypress .comment-reply-link:hover,
#buddypress .generic-button a:hover,
#buddypress a.button:hover,
#buddypress button:hover,
#buddypress input[type="button"]:hover,
#buddypress input[type="reset"]:hover,
#buddypress input[type="submit"]:hover,
#buddypress ul.button-nav li a:hover,
a.bp-title-button:hover {
	color: {$colors['bg_color']};
	background-color: {$colors['text_dark']};
}

/* Tabs */
#buddypress div.item-list-tabs ul li a {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_color']};
}
#buddypress div.item-list-tabs ul li a:hover,
#buddypress div.item-list-tabs ul li.current a,
#buddypress div.item-list-tabs ul li.selected a {
	color: {$colors['inverse_dark']};
	background-color: {$colors['alter_link']};
}

/* Input fields */
#buddypress .dir-search input[type="search"],
#buddypress .dir-search input[type="text"],
#buddypress .groups-members-search input[type="search"],
#buddypress .groups-members-search input[type="text"],
#buddypress .standard-form input[type="color"],
#buddypress .standard-form input[type="date"],
#buddypress .standard-form input[type="datetime-local"],
#buddypress .standard-form input[type="datetime"],
#buddypress .standard-form input[type="email"],
#buddypress .standard-form input[type="month"],
#buddypress .standard-form input[type="number"],
#buddypress .standard-form input[type="password"],
#buddypress .standard-form input[type="range"],
#buddypress .standard-form input[type="search"],
#buddypress .standard-form input[type="tel"],
#buddypress .standard-form input[type="text"],
#buddypress .standard-form input[type="time"],
#buddypress .standard-form input[type="url"],
#buddypress .standard-form input[type="week"],
#buddypress .standard-form select,
#buddypress .standard-form textarea,
#buddypress form#whats-new-form textarea {
	color: {$colors['input_text']};
	border-color: {$colors['input_bd_color']};
	background-color: {$colors['input_bg_color']};
}
#buddypress .dir-search input[type="search"]:focus,
#buddypress .dir-search input[type="text"]:focus,
#buddypress .groups-members-search input[type="search"]:focus,
#buddypress .groups-members-search input[type="text"]:focus,
#buddypress .standard-form input[type="color"]:focus,
#buddypress .standard-form input[type="date"]:focus,
#buddypress .standard-form input[type="datetime-local"]:focus,
#buddypress .standard-form input[type="datetime"]:focus,
#buddypress .standard-form input[type="email"]:focus,
#buddypress .standard-form input[type="month"]:focus,
#buddypress .standard-form input[type="number"]:focus,
#buddypress .standard-form input[type="password"]:focus,
#buddypress .standard-form input[type="range"]:focus,
#buddypress .standard-form input[type="search"]:focus,
#buddypress .standard-form input[type="tel"]:focus,
#buddypress .standard-form input[type="text"]:focus,
#buddypress .standard-form input[type="time"]:focus,
#buddypress .standard-form input[type="url"]:focus,
#buddypress .standard-form input[type="week"]:focus,
#buddypress .standard-form select:focus,
#buddypress .standard-form textarea:focus,
#buddypress form#whats-new-form textarea:focus {
	color: {$colors['input_dark']};
	border-color: {$colors['input_bd_hover']};
	background-color: {$colors['input_bg_hover']};
}

#buddypress div#item-header-cover-image .user-nicename a, 
#buddypress div#item-header-cover-image .user-nicename {
	color: {$colors['text_dark']};
}
#buddypress table.notifications,
#buddypress table.notifications tr td,
#buddypress table.notifications tr th {
	border-color: {$colors['alter_bd_color']};
}
#buddypress table.notifications tr th {
	color: {$colors['alter_dark']};
	background-color: {$colors['alter_bg_color']};
}
#buddypress table.profile-fields tr td,
#buddypress table.profile-fields tr th {
	color: {$colors['text_dark']};
}

#buddypress ul.item-list,
#buddypress ul.item-list li,
#buddypress table.forum tr td.label,
#buddypress table.messages-notices tr td.label,
#buddypress table.notifications tr td.label,
#buddypress table.notifications-settings tr td.label,
#buddypress table.profile-fields tr td.label,
#buddypress table.wp-profile-fields tr td.label,
.activity-list li.bbp_topic_create .activity-content .activity-inner,
.activity-list li.bbp_reply_create .activity-content .activity-inner {
	border-color: {$colors['bd_color']};
}

#buddypress div#item-header div#item-meta {
	color: {$colors['text_light']};
}
#buddypress #item-header-cover-image #item-header-avatar img.avatar {
	border-color: {$colors['bd_color']};
}

CSS;
		}
		
		return $css;
	}
}
?>