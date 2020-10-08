<?php


class Quire_Data_Assignment extends Quire_Data_Abstract implements Quire_Data_Assignment_Interface {

	protected $progress;
	protected $users;
	protected $courses;

	public function __construct( $ID, $raw = [] ) {
		parent::__construct( $ID, $raw );
	}

	public function getUsers() {
		// TODO: Implement getUsers() method.
		return $this->users;
	}

	/**
	 * @param mixed $users
	 */
	public function setUsers( $users ): void {
		$this->users = $users;
	}

	public function getProgress() {
		// TODO: Implement getProgress() method.
		return $this->progress;
	}

	/**
	 * @param mixed $progress
	 */
	public function setProgress( $progress ): void {
		$this->progress = $progress;
	}

	public function getCoures() {
		// TODO: Implement getCoures() method.
		return $this->courses;
	}

	/**
	 * @param mixed $courses
	 */
	public function setCourses( $courses ): void {
		$this->courses = $courses;
	}


}