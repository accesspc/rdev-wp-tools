<?php
/**
 * Define the internationalization functionality
 *
 * @package Rdev\WpTools
 * @subpackage Rdev\WpTools\Admin
 */

namespace Rdev\WpTools\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The admin-settings specific functionality of the plugin
 *
 * @since 1.1.0
 */
class Settings {

    /**
     * RDWT option name.
     *
     * @access protected
     * @since 1.0.0
     * @var string
     */
    protected $option = 'rdwt';

    /**
     * RDWT Options
     *
     * @access protected
     * @since 1.0.0
     * @var array
     */
    protected $options = array();

    /**
     * Main construct function.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function __construct() {
        $this->add_hooks();
        $this->init();
    }

    /**
     * Add Settings hooks.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function add_hooks(): void {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'add_settings' ) );

        add_filter( 'plugin_action_links_' . RDWT_BASE, array( $this, 'plugin_action_links' ) );
    }

    /**
     * Add admin menu for RDWT.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function add_admin_menu(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        add_menu_page(
            esc_html__( 'Rdev WP Tools', 'rdwt' ),
            esc_html__( 'Rdev WP Tools', 'rdwt' ),
            'manage_options',
            RDWT_SLUG,
            array( $this, 'display_admin_overview' ),
            '',
            80
        );

        add_submenu_page(
            RDWT_SLUG,
            __( 'Rdev', 'rdwt' ),
            __( 'Settings', 'rdwt' ),
            'manage_options',
            RDWT_SLUG . '-settings',
            array( $this, 'display_admin_settings' ),
        );
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
            'rdwt_plugin',
            'rdwt',
            array( $this, 'validate_settings' )
        );

        add_settings_section(
            'rdwt-settings-overview',
            __( 'Overview', 'rdwt' ),
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
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function display_admin_overview(): void {
        include_once RDWT_DIR . 'assets/partials/display-overview.php';
    }

    /**
     * Display: admin settings page.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function display_admin_settings(): void {
        $options = get_option( $this->option, $this->get_default_options() );

        include_once RDWT_DIR . 'assets/partials/display-settings.php';
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function enqueue_scripts(): void {
        wp_enqueue_script( RDWT_SLUG, RDWT_URL . 'assets/js/rdwt-admin.js', array( 'jquery' ), RDWT_VERSION, false );
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function enqueue_styles(): void {
        wp_enqueue_style( RDWT_SLUG, RDWT_URL . 'assets/css/rdwt-admin.css', array(), RDWT_VERSION, 'all' );
    }

    /**
     * Retrieve default options / settings.
     *
     * @access public
     * @return array
     * @since 1.0.0
     */
    public function get_default_options(): array {
        return apply_filters( 'rdwt_default_options', $this->options );
    }

    /**
     * Init
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function init(): void {
        // If no options exist, create them.
        if ( ! get_option( $this->option ) ) {
            update_option( $this->option, $this->get_default_options() );
        }
    }

    /**
     * Plugin action links callback.
     *
     * @access public
     * @return array
     * @since 1.2.0
     * @param array $links Action links.
     */
    public function plugin_action_links( $links ): array {
        $settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=rdwt' ), __( 'Overview', 'rdwt' ) );
        array_unshift( $links, $settings_link );

        $settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=rdwt-settings' ), __( 'Settings', 'rdwt' ) );
        array_unshift( $links, $settings_link );

        return $links;
    }

    /**
     * Settings section callback.
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function render_section_overview(): void {
        esc_html_e( 'Rdev WP Tools is a collection of tools in a single bloat-less WordPress plugin.', 'rdwt' );
    }

    /**
     * Settings field callback.
     *
     * @access public
     * @return int
     * @since 1.0.0
     * @param array $args Settings arguments.
     */
    public function render_settings_field( $args ) {
        $this->set_name_and_value( $args );

        $args = wp_parse_args( $args, array( 'classes' => array() ) );

        if ( empty( $args['id'] ) || empty( $args['page'] ) ) {
            return;
        }

        switch ( $args['type'] ) {
            case 'checkbox':
                ?>
                <input
                    type='checkbox'
                    id='<?php echo esc_attr( $args['id'] ); ?>'
                    name='<?php echo esc_attr( $args['name'] ); ?>'
                    value='1'
                    class='<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>'
                    <?php
                    if ( isset( $args['value'] ) ) {
                        checked( '1', $args['value'] );
                    }
                    ?>
                />
                <?php
                break;

            case 'radio':
                foreach ( $args['options'] as $option ) {
                    ?>
                    <div class="rdwt-radio">
                    <input
                        type='radio'
                        id='<?php echo esc_attr( $args['id'] ); ?>'
                        name='<?php echo esc_attr( $args['name'] ); ?>'
                        value='<?php echo esc_attr( $option['value'] ); ?>'
                        class='<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>'
                        <?php
                            checked( esc_attr( $option['value'] ), $args['value'] );
                        ?>
                    />
                    <?php
                        echo wp_kses_post( $option['desc'] );
                    ?>
                    </div>
                    <?php
                }
                break;

            case 'range':
                ?>
                <input
                    type='range'
                    id='<?php echo esc_attr( $args['id'] ); ?>'
                    name='<?php echo esc_attr( $args['name'] ); ?>'
                    value='<?php echo esc_attr( $args['value'] ); ?>'
                    class='<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>'
                    min='<?php echo esc_attr( $args['min'] ); ?>'
                    max='<?php echo esc_attr( $args['max'] ); ?>'
                    step='<?php echo esc_attr( $args['step'] ); ?>'
                />
                <?php

                break;

            case 'raw':
                if ( ! isset( $args['html'] ) ) {
                    break;
                }
                echo wp_kses_post( $args['html'] );
                break;

            case 'text':
                ?>
                <input
                    type='text'
                    id='<?php echo esc_attr( $args['id'] ); ?>'
                    name='<?php echo esc_attr( $args['name'] ); ?>'
                    value='<?php echo esc_attr( $args['value'] ); ?>'
                    class='<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>'
                />
                <?php
                break;

            default:
                break;
        }

        if ( isset( $args['sub_desc'] ) && ! empty( $args['sub_desc'] ) ) {
            echo '<span class="sub-desc">';
            echo wp_kses_post( $args['sub_desc'] );
            echo '</span>';
        }

        if ( isset( $args['desc'] ) && ! empty( $args['desc'] ) ) {
            echo '<div class="description">';
            if ( is_array( $args['desc'] ) ) {

                array_walk(
                    $args['desc'],
                    function ( &$line ) {
                        $line = sprintf( '<div>%s</div>', wp_kses_post( $line ) );
                    }
                );
                echo esc_attr( implode( '', $args['desc'] ) );

            } else {
                echo wp_kses_post( $args['desc'] );
            }
            echo '</div>';
        }
    }

    /**
     * Set name and value from settings / options.
     *
     * @access private
     * @return void
     * @since 1.0.0
     * @param array $args Key-value pairs.
     */
    private function set_name_and_value( &$args ): void {
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
     * @access public
     * @return array
     * @since 1.0.0
     * @param array $input array to validate.
     */
    public function validate_settings( $input ): array {
        return $input;
    }
}
