<?php
/**
 * Admin -> Overview -> GA settings information.
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
    This tool allows you to place a Google Analytics tracking code on your website.<br/>
    <a href="?page=rdwt-settings">Settings</a> page allows you to change the following settings:
    <ul>
        <li>GA Tracking ID: can be created on <a href="https://analytics.google.com/" target="_blank">Google Analytics</a> page</li>
        <li>Code placement location:
            <ul>
                <li>Inside website's <code>&lt;head&gt;</code> element using <code>wp_head</code></li>
                <li>At the bottom of website's <code>&lt;body&gt;</code> element using <code>wp_footer</code></li>
            </ul>
        </li>
    </ul>
</div>
