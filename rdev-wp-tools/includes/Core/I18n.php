<?php

/**
 * Internationalization functionality
 * php version 7.3.0
 *
 * @category Core
 * @package  Rdev\WpTools\Core
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\Core;

// phpcs:disable
if (! defined('ABSPATH')) {
    exit;
}
// phpcs:enable

/**
 * Class: I18n
 *
 * @category Core
 * @package  Rdev\WpTools\Core
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    1.0.0
 */
class I18n
{
    /**
     * Load plugin text domain for translations
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public static function loadPluginTextdomain(): void
    {
        load_plugin_textdomain(
            RDWT_DOMAIN,
            false,
            dirname(plugin_basename(__FILE__), 2) . '/languages/'
        );
    }
}
