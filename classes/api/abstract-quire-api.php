<?php


abstract class Quire_API_Abstract extends WP_REST_Controller {

	public function __construct( $rest_base ) {
		$this->namespace = 'quire/v2';
		$this->rest_base = $rest_base;
	}

	public function register_routes() {
		$routes = $this->getRoutes();

		$rest_base = $this->rest_base;

		if ( ! $routes ) {
			return;
		}

		foreach ( $routes as $key => $route ) {

			if ( ! is_numeric( $key ) ) {
				$rest_base = "{$rest_base}/{$key}";
			}

			register_rest_route( $this->namespace, '/' . $rest_base, $route );
		}
	}

	abstract protected function getRoutes();

}