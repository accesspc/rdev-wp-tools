<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Password Generator functionality of the plugin
 * 
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 * @link       https://reiciunas.dev/plugins/rdev-wp-tools/
 * 
 * @since      1.1.0
 * @package    RDWT
 * @subpackage RDWT/admin
 */
class RDWT_PwdGen extends RDWT_Settings {

	/**
	 * RDWT option name.
	 * 
	 * @access	protected
	 * @since		1.1.0
	 * @var			string
	 */
	protected $option = 'rdwt_pwdgen';

	/**
	 * RDWT Options
	 * 
	 * @access	protected
	 * @since		1.1.0
	 * @var			array
	 */
	protected $options = array(
		'pwdgen_enable' => false,
		'pwdgen_count' => 3,
    'pwdgen_length' => 16,
    'pwdgen_inc_numbers' => true,
    'pwdgen_inc_lower' => true,
    'pwdgen_inc_upper' => true,
    'pwdgen_inc_symbols' => false,
	);

	/**
	 * RDWT PwdGen Shortcode tag.
	 * 
	 * @access	public
	 * @since		1.1.0
	 * @var			string
	 */
	public $shortcode_tag = 'rdwt_pwdgen';

	/**
	 * RDWT Version Number.
	 * 
	 * @access	protected
	 * @since		1.1.0
	 * @var			string
	 */
	protected $version;

	/**
	 * Main construct function.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.1.0
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
	 * @since		1.1.0
	 */
	public function add_hooks() {
    add_action( 'admin_init', array( $this, 'add_settings' ) );

    add_action( 'init', array( $this, 'init' ) );
  }

