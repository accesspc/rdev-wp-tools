<?php
/**
 * Password Generator
 * php version 7.3.0
 *
 * @category Module
 * @package  Rdev\WpTools\Module
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\Module;

use Rdev\WpTools\Admin\Settings;
use Rdev\WpTools\View\PwdGen as ViewPwdGen;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * Class: PwdGen
 *
 * @category Module
 * @package  Rdev\WpTools\Module
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
    protected string $optionName = 'rdwt_pwdgen';

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
            'rdwt_plugin_settings_pwdgen',
            $this->optionName,
            array( $this, 'validateSettings' )
        );

        // Settings section and fields.
        $page = 'rdwt-settings-pwdgen';
        $section = 'rdwt-settings-pwdgen-section';

        add_settings_section(
            $section,
            __('Password Generator', 'rdwt'),
            array( 'Rdev\WpTools\View\PwdGen', 'renderSection' ),
            'rdwt-settings-pwdgen',
            array(
                'after_section' => '<hr/>',
            ),
        );

        add_settings_field(
            'pwdgen_enable',
            __('Enable', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $page,
            $section,
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
            ),
        );

        add_settings_field(
            'pwdgen_count',
            __('Number of passwords', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $page,
            $section,
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
            ),
        );

        add_settings_field(
            'pwdgen_length',
            __('Password length', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $page,
            $section,
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
            ),
        );

        $pwdgen_inc = array(
            array(
                'id' => 'pwdgen_inc_numbers',
                'desc' => '',
                'sub_desc' => __('Numbers <code>[0-9]</code>', 'rdwt'),
            ),
            array(
                'id' => 'pwdgen_inc_lower',
                'desc' => '',
                'sub_desc' => __('Lower case letters <code>[a-z]</code>', 'rdwt'),
            ),
            array(
                'id' => 'pwdgen_inc_upper',
                'desc' => '',
                'sub_desc' => __('Upper case letters <code>[A-Z]</code>', 'rdwt'),
            ),
            array(
                'id' => 'pwdgen_inc_symbols',
                'desc' => '',
                'sub_desc' => __(
                    'Symbols <code>!@#$%^&*(){}[]=&lt;&gt;/,.</code>',
                    'rdwt'
                ),
            ),
        );

        foreach ($pwdgen_inc as $obj) {
            add_settings_field(
                $obj['id'],
                '',
                array( $this, 'renderSettingsField' ),
                $page,
                $section,
                array(
                    'class'     => 'rdwt-setting',
                    'desc'      => $obj['desc'],
                    'id'        => $obj['id'],
                    'label_for' => $obj['id'],
                    'page'      => 'rdwt_pwdgen',
                    'sub_desc'  => $obj['sub_desc'],
                    'type'      => 'checkbox',
                )
            );
        }
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

        $options = get_option($this->optionName, $this->getDefaultOptions());

        if (isset($options['pwdgen_enable']) && $options['pwdgen_enable'] ) {
            add_shortcode($this->shortcodeTag, array( $this, 'renderShortcode' ));
        }
    }

    /**
     * PwdGen: Render shortcode block
     *
     * @access public
     * @return string
     * @since  1.1.0
     */
    public function renderShortcode(): string
    {
        $options = get_option($this->optionName, $this->getDefaultOptions());
        $opts    = array();

        foreach ( $options as $k => $v ) {
            $opts[ str_replace('pwdgen_', '', $k) ] = $v;
        }

        return ViewPwdGen::getShortcode($options);
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
