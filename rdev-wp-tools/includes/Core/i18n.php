<?php

namespace Rdev\WpTools\Core;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Define the internationalization functionality
 *
 * @since       1.0.0
 * @package     Rdev\WpTools
 * @subpackage  Rdev\WpTools\Core
 */
class i18n
{

  /**
   * Load plugin text domain for translations
   * 
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public static function load_plugin_textdomain() : void {
    load_plugin_textdomain(
      RDWT_DOMAIN,
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );
  }

}
