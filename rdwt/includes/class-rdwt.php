<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * This is the main class of the plugin
 * 
 * @author     Robertas Reiciunas <accesspc@gmail.com>
 * @link       https://reiciunas.dev/plugins/rdev-wp-tools/
 *
 * @since      1.0.0
 * @package    RDWT
 * @subpackage RDWT/includes
 */
class RDWT {

	/**
	 * RDWT Google Analytics Object
	 * 
	 * @access	public
	 * @since		1.1.0
	 * @var			RDWT_GA
	 */
	public $ga;

	/**
	 * RDWT Internationalization Object
	 * 
	 * @access	public
	 * @since		1.0.0
	 * @var			RDWT_i18n
	 */
	public $i18n;

	/**
	 * RDWT Public Object
	 * 
	 * @access	public
	 * @since		1.0.0
	 * @var			RDWT_Public
	 */
	public $public;

	/**
	 * RDWT Password Generator Object
	 * 
	 * @access	public
	 * @since		1.1.0
	 * @var			RDWT_PwdGen
	 */
	public $pwdgen;

	/**
	 * RDWT Settings Object
	 * 
	 * @access	public
	 * @since		1.0.0
	 * @var			RDWT_Settings
	 */
	public $settings;

	/**
	 * RDWT Version Number
	 * 
	 * @access	protected
	 * @since		1.0.0
	 * @var			string
	 */
	protected $version;

	/**
	 * Main construct function
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function __construct() {
		$this->version = RDWT_VERSION;

		$this->load_dependencies();
		$this->add_hooks();
	}

	/**
	 * Add hooks and filters
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function add_hooks() {
		add_action( 'plugins_loaded', array( 'RDWT_i18n', 'load_plugin_textdomain' ) );
	}

	/**
	 * Load file dependencies
	 * 
	 * @access	public
	 * @return	void
	 * @since		1.0.0
	 */
	public function load_dependencies() {
		require_once RDWT_DIR . 'includes/class-rdwt-i18n.php';
		$this->i18n = new RDWT_i18n();

		require_once RDWT_DIR . 'includes/class-rdwt-settings.php';
		$this->settings = new RDWT_Settings();

		require_once RDWT_DIR . 'includes/class-rdwt-ga.php';
		$this->ga = new RDWT_GA();
		
		require_once RDWT_DIR . 'includes/class-rdwt-pwdgen.php';
		$this->pwdgen = new RDWT_PwdGen();

		require_once RDWT_DIR . 'includes/class-rdwt-public.php';
		$this->public = new RDWT_Public();
	}

}