	/**
	 * Register settings / options.
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.1.0
	 */
  public function add_settings() {
    register_setting(
      'rdwt_plugin_settings',
      $this->option,
      array( $this, 'validate_settings')
    );

    // Overview section's field
    ob_start();
    require_once RDWT_DIR . 'assets/partials/display-overview-pwdgen.php';
    $html = str_replace( array( "\r", "\n" ), '', ob_get_clean() );

    add_settings_field(
      'pwdgen_overview',
      __( 'Password Generator', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt',
      'rdwt-settings-overview',
      array(
        'html' => $html,
        'id' => 'pwdgen_overview',
        'page' => 'rdwt_overview',
        'type' => 'raw',
      )
    );

    // Settings section and fields
    add_settings_section(
      'rdwt-settings-pwdgen-section',
      __( 'Password Generator', RDWT_DOMAIN ),
      array( $this, 'render_section_pwdgen' ),
      'rdwt-settings',
      array(
        'after_section' => '<hr/>'
      )
    );

    add_settings_field(
      'pwdgen_enable',
      __( 'Enable', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt-settings',
      'rdwt-settings-pwdgen-section',
      array(
        'class' => 'rdwt-setting',
        'id' => 'pwdgen_enable',
        'label_for' => 'pwdgen_enable',
        'page' => 'rdwt_pwdgen',
        'sub_desc' => __( 'Check to enable Password Generator shortcode', RDWT_DOMAIN ),
        'type' => 'checkbox',
      )
    );

    add_settings_field(
      'pwdgen_count',
      __( 'Number of passwords', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt-settings',
      'rdwt-settings-pwdgen-section',
      array(
        'class' => 'rdwt-setting rdwt-range pwdgen-counter',
        'id' => 'pwdgen_count',
        'label_for' => 'pwdgen_count',
				'max' => 10,
				'min' => 1,
        'page' => 'rdwt_pwdgen',
				'step' => 1,
        'sub_desc' => $this->options[ 'pwdgen_count' ],
        'type' => 'range',
      )
    );

    add_settings_field(
      'pwdgen_length',
      __( 'Password length', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt-settings',
      'rdwt-settings-pwdgen-section',
      array(
        'class' => 'rdwt-setting rdwt-range',
        'id' => 'pwdgen_length',
        'label_for' => 'pwdgen_length',
				'max' => 32,
				'min' => 8,
        'page' => 'rdwt_pwdgen',
				'step' => 1,
        'sub_desc' => $this->options[ 'pwdgen_length' ],
        'type' => 'range',
      )
    );

    add_settings_field(
      'pwdgen_inc_numbers',
      __( '', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt-settings',
      'rdwt-settings-pwdgen-section',
      array(
        'class' => 'rdwt-setting',
        'id' => 'pwdgen_inc_numbers',
        'label_for' => 'pwdgen_inc_numbers',
        'page' => 'rdwt_pwdgen',
        'sub_desc' => __( 'Numbers', RDWT_DOMAIN ),
        'type' => 'checkbox',
      )
    );

    add_settings_field(
      'pwdgen_inc_lower',
      __( '', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt-settings',
      'rdwt-settings-pwdgen-section',
      array(
        'class' => 'rdwt-setting',
        'id' => 'pwdgen_inc_lower',
        'label_for' => 'pwdgen_inc_lower',
        'page' => 'rdwt_pwdgen',
        'sub_desc' => __( 'Lower case letters', RDWT_DOMAIN ),
        'type' => 'checkbox',
      )
    );

    add_settings_field(
      'pwdgen_inc_upper',
      __( '', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt-settings',
      'rdwt-settings-pwdgen-section',
      array(
        'class' => 'rdwt-setting',
        'id' => 'pwdgen_inc_upper',
        'label_for' => 'pwdgen_inc_upper',
        'page' => 'rdwt_pwdgen',
        'sub_desc' => __( 'Upper case letters', RDWT_DOMAIN ),
        'type' => 'checkbox',
      )
    );

    add_settings_field(
      'pwdgen_inc_symbols',
      __( '', RDWT_DOMAIN ),
      array( $this, 'render_settings_field' ),
      'rdwt-settings',
      'rdwt-settings-pwdgen-section',
      array(
        'class' => 'rdwt-setting',
        'id' => 'pwdgen_inc_symbols',
        'label_for' => 'pwdgen_inc_symbols',
        'page' => 'rdwt_pwdgen',
        'sub_desc' => __( 'Symbols', RDWT_DOMAIN ),
        'type' => 'checkbox',
      )
    );
  }

	/**
	 * PwdGen: Init function
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.1.0
	 */
  public function init() {
    // If no options exist, create them
		if ( ! get_option( $this->option ) ) {
			update_option( $this->option, $this->get_default_options() );
		}

		$options = get_option( $this->option, $this->get_default_options() );

    if ( isset( $options[ 'pwdgen_enable' ] ) && $options[ 'pwdgen_enable'] ) {
      add_shortcode( $this->shortcode_tag, array( $this, 'render_shortcode' ) );
    }
  }

  /**
	 * Settings section callback.
	 *
	 * @access	public
	 * @return	void
	 * @since		1.1.0
	 */
  public function render_section_pwdgen() {
    esc_html_e( 'These are the settings for Password Generator', RDWT_DOMAIN );
  }

  /**
   * PwdGen: Render shortcode block
   * 
   * @access  public
   * @return  string
   * @since   1.1.0
   */
  public function render_shortcode( $atts, $content = '' ) {
    $options = get_option( $this->option, $this->get_default_options() );
    $opts = array();

    foreach ( $options as $k => $v ) {
      $opts[ str_replace( 'pwdgen_', '', $k ) ] = $v;
    }

    // $atts = shortcode_atts(
    //   array(
    //     'count' => '3'
    //   ), $atts, $this->shortcode_tag
    // );

    ob_start();
    require_once RDWT_DIR . 'assets/partials/pwdgen-shortcode.php';
    return str_replace( array( "\r", "\n" ), '', ob_get_clean() );
  }
  
	/**
	 * Validate settings / options.
	 *
	 * @access	public
	 * @return	array
	 * @since		1.1.0
	 */
	public function validate_settings( $input ) {
    foreach ( array_keys( $this->options )  as $k ) {
      if ( isset( $input[ $k ] ) ) {
        $input[ $k ] = wp_filter_nohtml_kses( $input[ $k ] );
      }
    }

		return $input;
	}

}
