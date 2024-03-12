<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://cedcommerce.com
 * @since      1.0.0
 *
 * @package    Etsy_Grouped_Products
 * @subpackage Etsy_Grouped_Products/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Etsy_Grouped_Products
 * @subpackage Etsy_Grouped_Products/includes
 * @author     cedcommerce <plugins@cedcommerce.com>
 */
class Etsy_Grouped_Products_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'etsy-grouped-products',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
