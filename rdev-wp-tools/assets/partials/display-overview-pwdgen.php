<?php
/**
 * Admin -> Overview -> PwdGen settings information.
 * php version 7.3.0
 *
 * @category WpTools
 * @package  Rdev\Partials
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

if (! function_exists('add_action') ) {
    die();
}

?>
<div class="rdwt-section-field">
    This tool allows you to place a password generator shortcode anywhere on the site.</br>
    <a href="?page=rdwt-settings">Settings</a> page allows you to change the following settings for the shortcode:
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
