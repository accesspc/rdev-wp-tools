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
     * Get: Overview page.
     *
     * @access public
     * @return string
     * @since  2.0.0
     */
    public static function getOverview(): string
    {
        ob_start();
        ?>
        <div class="rdwt-section-field">
            This tool allows you to place a password generator shortcode anywhere
            on the site.</br><a href="?page=rdwt-settings">Settings</a>
            page allows you to change the following settings for the shortcode:
            <ul>
                <li>Number of password: <code>1-10</code></li>
                <li>Password length: <code>8-32</code></li>
                <li>Include any combination of the following:
                    <ul>
                        <li>Numbers: <code>[0-9]</code></li>
                        <li>Lower case letters: <code>[a-z]</code></li>
                        <li>Upper case letters: <code>[A-Z]</code></li>
                        <li>Symbols: <code>!@#$%^&*(){}[]=&lt;&gt;/,.</code></li>
                    </ul>
                </li>
            </ul>
            <p>
                Shortcode to insert anywhere on the site:
                <code>[rdwt_pwdgen]</code>
            </p>
        </div>
        <?php
        return str_replace(array( "\r", "\n"), '', ob_get_clean());
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

}
