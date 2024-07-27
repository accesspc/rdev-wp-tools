<?php
/**
 * This is the main class of the plugin
 * php version 7.3.0
 *
 * @category WpTools
 * @package  Rdev\WpTools
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 */

namespace Rdev\WpTools;

use Rdev\WpTools\Admin\Settings;
use Rdev\WpTools\Core\UI;
use Rdev\WpTools\Modules\GA;
use Rdev\WpTools\Modules\PwdGen;

/**
 * Class: WpTools
 *
 * @category WpTools
 * @package  Rdev\WpTools
 * @author   Robertas Reiciunas <accesspc@gmail.com>
 * @license  GPL-3.0 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://github.com/accesspc/rdev-wp-tools
 * @since    2.0.0
 */
class WpTools
{

    /**
     * Google Analytics Object
     *
     * @access public
     * @since  1.1.0
     * @var    \Rdev\WpTools\Modules\GA
     */
    public $ga;

    /**
     * Password Generator Object
     *
     * @access public
     * @since  1.1.0
     * @var    \Rdev\WpTools\Modules\PwdGen
     */
    public $pwdgen;

    /**
     * Settings Object
     *
     * @access public
     * @since  1.0.0
     * @var    \Rdev\WpTools\Admin\Settings
     */
    public $settings;

    /**
     * UI Object
     *
     * @access public
     * @since  1.0.0
     * @var    \Rdev\WpTools\Core\UI
     */
    public $ui;

    /**
     * Main construct function
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function __construct()
    {
        $this->loadDependencies();
        $this->addHooks();
    }

    /**
     * Add hooks and filters
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function addHooks(): void
    {
        add_action(
            'plugins_loaded', 
            array( 
                '\Rdev\WpTools\Core\I18n', 
                'loadPluginTextdomain' 
            )
        );
    }

    /**
     * Load dependencies
     *
     * @access public
     * @return void
     * @since  1.0.0
     */
    public function loadDependencies(): void
    {
        $this->settings = new Settings();

        $this->ga     = new GA();
        $this->pwdgen = new PwdGen();

        $this->ui = new UI();
    }
}
