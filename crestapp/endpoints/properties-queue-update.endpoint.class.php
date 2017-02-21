<?php

require_once 'endpoint.class.php';

class Properties_Queue_Update_Endpoint extends Endpoint {
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'update_queue.class.php';
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		
		$crest = new Crest();
		$connect = new Connect();
		$feed = new Feed( $connect->connect(), 1 );
		
		$update_queue = new Update_Queue( $connect->connect(), $crest, $feed );
		
		$args = $this->get_args();
		
		$updates = $update_queue->get_crest_updates( $args );
		
		$this->response( true, count( $updates ) . ' Properites Added For ' . $args['end_time'], count( $updates ) ); 
		
	} // end do_request
	
	
	protected function get_args(){
		
		$now = ( ! empty( $_POST['end_time'] ) ) ? new DateTime($_POST['end_time']): new DateTime();
		
		$args = array(
			'save' => true, 
			'minutes' => ( ! empty( $_POST['minutes'] ) ) ? $_POST['minutes'] : 15,
			'end_time' => $now->format('Y-m-d'),
		);
		
		var_dump( $args );
		
		return $args;
		
	} // end 
	
}