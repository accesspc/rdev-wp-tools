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
			update_option( 'rdwt_options', self::get_default_options() );
		}

		register_setting(
			'rdwt_plugin_options',
			'rdwt_options',
			array( $this, 'validate_settings')
		);

		add_settings_section(
			'rdwt-settings-ga-section',
			__( 'Google Analytics Settings', 'rdwt' ),
			array( $this, 'render_section_ga' ),
			'rdwt-settings'
		);

		add_settings_field(
			'ga_enable',
			__( 'Enable', 'rdwt' ),
			array( $this, 'render_settings_field' ),
			'rdwt-settings',
			'rdwt-settings-ga-section',
			array(
				'id' => 'ga_enable',
				'page' => 'rdwt_options',
				'classes' => array(),
				'type' => 'checkbox',
			)
		);

		add_settings_field(
			'ga_id',
			__( 'GA Tracking ID', 'rdwt' ),
			array( $this, 'render_settings_field' ),
			'rdwt-settings',
			'rdwt-settings-ga-section',
			array(
				'id' => 'ga_id',
				'page' => 'rdwt_options',
				'classes' => array(),
				'type' => 'text',
				'desc' => __( 'Googla Analytics Tracking ID', 'rdwt' ),
				'sub_desc' => __( 'Sub description', 'rdwt' )
			)
		);

		add_settings_field(
			'ga_location',
			__( 'Tracking code location', 'rdwt' ),
			array( $this, 'render_settings_field' ),
			'rdwt-settings',
			'rdwt-settings-ga-section',
			array(
				'id' => 'ga_location',
				'page' => 'rdwt_options',
				'classes' => array(),
				'type' => 'radio',
				'options' => array(
					array(
						'value' => 'header',
						'desc' => __( 'Include tracking code in page head', 'rdwt' )
					),
					array(
						'value' => 'footer',
						'desc' => __( 'Include tracking code in page footer', 'rdwt' )
					),
				)
			)
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
		$rdwt_options = get_option( 'rdwt_options', self::get_default_options() );

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
		$rdwt_options = get_option( 'rdwt_options', self::get_default_options() );

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
		$rdwt_options = get_option( 'rdwt_options', self::get_default_options() );

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
	public function render_section_ga() {
		esc_html_e( 'These are some basic settings for Googla Analytics', 'rdwt' );
	}

	/**
	 * Settings field callback.
	 *
	 * @access	public
	 * @return	int
	 * @since		1.0.0
	 */
	public function render_settings_field( $args ) {
		self::set_name_and_value( $args );
		extract( $args, EXTR_SKIP );

		$args = wp_parse_args( $args, array( 'classes' => array() ) );

		if ( empty( $args[ 'id' ] ) || empty( $args[ 'page' ] ) ) {
			return;
		}

		switch ($type) {
			case 'checkbox':
				?>
				<input 
					type='checkbox' 
					id='<?php echo esc_attr( $args['id'] ); ?>' 
					name='<?php echo esc_attr( $name ); ?>' 
					value='1' 
					class='<?php echo implode( ' ', $args['classes'] ); ?>' 
					<?php if ( isset( $args['value'] ) ) checked( '1', $args['value'] ); ?>
				/>
				<?php
				break;

			case 'radio':

				foreach( $options as $option ) {
					?>
					<input 
						type='radio' 
						id='<?php echo esc_attr( $args['id'] ); ?>' 
						name='<?php echo esc_attr( $name ); ?>' 
						value='<?php echo esc_attr( $option['value'] ); ?>' 
						class='<?php echo implode( ' ', $args['classes'] ); ?>' 
						<?php checked( esc_attr( $option['value'] ), $args['value'] ); ?> />
					<?php
					esc_html_e( $option['desc'], 'rdwt' );
					?><br /><?php
				}
				break;

			case 'text':

				?>
				<input 
					type='text' 
					id='<?php echo esc_attr( $args['id'] ); ?>'
					name='<?php echo esc_attr( $name ); ?>' 
					value='<?php echo esc_attr( $value ); ?>' 
					class='<?php echo implode( ' ', $args['classes'] ); ?>' 
				/>
				<?php
				break;
		}

		if ( isset( $sub_desc ) && ! empty( $sub_desc ) ) {
			echo wp_kses_post( $sub_desc );
		}

		if ( ! empty( $desc ) ) : ?>
		<p class='description'><?php echo wp_kses_post( $desc ); ?></p>
		<?php endif;
	}

	/**
	 * Set name and value from settings / options.
	 *
	 * @access	private
	 * @return	void
	 * @since		1.0.0
	 */
	private function set_name_and_value( &$args ) {
		if ( ! isset( $args['name'] ) ) {
			$args['name'] = sprintf( '%s[%s]', esc_attr( $args['page'] ), esc_attr( $args['id'] ) );
		}

		if ( ! isset( $args['value'] ) ) {
			$rdwt_options = get_option( 'rdwt_options', self::get_default_options() );
			$args['value'] = $rdwt_options[ $args['id'] ];
		}
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

			$message  = esc_html__( 'Error: your tracking code begins with', 'rdwt' ) .' <code>GTM-</code> ';
			$message .= esc_html__( '(for Google Tag Manager), which is not supported. Please try again with a supported tracking code.', 'rdwt' );

			add_settings_error( 'ga_id', 'invalid-tracking-code', $message, 'error' );
		}

		return $input;
	}

}
