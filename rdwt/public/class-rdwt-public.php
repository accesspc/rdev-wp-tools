<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RDWT
 * @subpackage RDWT/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    RDWT
 * @subpackage RDWT/public
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 */
class RDWT_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rdwt    The ID of this plugin.
	 */
	private $rdwt;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $rdwt       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $rdwt, $version ) {

		$this->rdwt = $rdwt;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->rdwt, plugin_dir_url( __FILE__ ) . 'css/rdwt-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->rdwt, plugin_dir_url( __FILE__ ) . 'js/rdwt-public.js', array( 'jquery' ), $this->version, false );

	}

}
