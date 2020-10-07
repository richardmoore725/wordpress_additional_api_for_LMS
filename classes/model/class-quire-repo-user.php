<?php


class Quire_Repo_User extends Quire_Repo_Abstract {

	protected $agency_repo;
	protected $group_repo;

	/**
	 * Quire_Repo_User constructor.
	 */
	public function __construct() {
		$this->agency_repo = new Quire_Repo_Agency();
		$this->group_repo  = new Quire_Repo_Group();
	}

	public function getCPT() {
		// TODO: Implement getCPT() method.
		return;
	}

	public function getItems( $query_args ) {
		// TODO: Implement getItems() method.

		$items = [];
		$users = get_users( $query_args );
		foreach ( $users as $user ) {
			$items[] = $this->getItem( $user->ID, $user );
		}

		return $items;
	}

	public function getItem( $id, $raw = [] ) {
		// TODO: Implement getItem() method.
		$user = new Quire_Data_User( $id, $raw );
		$user->setFirstName( get_user_meta( $id, 'first_name', true ) );
		$user->setLastName( get_user_meta( $id, 'last_name', true ) );
		$user->setDisplayName( $user->getRaw( 'display_name' ) );
		$user->setUserEmail( $user->getRaw( 'user_email' ) );
		$user->setUsername( $user->getRaw( 'user_nicename' ) );
		$user->setPhoneNumber( get_user_meta( $id, 'phone_number', true ) );
		$user->setAvatarUrl( get_avatar_url( $id ) );
		$user->setRoles( $user->getRaw( 'roles' ) );

		$agency = $this->agency_repo->getItem( get_user_meta( $id, 'agency', true )['ID'] );
		$user->setAgency( $agency );

		$user->setGroups(
			array_map(
				function ( $group ) {
					return $this->group_repo->getItem( $group['ID'] );
				},
				get_user_meta( $id, 'groups' )
			)
		);

		return $user;
	}

	public function createItem( $obj ) {
		// TODO: Implement createItem() method.
	}

	public function updateItem( $obj ) {
		// TODO: Implement updateItem() method.
	}

	public function deleteItem( $obj ) {
		// TODO: Implement deleteItem() method.
	}

	public function getCurrentUser( bool $full = false ) {
		$user = wp_get_current_user();
		$user = $this->getItem( $user->ID );
		if ( $full ) {
			$user->setAgency( $user->get_user_meta( $id, 'agency', true )['ID'] );
		}

		return $user;
	}
}