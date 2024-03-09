<?php
/**
 * Plugin Name: WD Types
 * Description: “WD Types” is s WordPress plugin that can be used: to create custom post types;  to create custom taxonomies (eg: categories, tags, etc.); to create custom meta fields and custom groups to manage meta
 * Plugin URI:  
 * Version:     1.1
 * Author: <a href="https://business.fiverr.com/freelancers/webdnix">WDSeller</a>
 * Tags: custom post, post type, custom taxonomy, post taxonomy, add taxonomy, custom meta, post meta, add post meta, custom post type
 * Requires at least: 5.0
 * Tested up to: 6.0
 * Author URI: https://wdseller.com
 * Text Domain: wdtypes
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//use Pluggable\Plugin\License;

//require_once 'vendor/autoload.php';

if ( ! defined( 'WDT_POST_TYPE' ) ) define('WDT_POST_TYPE', 'wdtypes');
if ( ! defined( 'WDT_DIR' ) ) define('WDT_DIR', plugin_dir_path(__FILE__));
// initialize the plugin 
final class WDT_Post_Types {

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}
	//include plugins all required files
	public function init() {
		global $plugin;
	
		$activate = __FILE__; 	// reference to the main plugin file. required.
		$args 	= [		// some settings. optional.
			'hide_notice'	=> true,	// Should we hide the license notice from the dashboard? Default is `false`.
			'redirect'	=> admin_url( 'edit.php?post_type=wdtypes&page=wdt_license' ), // Where should it take after activating the license? Default is the current page
		];
	
		//$plugin = new License( $activate, $args );
		require_once( WDT_DIR.'inc/functions.php' );
		require_once( WDT_DIR.'inc/classes/wd-post.php' );
		require_once( WDT_DIR.'inc/classes/wd-group.php' );
		require_once( WDT_DIR.'inc/classes/wd-meta.php' );
		require_once( WDT_DIR.'inc/classes/wd-taxonomy.php' );
		require_once( WDT_DIR.'inc/classes/wd-types.php' );
		require_once( WDT_DIR.'inc/classes/wd-shortcode.php' );
		wdt_inc_pro();
		//wdt_license_validation();
	}
}
new WDT_Post_Types();
