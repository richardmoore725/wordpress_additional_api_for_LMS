<?php

function quire_post_insert_hook($order_id, $order, $update){

	if ( $order->post_type === LP_ORDER_CPT && $order_id ) {
		$assignment_repo = new Quire_Repo_Assignment();
		$assignment_repo->addProgressToItem($order_id);
	}
}