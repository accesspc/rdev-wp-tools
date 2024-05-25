<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The public-facing functionality of the plugin.
 * 
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 * @link       https://reiciunas.dev/plugins/rdev-wp-tools/
 * 
 * @since      1.0.0
 * @package    RDWT
 * @subpackage RDWT/public
 */
class RDWT_Public {

	/**
	 * RDWT Version Number
	 * 
	 * @access	protected
	 * @since		1.0.0
	 * @var			string
	 */
	protected $version;

	/**
	 * Main construct function
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function __construct() {
		$this->version = RDWT_VERSION;

		$this->add_hooks();
	}

	/**
	 * Add Settings hooks.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function add_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( RDWT_SLUG, plugin_dir_url( __FILE__ ) . 'css/rdwt-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( RDWT_SLUG, plugin_dir_url( __FILE__ ) . 'js/rdwt-public.js', array( 'jquery' ), $this->version, false );
	}

}
