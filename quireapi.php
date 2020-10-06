<?php

/*
Plugin Name: QuireAPI
Plugin URI: https://github.com/richardmoore725/quire-api
Description: Backend for QuireApp
Version: 1.0
Author: richard
Author URI: https://github.com/richardmoore725
License: A "Slug" license name e.g. GPL2
*/

defined( 'ABSPATH' ) || exit();

if ( ! defined( 'QUIRE_API_PLUGIN_FILE' ) ) {
	define( 'QUIRE_API_PLUGIN_FILE', __FILE__ );
	require_once dirname( __FILE__ ) . '/includes/constants.php';
}

class QuireApi {

	private static $instance = null;

	/**
	 * QuireApp constructor.
	 */
	private function __construct() {
		$this->_init();
	}

	private function _init() {
		$this->includes();
	}

	public function includes() {
		//Repository autoloader
		require_once QUIRE_API_PLUGIN_CLASSES_DIR . '/class-quire-autoloader.php';

		//API creator
		require_once QUIRE_API_PLUGIN_CLASSES_DIR . '/class-quire-api-loader.php';
	}

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

$GLOBALS['QuireApp'] = QuireApi::getInstance();