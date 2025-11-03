<?php
/**
 * GeoDirectory Real Estate Directory
 *
 * @package           Real_Estate_Directory
 * @author            AyeCode Ltd
 * @copyright         2023 AyeCode Ltd
 * @license           GPLv3
 *
 * @wordpress-plugin
 * Plugin Name:       Real Estate Directory
 * Plugin URI:        https://wpgeodirectory.com/
 * Description:       Add real estate functionality to your site.
 * Version:           2.0.6
 * Requires at least: 6.0
 * Requires PHP:      7.0
 * Author:            AyeCode Ltd
 * Author URI:        https://ayecode.io
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Requires Plugins:  geodirectory
 * Text Domain:       real-estate-directory
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'REAL_ESTATE_DIRECTORY_VERSION' ) ) {
	define( 'REAL_ESTATE_DIRECTORY_VERSION', '2.0.6' );
}

if ( ! defined( 'REAL_ESTATE_DIRECTORY_MIN_CORE' ) ) {
	define( 'REAL_ESTATE_DIRECTORY_MIN_CORE', '2.3' );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 2.0
 */
function real_estate_directory_load() {

	if ( ! defined( 'REAL_ESTATE_DIRECTORY_PLUGIN_FILE' ) ) {
		define( 'REAL_ESTATE_DIRECTORY_PLUGIN_FILE', __FILE__ );
	}

	// Min core version check
	if ( ! function_exists( 'geodir_min_version_check' ) ) {
		return '';
	}

	/**
	 * The core plugin class that is used to define internationalization,
	 * dashboard-specific hooks, and public-facing site hooks.
	 */
	require_once( plugin_dir_path( REAL_ESTATE_DIRECTORY_PLUGIN_FILE ) . 'includes/class-real-estate-directory.php' );

	return Real_Estate_Directory::instance();
}
add_action( 'plugins_loaded', 'real_estate_directory_load' );

