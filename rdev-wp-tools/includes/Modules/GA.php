<?php
/**
 * Google Analytics functionality of the plugin
 *
 * @package Rdev\WpTools
 * @subpackage Rdev\WpTools\modules
 * @since 1.0.0
 */

namespace Rdev\WpTools\Modules;

use Rdev\WpTools\Admin\Settings;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class: GA
 *
 * @since 1.0.0
 */
class GA extends Settings {

    /**
     * RDWT option name.
     *
     * @access protected
     * @since 1.0.0
     * @var string
     */
    protected $option = 'rdwt_ga';

    /**
     * RDWT Options
     *
     * @access protected
     * @since 1.0.0
     * @var array
     */
    protected $options = array(
        'ga_enable'   => false,
        'ga_id'       => '',
        'ga_location' => 'header',
    );

    /**
     * Main construct function.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function __construct() {
        $this->add_hooks();
    }

    /**
     * Add Settings hooks.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function add_hooks(): void {
        add_action( 'admin_init', array( $this, 'add_settings' ) );

        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Register settings / options.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function add_settings(): void {
        register_setting(
            'rdwt_plugin_settings',
            $this->option,
            array( $this, 'validate_settings' )
        );

        // Overview section's field.
        ob_start();
        require_once RDWT_DIR . 'assets/partials/display-overview-ga.php';
        $html = str_replace( array( "\r", "\n" ), '', ob_get_clean() );

        add_settings_field(
            'ga_overview',
            __( 'Google Analytics', 'rdwt' ),
            array( $this, 'render_settings_field' ),
            'rdwt',
            'rdwt-settings-overview',
            array(
                'html' => $html,
                'id'   => 'ga_overview',
                'page' => 'rdwt_overview',
                'type' => 'raw',
            )
        );

        // Settings section and fields.
        add_settings_section(
            'rdwt-settings-ga-section',
            __( 'Google Analytics', 'rdwt' ),
            array( $this, 'render_section_ga' ),
            'rdwt-settings',
            array(
                'after_section' => '<hr/>',
            ),
        );

        add_settings_field(
            'ga_enable',
            __( 'Enable', 'rdwt' ),
            array( $this, 'render_settings_field' ),
            'rdwt-settings',
            'rdwt-settings-ga-section',
            array(
                'class'     => 'rdwt-setting',
                'id'        => 'ga_enable',
                'label_for' => 'ga_enable',
                'page'      => 'rdwt_ga',
                'sub_desc'  => __( 'Check to place the tracking code on website', 'rdwt' ),
                'type'      => 'checkbox',
            )
        );

        add_settings_field(
            'ga_id',
            __( 'GA Tracking ID', 'rdwt' ),
            array( $this, 'render_settings_field' ),
            'rdwt-settings',
            'rdwt-settings-ga-section',
            array(
                'class'     => 'rdwt-setting',
                'desc'      => array(
                    __( 'Enter your Google Tracking ID. Note: the Tracking ID also may be referred to as Tag ID, Measurement ID, or Property ID.', 'rdwt' ),
                    __( 'Supported ID formats include AW-XXXXXXXXX, G-XXXXXXXXX, GT-XXXXXXXXX, and UA-XXXXXXXXX. Google Tag Manager (GTM-XXXXXXXXX) currently is not supported.', 'rdwt' ),
                ),
                'id'        => 'ga_id',
                'label_for' => 'ga_id',
                'page'      => 'rdwt_ga',
                'sub_desc'  => '',
                'type'      => 'text',
            )
        );

        add_settings_field(
            'ga_location',
            __( 'Tracking code location', 'rdwt' ),
            array( $this, 'render_settings_field' ),
            'rdwt-settings',
            'rdwt-settings-ga-section',
            array(
                'class'     => 'rdwt-setting',
                'desc'      => __( 'Tip: Google recommends including the tracking code in the page head, but including it in the footer can benefit page performance. If in doubt, go with the head option.', 'rdwt' ),
                'id'        => 'ga_location',
                'label_for' => 'ga_location',
                'options'   => array(
                    array(
                        'value' => 'header',
                        'desc'  => __( 'Include tracking code in page head (via <code>wp_head</code>)', 'rdwt' ),
                    ),
                    array(
                        'value' => 'footer',
                        'desc'  => __( 'Include tracking code in page footer (via <code>wp_footer</code>)', 'rdwt' ),
                    ),
                ),
                'page'      => 'rdwt_ga',
                'type'      => 'radio',
            )
        );
    }

    /**
     * GA: Init function to render/or not the tracking code
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function init(): void {
        parent::init();

        $options = get_option( $this->option, $this->get_default_options() );

        if ( isset( $options['ga_enable'] ) && $options['ga_enable'] ) {

            $location = isset( $options['ga_location'] ) ? $options['ga_location'] : 'header';

            if ( 'header' === $location ) {
                add_action( 'wp_head', array( &$this, 'render_tracking_code' ) );
            } else {
                add_action( 'wp_footer', array( &$this, 'render_tracking_code' ) );
            }
        }
    }

    /**
     * Settings section callback.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public static function render_section_ga(): void {
        esc_html_e( 'These are the settings for Googla Analytics', 'rdwt' );
    }

    /**
     * GA: Render tracking code.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function render_tracking_code(): void {
        $options = get_option( $this->option, $this->get_default_options() );

        require_once RDWT_DIR . 'assets/partials/ga-code.php';
    }

    /**
     * Validate settings / options.
     *
     * @access public
     * @return array
     * @since 1.0.0
     * @param array $input Key-value array.
     */
    public function validate_settings( $input ): array {
        if ( isset( $input['ga_id'] ) ) {
            $input['ga_id'] = wp_filter_nohtml_kses( $input['ga_id'] );

            if ( preg_match( '/^GTM-/i', $input['ga_id'] ) ) {
                $input['ga_id'] = '';

                $message  = esc_html__( 'Error: your tracking code begins with', 'rdwt' ) . ' <code>GTM-</code> ';
                $message .= esc_html__( '(for Google Tag Manager), which is not supported. Please try again with a supported tracking code.', 'rdwt' );

                add_settings_error( 'ga_id', 'invalid-tracking-code', $message, 'error' );
            }
        }
        return $input;
    }
}
