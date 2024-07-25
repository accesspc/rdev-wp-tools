<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the internationalization functionality
 *
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 * @link       https://reiciunas.dev/plugins/rdev-wp-tools/
 *
 * @since      1.0.0
 * @package    RDWT
 * @subpackage RDWT/includes
 */
class RDWT_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain(
			RDWT_DOMAIN,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

}
