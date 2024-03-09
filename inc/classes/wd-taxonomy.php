<?php
//Class to create taxonomy post in dashboard
if(!class_exists('WDT_CUSTUM_POST_TAXONOMY')){
	class WDT_CUSTUM_POST_TAXONOMY{
		function __construct() {
			add_action( 'init', array($this, 'wdt_post_taxonomies') );
			add_action( 'add_meta_boxes', array($this, 'wdt_add_taxonomies_boxes') );
		}
		//register taxonomy post
		function wdt_post_taxonomies(){
		   $labels = array(
				'name'               => _x( 'Post Taxonomy', 'post type general name', WDT_POST_TYPE ),
				'singular_name'      => _x( 'Post Taxonomy', 'post type singular name', WDT_POST_TYPE ),
				'menu_name'          => _x( 'Post Taxonomies', 'admin menu', WDT_POST_TYPE ),
				'name_admin_bar'     => _x( 'Post Taxonomy', 'add new on admin bar', WDT_POST_TYPE ),
				'add_new'            => _x( 'Add New', 'Taxonomy', WDT_POST_TYPE ),
				'add_new_item'       => __( 'Add New Post Taxonomy', WDT_POST_TYPE ),
				'new_item'           => __( 'New Post Taxonomy', WDT_POST_TYPE ),
				'edit_item'          => __( 'Edit Post Taxonomy', WDT_POST_TYPE ),
				'view_item'          => __( 'View Post Taxonomy', WDT_POST_TYPE ),
				'all_items'          => __( 'All Taxonomies', WDT_POST_TYPE ),
				'search_items'       => __( 'Search Post Taxonomy', WDT_POST_TYPE ),
				'not_found'          => __( 'No Post Taxonomy found.', WDT_POST_TYPE ),
				'not_found_in_trash' => __( 'No Post Taxonomy found in Trash.', WDT_POST_TYPE )
			);
		    $args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', 'Add New Custom Post', WDT_POST_TYPE  ),
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'wdt_taxonomies' ),
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 10,
				'supports'           => array( 'title' ),
				'show_in_menu' => 'edit.php?post_type=wdtypes'
			);
			register_post_type( 'wdt_taxonomies', $args );
		}
		//add required meta in taxonomies type
		function wdt_add_taxonomies_boxes(){
			add_meta_box(
				'wdt_post_other_args',                 
				__( 'Custom Taxonomy', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_others_html'), 
				'wdt_taxonomies',
				'normal'                      
			);
			add_meta_box(
				'wdt_post_labels',                 
				__( 'Taxonomy Labels', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_labels_html'), 
				'wdt_taxonomies',
				'normal'                      
			);
			add_meta_box(
				'wdt_post_args',                 
				__( 'Taxonomy Args(True/False)', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_args_html'), 
				'wdt_taxonomies',
				'side'                         
			);
			add_meta_box(
				'wdt_post_rewrite_args',                 
				__( 'Taxonomy Rewrite', WDT_POST_TYPE ),     
				array($this, 'wdt_rewrite_html'), 
				'wdt_taxonomies',
				'side'                         
			);
			add_meta_box(
				'wdt_post_capabilities_args',                 
				__( 'Taxonomy Capabilities', WDT_POST_TYPE ),     
				array($this, 'wdt_capabilities_html'), 
				'wdt_taxonomies',
				'side'                         
			);
/*			add_meta_box(
				'wdt_post_column_filter_options_args',                 
				__( 'Taxonomy Options', WDT_POST_TYPE ),     
				array($this, 'wdt_column_filter_options_html'), 
				'wdt_taxonomies',
				'side',
				'high'                         
			);*/
			add_meta_box(
				'wdt_post_default_term_args',                 
				__( 'Taxonomy Default Term', WDT_POST_TYPE ),     
				array($this, 'wdt_default_term_html'), 
				'wdt_taxonomies',
				'side'                         
			);
		}
		// dashboar custom taxonomy capabilities html
		function wdt_capabilities_html($post){
			$args = get_post_meta( $post->ID, 'args', true );
			$args = json_decode(stripslashes($args), true);
			$capabilities = isset($args['capabilities']) ? $args['capabilities'] : '';
			$manage_terms = isset($capabilities['manage_terms']) ? $capabilities['manage_terms'] : '';
			$edit_terms = isset($capabilities['edit_terms']) ? $capabilities['edit_terms'] : '';
			$delete_terms = isset($capabilities['delete_terms']) ? $capabilities['delete_terms'] : '';
			$assign_terms = isset($capabilities['assign_terms']) ? $capabilities['assign_terms'] : '';
			?>
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Manage terms', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][capabilities][manage_terms]" id="manage_terms" class="postbox" value="<?php wdt_esc_e(!empty($manage_terms) ? $manage_terms : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Edit terms', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][capabilities][edit_terms]" id="edit_terms" class="postbox" value="<?php wdt_esc_e(!empty($edit_terms) ? $edit_terms : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Delete terms', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][capabilities][delete_terms]" id="delete_terms" class="postbox" value="<?php wdt_esc_e(!empty($delete_terms) ? $delete_terms : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Assign terms', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][capabilities][assign_terms]" id="assign_terms" class="postbox" value="<?php wdt_esc_e(!empty($assign_terms) ? $assign_terms : '');?>" />
               </div>
            </div>
			<?php
		}
		// dashboar custom taxonomy rewrite html
		function wdt_rewrite_html($post){
			$rewrite = get_post_meta( $post->ID, 'rewrite', false );
			$with_front = get_post_meta( $post->ID, 'with_front', true );
			$feeds = get_post_meta( $post->ID, 'feeds', true );
			$pages = get_post_meta( $post->ID, 'pages', true );
			$rewrite = isset($rewrite[0]) && !is_array($rewrite[0]) ? json_decode($rewrite[0], true) : $rewrite;
			$slug = isset($rewrite['slug']) ? $rewrite['slug'] : '';
			$ep_mask = isset($rewrite['ep_mask']) ? $rewrite['ep_mask'] : '';
			?>
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Slug', 'label' );?></strong></label>
            		<input type="text" name="wdt_args[text][rewrite][slug]" id="rewrite_slug" class="postbox" value="<?php wdt_esc_e(!empty($slug) ? $slug : '', 'text');?>" />
               </div>
   			   <div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'With Front', 'label' );?></strong></label>
                    <select name="wdt_args[select][with_front]" id="rewrite_with_front" class="postbox">
                        <option value="true"<?php wdt_esc_e($with_front == 'true' ? ' selected="selected"' : '', 'text');?>>
							<?php wdt_esc_e( 'True', 'label' );?>
                        </option>
                        <option value="false"<?php wdt_esc_e($with_front == 'false' ? ' selected="selected"' : '', 'text');?>>
							<?php wdt_esc_e( 'False', 'label' );?>
                        </option>
                    </select>
               </div>
   			   <div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Feeds', 'label' );?></strong></label>
                    <select name="wdt_args[select][feeds]" id="rewrite_feeds" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Default(has_archive)', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($feeds == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($feeds == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
               </div>
   			   <div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Pages', 'label' );?></strong></label>
                    <select name="wdt_args[select][pages]" id="rewrite_pages" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($pages == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($pages == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'EP Mask', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][rewrite][ep_mask]" id="rewrite_ep_mask" class="postbox" value="<?php wdt_esc_e(!empty($ep_mask) ? $ep_mask : '');?>" />
               </div>
            </div>
			<?php
		}
		
		// dashboar custom taxonomy column and filter options html
		function wdt_column_filter_options_html($post){
			$manage_column = get_post_meta( $post->ID, 'manage_column', true );
			$manage_checked = $manage_column == 1 ? ' checked="checked"' : '';
			$filter_option = get_post_meta( $post->ID, 'filter_option', true );
			$filter_checked = $filter_option == 1 ? ' checked="checked"' : '';
			?>
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <input type="checkbox" name="wdt_args[checkbox][manage_column]" id="manage_column_<?php echo $post->ID;?>"<?=$manage_checked;?> class="postbox" value="1" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Manage column option', 'label' );?></strong></label>
               </div>
				<div class="input-wrp">
                    <input type="checkbox" name="wdt_args[checkbox][filter_option]" id="filter_option_<?php echo $post->ID;?>"<?=$filter_checked;?> class="postbox" value="1" />
               		<label for="wdt_field"><strong><?php wdt_esc_e( 'Filter option', 'label' );?></strong></label>
                </div>
            </div>
			<?php
		}
		// dashboar custom taxonomy terms html
		function wdt_default_term_html($post){
			$args = get_post_meta( $post->ID, 'args', true );
			$args = json_decode(stripslashes($args), true);
			$default_term = isset($args['default_term']) ? $args['default_term'] : '';
			$name = isset($default_term['name']) ? $default_term['name'] : '';
			$slug = isset($default_term['slug']) ? $default_term['slug'] : '';
			$description = isset($default_term['description']) ? $default_term['description'] : '';
			?>
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Term Name', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][default_term][name]" id="name" class="postbox" value="<?php wdt_esc_e(!empty($name) ? $name : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Term Slug', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][default_term][slug]" id="slug" class="postbox" value="<?php wdt_esc_e(!empty($slug) ? $slug : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Term Description', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][default_term][description]" id="description" class="postbox" value="<?php wdt_esc_e(!empty($description) ? $description : '');?>" />
               </div>
            </div>
			<?php
		}
		// dashboar custom taxonomy advance html
		function wdt_custom_others_html($post){
			$taxonomy_key = get_post_meta( $post->ID, 'taxonomy_key', true );
			$labels = get_post_meta( $post->ID, 'labels', false );
			$labels = isset($labels[0]) && !is_array($labels[0]) ? json_decode($labels[0], true) : $labels;
			$singular_name = isset($labels['singular_name']) ? $labels['singular_name'] : '';
			$description = get_post_meta( $post->ID, 'description', true );			
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
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Taxonomy key:', 'label' );?> <span class="required">*</span></strong></label>
                    <input type="text" name="wdt_args[text][taxonomy_key]" id="taxonomy_key" required="required" class="postbox" value="<?php wdt_esc_e(!empty($taxonomy_key) ? $taxonomy_key : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Singular Label:', 'label' );?> <span class="required">*</span></strong></label>
                    <input type="text" name="wdt_args[text][labels][singular_name]" id="singular_name" required="required" class="postbox" value="<?php wdt_esc_e(!empty($singular_name) ? $singular_name : '');?>" />
               </div>
               <div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Post Type Description', 'label' );?></strong></label>
                    <textarea name="wdt_args[textarea][description]"><?php wdt_esc_e(!empty($description) ? $description : '');?></textarea>
               </div>     
            </div>
			<?php
		}
		// dashboar custom taxonomy labels html
		function wdt_custom_labels_html($post){
			$args = get_post_meta( $post->ID, 'args', true );
			$args = json_decode(stripslashes($args), true);
			$labels = isset($args['labels']) ? $args['labels'] : '';
			$search_items = isset($labels['search_items']) ? $labels['search_items'] : '';
			$all_items = isset($labels['all_items']) ? $labels['all_items'] : '';
			$view_item = isset($labels['view_item']) ? $labels['view_item'] : '';
			$parent_item = isset($labels['parent_item']) ? $labels['parent_item'] : '';
			$parent_item_colon = isset($labels['parent_item_colon']) ? $labels['parent_item_colon'] : '';
			$edit_item = isset($labels['edit_item']) ? $labels['edit_item'] : '';
			$update_item = isset($labels['update_item']) ? $labels['update_item'] : '';
			$add_new_item = isset($labels['add_new_item']) ? $labels['add_new_item'] : '';
			$new_item_name = isset($labels['new_item_name']) ? $labels['new_item_name'] : '';
			$back_to_items = isset($labels['back_to_items']) ? $labels['back_to_items'] : '';
			$separate_items_with_commas = isset($labels['separate_items_with_commas']) ? $labels['separate_items_with_commas'] : '';
			$add_or_remove_items = isset($labels['add_or_remove_items']) ? $labels['add_or_remove_items'] : '';
			$choose_from_most_used = isset($labels['choose_from_most_used']) ? $labels['choose_from_most_used'] : '';
			$not_found = isset($labels['not_found']) ? $labels['not_found'] : '';
			$no_terms = isset($labels['no_terms']) ? $labels['no_terms'] : '';
			$filter_by_item = isset($labels['filter_by_item']) ? $labels['filter_by_item'] : '';
			$items_list_navigation = isset($labels['items_list_navigation']) ? $labels['items_list_navigation'] : '';
			$items_list = isset($labels['items_list']) ? $labels['items_list'] : '';
			$most_used = isset($labels['most_used']) ? $labels['most_used'] : '';
			$back_to_items = isset($labels['back_to_items']) ? $labels['back_to_items'] : '';
			$item_link = isset($labels['item_link']) ? $labels['item_link'] : '';
			$item_link_description = isset($labels['item_link_description']) ? $labels['item_link_description'] : '';
			?>           
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Search Items', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][search_items]" id="search_items" class="postbox" value="<?php wdt_esc_e(!empty($search_items) ? $search_items : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'All Items', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][all_items]" id="all_items" class="postbox" value="<?php wdt_esc_e(!empty($all_items) ? $all_items : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'View Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][view_item]" id="view_item" class="postbox" value="<?php wdt_esc_e(!empty($view_item) ? $view_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Parent Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][parent_item]" id="parent_item" class="postbox" value="<?php wdt_esc_e(!empty($parent_item) ? $parent_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Parent Item Colon', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][parent_item_colon]" id="parent_item_colon" class="postbox" value="<?php wdt_esc_e(!empty($parent_item_colon) ? $parent_item_colon : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Edit Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][edit_item]" id="edit_item" class="postbox" value="<?php wdt_esc_e(!empty($edit_item) ? $edit_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Update Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][update_item]" id="update_item" class="postbox" value="<?php wdt_esc_e(!empty($update_item) ? $update_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Add New Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][add_new_item]" id="add_new_item" class="postbox" value="<?php wdt_esc_e(!empty($add_new_item) ? $add_new_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'New Item Name', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][new_item_name]" id="new_item_name" class="postbox" value="<?php wdt_esc_e(!empty($new_item_name) ? $new_item_name : '');?>" /><label for="wdt_field"><strong><?php wdt_esc_e( 'Back To Items');?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][back_to_items]" id="back_to_items" class="postbox" value="<?php wdt_esc_e(!empty($back_to_items) ? $back_to_items : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Separate tags with commas', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][separate_items_with_commas]" id="separate_items_with_commas" class="postbox" value="<?php wdt_esc_e(!empty($separate_items_with_commas) ? $separate_items_with_commas : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Add or remove tags', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][add_or_remove_items]" id="add_or_remove_items" class="postbox" value="<?php wdt_esc_e(!empty($add_or_remove_items) ? $add_or_remove_items : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Choose from the most used tags', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][choose_from_most_used]" id="choose_from_most_used" class="postbox" value="<?php wdt_esc_e(!empty($choose_from_most_used) ? $choose_from_most_used : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Not Found', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][not_found]" id="not_found" class="postbox" value="<?php wdt_esc_e(!empty($not_found) ? $not_found : '');?>" />
     
     
     
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'No tags\'/\'No categories', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][no_terms]" id="no_terms" class="postbox" value="<?php wdt_esc_e(!empty($no_terms) ? $no_terms : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Filter by', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][filter_by_item]" id="filter_by_item" class="postbox" value="<?php wdt_esc_e(!empty($filter_by_item) ? $filter_by_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Item List Navigation', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][items_list_navigation]" id="items_list_navigation" class="postbox" value="<?php wdt_esc_e(!empty($items_list_navigation) ? $items_list_navigation : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Items List', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][items_list]" id="items_list" class="postbox" value="<?php wdt_esc_e(!empty($items_list) ? $items_list : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Most Used', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][most_used]" id="most_used" class="postbox" value="<?php wdt_esc_e(!empty($most_used) ? $most_used : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Back To Items', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][back_to_items]" id="back_to_items" class="postbox" value="<?php wdt_esc_e(!empty($back_to_items) ? $back_to_items : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Item Link', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][args][labels][item_link]" id="item_link" class="postbox" value="<?php wdt_esc_e(!empty($item_link) ? $item_link : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Item Link Description', 'label' );?></strong></label>
					<input type="text" name="wdt_args[text][args][labels][item_link_description]" id="item_link_description" class="postbox" value="<?php wdt_esc_e( !empty($item_link_description) ? $item_link_description : '');?>" />
                      
                </div>
            </div>
            <?php
		}
		// dashboar custom taxonomy args html
		function wdt_custom_args_html($post){
			$hierarchical = get_post_meta( $post->ID, 'hierarchical', true );
			$public = get_post_meta( $post->ID, 'public', true );
			$publicly_queryable = get_post_meta( $post->ID, 'publicly_queryable', true );
			$show_ui = get_post_meta( $post->ID, 'show_ui', true );
			$show_in_menu = get_post_meta( $post->ID, 'show_in_menu', true );
			$show_in_nav_menus = get_post_meta( $post->ID, 'show_in_nav_menus', true );
			$show_admin_column = get_post_meta( $post->ID, 'show_admin_column', true );
			$query_var = get_post_meta( $post->ID, 'query_var', true );
			$show_tagcloud = get_post_meta( $post->ID, 'show_tagcloud', true );
			$show_in_quick_edit = get_post_meta( $post->ID, 'show_in_quick_edit', true );
			$sort = get_post_meta( $post->ID, 'sort', true );
			$_builtin = get_post_meta( $post->ID, '_builtin', true );
			$show_in_rest = get_post_meta( $post->ID, 'show_in_rest', true );

			?>
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Hierarchical', 'label' );?></strong></label>
                    <select name="wdt_args[select][hierarchical]" id="wdt_hierarchical" class="postbox">
                        <option value="true"<?php wdt_esc_e($hierarchical == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($hierarchical == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Public', 'label' );?></strong></label>
                    <select name="wdt_args[select][public]" id="wdt_public" class="postbox">
                        <option value="true"<?php wdt_esc_e($public == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($public == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Public queryable', 'label' );?></strong></label>
                    <select name="wdt_args[select][publicly_queryable]" id="publicly_queryable" class="postbox">
                        <option value="true"<?php wdt_esc_e($publicly_queryable == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($publicly_queryable == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show ui', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_ui]" id="wdt_show_ui" class="postbox">
                        <option value="true"<?php wdt_esc_e($show_ui == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_ui == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in the admin menu', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_menu]" id="show_in_menu" class="postbox">
                        <option value="true"<?php wdt_esc_e($show_in_menu == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_menu == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc( 'Show  in navigation menus', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_nav_menus]" id="show_in_nav_menus" class="postbox">
                        <option value="true"<?php wdt_esc_e($show_in_nav_menus == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_nav_menus == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show admin column', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_admin_column]" id="wdt_show_in_menu" class="postbox">
                        <option value="true"<?php wdt_esc_e($show_admin_column == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_admin_column == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Query var', 'label' );?></strong></label>
                    <select name="wdt_args[select][query_var]" id="query_var" class="postbox">
                        <option value="true"<?php wdt_esc_e($query_var == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($query_var == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in tag cloud', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_tagcloud]" id="show_tagcloud" class="postbox">
                        <option value="true"<?php wdt_esc_e($show_tagcloud == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_tagcloud == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in quick edit', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_quick_edit]" id="show_in_quick_edit" class="postbox">
                        <option value="true"<?php wdt_esc_e($show_in_quick_edit == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_quick_edit == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Sort', 'label' );?></strong></label>
                    <select name="wdt_args[select][sort]" id="sort" class="postbox">
                        <option value="true"<?php wdt_esc_e($sort == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($sort == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Builtin', 'label' );?></strong></label>
                    <select name="wdt_args[select][_builtin]" id="_builtin" class="postbox">
                        <option value="true"<?php wdt_esc_e($_builtin == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($_builtin == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in rest', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_rest]" id="wdt_show_in_rest" class="postbox">
                        <option value="true"<?php wdt_esc_e($show_in_rest == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_rest == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                </div>
            </div>
            <?php
		}
	}
	new WDT_CUSTUM_POST_TAXONOMY();
}