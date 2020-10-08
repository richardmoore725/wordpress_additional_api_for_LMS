<?php


class Quire_API_Course extends Quire_API_Abstract implements Quire_API_Course_Interface {

	public function __construct( $rest_base='courses' ) {
		parent::__construct( $rest_base );
	}

	protected function getRoutes() {
		// TODO: Implement getRoutes() method.
	}

}