<?php


class Quire_Repo_User extends Quire_Repo_Abstract {

	protected Quire_Repo_Agency $agency_repo;
	protected Quire_Repo_Group $group_repo;

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


		return $this->_buildItem( $user );
	}

	protected function _buildItem( $user ) {
		/** @var Quire_Data_User $user */
		$id = $user->getID();
		if ( ! isset( $id ) ) {
			return;
		}

		$user->setFirstName( $user->get_meta( 'first_name', true ) );
		$user->setLastName( $user->get_meta( 'last_name', true ) );
		$user->setDisplayName( $user->getRaw( 'display_name' ) );
		$user->setUserEmail( $user->getRaw( 'user_email' ) );
		$user->setUsername( $user->getRaw( 'user_nicename' ) );
		$user->setPhoneNumber( $user->get_meta( 'phone_number', true ) );
		$user->setAvatarUrl( get_avatar_url( $id ) );
		$user->setRoles( $user->getRaw( 'roles' ) );

		try {
			$agency = $user->get_meta( 'agency', true );
			$user->setAgency( $this->agency_repo->getItem( $agency['ID'] ) );

			$groups = $user->get_meta( 'groups' );
			$user->setGroups(
				array_map(
					function ( $group ) {
						return $this->group_repo->getItem( $group['ID'] );
					},
					$groups
				)
			);
		} catch ( Exception $e ) {
		}

		return $user;
	}

	public function createItem( $preparedItem ) {
		// TODO: Implement createItem() method.
	}

	public function updateItem( $preparedItem ) {
		// TODO: Implement updateItem() method.
	}

	public function deleteItem( $preparedItem ) {
		// TODO: Implement deleteItem() method.
	}

	public function getCurrentUser( bool $full = false ) {
		$user = wp_get_current_user();
		$user = $this->getItem( $user->ID );
		if ( ! $full ) {
			$user->setAgency( $user->get_user_meta( 'agency', true )['ID'] );
		}

		return $user;
	}
}