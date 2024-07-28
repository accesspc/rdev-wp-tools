<?php
/**
 * Google Analytics
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
class GA
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
            This tool allows you to place a Google Analytics tracking code on your
            website.<br/>
            <a href="?page=rdwt-settings">Settings</a> page allows you to change
            the following settings:
            <ul>
                <li>GA Tracking ID: can be created on
                    <a href="https://analytics.google.com/" target="_blank">
                        Google Analytics
                    </a> page</li>
                <li>Code placement location:
                    <ul>
                        <li>Inside website's <code>&lt;head&gt;</code> element using
                        <code>wp_head</code></li>
                        <li>At the bottom of website's <code>&lt;body&gt;</code>
                        element using <code>wp_footer</code></li>
                    </ul>
                </li>
            </ul>
        </div>
        <?php
        return str_replace(array( "\r", "\n"), '', ob_get_clean());
    }

    /**
     * Render: Script.
     *
     * @param array $options Script options.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public static function renderScript($options): void
    {
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

}
