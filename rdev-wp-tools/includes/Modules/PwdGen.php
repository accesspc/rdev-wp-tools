<?php
/**
 * Password Generator
 * php version 7.3.0
 *
 * @category Modules
 * @package  Rdev\WpTools\Modules
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\Modules;

use Rdev\WpTools\Admin\Settings;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * Class: PwdGen
 *
 * @category Modules
 * @package  Rdev\WpTools\Modules
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    1.1.0
 */
class PwdGen extends Settings
{

    /**
     * RDWT option name.
     *
     * @access protected
     * @since  1.1.0
     * @var    string
     */
    protected string $option = 'rdwt_pwdgen';

    /**
     * RDWT Options
     *
     * @access protected
     * @since  1.1.0
     * @var    array
     */
    protected array $options = array(
        'pwdgen_enable'      => false,
        'pwdgen_count'       => 3,
        'pwdgen_length'      => 16,
        'pwdgen_inc_numbers' => true,
        'pwdgen_inc_lower'   => true,
        'pwdgen_inc_upper'   => true,
        'pwdgen_inc_symbols' => false,
    );

    /**
     * RDWT PwdGen Shortcode tag.
     *
     * @access public
     * @since  1.1.0
     * @var    string
     */
    public $shortcodeTag = 'rdwt_pwdgen';

    /**
     * Main construct function.
     *
     * @access public
     * @return void
     * @since  1.1.0
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Add Settings hooks.
     *
     * @access public
     * @return void
     * @since  1.1.0
     */
    public function addHooks(): void
    {
        add_action('admin_init', array( $this, 'addSettings' ));

        add_action('init', array( $this, 'init' ));
    }

