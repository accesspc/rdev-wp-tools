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
        This tool allows you to place a Google Analytics tracking code on your
        website.
        <?php
    }
}
