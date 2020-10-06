<?php


class Quire_API_Loader {

	private $apis = [];

	/**
	 * QuireApp_API_Register constructor.
	 */
	public function __construct() {
		$this->_init();
	}

	private function _init() {
		if ( ! class_exists( 'WP_REST_Server' ) ) {
			return;
		}

		add_action( 'rest_api_init', [ $this, 'createAPIs' ] );
	}

	public function createAPIs() {
		$apis = $this->getAPIs();
		foreach ( $apis as $key => $api ) {
			if ( is_string( $api ) ) {
				$key                = $api;
				$this->apis[ $key ] = new $api( $key );
			} else {
				$this->apis[ $key ] = $api;
			}

			$this->apis[ $key ]->register_routes();
		}
	}

	protected function getAPIs() {
		return [
			'agency'     => 'Quire_API_Agency',
			'assignment' => 'Quire_API_Assignment',
			'course'     => 'Quire_API_Course',
			'group'      => 'Quire_API_Group',
			'user'       => 'Quire_API_User',
		];
	}

}

new Quire_API_Loader();