<?php
if(!class_exists('WD_TEMPLATES_ACTION')){
	class WD_TEMPLATES_ACTION{
		function __construct() {
			add_filter( 'template_include', array($this, 'wd_single_template'), 99  );
			add_action("wd_posts_template", array($this, "wd_register_posts_template"), 10, 3);
			if ( class_exists( 'woocommerce' ) ) add_action("wd_posts_template", array($this, "wd_register_woo_product_template"), 9, 3);
		}
		function wd_single_template($template) {
			global $cpt_post, $post;
			if(is_array(get_query_var('post_type'))) $p_type = get_post_type( $post->ID );
			else $p_type = get_query_var('post_type');
			$args = array(
							'numberposts' => 1,
							'post_type'   => 'wd-templates',
							'meta_key'    => 'template',
							'meta_value'  => $p_type,
						);
			$types_custom = get_posts( $args );print_r($args);
			if ( $types_custom ) {
				foreach ( $types_custom  as $_post ) {
					$cpt_post = $_post;
				}
				$template = $post->post_type == $p_type ? WD_DIR.'templates/template.php' : $template;
				if(file_exists($template)) {
					return $template;
				}
			}			
			return $template;
		}
		function wd_register_posts_template($current){
			?>
			<option value="post"<?php wd_esc_e($current == "post" ? ' selected="selected"' : '', 'text');?>><?php wd_esc_e( 'Posts' );?></option>
			<?php
		}
		function wd_register_woo_product_template($current){
			?>
			<option value="product"<?php wd_esc_e($current == "product" ? ' selected="selected"' : '', 'text');?>><?php wd_esc_e( 'Products' );?></option>
			<?php
		}
	}
	new WD_TEMPLATES_ACTION();
}
?>