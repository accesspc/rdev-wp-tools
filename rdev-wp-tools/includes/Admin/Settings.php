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

use Rdev\WpTools\Core\Module;

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
class Settings extends Module
{

    /**
     * RDWT module name.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $module = 'settings';

    /**
     * RDWT module title.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $moduleTitle = 'Rdev WP Tools';

    /**
     * RDWT option group.
     *
     * @access protected
     * @since  2.2.0
     * @var    string
     */
    protected string $optionGroup = 'rdwt_modules';

    /**
     * RDWT option name.
     *
     * @access protected
     * @since  1.0.0
     * @var    string
     */
    protected string $optionName = 'rdwt';

    /**
     * RDWT Options
     *
     * @access protected
     * @since  1.0.0
     * @var    array
     */
    protected array $options = array(
        'ga'     => false,
        'pwdgen' => false,
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
        $this->init();
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
            __($this->moduleTitle, 'rdwt'),
            __($this->moduleTitle, 'rdwt'),
            'manage_options',
            RDWT_SLUG,
            array( $this, 'renderSettings' ),
            '',
            80
        );
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
     * Register settings / options.
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function addSettings(): void
    {
        parent::addSettings();

        // Settings section and fields
        $page = 'rdwt';
        $section = 'rdwt-settings-section';

        add_settings_section(
            $section,
            __('Modules', 'rdwt'),
            array( $this, 'renderSectionOverview' ),
            $page,
            array(
                'after_section' => '<hr/>',
            )
        );

        $modules = array(
            array(
                'id' => 'ga',
                'title' => __('Google Analytics', 'rdwt'),
                'sub_desc' => __(
                    'Check to place the tracking code on website',
                    'rdwt'
                ),
            ),
            array(
                'id' => 'pwdgen',
                'title' => __('Password Generator', 'rdwt'),
                'sub_desc' => __(
                    'Check to enable Password Generator shortcode',
                    'rdwt'
                ),
            ),
        );

        foreach ($modules as $obj) {
            add_settings_field(
                $obj['id'],
                $obj['title'],
                array( $this, 'renderSettingsField' ),
                $page,
                $section,
                array(
                    'class'     => 'rdwt-setting',
                    'id'        => $obj['id'],
                    'label_for' => $obj['id'],
                    'page'      => $page,
                    'sub_desc'  => $obj['sub_desc'],
                    'type'      => 'checkbox',
                ),
            );
        }
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
     * Render: Settings page.
     *
     * @access public
     * @return void
     * @since  2.0.0
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
                <a href="?page=<?php echo RDWT_SLUG; ?>"
                class="nav-tab <?php
                if ($tab === null) :
                    ?>nav-tab-active<?php
                endif;
                ?>">Overview</a>
            </nav>

            <form method="post" action="options.php">

                <?php
                    settings_fields($this->optionGroup);
                    do_settings_sections('rdwt');
                    submit_button();
                ?>

            </form>
        </div>
        <?php
    }
}
