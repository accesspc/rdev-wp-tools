<?php
/**
 * Plugin Module
 * php version 7.3.0
 *
 * @category Core
 * @package  Rdev\WpTools\Core
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\Core;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * Class: Module
 *
 * @category Core
 * @package  Rdev\WpTools\Core
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    2.2.0
 */
class Module
{

    /**
     * RDWT module name.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $module = 'module';

    /**
     * RDWT module title.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $moduleTitle = 'Module';

    /**
     * RDWT option name.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $optionName = 'rdwt';

    /**
     * RDWT Options
     *
     * @access protected
     * @since  2.2.0
     * @var    array
     */
    protected array $options = array();

    /**
     * Main construct function.
     *
     * @access public
     * @return void
     * @since  2.2.0
     */
    public function __construct()
    {
        if ($this->isEnabled()) {
            $this->addHooks();
        }
    }

    /**
     * Add admin submenu.
     *
     * @access public
     * @return void
     * @since  2.2.0
     */
    public function addAdminMenu(): void
    {
        if (! current_user_can('manage_options') ) {
            return;
        }

        add_submenu_page(
            RDWT_SLUG,
            __($this->moduleTitle, 'rdwt'),
            __($this->moduleTitle, 'rdwt'),
            'manage_options',
            RDWT_SLUG . '-' . $this->module,
            array( $this, 'renderSettings' ),
        );
    }

    /**
     * Add Settings hooks.
     *
     * @access public
     * @return void
     * @since  2.2.0
     */
    public function addHooks(): void
    {
        add_action('admin_menu', array( $this, 'addAdminMenu' ));
        add_action('admin_init', array( $this, 'addSettings' ));

        add_action('init', array( $this, 'init' ));
    }

    /**
     * Register settings / options.
     *
     * @access public
     * @return void
     * @since  2.2.0
     */
    public function addSettings(): void
    {
        
    }

    /**
     * Retrieve default options / settings.
     *
     * @access public
     * @return array
     * @since  2.2.0
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
     * @since  2.2.0
     */
    public function init(): void
    {
        // If no options exist, create them.
        if (! get_option($this->optionName) ) {
            update_option($this->optionName, $this->getDefaultOptions());
        }
    }

    /**
     * Is module enabled.
     *
     * @access public
     * @return bool
     * @since  2.2.0
     */
    public function isEnabled(): bool
    {
        $options = get_option('rdwt');
        if (in_array($this->module, array_keys($options))
            && $options[$this->module]
        ) {
                return true;
        }
        return false;
    }

    /**
     * Render: Settings page.
     *
     * @access public
     * @return void
     * @since  2.2.0
     */
    public function renderSettings(): void
    {

    }

    /**
     * Settings field callback.
     *
     * @param array $args Settings arguments.
     *
     * @access public
     * @return void
     * @since  2.2.0
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
     * @since  2.2.0
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
            $options = get_option($this->optionName, $this->getDefaultOptions());

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
     * @since  2.2.0
     */
    public function validateSettings( $input ): array
    {
        if ($input == null) {
            return array();
        }
        return $input;
    }
}
