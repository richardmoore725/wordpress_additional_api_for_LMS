<?php


class Quire_API_Agency extends Quire_API_Abstract implements Quire_API_Agency_Interface {

	private $repoAgency = null;

	private $repoUser = null;

	public function __construct( $rest_base ) {
		$this->rest_base  = $rest_base;
		$this->repoAgency = new Quire_Repo_Agency();
		$this->repoUser   = new Quire_Repo_User();
	}

	protected function getRoutes() {
		// TODO: Implement getRoutes() method.
		return array();
	}

}