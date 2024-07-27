<?php
/**
 * Define the internationalization functionality
 *
 * @package Rdev\WpTools\Core
 * @since   1.0.0
 */

namespace Rdev\WpTools\Core;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * Class: I18n
 *
 * @since 1.0.0
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
