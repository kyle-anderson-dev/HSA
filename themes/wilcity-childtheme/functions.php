<?php

if (!defined('WILOKE_STORE_WITH_DB')) {
	define('WILOKE_STORE_WITH_DB', 'yes');
}
add_action('wp_enqueue_scripts', 'wilcityChildThemeScripts', 9999);

function wilcityChildThemeScripts(){
	$oTheme = wp_get_theme();
	wp_enqueue_style('wilcity-parent', get_template_directory_uri() . '/style.css', array(), $oTheme->get( 'Version' ));
	wp_enqueue_script('wilcity-child', get_stylesheet_directory_uri() . '/script.js', array('jquery'), '1.0', true);
};

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

// add_action('init', 'bulk_update_post_meta_data');

// function bulk_update_post_meta_data() {
// //     $args = array(
// //     'posts_per_page' => -1,
// //     'post_type' => 'listing',
// //     'suppress_filters' => true
// //      );
    
// //     $posts_array = get_posts( $args );
    
// //     foreach($posts_array as $post_array) {
// 	update_post_meta(17056,"wilcity_belongs_to","16021");
// 	update_post_meta(16594,"wilcity_belongs_to","16021");
// 	update_post_meta(16800,"wilcity_belongs_to","16021");
// 	update_post_meta(16840,"wilcity_belongs_to","16021");
// 	update_post_meta(17060,"wilcity_belongs_to","16021");
// 	update_post_meta(17061,"wilcity_belongs_to","16021");
	
// 	update_post_meta(16809,"wilcity_belongs_to","16021");
// 	update_post_meta(17063,"wilcity_belongs_to","16021");
// 	update_post_meta(17064,"wilcity_belongs_to","16021");
// 	update_post_meta(17065,"wilcity_belongs_to","16021");
// 	update_post_meta(17066,"wilcity_belongs_to","16021");
// 	update_post_meta(16852,"wilcity_belongs_to","16021");
	
// 	update_post_meta(16830,"wilcity_belongs_to","16021");
// 	update_post_meta(17070,"wilcity_belongs_to","16021");
// 	update_post_meta(17071,"wilcity_belongs_to","16021");
// 	update_post_meta(17072,"wilcity_belongs_to","16021");
// 	update_post_meta(16782,"wilcity_belongs_to","16021");

// //     }
// }

// add_filter( 'wp_mail_smtp_custom_options', function( $phpmailer ) {
//     $phpmailer->AuthType = 'LOGIN';
//     return $phpmailer;
// } );