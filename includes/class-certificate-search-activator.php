<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://webtechsofts.co.uk/
 * @since      1.0.0
 *
 * @package    Certificate_Search
 * @subpackage Certificate_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Certificate_Search
 * @subpackage Certificate_Search/includes
 * @author     Web Tech Softs <info@webtechsofts.com>
 */
class Certificate_Search_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            wp_die('This plugin requires WooCommerce to be installed and activated.');
        }
        if (!class_exists('ACF')) {
            wp_die('This plugin requires ACF to be installed and activated.');
        }
	}

}
