<?php
/**
 * Plugin Name
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           RDWT
 * @author            Robertas Reiciunas
 * @copyright         2024 Robertas Reiciunas
 * @license           GPL-3.0
 *
 * @wordpress-plugin
 * Plugin Name:       Rdev WP Tools
 * Plugin URI:        https://github.com/accesspc/rdev-wp-tools
 * Description:       RDev bloat-less wordpress tools.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Robertas Reiciunas
 * Author URI:        https://reiciunas.dev/
 * License:           GPL-v3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       rdwt
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Currently plugin version.
 */
define( 'RDWT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rdwt-activator.php
 */
function activate_rdwt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rdwt-activator.php';
	RDWT_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_rdwt' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rdwt-deactivator.php
 */
function deactivate_rdwt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rdwt-deactivator.php';
	RDWT_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_rdwt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-rdwt.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rdwt() {

	$plugin = new RDWT();
	$plugin->run();

}
run_rdwt();

/*

add_action( 'init', array( 'RDWT', 'init' ) );

if ( is_admin() ) {
	require_once( RDWT__PLUGIN_DIR . 'includes/class-rdwt-admin.php' );
	add_action( 'init', array( 'RDWT_Admin', 'init' ) );
}

 */
