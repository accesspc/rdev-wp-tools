<?php
/**
 * This is the main class of the plugin
 *
 * @link https://reiciunas.dev/plugins/rdev-wp-tools/
 * @since 2.0.0
 *
 * @package Rdev\WpTools
 * @subpackage Rdev\WpTools
 */

namespace Rdev\WpTools;

use Rdev\WpTools\Admin\Settings;
use Rdev\WpTools\Core\UI;
use Rdev\WpTools\Modules\GA;
use Rdev\WpTools\Modules\PwdGen;

/**
 * Class: WpTools
 *
 * @since 2.0.0
 */
class WpTools {

    /**
     * Google Analytics Object
     *
     * @access public
     * @since 1.1.0
     * @var \Rdev\WpTools\Modules\GA
     */
    public $ga;

    /**
     * Password Generator Object
     *
     * @access public
     * @since 1.1.0
     * @var \Rdev\WpTools\Modules\PwdGen
     */
    public $pwdgen;

    /**
     * Settings Object
     *
     * @access public
     * @since 1.0.0
     * @var \Rdev\WpTools\Admin\Settings
     */
    public $settings;

    /**
     * UI Object
     *
     * @access public
     * @since 1.0.0
     * @var \Rdev\WpTools\Core\UI
     */
    public $ui;

    /**
     * Main construct function
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function __construct() {
        $this->load_dependencies();
        $this->add_hooks();
    }

    /**
     * Add hooks and filters
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function add_hooks(): void {
        add_action( 'plugins_loaded', array( '\Rdev\WpTools\Core\I18n', 'load_plugin_textdomain' ) );
    }

    /**
     * Load dependencies
     *
     * @access public
     * @return void
     * @since 1.0.0
     */
    public function load_dependencies(): void {
        $this->settings = new Settings();

        $this->ga     = new GA();
        $this->pwdgen = new PwdGen();

        $this->ui = new UI();
    }
}
