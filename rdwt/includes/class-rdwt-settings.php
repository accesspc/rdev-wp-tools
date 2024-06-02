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
	 * RDWT option name.
	 * 
	 * @access	protected
	 * @since		1.0.0
	 * @var			string
	 */
	protected $option = 'rdwt';

	/**
	 * RDWT Options
	 * 
	 * @access	protected
	 * @since		1.0.0
	 * @var			array
	 */
	protected $options = array(

	);

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
		$this->init();
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

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'add_settings' ) );

		add_filter( 'plugin_action_links_' . RDWT_BASE, array( $this, 'plugin_action_links' ) );
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
			esc_html__( 'Rdev WP Tools', RDWT_DOMAIN ),
			esc_html__( 'Rdev WP Tools', RDWT_DOMAIN ),
			'manage_options',
			RDWT_SLUG,
			array( $this, 'display_admin_overview' ),
			'',
			80
		);

		add_submenu_page(
			RDWT_SLUG,
			__( 'Rdev', RDWT_DOMAIN ),
			__( 'Settings', RDWT_DOMAIN ),
			'manage_options',
			RDWT_SLUG . '-settings',
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
		register_setting(
			'rdwt_plugin',
			'rdwt',
			array( $this, 'validate_settings')
		);

		add_settings_section(
			'rdwt-settings-overview',
			__( 'Overview', RDWT_DOMAIN ),
			array( $this, 'render_section_overview' ),
			'rdwt',
			array(
				'after_section' => '<hr/>',
			)
		);
	}

	/**
	 * Display: admin overview page.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function display_admin_overview() {
		require_once RDWT_DIR . 'assets/partials/display-overview.php';
	}

	/**
	 * Display: admin settings page.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function display_admin_settings() {
		$options = get_option( $this->option, $this->get_default_options() );

		require_once RDWT_DIR . 'assets/partials/display-settings.php';
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( RDWT_SLUG, RDWT_URL . 'assets/js/rdwt-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( RDWT_SLUG, RDWT_URL . 'assets/css/rdwt-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Retrieve default options / settings.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function get_default_options() {
		return apply_filters( 'rdwt_default_options', $this->options );
	}

	/**
	 * Init
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function init() {
		// If no options exist, create them
		if ( ! get_option( $this->option ) ) {
			update_option( $this->option, $this->get_default_options() );
		}
	}

	/**
	 * Plugin action links callback.
	 *
	 * @access	public
	 * @return	array
	 * @since		1.2.0
	 */
	public function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=rdwt' ), __( 'Overview', RDWT_DOMAIN ) );
		array_unshift( $links, $settings_link );

		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=rdwt-settings' ), __( 'Settings', RDWT_DOMAIN ) );
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Settings section callback.
	 *
	 * @access	public
	 * @return	int
	 * @since		1.0.0
	 */
	public function render_section_overview() {
		esc_html_e( 'Rdev WP Tools is a collection of tools in a single bloat-less wordpress plugin.', RDWT_DOMAIN );
	}

	/**
	 * Settings field callback.
	 *
	 * @access	public
	 * @return	int
	 * @since		1.0.0
	 */
	public function render_settings_field( $args ) {
		$this->set_name_and_value( $args );
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
					<div class="rdwt-radio">
						<input 
							type='radio' 
							id='<?php echo esc_attr( $args['id'] ); ?>' 
							name='<?php echo esc_attr( $name ); ?>' 
							value='<?php echo esc_attr( $option['value'] ); ?>' 
							class='<?php echo implode( ' ', $args['classes'] ); ?>' 
							<?php checked( esc_attr( $option['value'] ), $args['value'] ); ?>
						/>
						<?php echo wp_kses_post( $option['desc'] ); ?>
					</div>
					<?php
				}
				break;
			
			case 'range':

				?>
				<input
					type='range'
					id='<?php echo esc_attr( $args['id'] ); ?>'
					name='<?php echo esc_attr( $name ); ?>'
					value='<?php echo esc_attr( $value ); ?>'
					class='<?php echo implode( ' ', $args['classes'] ); ?>' 
					min='<?php echo esc_attr( $args[ 'min' ] ); ?>' 
					max='<?php echo esc_attr( $max ); ?>' 
					step='<?php echo esc_attr( $step ); ?>' 
				/>
				<?php

				break;
			
			case 'raw':

					if ( ! isset( $html ) ) {
						break;
					}
	
					echo wp_kses_post( $html );
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
			echo '<span class="sub-desc">';
			echo wp_kses_post( $sub_desc );
			echo '</span>';
		}

		if ( isset( $desc ) && ! empty( $desc ) ) {
			echo '<div class="description">';
			if ( is_array( $desc ) ) {

				array_walk( $desc, function( &$line ) { $line = sprintf( "<div>%s</div>", wp_kses_post( $line ) ); } );
				echo implode( '', $desc );

			} else {
				echo wp_kses_post( $desc );
			}
			echo '</div>';
		}
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
			$options = get_option( $this->option, $this->get_default_options() );
			$args['value'] = $options[ $args['id'] ];
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
		return $input;
	}

}
