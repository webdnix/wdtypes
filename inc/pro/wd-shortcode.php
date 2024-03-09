<?php
if(!class_exists('WDT_POSTS_SHORTCODE')){
	class WDT_POSTS_SHORTCODE{
		function __construct() {
			add_shortcode('wdcode', array($this, 'wdt_shortcode_handler'));
			add_action('admin_menu', array($this, 'wdt_menu_handler'));
		}
		function wdt_shortcode_handler($atts){
			extract( shortcode_atts( array(
									  'name' => '',
									  'type' => 'meta', 
									  'format' => '', 
									  'tag' => '',
  									  'style' => '',
									  'class' => '',
									  'id' => ''
								  ), $atts ));
			if(empty($name)) return;					  
			ob_start();
			$file = empty($format) ? $type : $type.'-'.$format;
			require( WDT_DIR.'inc/pro/templates/shortcode/'.$file.'.php' );
			return ob_get_clean();					  
		}
		function wdt_menu_handler(){
			add_submenu_page(
				'edit.php?post_type=wdtypes',
				__( 'Post Types Shortcodes', WDT_POST_TYPE ),
				__( 'Shortcodes', WDT_POST_TYPE ),
				'manage_options',
				'wdt_shortcode',
				array($this, 'wdt_shortcode_callback'),
				33
			);
		}
		function wdt_shortcode_callback(){
			$args = array(
				'numberposts' => -1,
				'post_type' => 'wdtypes',
				'post_status' => 'publish',
			);
			$types = get_posts( $args );
			if ( $types ) {
				foreach ( $types as $type ){
					$this->wdt_list_group_code( $type );
					$this->wdt_list_tax_code( $type );
					$this->wdt_list_meta_code( $type );
				}
			}
			?>
            <style>
				.input-wrp {
					width: 100%;
					margin-bottom: 20px;
				}
			</style>
             <script type="text/javascript">		
					jQuery(document).ready(function() {
						jQuery(document).on('change', '#type', function() {
							var id = jQuery('#type option:selected').val();
							jQuery('.select-type').css("display", "none");
							jQuery('#'+id).css("display", "block");						
						});
					})
				</script>
                <h4>Create Shortcode</h4>
                <label for="type">Shortcode Type</label>
                <select id="type">
                	<option value="">Select</option>
                	<option value="meta">Meta</option>
                	<option value="taxo">Taxonomy</option>
                	<option value="data">Post Data</option>
                </select>
                <div id="meta" class="select-type" style="display:none;">
                	<label for="type">Meta Name</label>
                    <select id="meta_name">
                        <option value="meta">Select</option>
                        <option value="meta">Meta</option>
                        <option value="meta">Taxonomy</option>
                        <option value="meta">Post Data</option>
                    </select>
                    <label for="type">Meta Format</label>
                    <select id="meta_format">
                        <option value="meta">Select</option>
                        <option value="free">Free Text</option>
                        <option value="meta">Edit Able</option>                    
                    </select>
                	<label for="type">Tag</label>
                    <select id="meta_tag">
                        <option value="meta">Select</option>
                        <option value="meta">Meta</option>
                        <option value="meta">Taxonomy</option>
                        <option value="meta">Post Data</option>
                    </select>
                	<label for="type">Class</label>
                    <input id="meta_class" value="" />
                	<label for="type">ID</label>
                    <input id="meta_id" value="" />
                	<label for="type">Style</label>
                    <textarea id="meta_style"></textarea>
                </div>
                <div id="taxo" class="select-type" style="display:none;">
                	<label for="type">Taxonomy Name</label>
                    <select id="meta_name">
                        <option value="meta">Select</option>
                        <option value="meta">Meta</option>
                        <option value="meta">Taxonomy</option>
                        <option value="meta">Post Data</option>
                    </select>
                </div>

                <div id="data" class="select-type" style="display:none;">
                	<label for="type">Post</label>
                    <select id="meta_name">
                        <option value="meta">Select</option>
                        <option value="meta">Title</option>
                        <option value="meta">Content</option>
                        <option value="meta">Feature Image</option>
                    </select>
                </div>

            <?php
		}
		function wdt_list_group_code($type){}
		function wdt_list_tax_code($type){}
		function wdt_list_meta_code($type){}
	}
	new WDT_POSTS_SHORTCODE();
}