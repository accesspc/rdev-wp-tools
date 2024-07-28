<?php
/**
 * Admin
 * php version 7.3.0
 *
 * @category View
 * @package  Rdev\WpTools\View
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\View;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * Class: GA
 *
 * @category View
 * @package  Rdev\WpTools\View
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    2.0.0
 */
class Admin
{

    /**
     * Main construct function.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public function __construct()
    {

    }

    /**
     * Render: Overview page.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public static function renderOverview(): void
    {
        ?>
        <div class="wrap rdwt-admin-wrap">
            <h1 class="rdwt-title">
                <?php echo esc_html(get_admin_page_title()); ?>
            </h1>
            <?php settings_errors(); ?>

            <form method="post" action="options.php">

                <?php
                    settings_fields('rdwt_plugin');
                    do_settings_sections('rdwt');
                ?>

            </form>
        </div>
        <?php
    }

    /**
     * Render: Settings page.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public static function renderSettings(): void
    {
        $default_tab = null;
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

        ?>
        <div class="wrap rdwt-admin-wrap">
        <h1 class="rdwt-title"><?php esc_html_e('Settings', 'rdwt'); ?></h1>
        <?php settings_errors(); ?>

        <nav class="nav-tab-wrapper">
            <a href="?page=<?php echo RDWT_SLUG; ?>-settings"
            class="nav-tab <?php
            if ($tab === null) :
                ?>nav-tab-active<?php
            endif;
            ?>">Overview</a>

            <a href="?page=<?php echo RDWT_SLUG; ?>-settings&tab=ga"
            class="nav-tab <?php
            if ($tab === 'ga') :
                ?>nav-tab-active<?php
            endif;
            ?>">Google Analytics</a>

            <a href="?page=<?php echo RDWT_SLUG; ?>-settings&tab=pwdgen"
            class="nav-tab <?php
            if ($tab === 'pwdgen') :
                ?>nav-tab-active<?php
            endif;
            ?>">Password Generator</a>
        </nav>

        <form method="post" action="options.php">
            <div class="tab-content">

            <?php
            switch ($tab) {
            case 'ga':
                settings_fields('rdwt_plugin_settings_ga');
                do_settings_sections('rdwt-settings-ga');
                submit_button();
                break;

            case 'pwdgen':
                settings_fields('rdwt_plugin_settings_pwdgen');
                do_settings_sections('rdwt-settings-pwdgen');
                submit_button();
                break;

            default:
                settings_fields('rdwt_plugin_settings');
                do_settings_sections('rdwt-settings');
                // submit_button();
                break;
            }
            ?>

            </div>
        </form>
        </div>
        <?php
    }
}
