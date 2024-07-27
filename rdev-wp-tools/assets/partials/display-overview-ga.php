<?php
/**
 * Admin -> Overview -> GA settings information.
 *
 * @package Rdev\WpTools
 */

if ( ! function_exists( 'add_action' ) ) {
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
