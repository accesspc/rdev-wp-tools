<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://reiciunas.dev/plugins/rdev-wp-tools/
 * @since      1.0.0
 *
 * @package    RDWT
 * @subpackage RDWT/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    RDWT
 * @subpackage RDWT/admin
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 */
class RDWT_Admin {

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
	 * @param      string    $rdwt       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $rdwt, $version ) {

		$this->rdwt = $rdwt;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in RDWT_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The RDWT_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->rdwt, plugin_dir_url( __FILE__ ) . 'css/rdwt-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in RDWT_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The RDWT_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->rdwt, plugin_dir_url( __FILE__ ) . 'js/rdwt-admin.js', array( 'jquery' ), $this->version, false );

	}

}
