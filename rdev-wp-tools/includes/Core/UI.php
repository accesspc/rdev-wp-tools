<?php
/**
 * The public-facing functionality of the plugin
 *
 * @package   Rdev\WpTools
 * @subpackage  Rdev\WpTools\Core
 * @since     1.0.0
 */

namespace Rdev\WpTools\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class: UI
 *
 * @since 1.0.0
 */
class UI {

	/**
	 * Main construct function
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Add Settings hooks.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function add_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( RDWT_SLUG, RDWT_URL . 'assets/css/rdwt-public.css', array(), RDWT_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( RDWT_SLUG, RDWT_URL . 'assets/js/rdwt-public.js', array( 'jquery' ), RDWT_VERSION, false );
	}
}
