<?php
if(!class_exists('WDT_POSTS_TEMPLATES')){
	class WDT_POSTS_TEMPLATES{
		function __construct() {
			add_action( 'init', array($this, 'wdt_templates_post_callback') );
			add_action( 'add_meta_boxes', array($this, 'wdt_templates_meta_callback') );
			add_action('wp_footer', array($this, 'wdt_single_template'));
		}
		function wdt_templates_post_callback(){
					   $labels = array(
				'name'               => _x( 'Post Template', 'post type general name', WDT_POST_TYPE ),
				'singular_name'      => _x( 'Post Template', 'post type singular name', WDT_POST_TYPE ),
				'menu_name'          => _x( 'Post Templates', 'admin menu', WDT_POST_TYPE ),
				'name_admin_bar'     => _x( 'Post Template', 'add new on admin bar', WDT_POST_TYPE ),
				'add_new'            => _x( 'Add New', 'Template', WDT_POST_TYPE ),
				'add_new_item'       => __( 'Add New Post Template', WDT_POST_TYPE ),
				'new_item'           => __( 'New Post Template', WDT_POST_TYPE ),
				'edit_item'          => __( 'Edit Post Template', WDT_POST_TYPE ),
				'view_item'          => __( 'View Post Template', WDT_POST_TYPE ),
				'all_items'          => __( 'All Templates', WDT_POST_TYPE ),
				'search_items'       => __( 'Search Post Template', WDT_POST_TYPE ),
				'not_found'          => __( 'No Post Template found.', WDT_POST_TYPE ),
				'not_found_in_trash' => __( 'No Post Template found in Trash.', WDT_POST_TYPE )
			);
		    $args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', 'Add New Custom Post', WDT_POST_TYPE  ),
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'wdt-templates' ),
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 10,
				'supports'           => array( 'title', 'editor' ),
				'show_in_menu' => 'edit.php?post_type=wdtypes'
			);
			register_post_type( 'wdt-templates', $args );
		}
		
		function wdt_templates_meta_callback(){
			  add_meta_box(
				'wdt_template_post_type_args',                 
				__( 'Post Type Template', WDT_POST_TYPE ),     
				array($this, 'wdt_template_post_type_html'), 
				'wdt-templates',
				'side'                      
			); 
		}// dashboar custom template terms html
		function wdt_template_post_type_html($post){
			$template = get_post_meta( $post->ID, 'template', true );
			$args = array(
							 'public'   => true,
							 '_builtin' => true
						  );
			$types_builtin = get_post_types( $args, 'objects' );
			$args = array(
				'numberposts' => -1,
				'post_type' => 'wdtypes',
				'post_status' => 'publish',
			);
			$types_custom = get_posts( $args );
			if ( $types_builtin || $types_custom ) {?>
            	<div class="inputs-wrp">
					<div class="input-wrp">
                    	<label for="wdt_field"><strong><?php wdt_esc_e( 'Post Type', 'label' );?></strong></label>
                        <select name="wdt_args[select][template]" id="template" class="postbox">
                       		<option value=""><?php wdt_esc_e( 'Post Type' );?></option>
                            <option value="post"<?php wdt_esc_e($template == 'post' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Post' );?></option>
                		<?php 
						if ( $types_custom ){   
							foreach ( $types_custom  as $post_type ) {
								$_type = get_post_meta( $post_type->ID, 'post_type', false );
								$type = is_array($_type) ? $_type[0] : (!empty($_type) ? $_type : '');
								 ?>
								 <option value="<?php wdt_esc_e($type, 'text');?>"<?php wdt_esc_e($template == $type ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( $post_type->post_title );?></option>
								 <?php
							}
						}
						?>
                        </select>
                     </div>
                 </div>
            <?php
			}
		}
		/**
		* Single template function which will choose our template
		*/
		function wdt_single_template($template_path) {
			global $template;
			$args = array(
							'numberposts' => 1,
							'post_type'   => 'wdt-templates',
							'meta_key'    => 'template',
    						'meta_value'  => get_query_var( 'post_type' ),
						);
			$types_custom = get_posts( $args );
			if ( $types_custom ) {
				foreach ( $types_custom  as $post_type ) {
					$template = $post_type;
				}
				$template_path = WDT_DIR.'inc/templates/custom-template.php';
				if(file_exists($template_path)) {
					return $template_path;
				}
			}
					
			return $template_path;
		}
		function wdt_list_tax_code($type){}
		function wdt_list_meta_code($type){}
	}
	new WDT_POSTS_TEMPLATES();
}