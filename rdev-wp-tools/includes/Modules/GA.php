<?php

/**
 * Google Analytics
 * php version 7.3.0
 *
 * @category Module
 * @package  Rdev\WpTools\Modules
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\Modules;

use Rdev\WpTools\Core\Module;

/**
 * Class: GA
 *
 * @category Module
 * @package  Rdev\WpTools\Modules
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    1.0.0
 */
class GA extends Module
{
    /**
     * RDWT module name.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $module = 'ga';

    /**
     * RDWT module title.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $moduleTitle = 'Google Analytics';

    /**
     * RDWT option group.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $optionGroup = 'rdwt_module_ga';

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
        'id'       => '',
        'location' => 'header',
    );

    /**
     * Register settings / options.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function addSettings(): void
    {
        parent::addSettings();

        // Settings section and fields.
        $section = $this->settingsPage . '-section';

        add_settings_section(
            $section,
            __($this->moduleTitle, 'rdwt'),
            array($this, 'renderSection'),
            $this->settingsPage,
            array(
                'after_section' => '<hr/>',
            ),
        );

        add_settings_field(
            'id',
            __('GA Tracking ID', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $this->settingsPage,
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
                'id'        => 'id',
                'label_for' => 'id',
                'page'      => $this->optionName,
                'sub_desc'  => '',
                'type'      => 'text',
            ),
        );

        add_settings_field(
            'location',
            __('Tracking code location', 'rdwt'),
            array( $this, 'renderSettingsField' ),
            $this->settingsPage,
            $section,
            array(
                'class'     => 'rdwt-setting',
                'desc'      => __(
                    'Tip: Google recommends including the tracking code in the ' .
                    'page head, but including it in the footer can benefit page ' .
                    'performance. If in doubt, go with the head option.',
                    'rdwt'
                ),
                'id'        => 'location',
                'label_for' => 'location',
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
                'page'      => $this->optionName,
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

        if ($this->isEnabled()) {
            $location = isset($options['location'])
                ? $options['location'] : 'header';

            if ('header' === $location) {
                add_action('wp_head', array( &$this, 'renderTrackingCode' ));
            } else {
                add_action('wp_footer', array( &$this, 'renderTrackingCode' ));
            }
        }
    }

    /**
     * Render: Settings section.
     *
     * @access public
     * @return void
     * @since  2.1.0
     */
    public function renderSection(): void
    {
        ?>
        This tool allows you to place a Google Analytics tracking code on your
        website.
        <?php
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

        ?>
        <!-- Google tag (gtag.js) -->
        <script async
            src="https://www.googletagmanager.com/gtag/js?id=<?php
                echo $options['id'];
            ?>">
        </script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){window.dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?php echo $options['id']; ?>');
        </script>
        <!-- End: Google tag (gtag.js) -->
        <?php
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
    public function validateSettings($input): array
    {
        if (isset($_POST['reset'])) {
            add_settings_error(
                $this->optionGroup,
                'reset-defaults',
                __(
                    'Module settings reset to defaults.',
                    'rdwt'
                ),
                'warning'
            );

            return $this->options;
        }

        if (isset($input['id'])) {
            $input['id'] = wp_filter_nohtml_kses($input['id']);

            if (preg_match('/^GTM-/i', $input['id'])) {
                $input['id'] = '';

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
                    'id',
                    'invalid-tracking-code',
                    $message,
                    'error'
                );
            }
        }

        return $input;
    }
}
