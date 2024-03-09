<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */
global $cpt_post, $post;
get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			$content = "";
			
			if (class_exists("\\Elementor\\Plugin")) {
				$post_ID = $cpt_post->ID;
				$elementor_page = get_post_meta( $post_ID, '_elementor_edit_mode', true );
				if ( (bool)$elementor_page ){
					$pluginElementor = \Elementor\Plugin::instance();
					$content = $pluginElementor->frontend->get_builder_content($post_ID);
					$content = !empty($content) ? $content: $pluginElementor->frontend->get_builder_content($post->ID);
				}else{
					$content = apply_filters('the_content',$cpt_post->post_content);
				}
			}else{
				$content = apply_filters('the_content',$cpt_post->post_content);
			}
			//if(empty($content) && $post->post_content != NULL) $content = apply_filters('the_content',$post->post_content);			
			echo $content;
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
