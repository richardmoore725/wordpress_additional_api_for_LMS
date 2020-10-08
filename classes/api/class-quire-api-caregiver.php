<?php


class Quire_API_Caregiver extends Quire_API_User {

	protected Quire_Repo_Caregiver $base_repo;

	public function __construct( $rest_base='caregivers' ) {
		parent::__construct( $rest_base );
		$this->base_repo = new Quire_Repo_Caregiver();
	}

	protected function getRoutes() {
		// TODO: Implement getRoutes() method.
		return [
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			),
			'(?P<id>[\d]+)' => array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the user.' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
					'args'                => array(
						'force'    => array(
							'type'        => 'boolean',
							'default'     => false,
							'description' => __( 'Required to be true, as users do not support trashing.' ),
						),
						'reassign' => array(
							'type'              => 'integer',
							'description'       => __( 'Reassign the deleted user\'s posts and links to this user ID.' ),
							'required'          => true,
							'sanitize_callback' => array( $this, 'check_reassign' ),
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)

		];
	}

	public function get_item_permissions_check( $request ) {
		return true; // TODO: Change the autogenerated stub
	}

	public function get_item( $request ) {

		$error = new WP_Error(
			'rest_user_invalid_id',
			__( 'Invalid user ID.' ),
			array( 'status' => 404 )
		);

		$caregiver = $this->base_repo->getItem( $request['id'] );

		if (!$caregiver){
			return $error;
		}

		$caregiver     = $this->prepare_item_for_response( $caregiver, $request );
		$response = rest_ensure_response( $caregiver );

		return $response;
	}

	public function prepare_item_for_response( $item, $request ) {
		return $item; // TODO: Change the autogenerated stub
	}
}