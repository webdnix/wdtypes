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
global $cpt_content;
get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" style="margin-top:150px;">

			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				echo apply_filters('the_content',$cpt_content);			
			endwhile; 
			// End the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
