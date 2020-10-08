<?php


class Quire_API_Agency extends Quire_API_Abstract implements Quire_API_Agency_Interface {

	public function __construct( $rest_base='agencies' ) {
		parent::__construct( $rest_base );
	}

	protected function getRoutes() {
		// TODO: Implement getRoutes() method.
		return array();
	}

}