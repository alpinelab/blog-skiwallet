<?php
/**
 * SG CachePress
 *
 * @package           SG_CachePress
 * @author            George Penkov
 * @author            Gary Jones <gary@gamajo.com>
 * @link              http://www.siteground.com/
 *
 * @wordpress-plugin
 * Plugin Name:       SG CachePress
 * Description:       Through the settings of this plugin you can manage how your Wordpress interracts with SG Cache. Before you can use this plugin you need to have the SG Cache service installed and activated.
 * Version:           2.1.0
 * Author:            George Penkov
 * Text Domain:       sg-cachepress
 * Domain Path:       /languages
 */

// Load the auto-update class
add_action('init', 'sg_update_check');
function sg_update_check()
{
    require_once ('class-sg-autoupdate.php');
    $sg_plugin_current_version = '2.1.0';
    $sg_plugin_remote_path = 'http://download.siteground.com/cacheupdate.php';
    $sg_plugin_slug = plugin_basename(__FILE__);
    new sg_auto_update ($sg_plugin_current_version, $sg_plugin_remote_path, $sg_plugin_slug);
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// @todo Consider an autoloader?
require plugin_dir_path( __FILE__ ) . 'class-sg-cachepress.php';
require plugin_dir_path( __FILE__ ) . 'class-sg-cachepress-options.php';
require plugin_dir_path( __FILE__ ) . 'class-sg-cachepress-environment.php';
require plugin_dir_path( __FILE__ ) . 'class-sg-cachepress-supercacher.php';
require plugin_dir_path( __FILE__ ) . 'class-sg-cachepress-memcache.php';
require plugin_dir_path( __FILE__ ) . 'class-sg-cachepress-admin.php';


// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'SG_CachePress', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'SG_CachePress', 'deactivate' ) );

add_action( 'plugins_loaded','sg_cachepress_start' );
/**
 * Initialise the classes in this plugin.
 *
 * @since 1.1.0
 *
 * @todo Consider moving this to a dependency injection container, so we can avoid globals?
 */
function sg_cachepress_start() {

 	global $sg_cachepress, $sg_cachepress_options, $sg_cachepress_environment, $sg_cachepress_memcache,
 	$sg_cachepress_admin, $sg_cachepress_supercacher;

	$sg_cachepress_options        = new SG_CachePress_Options;
	$sg_cachepress                = new SG_CachePress( $sg_cachepress_options );
	$sg_cachepress_environment    = new SG_CachePress_Environment( $sg_cachepress_options );
	$sg_cachepress_admin    		= new SG_CachePress_Admin( $sg_cachepress_options );
	$sg_cachepress_memcache       = new SG_CachePress_Memcache( $sg_cachepress_options, $sg_cachepress_environment );
	$sg_cachepress_supercacher    = new SG_CachePress_Supercacher( $sg_cachepress_options, $sg_cachepress_environment );

	$sg_cachepress->run();
	$sg_cachepress_admin->run();

	if ( $sg_cachepress_environment->cache_is_enabled() ){
		if ( $sg_cachepress_environment->autoflush_enabled() ){
			$sg_cachepress_supercacher->run();
		}
	}

	if ( $sg_cachepress_environment->memcached_is_enabled() ){
		$sg_cachepress_memcache->run();
	}
}
