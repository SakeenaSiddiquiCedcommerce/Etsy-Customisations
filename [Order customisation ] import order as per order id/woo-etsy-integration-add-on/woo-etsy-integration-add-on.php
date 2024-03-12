<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cedcommerce.com/
 * @since             1.0.0
 * @package           Woo_Etsy_Integration_Add_On
 *
 * @wordpress-plugin
 * Plugin Name:       Etsy Integration Add On 
 * Plugin URI:        https://cedcommerce.com/
 * Description:       This plugin contains customization regarding, upload variation images and many more.....
 * Version:           1.0.0
 * Author:            cedcommerce
 * Author URI:        https://cedcommerce.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-etsy-integration-add-on
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOO_ETSY_INTEGRATION_ADD_ON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-etsy-integration-add-on-activator.php
 */
function activate_woo_etsy_integration_add_on() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-etsy-integration-add-on-activator.php';
	Woo_Etsy_Integration_Add_On_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-etsy-integration-add-on-deactivator.php
 */
function deactivate_woo_etsy_integration_add_on() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-etsy-integration-add-on-deactivator.php';
	Woo_Etsy_Integration_Add_On_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_etsy_integration_add_on' );
register_deactivation_hook( __FILE__, 'deactivate_woo_etsy_integration_add_on' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-etsy-integration-add-on.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_etsy_integration_add_on() {

	$plugin = new Woo_Etsy_Integration_Add_On();
	$plugin->run();

}
run_woo_etsy_integration_add_on();
