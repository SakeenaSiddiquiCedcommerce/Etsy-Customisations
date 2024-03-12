<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://cedcommerce.com
 * @since             1.0.0
 * @package           Etsy_Grouped_Products
 *
 * @wordpress-plugin
 * Plugin Name:       Etsy Integration For Grouped Products
 * Plugin URI:        https://https://cedcommerce.com
 * Description:       This plugin is used for uploading grouped products on etsy
 * Version:           1.0.0
 * Author:            cedcommerce
 * Author URI:        https://https://cedcommerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       etsy-grouped-products
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
define( 'ETSY_GROUPED_PRODUCTS_VERSION', '1.0.0' );
define( 'ETSY_GROUPED_PRODUCTS_BASE_NAME', plugin_basename( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-etsy-grouped-products-activator.php
 */
function activate_etsy_grouped_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-etsy-grouped-products-activator.php';
	Etsy_Grouped_Products_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-etsy-grouped-products-deactivator.php
 */
function deactivate_etsy_grouped_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-etsy-grouped-products-deactivator.php';
	Etsy_Grouped_Products_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_etsy_grouped_products' );
register_deactivation_hook( __FILE__, 'deactivate_etsy_grouped_products' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-etsy-grouped-products.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_etsy_grouped_products() {

	$plugin = new Etsy_Grouped_Products();
	$plugin->run();

}
// run_etsy_grouped_products();

/**
 * Ced_admin_notice_example_activation_hook_ced_etsy_group.
 *
 * @since 1.0.0
 */
function ced_admin_notice_example_activation_hook_ced_etsy_group() {
	set_transient( 'ced-etsy-group-admin-notice', true, 5 );
}

/**
 * Check WooCommerce is Installed and Active.
 *
 * @since 1.0.0
 */
if ( ced_etsy_group_check_etsy_active() ) {
	run_etsy_grouped_products();
	register_activation_hook( __FILE__, 'ced_admin_notice_example_activation_hook_ced_etsy_group' );
	add_action( 'admin_notices', 'ced_etsy_group_admin_notice_activation' );
} else {
	add_action( 'admin_init', 'deactivate_ced_etsy_group_woo_missing' );
}

function ced_etsy_group_check_etsy_active() {
	/**Get active plugin list
	 *
	 *@since 1.0.0
	 */
	if ( in_array( 'woocommerce-etsy-integration/woocommerce-etsy-integration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	}
	return false;
}
/**
 * Ced_etsy_group_admin_notice_activation.
 *
 * @since 1.0.0
 */
function ced_etsy_group_admin_notice_activation() {
	if ( get_transient( 'ced-etsy-group-admin-notice' ) ) {?>
		<div class="updated notice is-dismissible">
			<p>Welcome to Etsy Group Product Addon for Woocommerce Etsy Integration.</p>
		</div>
		<?php
		delete_transient( 'ced-etsy-group-admin-notice' );
	}

}

/**
 * This code runs when WooCommerce is not activated,
 *
 * @since 1.0.0
 */
function deactivate_ced_etsy_group_woo_missing() {
	deactivate_plugins( ETSY_GROUPED_PRODUCTS_BASE_NAME );
	add_action( 'admin_notices', 'ced_etsy__group_missing_notice' );
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

/**
 * Callback function for sending notice if woocommerce is not activated.
 *
 * @since 1.0.0
 */
function ced_etsy__group_missing_notice() {
	// translators: %s: search term !!
	echo '<div class="notice notice-error is-dismissible"><p>' . sprintf( esc_html( __( 'Etsy Group Products requires WooCommerce Etsy Integration to be installed and active.' ) ) ) . '</p></div>';
}