<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Google Analytics functionality of the plugin
 * 
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 * @link       https://reiciunas.dev/plugins/rdev-wp-tools/
 * 
 * @since      1.0.0
 * @package    RDWT
 * @subpackage RDWT/admin
 */
class RDWT_GA {

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
	 * Add Settings hooks.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function add_hooks() {
    add_action( 'admin_init', array( $this, 'add_settings' ) );
  }

	/**
	 * Register settings / options.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
  public function add_settings() {
		add_settings_section(
			'rdwt-settings-ga-section',
			__( 'Google Analytics Settings', RDWT_DOMAIN ),
			array( $this, 'render_section_ga' ),
			'rdwt-settings',
			array(
				'after_section' => '<hr/>',
			),
		);

		add_settings_field(
			'ga_enable',
			__( 'Enable', RDWT_DOMAIN ),
			array( 'RDWT_Settings', 'render_settings_field' ),
			'rdwt-settings',
			'rdwt-settings-ga-section',
			array(
				'class' => 'rdwt-setting',
				'id' => 'ga_enable',
				'label_for' => 'ga_enable',
				'page' => 'rdwt_options',
				'sub_desc' => __( 'Check to place the tracking code on website', RDWT_DOMAIN ),
				'type' => 'checkbox',
			)
		);

		add_settings_field(
			'ga_id',
			__( 'GA Tracking ID', RDWT_DOMAIN ),
			array( 'RDWT_Settings', 'render_settings_field' ),
			'rdwt-settings',
			'rdwt-settings-ga-section',
			array(
				'class' => 'rdwt-setting',
				'desc' => array(
					__( 'Enter your Google Tracking ID. Note: the Tracking ID also may be referred to as Tag ID, Measurement ID, or Property ID.', RDWT_DOMAIN ),
					__( 'Supported ID formats include AW-XXXXXXXXX, G-XXXXXXXXX, GT-XXXXXXXXX, and UA-XXXXXXXXX. Google Tag Manager (GTM-XXXXXXXXX) currently is not supported.', RDWT_DOMAIN ),
				),
				'id' => 'ga_id',
				'label_for' => 'ga_id',
				'page' => 'rdwt_options',
				'sub_desc' => __( '', RDWT_DOMAIN ),
				'type' => 'text',
			)
		);

		add_settings_field(
			'ga_location',
			__( 'Tracking code location', RDWT_DOMAIN ),
			array( 'RDWT_Settings', 'render_settings_field' ),
			'rdwt-settings',
			'rdwt-settings-ga-section',
			array(
				'class' => 'rdwt-setting',
				'desc' => __( 'Tip: Google recommends including the tracking code in the page head, but including it in the footer can benefit page performance. If in doubt, go with the head option.', RDWT_DOMAIN ),
				'id' => 'ga_location',
				'label_for' => 'ga_location',
				'options' => array(
					array(
						'value' => 'header',
						'desc' => __( 'Include tracking code in page head (via <code>wp_head</code>)', RDWT_DOMAIN )
					),
					array(
						'value' => 'footer',
						'desc' => __( 'Include tracking code in page footer (via <code>wp_footer</code>)', RDWT_DOMAIN )
					),
				),
				'page' => 'rdwt_options',
				'type' => 'radio',
			)
		);
  }
	/**
	 * GA: Init function to render/or not the tracking code.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function ga_init() {
		$rdwt_options = get_option( 'rdwt_options', RDWT_Settings::get_default_options() );

		if ( isset( $rdwt_options[ 'ga_enable'] ) && $rdwt_options[ 'ga_enable' ] ) {

			$location = isset($rdwt_options[ 'ga_location' ]) ? $rdwt_options[ 'ga_location' ] : 'header';

			if ( $location == 'header' ) {
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
		$rdwt_options = get_option( 'rdwt_options', RDWT_Settings::get_default_options() );

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
	 * Settings section callback.
	 *
	 * @access	public
	 * @return	int
	 * @since		1.0.0
	 */
	public static function render_section_ga() {
		esc_html_e( 'These are some basic settings for Googla Analytics', RDWT_DOMAIN );
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

			$message  = esc_html__( 'Error: your tracking code begins with', RDWT_DOMAIN ) .' <code>GTM-</code> ';
			$message .= esc_html__( '(for Google Tag Manager), which is not supported. Please try again with a supported tracking code.', RDWT_DOMAIN );

			add_settings_error( 'ga_id', 'invalid-tracking-code', $message, 'error' );
		}

		return $input;
	}

}