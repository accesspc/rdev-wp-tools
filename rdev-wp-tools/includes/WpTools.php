<?php

namespace Rdev\WpTools;

use Rdev\WpTools\Admin\Settings;
use Rdev\WpTools\Modules\GA;
use Rdev\WpTools\Modules\PwdGen;

/**
 * This is the main class of the plugin
 *
 * @link        https://reiciunas.dev/plugins/rdev-wp-tools/
 * @since       2.0.0
 * 
 * @package     Rdev\WpTools
 * @subpackage  Rdev\WpTools
 */
class WpTools
{

  /**
   * Google Analytics Object
   *
   * @access  public
   * @since   1.1.0
   * @var     \Rdev\modules\GA
   */
  public $ga;

  /**
   * Internationalization Object
   *
   * @access  public
   * @since   1.0.0
   * @var     \Rdev\core\i18n
   */
  public $i18n;

  /**
   * Public Object
   *
   * @access  public
   * @since   1.0.0
   * @var     \Rdev\core\public?
   */
  public $public;

  /**
   * Password Generator Object
   *
   * @access  public
   * @since   1.1.0
   * @var     \Rdev\modules\PwdGen
   */
  public $pwdgen;

  /**
   * Settings Object
   *
   * @access  public
   * @since   1.0.0
   * @var     \Rdev\admin\Settings
   */
  public $settings;

  /**
   * Main construct function
   *
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function __construct()
  {
    $this->load_dependencies();
    $this->add_hooks();
  }

  /**
   * Add hooks and filters
   *
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function add_hooks() : void
  {
    add_action( 'plugins_loaded', array( '\Rdev\WpTools\Core\i18n', 'load_plugin_textdomain' ) );
  }

  /**
   * Load dependencies
   * 
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function load_dependencies() : void
  {
    $this->settings = new Settings();

    $this->ga = new GA();
    $this->pwdgen = new PwdGen();

  }
}
