<?php


class Quire_Repo_Caregiver extends Quire_Repo_User {

	public function getItem( $id, $raw = [] ) {
		// TODO: Implement getItem() method.
		/** @var Quire_Data_Caregiver $user */
		$user = $this->_buildItem( new Quire_Data_Caregiver( $id, $raw ) );

		$user->setAssignments($user->get_meta('assignments')) ;
		$user->setCourses($user->get_meta('courses')) ;

		return $user;
	}
}