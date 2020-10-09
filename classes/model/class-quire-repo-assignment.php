<?php


class Quire_Repo_Assignment extends Quire_Repo_Abstract {

	protected $course_repo;
	protected $user_repo;
	protected $group_repo;

	/**
	 * Quire_Repo_Assignment constructor.
	 */
	public function __construct() {
		$this->course_repo = new Quire_Repo_Course();
		$this->user_repo   = new Quire_Repo_User();
		$this->group_repo = new Quire_Repo_Group();
	}

	public function getCPT() {
		// TODO: Implement getCPT() method.
		return ASSIGNMENT_CPT;
	}


	public function getItem( $id, $raw = [] ) {
		// TODO: Implement getItem() method.
		if ( get_post_type( $id ) != $this->getCPT() ) {
			return;
		}
		$raw = empty( $raw ) ? get_post( $id ) : $raw;

		$assignment = new Quire_Data_Assignment( $id, $raw );

		try {
			$order      = new LP_Order( $id );
			$course_ids = $order->get_item_ids();
			$assignment->setCourses(
				array_map(
					function ( $item_id ) {
						return $this->course_repo->getItem( $item_id );
					},
					$course_ids
				)
			);

			$progress = $assignment->get_meta( 'progress', true );
			if ( $progress = $this->getProgress( $progress['ID'] ) ) {
				$assignment->setProgress( $progress );
			}

			$users = $order->get_user_id();
			if ( ! is_array( $users ) ) {
				$users = [ $users ];
			}
			$assignment->setUsers(
				array_map(
					function ( $user_id ) {
						return $this->user_repo->getItem( $user_id );
					},
					$users
				)
			);

			$groups = $assignment->get_meta( 'groups');
			if ($groups){
				settype($groups, 'array');
				$assignment->setGroups(
					array_map(
						function ( $group_id ) {
							return $this->group_repo->getItem( $group_id );
						},
						$groups
					)
				);
			}
		} catch ( Exception $e ) {
			return;
		}
	}

	public function getProgress( $progress_id ) {
		if ( get_post_type( $progress_id ) != PROGRESS_CPT ) {
			return;
		}
		$raw = empty( $raw ) ? get_post( $progress_id ) : $raw;

		$progress = new Quire_Data_Assignment_Progress( $progress_id, $raw );
		$progress->setUser( absint( $progress->getRaw()->post_author ) );
		$progress->setOrder( absint( $progress->get_meta( 'order', true )['ID'] ) );
		$progress->setPercent( absint( $progress->get_meta( 'percent', true ) ) );
		$progress->setDueDate( $progress->get_meta( 'due_date', true ) );

		return $progress;
	}

	public function createItem( $preparedItem ) {
		// TODO: Implement createItem() method.
	}

	public function updateItem( $id, $preparedItem ) {
		// TODO: Implement updateItem() method.
	}

	public function deleteItem( $id ) {
		// TODO: Implement deleteItem() method.
	}

	public function addProgressToItem( $id ) {

		try {
			$order = new LP_Order( $id );
			if ( $order->is_child() ) {
				$this->_addProgressToItem( $order );
			} else {
				$child_orders = $order->get_child_orders();
				foreach ( $child_orders as $child_order_id ) {
					$this->addProgressToItem( $child_order_id );
				}
			}

		} catch ( Exception $e ) {
		}
	}

	protected function _addProgressToItem( $order ) {

		if ( ! $order instanceof LP_Order ) {
			return;
		}

		$order_agency = $order->get_meta( 'agency', true );
		$users        = $order->get_users();
		if ( ! empty( $users ) ) {
			$agency =  false;
			foreach ( $users as $user_id ) {
				$user = $this->user_repo->getItem( $user_id );
				if ( $user ) {
					$agency = $user->getAgency();
				}
			}

			if ( ! $order_agency && $agency ) {
				$order->update_meta( 'agency', $agency->getID() );
			}

			$progress = $this->createProgress( [
				'post_author' => $user->getID(),
				'post_title'  => $user->getRaw( 'display_name' ) . ' - ' . $order->get_id(),
				'meta_input'  => [
					'order'    => $order->get_id(),
					'percent'  => 0,
					'due_date' => '0000-00-00 00:00:00',
				],
			] );

			if ( $progress ) {
				$order->update_meta( 'progress', $progress->ID );
			}
		}

	}

	public function createProgress( $obj ) {

		$default = [
			'post_type'   => 'progress_tracker',
			'post_status' => 'private',
			'meta_input'  => [
				'percent'  => 0,
				'due_date' => '0000-00-00 00:00:00',
			],
		];

		if ( is_array( $obj ) ) {
			if ( ! array_key_exists( 'post_author', $obj ) ) {
				return;
			}
			if ( ! array_key_exists( 'post_title', $obj ) ) {
				return;
			}
			if ( ! array_key_exists( 'meta_input', $obj ) || ! array_key_exists( 'order', $obj['meta_input'] ) ) {
				return;
			}
		}

		return wp_insert_post( array_merge( $default, $obj ) );
	}

}