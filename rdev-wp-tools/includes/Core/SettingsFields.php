<?php
/**
 * Settings Fields
 * php version 7.3.0
 *
 * @category Core
 * @package  Rdev\WpTools\Core
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools\Core;

if (! defined('ABSPATH') ) {
    exit;
}

/**
 * The admin-settings fields specific functionality of the plugin
 *
 * @category Core
 * @package  Rdev\WpTools\Core
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    2.0.0
 */

class SettingsFields
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
     * Render checkbox field.
     *
     * @param array $args field args.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public static function renderCheckbox($args): void
    {
        ?>
        <input
            type='checkbox'
            id='<?php echo esc_attr($args['id']); ?>'
            name='<?php echo esc_attr($args['name']); ?>'
            value='1'
            class='<?php echo implode(' ', $args['classes']); ?>'
            <?php
            if (isset($args['value']) ) {
                checked('1', $args['value']);
            }
            ?>
        />
        <?php
    }

    /**
     * Render radio field.
     *
     * @param array $args field args.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public static function renderRadio($args): void
    {
        foreach ( $args['options'] as $option ) {
            ?>
            <div class="rdwt-radio">
            <input
                type='radio'
                id='<?php echo esc_attr($args['id']); ?>'
                name='<?php echo esc_attr($args['name']); ?>'
                value='<?php echo esc_attr($option['value']); ?>'
                class='<?php echo implode(' ', $args['classes']); ?>'
            <?php
                checked(esc_attr($option['value']), $args['value']);
            ?>
            />
            <?php
            echo wp_kses_post($option['desc']);
            ?>
            </div>
            <?php
        }
    }

    /**
     * Render range field.
     *
     * @param array $args field args.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public static function renderRange($args): void
    {
        ?>
        <input
            type='range'
            id='<?php echo esc_attr($args['id']); ?>'
            name='<?php echo esc_attr($args['name']); ?>'
            value='<?php echo esc_attr($args['value']); ?>'
            class='<?php echo implode(' ', $args['classes']); ?>'
            min='<?php echo esc_attr($args['min']); ?>'
            max='<?php echo esc_attr($args['max']); ?>'
            step='<?php echo esc_attr($args['step']); ?>'
        />
        <?php
    }

    /**
     * Render text field.
     *
     * @param array $args field args.
     *
     * @access public
     * @return void
     * @since  2.0.0
     */
    public static function renderText($args): void
    {
        ?>
        <input
            type='text'
            id='<?php echo esc_attr($args['id']); ?>'
            name='<?php echo esc_attr($args['name']); ?>'
            value='<?php echo esc_attr($args['value']); ?>'
            class='<?php echo implode(' ', $args['classes']); ?>'
        />
        <?php
    }
}
