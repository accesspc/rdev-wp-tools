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

use Rdev\WpTools\Core\Module;

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
class PwdGen extends Module
{

    /**
     * RDWT module name.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $module = 'pwdgen';

    /**
     * RDWT module title.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $moduleTitle = 'Password Generator';

    /**
     * RDWT option group.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $optionGroup = 'rdwt_module_pwdgen';

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
     * Register settings / options.
     *
     * @access public
     * @return void
     * @since  1.1.0
     */
    public function addSettings(): void
    {
        parent::addSettings();

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
                'page'      => $this->optionName,
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
                'page'      => $this->optionName,
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
                    'page'      => $this->optionName,
                    'sub_desc'  => $obj['sub_desc'],
                    'type'      => 'checkbox',
                )
            );
        }
    }

    /**
     * Get: Shortcode.
     *
     * @param array $options Shortcode options.
     *
     * @access public
     * @return string
     * @since  2.0.0
     */
    public function getShortcode($options): string
    {
        ob_start();
        ?>
        <div class="rdwt-pwdgen">
            <input
                type="hidden" class="pwdgen-count" name="pwdgen-count"
                value="<?php esc_html_e($options['pwdgen_count']); ?>"
            />
            <input
                type="hidden" class="pwdgen-length" name="pwdgen-length"
                value="<?php esc_html_e($options['pwdgen_length']); ?>"
            />
            <input
                type="hidden" class="pwdgen-inc_numbers" name="pwdgen-inc_numbers"
                value="<?php esc_html_e($options['pwdgen_inc_numbers']); ?>"
            />
            <input
                type="hidden" class="pwdgen-inc_lower" name="pwdgen-inc_lower"
                value="<?php esc_html_e($options['pwdgen_inc_lower']); ?>"
            />
            <input
                type="hidden" class="pwdgen-inc_upper" name="pwdgen-inc_upper"
                value="<?php esc_html_e($options['pwdgen_inc_upper']); ?>"
            />
            <input
                type="hidden" class="pwdgen-inc_symbols" name="pwdgen-inc_symbols"
                value="<?php esc_html_e($options['pwdgen_inc_symbols']); ?>"
            />
            <div class="wp-block-button rdwt-pwdgen-generate">
                <a class="wp-block-button__link wp-element-button">Generate</a>
            </div>
            <div class="pwdgen-list"></div>
        </div>
        <?php
        return str_replace(array( "\r", "\n"), '', ob_get_clean());
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

        if ($this->isEnabled()) {
            add_shortcode($this->shortcodeTag, array( $this, 'renderShortcode' ));
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
        This tool allows you to place a password generator shortcode anywhere
        on the site.
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
            <a href="?page=<?php echo RDWT_SLUG; ?>-pwdgen"
            class="nav-tab <?php
            if ($tab === null) :
                ?>nav-tab-active<?php
            endif;
            ?>">Settings</a>
        </nav>

        <form method="post" action="options.php">
            <div class="tab-content">

            <?php
                settings_fields($this->optionGroup);
                do_settings_sections('rdwt-settings-' . $this->module);
                submit_button();
            ?>

            </div>
        </form>
        </div>
        <?php
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
        $options = array_merge(
            $this->getDefaultOptions(),
            get_option($this->optionName, $this->getDefaultOptions()),
        );
        $opts    = array();

        foreach ( $options as $k => $v ) {
            $opts[ str_replace('pwdgen_', '', $k) ] = $v;
        }

        return $this->getShortcode($options);
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
