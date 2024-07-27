<?php
/**
 * UI -> GA code.
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
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $options['ga_id']; ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){window.dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?php echo $options['ga_id']; ?>');
</script>
<!-- End: Google tag (gtag.js) -->
