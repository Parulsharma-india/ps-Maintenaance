<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/ps-maintenaance
 * @since             1.0.0
 * @package           PS_Maintenaance
 *
 * @wordpress-plugin
 * Plugin Name:       PS maintenaance
 * Plugin URI:        https://ps-maintenaance
 * Description:       Adds a splash page to your site to inform visitors that your site is temporarily down for maintenance. Ideal for a 'Coming Soon' or landing page.
 * Version:           1.0.0
 * Author:            Parul Sharma
 * Author URI:        https://wordpress.org/plugins/ps-maintenaance/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ps-maintenaance
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PS_MAINTENAANCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ps-maintenaance-activator.php
 */
function ps_maintenaance_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ps-maintenaance-activator.php';
	PS_Maintenaance_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ps-maintenaance-deactivator.php
 */
function ps_maintenaance_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ps-maintenaance-deactivator.php';
	PS_Maintenaance_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ps_maintenaance_activate' );
register_deactivation_hook( __FILE__, 'ps_maintenaance_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ps-maintenaance.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function ps_maintenaance_run() {

	$plugin = new PS_Maintenaance();
	$plugin->run();
}
ps_maintenaance_run();