    /**
     * Register settings / options.
     *
     * @access public
     * @return void
     * @since  1.1.0
     */
    public function addSettings(): void
    {
        register_setting(
            'rdwt_plugin_settings',
            $this->option,
            array( $this, 'validateSettings' )
        );

        // Overview section's field.
        ob_start();
        include_once RDWT_DIR . 'assets/partials/display-overview-pwdgen.php';
        $html = str_replace(array( "\r", "\n" ), '', ob_get_clean());

        add_settings_field(
            'pwdgen_overview',
            __('Password Generator', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            'rdwt',
            'rdwt-settings-overview',
            array(
                'html' => $html,
                'id'   => 'pwdgen_overview',
                'page' => 'rdwt_overview',
                'type' => 'raw',
            )
        );

        // Settings section and fields.
        add_settings_section(
            'rdwt-settings-pwdgen-section',
            __('Password Generator', 'rdwt'),
            array( $this, 'renderSectionPwdGen' ),
            'rdwt-settings',
            array(
                'after_section' => '<hr/>',
            )
        );

        add_settings_field(
            'pwdgen_enable',
            __('Enable', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            'rdwt-settings',
            'rdwt-settings-pwdgen-section',
            array(
                'class'     => 'rdwt-setting',
                'id'        => 'pwdgen_enable',
                'label_for' => 'pwdgen_enable',
                'page'      => 'rdwt_pwdgen',
                'sub_desc'  => __(
                    'Check to enable Password Generator shortcode',
                    'rdwt'
                ),
                'type'      => 'checkbox',
            )
        );

        add_settings_field(
            'pwdgen_count',
            __('Number of passwords', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            'rdwt-settings',
            'rdwt-settings-pwdgen-section',
            array(
                'class'     => 'rdwt-setting rdwt-range pwdgen-counter',
                'id'        => 'pwdgen_count',
                'label_for' => 'pwdgen_count',
                'max'       => 10,
                'min'       => 1,
                'page'      => 'rdwt_pwdgen',
                'step'      => 1,
                'sub_desc'  => $this->options['pwdgen_count'],
                'type'      => 'range',
            )
        );

        add_settings_field(
            'pwdgen_length',
            __('Password length', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            'rdwt-settings',
            'rdwt-settings-pwdgen-section',
            array(
                'class'     => 'rdwt-setting rdwt-range',
                'id'        => 'pwdgen_length',
                'label_for' => 'pwdgen_length',
                'max'       => 32,
                'min'       => 8,
                'page'      => 'rdwt_pwdgen',
                'step'      => 1,
                'sub_desc'  => $this->options['pwdgen_length'],
                'type'      => 'range',
            )
        );

        add_settings_field(
            'pwdgen_inc_numbers',
            '',
            array( $this, 'renderSettingsField' ),
            'rdwt-settings',
            'rdwt-settings-pwdgen-section',
            array(
                'class'     => 'rdwt-setting',
                'id'        => 'pwdgen_inc_numbers',
                'label_for' => 'pwdgen_inc_numbers',
                'page'      => 'rdwt_pwdgen',
                'sub_desc'  => __('Numbers', 'rdwt'),
                'type'      => 'checkbox',
            )
        );

        add_settings_field(
            'pwdgen_inc_lower',
            '',
            array( $this, 'renderSettingsField' ),
            'rdwt-settings',
            'rdwt-settings-pwdgen-section',
            array(
                'class'     => 'rdwt-setting',
                'id'        => 'pwdgen_inc_lower',
                'label_for' => 'pwdgen_inc_lower',
                'page'      => 'rdwt_pwdgen',
                'sub_desc'  => __('Lower case letters', 'rdwt'),
                'type'      => 'checkbox',
            )
        );

        add_settings_field(
            'pwdgen_inc_upper',
            '',
            array( $this, 'renderSettingsField' ),
            'rdwt-settings',
            'rdwt-settings-pwdgen-section',
            array(
                'class'     => 'rdwt-setting',
                'id'        => 'pwdgen_inc_upper',
                'label_for' => 'pwdgen_inc_upper',
                'page'      => 'rdwt_pwdgen',
                'sub_desc'  => __('Upper case letters', 'rdwt'),
                'type'      => 'checkbox',
            )
        );

        add_settings_field(
            'pwdgen_inc_symbols',
            '',
            array( $this, 'renderSettingsField' ),
            'rdwt-settings',
            'rdwt-settings-pwdgen-section',
            array(
                'class'     => 'rdwt-setting',
                'id'        => 'pwdgen_inc_symbols',
                'label_for' => 'pwdgen_inc_symbols',
                'page'      => 'rdwt_pwdgen',
                'sub_desc'  => __('Symbols', 'rdwt'),
                'type'      => 'checkbox',
            )
        );
    }

    /**
     * PwdGen: Init function
     *
     * @access public
     * @return void
     * @since  1.1.0
     */
    public function init(): void
    {
        parent::init();

        $options = get_option($this->option, $this->getDefaultOptions());

        if (isset($options['pwdgen_enable']) && $options['pwdgen_enable'] ) {
            add_shortcode($this->shortcodeTag, array( $this, 'renderShortcode' ));
        }
    }

    /**
     * Settings section callback.
     *
     * @access public
     * @return void
     * @since  1.1.0
     */
    public function renderSectionPwdGen(): void
    {
        esc_html_e('These are the settings for Password Generator', 'rdwt');
    }

    /**
     * PwdGen: Render shortcode block
     *
     * @param array  $atts    Shortcode attributes.
     * @param string $content Shortcode content.
     *
     * @access public
     * @return string
     * @since  1.1.0
     */
    public function renderShortcode( $atts, $content = '' ): string
    {
        $options = get_option($this->option, $this->getDefaultOptions());
        $opts    = array();

        foreach ( $options as $k => $v ) {
            $opts[ str_replace('pwdgen_', '', $k) ] = $v;
        }

        // $atts = shortcode_atts(
        // array(
        // 'count' => '3'
        // ), $atts, $this->shortcodeTag
        // );

        ob_start();
        include_once RDWT_DIR . 'assets/partials/pwdgen-shortcode.php';
        return str_replace(array( "\r", "\n" ), '', ob_get_clean());
    }

    /**
     * Validate settings / options.
     *
     * @param array $input Key-value pairs.
     *
     * @access public
     * @return array
     * @since  1.1.0
     */
    public function validateSettings( $input ): array
    {
        foreach ( array_keys($this->options) as $k ) {
            if (isset($input[ $k ]) ) {
                $input[ $k ] = wp_filter_nohtml_kses($input[ $k ]);
            }
        }
        return $input;
    }
}
