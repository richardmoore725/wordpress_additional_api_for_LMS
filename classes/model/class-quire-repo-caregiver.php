<?php


class Quire_Repo_Caregiver extends Quire_Repo_User {

	public function getItem( $id, $raw = [] ) {
		// TODO: Implement getItem() method.
		if ( ! get_userdata( $id ) ) {
			return;
		}
		$raw = empty( $raw ) ? get_userdata( $id ) : $raw;

		/** @var Quire_Data_Caregiver $user */
		$user = $this->_buildItem( new Quire_Data_Caregiver( $id, $raw ) );

		$user->setAssignments( $user->get_meta( 'assignments' ) );
		$user->setCourses( $user->get_meta( 'courses' ) );

		return $user;
	}

	public function updateItem( $id, $preparedItem ) {
		$caregiver = $this->getItem( $id );
		$default = [
			'first_name' =>$caregiver->getFirstName('first_name'),
			'last_name' =>$caregiver->getFirstName('last_name'),
			'phone_number' =>$caregiver->getFirstName('phone_number'),
			'assignments' =>$caregiver->getFirstName('assignments'),
			'courses' =>$caregiver->getFirstName('courses'),
		];

		$preparedItem = array_merge($default, $preparedItem);

		if ( ! $caregiver ) {
			return;
		}

		wp_update_user(
			array(
				'ID'           => $caregiver->getID(),
				'display_name' => $preparedItem['first_name'] . ' ' . $preparedItem['last_name'],
			)
		);

		$caregiver->update_meta( 'first_name', $preparedItem['first_name'] );
		$caregiver->update_meta( 'last_name', $preparedItem['last_name'] );
		$caregiver->update_meta( 'phone_number', $preparedItem['phone_number'] );

		$caregiver->update_multi_meta( 'assignments', $preparedItem['assignments'] );
		$caregiver->update_multi_meta( 'courses', $preparedItem['courses'] );

		return $this->getItem( $id );
	}
}