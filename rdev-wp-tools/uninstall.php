<?php

/**
 * Fired when the plugin is uninstalled.
 * php version 7.3.0
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string
 *   parameters.
 * - Repeat things for multisite. Once for a single site in the network, once
 *   sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however,
 * this is the general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @category Admin
 * @package  Rdev\WpTools
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    1.0.0
 */

if (! defined('ABSPATH')) {
    exit;
}

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// delete options.
delete_option('rdwt');
delete_option('rdwt_ga');
delete_option('rdwt_pwdgen');
