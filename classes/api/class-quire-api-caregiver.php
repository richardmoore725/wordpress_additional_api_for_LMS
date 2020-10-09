<?php


class Quire_API_Caregiver extends Quire_API_User {

	protected Quire_Repo_Caregiver $base_repo;
	protected Quire_Repo_Agency $agency_repo;

	public function __construct( $rest_base = 'caregivers' ) {
		parent::__construct( $rest_base );
		$this->base_repo   = new Quire_Repo_Caregiver();
		$this->agency_repo = new Quire_Repo_Agency();
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
				)
			),
			'(?P<id>[\d]+)' => array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the caregiver.' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
				),
				array(
					'methods'             => 'PATCH',
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			),
			'me'            => array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_me' ),
					'permission_callback' => array( $this, 'get_me_permissions_check' ),
				),
				array(
					'methods'             => 'PATCH',
					'callback'            => array( $this, 'update_me' ),
					'permission_callback' => array( $this, 'update_me_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)

		];
	}

	public function get_item_permissions_check( $request ) {
		$caregiver_id = $request['id'];

		$current_user = $this->base_repo->getCurrentUser( true );
		if ( ! $current_user ) {
			return new WP_Error(
				'rest_user_no_login',
				__( 'User not logged in.' ),
				array( 'status' => 404 )
			);
		}

		if ( ! in_array( CAREGIVER_ROLE, $current_user->getRoles() ) ) {
			$permission = $this->agency_repo->getItemsBy( $current_user->getID(), $caregiver_id );
		} else {
			$permission = $caregiver_id == $current_user->getID();
		}

		if ( ! $permission ) {
			return new WP_Error(
				'rest_user_no_permission',
				__( 'User have not permission to a caregiver!' ),
				array( 'status' => 404 )
			);
		}

		return true; // TODO: Change the autogenerated stub
	}

	public function get_item( $request ) {

		$caregiver = $this->base_repo->getItem( $request['id'] );

		if ( ! $caregiver ) {
			return new WP_Error(
				'rest_caregiver_invalid_id',
				__( 'Invalid caregiver ID.' ),
				array( 'status' => 404 )
			);
		}

		$data     = $this->prepare_item_for_response( $caregiver, $request );
		$response = rest_ensure_response( $data );

		return $response;
	}

	public function get_me_permissions_check( $request ) {

		$current_user = $this->base_repo->getCurrentUser( true );
		if ( ! $current_user ) {
			return new WP_Error(
				'rest_user_no_login',
				__( 'User not logged in.' ),
				array( 'status' => 404 )
			);
		}

		if ( ! in_array( CAREGIVER_ROLE, $current_user->getRoles() ) ) {
			return new WP_Error(
				'rest_user_no_caregiver',
				__( 'User is not a caregiver!' ),
				array( 'status' => 404 )
			);
		}

		return true; // TODO: Change the autogenerated stub
	}

	public function get_me( $request ) {

		$current_user = $this->base_repo->getCurrentUser( true );
		$data         = $this->prepare_item_for_response( $current_user, $request );
		$response     = rest_ensure_response( $data );

		return $response;
	}

	public function prepare_item_for_response( $item, $request ) {
		return $item; // TODO: Change the autogenerated stub
	}

	public function update_item_permissions_check( $request ) {
		return $this->get_item_permissions_check( $request ); // TODO: Change the autogenerated stub
	}

	public function update_item( $request ) {

		$caregiver_id = (int) $request->get_param( 'id' );
		$params       = $request->get_json_params();

		$caregiver = $this->base_repo->getItem( $caregiver_id );

		if ( ! $caregiver ) {
			return new WP_Error(
				'rest_caregiver_invalid_id',
				__( 'Invalid caregiver ID.' ),
				array( 'status' => 404 )
			);
		}

		$caregiver = $this->base_repo->updateItem( $caregiver_id, $params );

		$data     = $this->prepare_item_for_response( $caregiver, $request );
		$response = rest_ensure_response( $data );

		return $response;
	}

	public function update_me_permissions_check( $request ) {
		return $this->get_me_permissions_check( $request );
	}

	public function update_me( $request ) {

		$current_user = $this->base_repo->getCurrentUser( true );
		$params       = $request->get_json_params();

		if ( ! $params ) {
			return new WP_Error(
				'rest_caregiver_no_param',
				__( 'Failed to update caregiver, missing paramaters' ),
				array( 'status' => 404 )
			);
		}

		$caregiver = $this->base_repo->updateItem( $current_user->getID(), $params );

		$data     = $this->prepare_item_for_response( $caregiver, $request );
		$response = rest_ensure_response( $data );

		return $response;
	}

	public function get_items_permissions_check( $request ) {
		$agency_id = $request['agency'];
		if ( isset ( $agency_id ) ) {
			$current_user = $this->base_repo->getCurrentUser( true );
			if ( ! $current_user ) {
				return new WP_Error(
					'rest_user_no_login',
					__( 'User not logged in.' ),
					array( 'status' => 404 )
				);
			}

			/** @var Quire_Data_Agency $agency */
			$agency = $this->agency_repo->getItem( $agency_id );
			if ( ! $agency ) {
				return new WP_Error(
					'rest_agency_invalid_id',
					__( 'Invalid agency ID.' ),
					array( 'status' => 404 )
				);
			}
			$admin_ids = array_column( $agency->getAdministrators(), 'ID' );
			if ( ! in_array( $current_user->getID(), $admin_ids ) ) {
				return new WP_Error(
					'rest_user_no_permission',
					__( 'User have not permission to a agency!' ),
					array( 'status' => 404 )
				);
			}
		}

		return true; // TODO: Change the autogenerated stub
	}

	public function get_items( $request ) {

		$registered = $this->get_collection_params();

		$parameter_mappings = array(
			'order' => 'order',
		);

		$prepared_args = array();

		foreach ( $parameter_mappings as $api_param => $wp_param ) {
			if ( isset( $registered[ $api_param ], $request[ $api_param ] ) ) {
				$prepared_args[ $wp_param ] = $request[ $api_param ];
			}
		}

		if ( isset( $registered['orderby'] ) ) {
			$orderby_possibles        = array(
				'id'              => 'ID',
				'name'            => 'display_name',
				'registered_date' => 'registered',
				'email'           => 'user_email',
			);
			$prepared_args['orderby'] = $orderby_possibles[ $request['orderby'] ];
		}

		$prepared_args['role'] = CAREGIVER_ROLE;

		$agency = $request['agency'];
		if ( ! isset ( $agency ) ) {
			$current_user = $this->base_repo->getCurrentUser( true );

			/** @var Quire_Data_Agency $agency */
			$agency = $current_user->getAgency();
			if ( ! $agency ) {
				return new WP_Error(
					'rest_user_no_agency',
					__( 'User does not belong to a agency!' ),
					array( 'status' => 404 )
				);
			}
			$agency = (string) $agency->getID();
		}

		$prepared_args['meta_query'][] = array(
			'key'     => 'agency',
			'value'   => $agency,
			'compare' => '='
		);

		$caregivers = $this->base_repo->getItems( $prepared_args );

		if ( ! $caregivers ) {
			return new WP_Error(
				'rest_user_no_agency',
				__( 'User does not belong to a agency!' ),
				array( 'status' => 404 )
			);
		}

		$data = array();
		foreach ( $caregivers as $caregiver ) {
			$data[] = $this->prepare_item_for_response( $caregiver, $request );
		}

		$response = rest_ensure_response( $data );

		return $response;
	}

	public function get_collection_params() {
		return array(
			'agency'  => array(
				'description' => __( 'agency ID to retrieve caregivers.(default:current user\'s agency)' ),
				'type'        => 'integer'
			),
			'order'   => array(
				'default'     => 'asc',
				'description' => __( 'Order sort attribute ascending or descending.' ),
				'enum'        => array( 'asc', 'desc' ),
				'type'        => 'string',
			),
			'orderby' => array(
				'default'     => 'name',
				'description' => __( 'Sort collection by object attribute.' ),
				'enum'        => array(
					'id',
					'name',
					'registered_date',
					'email',
				),
				'type'        => 'string',
			)
		);
	}

	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'caregiver',
			'type'       => 'object',
			'properties' => array(
				'id'              => array(
					'description' => __( 'Unique identifier for the caregiver.' ),
					'type'        => 'integer',
					'context'     => array( 'embed', 'view', 'edit' ),
					'readonly'    => true,
				),
				'first_name'      => array(
					'description' => __( 'First name for the caregiver.' ),
					'type'        => 'string',
					'context'     => array( 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'last_name'       => array(
					'description' => __( 'Last name for the caregiver.' ),
					'type'        => 'string',
					'context'     => array( 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'email'           => array(
					'description' => __( 'The email address for the caregiver.' ),
					'type'        => 'string',
					'format'      => 'email',
					'context'     => array( 'edit' ),
					'required'    => true,
				),
				'phone_number'    => array(
					'description' => __( 'The phone number for the caregiver.' ),
					'type'        => 'string',
					'context'     => array( 'edit' ),
				),
				'registered_date' => array(
					'description' => __( 'Registration date for the caregiver.' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'edit' ),
					'readonly'    => true,
				),
				'assignments'     => array(
					'description' => __( 'All assignments assigned to the caregiver.' ),
					'type'        => 'array',
					'context'     => array( 'edit' ),
				),
				'courses'         => array(
					'description' => __( 'All courses assigned to the caregiver.' ),
					'type'        => 'array',
					'context'     => array( 'edit' ),
				),
			),
		);

		return array_merge( $schema, parent::get_item_schema() ); // TODO: Change the autogenerated stub
	}

}