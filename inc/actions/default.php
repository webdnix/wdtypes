<?php
add_action( 'admin_enqueue_scripts', 'wd_add_stylesheet_admin_callback' );
function wd_add_stylesheet_admin_callback() {
    wp_enqueue_style( 'wd_admin_css', WD_URI . '/assets/admin/style.css', false, '1.0.0' );
}
add_shortcode( 'wd-title', 'wd_post_title' );
function wd_post_title() {
	global $post;
	return $post->post_title;	
}

?>