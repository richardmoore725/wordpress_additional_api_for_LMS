<?php


class Quire_API_User extends Quire_API_Abstract implements Quire_API_User_Interface {

	public function __construct( $rest_base = 'users' ) {
		parent::__construct( $rest_base );
	}

	protected function getRoutes() {
		// TODO: Implement getRoutes() method.
	}

}