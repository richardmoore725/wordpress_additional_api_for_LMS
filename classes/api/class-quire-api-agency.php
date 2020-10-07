<?php


class Quire_API_Agency extends Quire_API_Abstract implements Quire_API_Agency_Interface {

	private $repoAgency = null;

	private $repoUser = null;

	public function __construct( $rest_base ) {
		$this->rest_base  = $rest_base;
	}

	protected function getRoutes() {
		// TODO: Implement getRoutes() method.
		return array();
	}

}