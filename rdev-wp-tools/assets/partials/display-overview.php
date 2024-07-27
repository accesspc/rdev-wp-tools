<?php
/**
 * Admin -> Overview.
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
