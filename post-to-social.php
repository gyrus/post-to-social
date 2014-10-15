<?php

/**
 * Post to Social
 *
 * @package   Post_to_Social
 * @author    Steve Taylor
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:			Post to Social
 * Description:			A WordPress plugin for auto-posting to social networks.
 * Version:				0.1
 * Author:				Steve Taylor
 * Text Domain:			post-to-social-locale
 * License:				GPL-2.0+
 * License URI:			http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:			/lang
 * GitHub Plugin URI:	https://github.com/gyrus/post-to-social
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-post-to-social.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Post_to_Social', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Post_to_Social', 'deactivate' ) );

Post_to_Social::get_instance();
