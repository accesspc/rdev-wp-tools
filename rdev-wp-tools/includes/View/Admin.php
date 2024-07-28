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
            <h1 class="rdwt-title"><?php esc_html_e('Rdev WP Tools', 'rdwt'); ?></h1>
            <?php settings_errors(); ?>
            <h2 class="nav-tab-wrapper">&nbsp;</h2>

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
        ?>
        <div class="wrap rdwt-admin-wrap">
            <h1 class="rdwt-title"><?php esc_html_e('Settings', 'rdwt'); ?></h1>
            <?php settings_errors(); ?>
            <h2 class="nav-tab-wrapper">&nbsp;</h2>

            <form method="post" action="options.php">

                <?php
                    settings_fields('rdwt_plugin_settings');
                    do_settings_sections('rdwt-settings');
                    submit_button();
                ?>

            </form>
        </div>
        <?php
    }
}
