<?php
//Class to create type of custom post in dashboard
if(!class_exists('WDT_CUSTUM_POST')){
	class WDT_CUSTUM_POST{
		function __construct() {
			add_action( 'init', array($this, 'wdt_post_types') );
			add_action( 'add_meta_boxes', array($this, 'wdt_add_boxes') );
		}
		//register type post
		function wdt_post_types(){
		   
		   $labels = array(
				'name'               => _x( 'Post Type', 'post type general name', WDT_POST_TYPE ),
				'singular_name'      => _x( 'Post Type', 'post type singular name', WDT_POST_TYPE ),
				'menu_name'          => _x( 'Post Types', 'admin menu', WDT_POST_TYPE ),
				'name_admin_bar'     => _x( 'Post Type', 'add new on admin bar', WDT_POST_TYPE ),
				'add_new'            => _x( 'Add New', 'Post Type', WDT_POST_TYPE ),
				'add_new_item'       => __( 'Add New Type', WDT_POST_TYPE ),
				'new_item'           => __( 'New Post Type', WDT_POST_TYPE ),
				'edit_item'          => __( 'Edit Post Type', WDT_POST_TYPE ),
				'view_item'          => __( 'View Post Type', WDT_POST_TYPE ),
				'all_items'          => __( 'All Types', WDT_POST_TYPE ),
				'search_items'       => __( 'Search Post Type', WDT_POST_TYPE ),
				'not_found'          => __( 'No Post Type found.', WDT_POST_TYPE ),
				'not_found_in_trash' => __( 'No Post Type found in Trash.', WDT_POST_TYPE )
			);
		    $args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', 'Add New Custom Post', WDT_POST_TYPE ),
				'public'             => true,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => false,
				'rewrite'            => array( 'slug' => 'wdtypes' ),
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => 10,
				'menu_icon'          =>'dashicons-plus',
				'supports'           => array( 'title' ),
			);
			register_post_type( 'wdtypes', $args );
		}
		//add required meta in post type
		function wdt_add_boxes(){
			add_meta_box(
				'wdt_post_other_args',                 
				__( 'Custom Post', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_others_html'), 
				'wdtypes',
				'normal'                      
			);
			add_meta_box(
				'wdt_post_labels',                 
				__( 'Custom Post Labels', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_labels_html'), 
				'wdtypes',
				'normal'                      
			);
			add_meta_box(
				'wdt_post_args',                 
				__( 'Custom Post(True/False)', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_args_html'), 
				'wdtypes',
				'side'                         
			);
			add_meta_box(
				'wdt_post_rewrite_args',                 
				__( 'Custom Post Rewrite', WDT_POST_TYPE ),     
				array($this, 'wdt_rewrite_html'), 
				'wdtypes',
				'side'                         
			);
			add_meta_box(
				'wdt_post_supports_args',                 
				__( 'Custom Post Supports', WDT_POST_TYPE ),     
				array($this, 'wdt_supports_html'), 
				'wdtypes',
				'side'                         
			);
			add_meta_box(
				'wdt_post_custom_taxonomies',                 
				__( 'Assign Taxonomies', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_taxonomies_html'), 
				'wdtypes',
				'side'                      
			);
			add_meta_box(
				'wdt_post_custom_metas',                 
				__( 'Assign Meta', WDT_POST_TYPE ),     
				array($this, 'wdt_custom_meta_html'), 
				'wdtypes',
				'side'                      
			);
		}
		// dashboar taxonomies of custom post html
		function wdt_custom_taxonomies_html($post){
			$_taxonomies = get_post_meta( $post->ID, 'taxonomies', false );
			$_taxonomies = isset($_taxonomies[0]) && !is_array($_taxonomies[0]) ? json_decode($_taxonomies[0], true) : $_taxonomies;
			//echo '<pre>'.$post->ID;print_r($_taxonomies);echo '</pre>';
			$category = isset($_taxonomies['category']) && $_taxonomies['category'] == '1-d' ? ' checked="checked"' : '';
			$post_tag = isset($_taxonomies['post_tag']) && $_taxonomies['post_tag'] == '1-d' ? ' checked="checked"' : '';
			$args = array(
				'post_type' => 'wdt_taxonomies',
				'post_status' => 'publish',
			);
			$taxonomies = get_posts( $args );
			$cboxes = '';
			if ( $taxonomies ) {
				foreach ( $taxonomies as $taxonomy ){
					$args = get_post_meta( $taxonomy->ID, 'args', true );
					$args = !is_array($args) ? json_decode($args, true) : $args;
					//$taxonomy_key = isset($args['taxonomy_key']) ? $args['taxonomy_key'] : '';
					$taxonomy_key = get_post_meta( $post->ID, 'taxonomy_key', true );
					$taxonomy_key = !empty($taxonomy_key) ? $taxonomy_key : $taxonomy->post_name;
					$checked = isset($_taxonomies[$taxonomy_key]) && $_taxonomies[$taxonomy_key] == $taxonomy->ID ? ' checked="checked"' : '';
					$cboxes .= '<div class="input-wrp">
								  <input type="checkbox" name="wdt_args[text][taxonomies]['.$taxonomy_key.']"'.$checked.' value="'.$taxonomy->ID.'" id="'.$taxonomy_key.'" class="checkbox">
								  <label for="wdt_field"><strong>'.wdt_esc( $taxonomy->post_title).'</strong></label>
							   </div>';		   
				}
			}
			$output = '<div class="inputs-wrp">
							<div class="input-wrp">
							  <input type="checkbox" name="wdt_args[text][taxonomies][category]"'.$category.' value="1-d" id="taxonomies_category" class="checkbox">
							  <label for="wdt_field"><strong>'.wdt_esc('Category').'</strong></label>
						   </div>
						   <div class="input-wrp">
							  <input type="checkbox" name="wdt_args[text][taxonomies][post_tag]"'.$post_tag.' value="1-d" id="taxonomies_post_tag" class="checkbox">
							  <label for="wdt_field"><strong>'.wdt_esc('Tag').'</strong></label>
						   </div>'.$cboxes.'
						</div>';	
				wdt_esc_e($output, 'raw');				
		}
		// dashboar meta of custom post html
		function wdt_custom_meta_html($post){
			$meta = get_post_meta( $post->ID, 'meta', false );
			$cgroup = get_post_meta( $post->ID, 'group', false );
			$meta = isset($meta[0]) && !is_array($meta[0]) ? json_decode($meta[0], true) : $meta;
			$cgroup = isset($cgroup[0]) && !is_array($cgroup[0]) ? json_decode($cgroup[0], true) : $cgroup;
			$group = array(
				'numberposts' => -1,
				'post_type' => 'wdt_group',
				'post_status' => 'publish',
			);
			$grouplist = get_posts( $group );
			$args = array(
				'numberposts' => -1,
				'post_type' => 'wdt_meta',
				'post_status' => 'publish',
			);
			$metalist = get_posts( $args );
			if ( $metalist ) {
				?>
                <table class="inputs-wrp" style="width: 100%;">
                <?php
    			foreach ( $metalist as $post ){
					$select = '';
					$sel = '';
					$_group = 'none';
				  if ( $grouplist ) {
					  $cg = isset($cgroup[$post->ID]) ? $cgroup[$post->ID] : '';
					  $select = '<select name="wdt_args[select][group]['.$post->ID.']"  id="group-'.wdt_esc($post->ID, 'text').'" class="wdt_select wdt_field">';
					  $select .= '<option value="">'.wdt_esc("Select Group", 'label' ).'</option>';
					  foreach ( $grouplist as $group ){
						  $sel = $cg == $group->ID  && in_array($post->ID, $meta)  ? ' selected="selected"' : '';
						  $select .= '<option data-check="'.$cg.' == '.$group->ID.'" value="'.wdt_esc($group->ID, 'text').'"'.$sel.'>'.wdt_esc($group->post_title, 'label' ).'</option>';
					  }
					  $select .= '</select>';
				  }
				?>
					<tr class="input-wrp">
						<td style="width: 20px;">
                        <input type="checkbox" name="wdt_args[text][meta][]" value="<?php wdt_esc_e($post->ID);?>" id="<?php wdt_esc_e($post->post_name);?>" class="checkbox"<?php wdt_esc_e(in_array($post->ID, $meta) ? 'checked="checked"' : '');?> /></td>
						<td><label for="wdt_field"><strong><?php wdt_esc_e( $post->post_title, 'label' );?></strong></label></td>
                        <td style="width: 50%;"><?php _e($select);?></td>
				    </tr>
               
           		<?php
				}
			}
			?>
            </table>    
            <?php
		}
		// dashboar custom post support html
		function wdt_supports_html($post){
			$supports = get_post_meta( $post->ID, 'supports', false );
			$supports = isset($supports[0]) && !is_array($supports[0]) ? json_decode($supports[0], true) : $supports;
			$title = isset($supports['title']) ? $supports['title'] : '';
			$editor = isset($supports['editor']) ? $supports['editor'] : '';
			$author = isset($supports['author']) ? $supports['author'] : '';
			$thumbnail = isset($supports['thumbnail']) ? $supports['thumbnail'] : '';
			$excerpt = isset($supports['excerpt']) ? $supports['excerpt'] : '';
			$comments = isset($supports['excerpt']) ? $supports['excerpt'] : '';
			?>
            <div class="inputs-wrp">
				<div class="input-wrp">
                    <input type="checkbox" name="wdt_args[text][supports][title]"<?php wdt_esc_e($title == 1 ? ' checked="checked"' : '');?> value="1" id="rewrite_supports_title" class="checkbox" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Title', 'label' );?></strong></label>
               </div>
               <div class="input-wrp">
                    <input type="checkbox" name="wdt_args[text][supports][editor]"<?php wdt_esc_e($editor == 1 ? ' checked="checked"' : '');?> value="1" id="rewrite_supports_editor" class="checkbox" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Editor', 'label' );?></strong></label>
               </div>
               <div class="input-wrp">
                    <input type="checkbox" name="wdt_args[text][supports][author]"<?php wdt_esc_e($author == 1 ? ' checked="checked"' : '');?> value="1" id="rewrite_supports_author" class="checkbox" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Author', 'label' );?></strong></label>
               </div>
               <div class="input-wrp">
                    <input type="checkbox" name="wdt_args[text][supports][thumbnail]"<?php wdt_esc_e($thumbnail == 1 ? ' checked="checked"' : '');?> value="1" id="rewrite_supports_thumbnail" class="checkbox" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Thumbnail', 'label' );?></strong></label>
               </div>
               <div class="input-wrp">
                    <input type="checkbox" name="wdt_args[text][supports][excerpt]"<?php wdt_esc_e($excerpt == 1 ? ' checked="checked"' : '');?> value="1" id="rewrite_supports_excerpt" class="checkbox" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Excerpt', 'label' );?></strong></label>
               </div>
               <div class="input-wrp">
                    <input type="checkbox" name="wdt_args[text][supports][comments]"<?php wdt_esc_e($comments == 1 ? ' checked="checked"' : '');?> value="1" id="rewrite_supports_comments" class="checkbox" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Comments', 'label' );?></strong></label>
               </div>
            </div>   
            <?php
		}
		// dashboar custom post rewrite html
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
		// dashboar custom post other fields html
		function wdt_custom_others_html($post){
			$labels = get_post_meta( $post->ID, 'labels', false );
			$labels = isset($labels[0]) && !is_array($labels[0]) ? json_decode($labels[0], true) : $labels;
			$labels = !is_array($labels) ? json_decode($labels, true) : $labels;
			$singular_name = isset($labels['singular_name']) ? $labels['singular_name'] : '';
			$post_type = get_post_meta( $post->ID, 'post_type', true );
			$menu_position = get_post_meta( $post->ID, 'menu_position', true );
			$menu_icon = get_post_meta( $post->ID, 'menu_icon', true );
			$description = get_post_meta( $post->ID, 'description', true );
			?>
            <style>
				.inputs-wrp .input-wrp {
					width: 100%;
					margin-bottom: 10px;
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
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Post type:', 'label' );?> <span class="required">*</span></strong></label>
                    <input type="text" name="wdt_args[text][post_type]" id="post_type" required="required" placeholder="Post Type Key(eg:post-name)" class="postbox" value="<?php wdt_esc_e(!empty($post_type) ? $post_type : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Singular Label:', 'label' );?> <span class="required">*</span></strong></label>
                    <input type="text" name="wdt_args[text][labels][singular_name]" id="singular_name" required="required" class="postbox" value="<?php wdt_esc_e(!empty($singular_name) ? $singular_name : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Menu Position', 'label' );?></strong></label>
                    <div class="toltip" style="display:none">
                    5 – below Posts
                    10 – below Media
                    15 – below Links
                    20 – below Pages
                    25 – below comments
                    60 – below first separator
                    65 – below Plugins
                    70 – below Users
                    75 – below Tools
                    80 – below Settings
                    100 – below second separator
                    </div>
                    <input type="number" name="wdt_args[text][menu_position]" id="menu_position" class="postbox" value="<?php wdt_esc_e(!empty($menu_position) ? $menu_position : '');?>" />
               </div>
				<div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Menu Icon', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][menu_icon]" id="menu_icon" class="postbox" value="<?php wdt_esc_e(!empty($menu_icon) ? $menu_icon : '');?>" />
               </div>
               <div class="input-wrp">
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Post Type Description', 'label' );?></strong></label>
                    <textarea name="wdt_args[textarea][description]"><?php wdt_esc_e(!empty($description) ? $description : '', 'textarea');?></textarea>
               </div>     
            </div>
			<?php
		}
		// dashboar custom post advance fields html
		function wdt_custom_labels_html($post){
			$labels = get_post_meta( $post->ID, 'labels', false );
			$labels = !is_array($labels) ? json_decode($labels, true) : $labels;
			if(isset($labels[0])){
				$labels = $labels[0];
				$labels = !is_array($labels) ? json_decode($labels, true) : $labels;
				$capability_type = isset($labels['capability_type']) ? $labels['capability_type'] : '';
				$menu_name = isset($labels['menu_name']) ? $labels['menu_name'] : '';
				$name_admin_bar = isset($labels['name_admin_bar']) ? $labels['name_admin_bar'] : '';
				$parent_item_colon = isset($labels['parent_item_colon']) ? $labels['parent_item_colon'] : '';
				$all_items = isset($labels['all_items']) ? $labels['all_items'] : '';
				$view_item = isset($labels['view_item']) ? $labels['view_item'] : '';
				$add_new_item = isset($labels['add_new_item']) ? $labels['add_new_item'] : '';
				$add_new = isset($labels['add_new']) ? $labels['add_new'] : '';
				
				$edit_item = isset($labels['edit_item']) ? $labels['edit_item'] : '';
				$update_item = isset($labels['update_item']) ? $labels['update_item'] : '';
				$search_items = isset($labels['search_items']) ? $labels['search_items'] : '';
				$not_found = isset($labels['not_found']) ? $labels['not_found'] : '';
				$not_found_in_trash = isset($labels['not_found_in_trash']) ? $labels['not_found_in_trash'] : '';
				$featured_image = isset($labels['featured_image']) ? $labels['featured_image'] : '';
				$set_featured_image = isset($labels['set_featured_image']) ? $labels['set_featured_image'] : '';
				$remove_featured_image = isset($labels['remove_featured_image']) ? $labels['remove_featured_image'] : '';
				$use_featured_image = isset($labels['use_featured_image']) ? $labels['use_featured_image'] : '';
				
				$archives = isset($labels['archives']) ? $labels['archives'] : '';
				$insert_into_item = isset($labels['insert_into_item']) ? $labels['insert_into_item'] : '';
				$uploaded_to_this_item = isset($labels['uploaded_to_this_item']) ? $labels['uploaded_to_this_item'] : '';
				$filter_items_list = isset($labels['filter_items_list']) ? $labels['filter_items_list'] : '';
				$items_list_navigation = isset($labels['items_list_navigation']) ? $labels['items_list_navigation'] : '';
				$items_list = isset($labels['items_list']) ? $labels['items_list'] : '';
			}
			?>           
            <div class="inputs-wrp">
				<div class="input-wrp">
                	<label for="wdt_field"><strong><?php wdt_esc_e( 'Capability Type', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][capability_type]" placeholder="Default 'post' Others page etc" id="capability_type" class="postbox" value="<?php wdt_esc_e(!empty($capability_type) ? $capability_type : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Menu Name', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][menu_name]" id="menu_name" class="postbox" value="<?php wdt_esc_e(!empty($menu_name) ? $menu_name : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Name Admin Bar', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][name_admin_bar]" id="name_admin_bar" class="postbox" value="<?php wdt_esc_e(!empty($name_admin_bar) ? $name_admin_bar : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Parent Item Colon', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][parent_item_colon]" id="parent_item_colon" class="postbox" value="<?php wdt_esc_e(!empty($parent_item_colon) ? $parent_item_colon : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'All Items', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][all_items]" id="all_items" class="postbox" value="<?php wdt_esc_e(!empty($all_items) ? $all_items : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'View Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][view_item]" id="view_item" class="postbox" value="<?php wdt_esc_e(!empty($view_item) ? $view_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Add New Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][add_new_item]" id="add_new_item" class="postbox" value="<?php wdt_esc_e(!empty($add_new_item) ? $add_new_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Add New', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][add_new]" id="add_new" class="postbox" value="<?php wdt_esc_e(!empty($add_new) ? $add_new : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Edit Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][edit_item]" id="edit_item" class="postbox" value="<?php wdt_esc_e(!empty($edit_item) ? $edit_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Update Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][update_item]" id="update_item" class="postbox" value="<?php wdt_esc_e(!empty($update_item) ? $update_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Search Items', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][search_items]" id="search_items" class="postbox" value="<?php wdt_esc_e(!empty($search_items) ? $search_items : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Not Found', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][not_found]" id="not_found" class="postbox" value="<?php wdt_esc_e(!empty($not_found) ? $not_found : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Not Found In Trash', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][not_found_in_trash]" id="not_found_in_trash" class="postbox" value="<?php wdt_esc_e(!empty($not_found_in_trash) ? $not_found_in_trash : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Featured Image Label', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][featured_image]" id="featured_image" class="postbox" value="<?php wdt_esc_e(!empty($featured_image) ? $featured_image : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Set Featured Image Label', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][set_featured_image]" id="set_featured_image" class="postbox" value="<?php wdt_esc_e(!empty($set_featured_image) ? $set_featured_image : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Remove Featured Image Label', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][remove_featured_image]" id="remove_featured_image" class="postbox" value="<?php wdt_esc_e(!empty($remove_featured_image) ? $remove_featured_image : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Use Featured Image Label', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][use_featured_image]" id="use_featured_image" class="postbox" value="<?php wdt_esc_e(!empty($use_featured_image) ? $use_featured_image : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Archives', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][archives]" id="archives" class="postbox" value="<?php wdt_esc_e(!empty($archives) ? $archives : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Insert Into Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][insert_into_item]" id="insert_into_item" class="postbox" value="<?php wdt_esc_e(!empty($insert_into_item) ? $insert_into_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Uploaded To This Item', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][uploaded_to_this_item]" id="uploaded_to_this_item" class="postbox" value="<?php wdt_esc_e(!empty($uploaded_to_this_item) ? $uploaded_to_this_item : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Filter Item List', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][filter_items_list]" id="filter_items_list" class="postbox" value="<?php wdt_esc_e(!empty($filter_items_list) ? $filter_items_list : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Item List Navigation', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][items_list_navigation]" id="items_list_navigation" class="postbox" value="<?php wdt_esc_e(!empty($items_list_navigation) ? $items_list_navigation : '');?>" />
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Item List', 'label' );?></strong></label>
                    <input type="text" name="wdt_args[text][labels][items_list]" id="items_list" class="postbox" value="<?php wdt_esc_e(!empty($items_list) ? $items_list : '');?>" />
                </div>
            </div>
            <?php
		}
		// dashboar custom post other args html
		function wdt_custom_args_html($post){
			$hierarchical = get_post_meta( $post->ID, 'hierarchical', true );
			$public = get_post_meta( $post->ID, 'public', true );
			$show_ui = get_post_meta( $post->ID, 'show_ui', true );
			$show_in_menu = get_post_meta( $post->ID, 'show_in_menu', true );
			$show_in_nav_menus = get_post_meta( $post->ID, 'show_in_nav_menus', true );
			$show_in_admin_bar = get_post_meta( $post->ID, 'show_in_admin_bar', true );
			$query_var = get_post_meta( $post->ID, 'query_var', true );
			$can_export = get_post_meta( $post->ID, 'can_export', true );
			$has_archive = get_post_meta( $post->ID, 'has_archive', true );
			$exclude_from_search = get_post_meta( $post->ID, 'exclude_from_search', true );
			$publicly_queryable = get_post_meta( $post->ID, 'publicly_queryable', true );
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
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show ui', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_ui]" id="wdt_show_ui" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($show_ui == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_ui == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in menu', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_menu]" id="wdt_show_in_menu" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($show_in_menu == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_menu == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in nav menu', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_nav_menus]" id="wdt_show_in_nav_menus" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($show_in_nav_menus == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_nav_menus == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in admin bar', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_admin_bar]" id="wdt_show_in_admin_bar" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($show_in_admin_bar == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_admin_bar == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Query var', 'label' );?></strong></label>
                    <select name="wdt_args[select][query_var]" id="wdt_query_var" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($query_var == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($can_export == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Can export', 'label' );?></strong></label>
                    <select name="wdt_args[select][can_export]" id="wdt_can_export" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($can_export == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($can_export == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Has archive', 'label' );?></strong></label>
                    <select name="wdt_args[select][has_archive]" id="wdt_has_archive" class="postbox">
                        <option value="true"<?php wdt_esc_e($has_archive == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($has_archive == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Exclude from search', 'label' );?></strong></label>
                    <select name="wdt_args[select][exclude_from_search]" id="wdt_exclude_from_search" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($exclude_from_search == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($exclude_from_search == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Publicly queryable', 'label' );?></strong></label>
                    <select name="wdt_args[select][publicly_queryable]" id="wdt_publicly_queryable" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($publicly_queryable == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($publicly_queryable == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                    <label for="wdt_field"><strong><?php wdt_esc_e( 'Show in rest', 'label' );?></strong></label>
                    <select name="wdt_args[select][show_in_rest]" id="wdt_show_in_rest" class="postbox">
                    	<option value=""><?php wdt_esc_e( 'Select', 'label' );?></option>
                        <option value="true"<?php wdt_esc_e($show_in_rest == 'true' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'True', 'label' );?></option>
                        <option value="false"<?php wdt_esc_e($show_in_rest == 'false' ? ' selected="selected"' : '', 'text');?>><?php wdt_esc_e( 'False', 'label' );?></option>
                    </select>
                </div>
            </div>
            <?php
		}
	}
	new WDT_CUSTUM_POST();
}