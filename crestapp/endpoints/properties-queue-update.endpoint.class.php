<?php

require_once 'endpoint.class.php';

class Properties_Queue_Update_Endpoint  extends Endpoint {
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'update_queue.class.php';
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		require_once CRESTAPPCLASSPATH . 'feed-factory.class.php';
		
		$crest = new Crest();
		$connect = new Connect();
		$feed_factory = new Feed_Factory( $connect->connect() );
		
		$update_queue = new Update_Queue( $connect->connect(), $crest, $feed_factory );
		
		$updates = $update_queue->get_crest_updates( 1 , array( 'save' => true, 'minutes' => 1440 ) );
		
	} // end do_request
	
}