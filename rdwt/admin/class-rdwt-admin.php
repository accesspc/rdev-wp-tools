<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
	 * The slug of this plugin settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $slug    Slug.
	 */
	public $slug = 'rdwt';

	/**
	 * The options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $options    Options.
	 */
	protected $options;

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

	public function build_admin_menu() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$capability = apply_filters( 'rdwt_required_capabilities', 'manage_options' );

		add_menu_page(
			__( 'Rdev WP Tools', 'rdwt'),
			__( 'Rdev WP Tools', 'rdwt'),
			$capability,
			$this->slug,
			array( &$this, 'display_admin_menu_page' ),
			'',
			80
		);

		add_submenu_page(
			$this->slug,
			__( 'Rdev', 'rdwt' ),
			__( 'Settings', 'rdwt' ),
			$capability,
			'rdwt-settings',
			array( &$this, 'display_admin_menu_settings' ),
		);

	}

	public function display_admin_menu_page() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/rdwt-admin-display.php';

	}

	public function display_admin_menu_settings() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/rdwt-admin-display-settings.php';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->rdwt, plugin_dir_url( __FILE__ ) . 'css/rdwt-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->rdwt, plugin_dir_url( __FILE__ ) . 'js/rdwt-admin.js', array( 'jquery' ), $this->version, false );
		
	}

}
