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

		$this->ga_init();

	}

	public function add_admin_menu() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_menu_page(
			esc_html__( 'Rdev WP Tools', $this->slug ),
			esc_html__( 'Rdev WP Tools', $this->slug ),
			'manage_options',
			$this->slug,
			array( $this, 'display_admin_overview' ),
			'',
			80
		);

		add_submenu_page(
			$this->slug,
			__( 'Rdev', 'rdwt' ),
			__( 'Settings', 'rdwt' ),
			'manage_options',
			'rdwt-settings',
			array( $this, 'display_admin_settings' ),
		);

	}

	public function add_settings() {

		// If no options exist, create them
		if ( ! get_option( 'rdwt_options' ) ) {
			update_option( 'rdwt_options', $this->get_default_options() );
		}

		register_setting(
			'rdwt_plugin_options',
			'rdwt_options',
			array( $this, 'validate_settings')
		);

	}

	public function admin_notices() {

		$screen_id = $this->get_screen_id();

	}

	public function display_admin_overview() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/display-overview.php';

	}

	public function display_admin_settings() {

		$rdwt_options = get_option( 'rdwt_options', $this->get_default_options() );

		require_once plugin_dir_path( __FILE__ ) . 'partials/display-settings.php';

	}

	public function get_default_options() {

		$options = array(

			'ga_enable' => false,
			'ga_id' => '',
			'ga_location' => 'header',

		);

		return apply_filters( 'rdwt_default_options', $options );

	}

	public function get_screen_id() {

		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . './wp-admin/includes/screen.php';
		}

		$screen = get_current_screen();

		if ( $screen && property_exists( $screen, 'id' ) ) {
			return $screen->id;
		}

		return false;

	}

	public function ga_init() {

		$rdwt_options = get_option( 'rdwt_options', $this->get_default_options() );

		if ( $rdwt_options[ 'ga_enable' ] ) {

			$location = isset($rdwt_options[ 'ga_location' ]) ? $rdwt_options[ 'ga_location' ] : 'header';

			if ( $location == "header" ) {
				add_action( 'wp_head', array( &$this, 'ga_tracking_code' ) );
			} else {
				add_action( 'wp_footer', array( &$this, 'ga_tracking_code' ) );
			}

		}

	}

	public function ga_tracking_code() {

		$rdwt_options = get_option( 'rdwt_options', $this->get_default_options() );

		require_once plugin_dir_path( __FILE__ ) . 'partials/ga-code.php';
		
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

	public function set_default_options() {
		
		// If no options exist, create them
		if ( ! get_option( 'rdwt_options' ) ) {
			update_option( 'rdwt_options', $this->get_default_options() );
		}

	}

	public function validate_settings( $input ) {

		$input[ 'ga_id' ] = wp_filter_nohtml_kses( $input[ 'ga_id' ] );

		if( isset($input[ 'ga_id' ] ) && preg_match("/^GTM-/i", $input[ 'ga_id' ]) ) {

			$input[ 'ga_id' ] = '';

			$message  = esc_html__('Error: your tracking code begins with', 'rdwt') .' <code>GTM-</code> ';
			$message .= esc_html__('(for Google Tag Manager), which is not supported. Please try again with a supported tracking code.', 'rdwt');

			add_settings_error('ga_id', 'invalid-tracking-code', $message, 'error');

		}

		return $input;

	}

}
