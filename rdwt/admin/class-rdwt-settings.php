<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The admin-settings specific functionality of the plugin.
 * 
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 * @link       https://reiciunas.dev/plugins/rdev-wp-tools/
 * 
 * @since      1.0.0
 * @package    RDWT
 * @subpackage RDWT/admin
 */
class RDWT_Settings {

	/**
	 * RDWT Version Number.
	 * 
	 * @access	protected
	 * @since		1.0.0
	 * @var			string
	 */
	protected $version;

	/**
	 * Main construct function.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function __construct() {
		$this->version = RDWT_VERSION;

		$this->add_hooks();

		$this->ga_init();
	}

	/**
	 * Plugin activation hook.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public static function activate() {
		// If no options exist, create them
		if ( ! get_option( 'rdwt_options' ) ) {
			update_option( 'rdwt_options', RDWT_Settings::get_default_options() );
		}
	}

	/**
	 * Add Settings hooks.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function add_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'admin_init', array( $this, 'add_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Add admin menu for RDWT.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function add_admin_menu() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_menu_page(
			esc_html__( 'Rdev WP Tools', 'rdwt' ),
			esc_html__( 'Rdev WP Tools', 'rdwt' ),
			'manage_options',
			'rdwt',
			array( $this, 'display_admin_overview' ),
			'',
			80
		);

		add_submenu_page(
			'rdwt',
			__( 'Rdev', 'rdwt' ),
			__( 'Settings', 'rdwt' ),
			'manage_options',
			'rdwt-settings',
			array( $this, 'display_admin_settings' ),
		);
	}

	/**
	 * Register settings / options.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
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

	/**
	 * TBD: Add admin notices.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function admin_notices() {
		$screen_id = $this->get_screen_id();
	}

	/**
	 * Plugin deactivation hook.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public static function deactivate() {

	}

	/**
	 * Display: admin overview page.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function display_admin_overview() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/display-overview.php';
	}

	/**
	 * Display: admin settings page.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function display_admin_settings() {
		$rdwt_options = get_option( 'rdwt_options', $this->get_default_options() );

		require_once plugin_dir_path( __FILE__ ) . 'partials/display-settings.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'rdwt', plugin_dir_url( __FILE__ ) . 'css/rdwt-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'rdwt', plugin_dir_url( __FILE__ ) . 'js/rdwt-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Retrieve default options / settings.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public static function get_default_options() {
		$options = array(
			'ga_enable' => false,
			'ga_id' => '',
			'ga_location' => 'header',
		);

		return apply_filters( 'rdwt_default_options', $options );
	}

	/**
	 * GA: Init function to render/or not the tracking code.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
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

	/**
	 * GA: Render tracking code.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function ga_tracking_code() {
		$rdwt_options = get_option( 'rdwt_options', $this->get_default_options() );

		require_once plugin_dir_path( __FILE__ ) . 'partials/ga-code.php';
	}

	/**
	 * Get screen id.
	 *
	 * @access	public
	 * @return	int
	 * @since		1.0.0
	 */
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

	/**
	 * Validate settings / options.
	 *
	 * @access	public
	 * @return	array
	 * @since		1.0.0
	 */
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
