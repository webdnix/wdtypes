<?php
//Class to create groups of custom fields in dashboard
if(!class_exists('WDT_CUSTUM_POST_META_GROUP')){
	class WDT_CUSTUM_POST_META_GROUP{
		function __construct() {
			add_action( 'init', array($this, 'wdt_post_meta') );
			add_action( 'add_meta_boxes', array($this, 'wdt_add_meta_boxes') );
		}
		//register group post
		function wdt_post_meta(){
		   $labels = array(
				'name'               => _x( 'Meta Group', 'post type general name', WDT_POST_TYPE ),
				'singular_name'      => _x( 'Meta Group', 'post type singular name', WDT_POST_TYPE ),
				'menu_name'          => _x( 'Meta Group', 'admin menu', WDT_POST_TYPE ),
				'name_admin_bar'     => _x( 'Meta Group', 'add new on admin bar', WDT_POST_TYPE ),
				'add_new'            => _x( 'Add New', 'product', WDT_POST_TYPE ),
				'add_new_item'       => __( 'Add New Group', WDT_POST_TYPE ),
				'new_item'           => __( 'New Meta Group', WDT_POST_TYPE ),
				'edit_item'          => __( 'Edit Meta Group', WDT_POST_TYPE ),
				'view_item'          => __( 'View Meta Group', WDT_POST_TYPE ),
				'all_items'          => __( 'All Groups', WDT_POST_TYPE ),
				'search_items'       => __( 'Search Meta Group', WDT_POST_TYPE ),
				'not_found'          => __( 'No Meta Group found.', WDT_POST_TYPE ),
				'not_found_in_trash' => __( 'No Meta Group found in Trash.', WDT_POST_TYPE )
			);
		    $args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', 'Add New Custom Post', WDT_POST_TYPE ),
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'wdt_group' ),
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 10,
				'supports'           => array( 'title' ),
				'show_in_menu' => 'edit.php?post_type=wdtypes'
			);
			register_post_type( 'wdt_group', $args );
		}
		//add required meta in group type
		function wdt_add_meta_boxes(){
			add_meta_box(
				'wdt_meta_group',                 
				__( 'Meta Group', WDT_POST_TYPE ),     
				array($this, 'wdt_meta_group_html'), 
				'wdt_group',
				'normal'                      
			);
			add_meta_box(
				'wdt_meta_description_group',                 
				__( 'Meta Group Description', WDT_POST_TYPE ),     
				array($this, 'wdt_meta_group_desc_html'), 
				'wdt_group',
				'side'                      
			);
		}
		// dashboar group fields html
		function wdt_meta_group_html($post){
			$type = get_post_meta( $post->ID, 'type', true );
			$css = get_post_meta( $post->ID, 'css', true );
			$class = get_post_meta( $post->ID, 'class', true );
			$position = get_post_meta( $post->ID, 'position', true );
			$priority = get_post_meta( $post->ID, 'priority', true );
			?>
            <style>
				.inputs-wrp {
					margin-top: 15px;
				}
				.inputs-wrp .input-wrp {
					width: 100%;
					margin-bottom: 20px;
				}
				.inputs-wrp .input-wrp input[type='checkbox'] {
   					margin-top: 2px;
					width:auto;
				}
				.inputs-wrp .input-wrp input {
					font-size: 1.2em;
				}
				.inputs-wrp .input-wrp input, .inputs-wrp .input-wrp select {
					padding: 3px 8px;
					line-height: 100%;
					width: 100%;
					outline: 0;
					margin: 10px 0 3px;
					background-color: #fff;
				}
				.inputs-wrp .input-wrp textarea {
					padding: 3px 8px;
					line-height: 100%;
					height: 10.7em;
					width: 100%;
					outline: 0;
					margin: 10px 0 3px;
					background-color: #fff;
				}
			</style>
            <div class="inputs-wrp"> 
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Group Type', 'label' );?></strong></label>
                    <select name="wdt_args[select][type]" id="field-type" class="wdt-select wdt-field">
                        <option value="default"<?php wdt_esc_e($type == 'default' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Default', 'label' );?></option>
                        <option value="inside"<?php wdt_esc_e($type == 'inside' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Inside', 'label' );?></option>
                    </select>
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Group Position', 'label' );?></strong></label>
                    <select name="wdt_args[select][position]" id="field-type" class="wdt-select wdt-field">
                        <option value="advanced"<?php wdt_esc_e($position == 'advanced' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Advanced', 'label' );?></option>
                        <option value="normal"<?php wdt_esc_e($position == 'normal' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Normal', 'label' );?></option>
                        <option value="side"<?php wdt_esc_e($position == 'side' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Side', 'label' );?></option>
                    </select>
               </div>
				<div class="input-wrp"> 
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Group Priority', 'label' );?></strong></label>
                    <select name="wdt_args[select][priority]" id="field-type" class="wdt-select wdt-field">
                        <option value="default"<?php wdt_esc_e($priority == 'default' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Default', 'label' );?></option>
                        <option value="core"<?php wdt_esc_e($priority == 'core' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Core', 'label' );?></option>
                        <option value="low"<?php wdt_esc_e($priority == 'low' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'Low', 'label' );?></option>
                        <option value="high"<?php wdt_esc_e($priority == 'high' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'High', 'label' );?></option>
                    </select>
               </div>
               <div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Custom Class', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][class]" value="<?php wdt_esc_e( !empty($class) ? $class : '', 'text' );?>" />
               </div>   
               <div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Custom CSS', 'label' );?></strong></label>
                    <textarea name="wdt_args[textarea][css]"><?php echo wdt_esc_e( !empty($css) ? $css : '', 'textarea' );?></textarea>
               </div>       
            </div>
			<?php
		}
		// dashboar group fields description html
		function wdt_meta_group_desc_html($post){
			$description = get_post_meta( $post->ID, 'description', true );
			$test = get_post_meta( $post->ID, 'test', true );
			?>
            <div class="inputs-wrp"> 
               <div class="input-wrp"> 
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Group Description', 'label' );?></strong></label>
                    <textarea name="wdt_args[textarea][description]"><?php wdt_esc_e( !empty($description) ? $description : '', 'textarea' );?></textarea>
               </div>
            </div>
			<?php
		}
	}
	new WDT_CUSTUM_POST_META_GROUP();
}