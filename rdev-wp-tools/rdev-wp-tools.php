<?php
/**
 * Plugin Name: Rdev WP Tools
 * Plugin URI: https://github.com/accesspc/rdev-wp-tools
 * Description: RDev bloat-less WordPress tools.
 * Version: 2.0.0
 * Author: Robertas Reiciunas
 * Author URI: https://reiciunas.dev/
 * License: GPL v3
 * Text Domain: rdevwt
 * Domain Path: /languages
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Rdev
 */

namespace Rdev\WpTools;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define constants
 */
if ( ! defined( 'RDWT_BASE' ) ) {
    define( 'RDWT_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'RDWT_DIR' ) ) {
    define( 'RDWT_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'RDWT_DOMAIN' ) ) {
    define( 'RDWT_DOMAIN', 'rdwt' );
}

if ( ! defined( 'RDWT_SLUG' ) ) {
    define( 'RDWT_SLUG', basename( __DIR__ ) );
}

if ( ! defined( 'RDWT_URL' ) ) {
    define( 'RDWT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'RDWT_VERSION' ) ) {
    define( 'RDWT_VERSION', '2.0.0' );
}

/**
 * Autoload
 */
require_once RDWT_DIR . 'vendor/autoload.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

use Rdev\WpTools\WpTools;

$rdwt = new WpTools();
