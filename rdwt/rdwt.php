<?php

/**
 * RDWT
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
 * Version:           1.1.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Robertas Reiciunas
 * Author URI:        https://reiciunas.dev/
 * License:           GPL-v3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       rdevwt
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Current plugin version.
 */
define( 'RDWT_DIR', plugin_dir_path( __FILE__ ) );
define( 'RDWT_DOMAIN', 'rdwt' );
define( 'RDWT_SLUG', basename( dirname( __FILE__ ) ) );
define( 'RDWT_URL', plugin_dir_url( __FILE__ ) );
define( 'RDWT_VERSION', '1.1.2' );

require_once 'includes/class-rdwt.php';

$rdwt = new RDWT();

// register_activation_hook( __FILE__, array( 'RDWT_Settings', 'activate' ) );

// register_deactivation_hook( __FILE__, array( 'RDWT_Settings', 'deactivate' ) );
