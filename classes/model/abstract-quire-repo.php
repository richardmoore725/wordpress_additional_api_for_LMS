<?php


abstract class Quire_Repo_Abstract {

	public function getItems( $query_args ) {
		// TODO: Implement getItems() method.
		$query_args['post_type'] = $this->getCPT();
//		$query_args['post_status'] = 'publish';

		$items = [];
		$posts = get_posts( $query_args );
		foreach ( $posts as $post ) {
			$items[] = $this->getItem( $post->ID, $post );
		}

		return $items;
	}

	/**
	 * @return string
	 */
	abstract public function getCPT();

	abstract public function getItem( $id, $raw = [] );

	abstract public function createItem( $preparedItem );

	abstract public function updateItem( $preparedItem );

	abstract public function deleteItem( $preparedItem );


}