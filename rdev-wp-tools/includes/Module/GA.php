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

use Rdev\WpTools\Core\Module;

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
        'ga_id'       => '',
        'ga_location' => 'header',
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
        register_setting(
            'rdwt_plugin_settings_' . $this->module,
            $this->optionName,
            array( $this, 'validateSettings' )
        );

        // Settings section and fields.
        $page = 'rdwt-settings-' . $this->module;
        $section = $page . '-section';

        add_settings_section(
            $section,
            __($this->moduleTitle, 'rdwt'),
            array($this, 'renderSection'),
            $page,
            array(
                'after_section' => '<hr/>',
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
                'page'      => $this->optionName,
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
     * Render: Settings page.
     *
     * @access public
     * @return void
     * @since  2.2.0
     */
    public function renderSettings(): void
    {
        $default_tab = null;
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

        ?>
        <div class="wrap rdwt-admin-wrap">
        <h1 class="rdwt-title">
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <?php settings_errors(); ?>

        <nav class="nav-tab-wrapper">
            <a href="?page=<?php echo RDWT_SLUG; ?>-ga"
            class="nav-tab <?php
            if ($tab === null) :
                ?>nav-tab-active<?php
            endif;
            ?>">Settings</a>
        </nav>

        <form method="post" action="options.php">
            <div class="tab-content">

            <?php
                settings_fields('rdwt_plugin_settings_' . $this->module);
                do_settings_sections('rdwt-settings-' . $this->module);
                submit_button();
            ?>

            </div>
        </form>
        </div>
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
                echo $options['ga_id'];
            ?>">
        </script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){window.dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?php echo $options['ga_id']; ?>');
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
