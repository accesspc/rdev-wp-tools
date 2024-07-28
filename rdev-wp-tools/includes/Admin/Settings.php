<?php
/**
 * Plugin Settings
 * php version 7.3.0
 *
 * @category Admin
 * @package  Rdev\WpTools\Admin
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\Admin;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * The admin-settings specific functionality of the plugin
 *
 * @category Admin
 * @package  Rdev\WpTools\Admin
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    1.1.0
 */
class Settings
{

    /**
     * RDWT option name.
     *
     * @access protected
     * @since  1.0.0
     * @var    string
     */
    protected string $option = 'rdwt';

    /**
     * RDWT Options
     *
     * @access protected
     * @since  1.0.0
     * @var    array
     */
    protected array $options = array();

    /**
     * Main construct function.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->addHooks();
        $this->init();
    }

    /**
     * Add Settings hooks.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function addHooks(): void
    {
        add_action('admin_enqueue_scripts', array( $this, 'enqueueStyles' ));
        add_action('admin_enqueue_scripts', array( $this, 'enqueueScripts' ));

        add_action('admin_menu', array( $this, 'addAdminMenu' ));
        add_action('admin_init', array( $this, 'addSettings' ));

        add_filter(
            'plugin_action_links_' . RDWT_BASE,
            array($this, 'pluginActionLinks')
        );
    }

    /**
     * Add admin menu for RDWT.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function addAdminMenu(): void
    {
        if (! current_user_can('manage_options') ) {
            return;
        }

        add_menu_page(
            __('Rdev WP Tools', 'rdwt'),
            __('Rdev WP Tools', 'rdwt'),
            'manage_options',
            RDWT_SLUG,
            array( 'Rdev\WpTools\View\Admin', 'renderOverview' ),
            '',
            80
        );

        add_submenu_page(
            RDWT_SLUG,
            __('Rdev', 'rdwt'),
            __('Settings', 'rdwt'),
            'manage_options',
            RDWT_SLUG . '-settings',
            array( 'Rdev\WpTools\View\Admin', 'renderSettings' ),
        );
    }

    /**
     * Register settings / options.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function addSettings(): void
    {
        register_setting(
            'rdwt_plugin',
            'rdwt',
            array( $this, 'validateSettings' )
        );

        add_settings_section(
            'rdwt-settings-overview',
            __('Overview', 'rdwt'),
            array( $this, 'renderSectionOverview' ),
            'rdwt',
            array(
                'after_section' => '<hr/>',
            )
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function enqueueScripts(): void
    {
        wp_enqueue_script(
            RDWT_SLUG,
            RDWT_URL . 'assets/js/rdwt-admin.js',
            array( 'jquery' ),
            RDWT_VERSION,
            false
        );
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function enqueueStyles(): void
    {
        wp_enqueue_style(
            RDWT_SLUG,
            RDWT_URL . 'assets/css/rdwt-admin.css',
            array(),
            RDWT_VERSION,
            'all'
        );
    }

    /**
     * Retrieve default options / settings.
     *
     * @access public
     * @return array
     * @since  1.0.0
     */
    public function getDefaultOptions(): array
    {
        return apply_filters('rdwt_default_options', $this->options);
    }

    /**
     * Init
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function init(): void
    {
        // If no options exist, create them.
        if (! get_option($this->option) ) {
            update_option($this->option, $this->getDefaultOptions());
        }
    }

    /**
     * Plugin action links callback.
     *
     * @param array $links Action links.
     *
     * @access public
     * @return array
     * @since  1.2.0
     */
    public function pluginActionLinks( $links ): array
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('admin.php?page=' . RDWT_SLUG),
            __('Overview', 'rdwt')
        );
        array_unshift($links, $settings_link);

        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('admin.php?page=' . RDWT_SLUG . '-settings'),
            __('Settings', 'rdwt')
        );
        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * Settings section callback.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function renderSectionOverview(): void
    {
        esc_html_e(
            'Rdev WP Tools is a collection of tools in a single ' .
            'bloat-less WordPress plugin.',
            'rdwt'
        );
    }

    /**
     * Settings field callback.
     *
     * @param array $args Settings arguments.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function renderSettingsField( $args ): void
    {
        $this->setNameAndValue($args);

        $args = wp_parse_args($args, array( 'classes' => array() ));

        if (empty($args['id']) || empty($args['page']) ) {
            return;
        }

        switch ( $args['type'] ) {
        case 'checkbox':
            SettingsFields::renderCheckbox($args);
            break;

        case 'radio':
            SettingsFields::renderRadio($args);
            break;

        case 'range':
            SettingsFields::renderRange($args);
            break;

        case 'raw':
            if (! isset($args['html']) ) {
                break;
            }
            echo wp_kses_post($args['html']);
            break;

        case 'text':
            SettingsFields::renderText($args);
            break;

        default:
            break;
        }

        if (isset($args['sub_desc']) && ! empty($args['sub_desc']) ) {
            echo '<span class="sub-desc">';
            echo wp_kses_post($args['sub_desc']);
            echo '</span>';
        }

        if (isset($args['desc']) && ! empty($args['desc']) ) {
            echo '<div class="description">';
            if (is_array($args['desc']) ) {

                array_walk(
                    $args['desc'],
                    function ( &$line ) {
                        $line = sprintf('<div>%s</div>', wp_kses_post($line));
                    }
                );
                echo implode('', $args['desc']);

            } else {
                echo wp_kses_post($args['desc']);
            }
            echo '</div>';
        }
    }

    /**
     * Set name and value from settings / options.
     *
     * @param array $args Key-value pairs.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function setNameAndValue( &$args ): void
    {
        if (! isset($args['name']) ) {
            $args['name'] = sprintf(
                '%s[%s]',
                esc_attr($args['page']),
                esc_attr($args['id'])
            );
        }

        if (! isset($args['value']) ) {
            $options = get_option($this->option, $this->getDefaultOptions());

            $args['value'] = $options[ $args['id'] ];
        }
    }

    /**
     * Validate settings / options.
     *
     * @param array $input array to validate.
     *
     * @access public
     * @return array
     * @since  1.0.0
     */
    public function validateSettings( $input ): array
    {
        return $input;
    }
}
