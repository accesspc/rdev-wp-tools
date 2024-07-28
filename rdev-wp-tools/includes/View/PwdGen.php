<?php
/**
 * Password Generator
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
class PwdGen
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
     * Get: Shortcode.
     *
     * @param array $options Shortcode options.
     *
     * @access public
     * @return string
     * @since  2.0.0
     */
    public static function getShortcode($options): string
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
     * Render: Settings section.
     *
     * @access public
     * @return void
     * @since  2.1.0
     */
    public static function renderSection(): void
    {
        ?>
        This tool allows you to place a password generator shortcode anywhere
        on the site.
        <?php
    }
}
