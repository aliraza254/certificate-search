<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://webtechsofts.co.uk/
 * @since             1.0.0
 * @package           Certificate_Search
 *
 * @wordpress-plugin
 * Plugin Name:       Certificate Search
 * Plugin URI:        https://https://webtechsofts.co.uk/
 * Description:       Use These ShortCode For Search Form And Result Form [certificate_search] & [certificate_search_table]
 * Version:           1.0.0
 * Author:            Web Tech Softs
 * Author URI:        https://https://webtechsofts.co.uk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       certificate-search
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
define( 'CERTIFICATE_SEARCH_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-certificate-search-activator.php
 */
function activate_certificate_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-certificate-search-activator.php';
	Certificate_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-certificate-search-deactivator.php
 */
function deactivate_certificate_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-certificate-search-deactivator.php';
	Certificate_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_certificate_search' );
register_deactivation_hook( __FILE__, 'deactivate_certificate_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-certificate-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_certificate_search() {

	$plugin = new Certificate_Search();
	$plugin->run();

}
run_certificate_search();
