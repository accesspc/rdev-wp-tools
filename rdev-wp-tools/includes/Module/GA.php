<?php
/**
 * Google Analytics
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
use Rdev\WpTools\View\GA as ViewGA;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * Class: GA
 *
 * @category Module
 * @package  Rdev\WpTools\Module
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    1.0.0
 */
class GA extends Settings
{

    /**
     * RDWT option name.
     *
     * @access protected
     * @since  1.0.0
     * @var    string
     */
    protected string $optionName = 'rdwt_ga';

    /**
     * RDWT Options
     *
     * @access protected
     * @since  1.0.0
     * @var    array
     */
    protected array $options = array(
        'ga_enable'   => false,
        'ga_id'       => '',
        'ga_location' => 'header',
    );

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
        add_action('admin_init', array( $this, 'addSettings' ));

        add_action('init', array( $this, 'init' ));
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
            'rdwt_plugin_settings_ga',
            $this->optionName,
            array( $this, 'validateSettings' )
        );

        // Settings section and fields.
        $page = 'rdwt-settings-ga';
        $section = 'rdwt-settings-ga-section';

        add_settings_section(
            $section,
            __('Google Analytics', 'rdwt'),
            array( 'Rdev\WpTools\View\GA', 'renderSection'),
            $page,
            array(
                'after_section' => '<hr/>',
            ),
        );

        add_settings_field(
            'ga_enable',
            __('Enable', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $page,
            $section,
            array(
                'class'     => 'rdwt-setting',
                'id'        => 'ga_enable',
                'label_for' => 'ga_enable',
                'page'      => 'rdwt_ga',
                'sub_desc'  => __(
                    'Check to place the tracking code on website',
                    'rdwt'
                ),
                'type'      => 'checkbox',
            ),
        );

        add_settings_field(
            'ga_id',
            __('GA Tracking ID', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $page,
            $section,
            array(
                'class'     => 'rdwt-setting',
                'desc'      => array(
                    __(
                        'Enter your Google Tracking ID. Note: the Tracking ID ' .
                        'also may be referred to as Tag ID, Measurement ID, or ' .
                        'Property ID.',
                        'rdwt'
                    ),
                    __(
                        'Supported ID formats include AW-XXXXXXXXX, G-XXXXXXXXX, ' .
                        'GT-XXXXXXXXX, and UA-XXXXXXXXX. Google Tag Manager ' .
                        '(GTM-XXXXXXXXX) currently is not supported.',
                        'rdwt'
                    ),
                ),
                'id'        => 'ga_id',
                'label_for' => 'ga_id',
                'page'      => 'rdwt_ga',
                'sub_desc'  => '',
                'type'      => 'text',
            ),
        );

        add_settings_field(
            'ga_location',
            __('Tracking code location', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $page,
            $section,
            array(
                'class'     => 'rdwt-setting',
                'desc'      => __(
                    'Tip: Google recommends including the tracking code in the ' .
                    'page head, but including it in the footer can benefit page ' .
                    'performance. If in doubt, go with the head option.',
                    'rdwt'
                ),
                'id'        => 'ga_location',
                'label_for' => 'ga_location',
                'options'   => array(
                    array(
                        'value' => 'header',
                        'desc'  => __(
                            'Include tracking code in page head ' .
                            '(via <code>wp_head</code>)',
                            'rdwt'
                        ),
                    ),
                    array(
                        'value' => 'footer',
                        'desc'  => __(
                            'Include tracking code in page footer ' .
                            '(via <code>wp_footer</code>)',
                            'rdwt'
                        ),
                    ),
                ),
                'page'      => 'rdwt_ga',
                'type'      => 'radio',
            ),
        );
    }

    /**
     * GA: Init function to render/or not the tracking code
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function init(): void
    {
        parent::init();

        $options = get_option($this->optionName, $this->getDefaultOptions());

        if (isset($options['ga_enable']) && $options['ga_enable'] ) {

            $location = isset($options['ga_location'])
                ? $options['ga_location'] : 'header';

            if ('header' === $location ) {
                add_action('wp_head', array( &$this, 'renderTrackingCode' ));
            } else {
                add_action('wp_footer', array( &$this, 'renderTrackingCode' ));
            }
        }
    }

    /**
     * GA: Render tracking code.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function renderTrackingCode(): void
    {
        $options = get_option($this->optionName, $this->getDefaultOptions());

        ViewGA::renderScript($options);
    }

    /**
     * Validate settings / options.
     *
     * @param array $input Key-value array.
     *
     * @access public
     * @return array
     * @since  1.0.0
     */
    public function validateSettings( $input ): array
    {
        if (isset($input['ga_id']) ) {
            $input['ga_id'] = wp_filter_nohtml_kses($input['ga_id']);

            if (preg_match('/^GTM-/i', $input['ga_id']) ) {
                $input['ga_id'] = '';

                $message  = esc_html__(
                    'Error: your tracking code begins with',
                    'rdwt'
                ) . ' <code>GTM-</code> ';
                $message .= esc_html__(
                    '(for Google Tag Manager), which is not supported. ' .
                    'Please try again with a supported tracking code.',
                    'rdwt'
                );

                add_settings_error(
                    'ga_id',
                    'invalid-tracking-code',
                    $message,
                    'error'
                );
            }
        }
        return $input;
    }
}
