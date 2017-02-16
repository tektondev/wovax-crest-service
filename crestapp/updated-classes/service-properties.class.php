<?php

require_once 'service.class.php';

class Service_Properties extends Service {
	
	
	protected function the_service( $service, $service_args ){
		
		error_reporting(E_ALL);
		
		if ( ! $service ) $service = $this->get_service();
		
		switch( $service ){
			
			case 'get-updates':
				$this->do_updates_service( $service_args );
				break;
			case 'preload':
				$this->do_preload_service( $service_args );
				break;
			case 'insert-queue':
				$this->do_insert_queue_service( $service_args );
				break;
			case 'get-details':
				$this->do_get_details_service( $service_args );
				break;
		} // end switch
		
	} // end the_service
	
	
	public function do_updates_service( $service_args = array(),  $echo_response = true, $die = true, $do_add = false ){
		
		$response = '';
		
		if ( ! $service_args ) $service_args = $this->get_updates_service_args();
		
		require_once 'connect.class.php';
		$connect = new Connect();
		$connection = $connect->connect();
		
		require_once 'feed.class.php';
		$feed = new Feed( $connection );
		$feed->get_feed_by_id( $service_args['feed_id'] );
		
		if ( $feed->authenticate() ) {
			
			require_once 'update_queue.class.php';
			$update_queue = new Update_Queue( $connection );
			
			$properties = $update_queue->get_crest_updates( $feed, $service_args );
			
			if ( $do_add ){
			
				$update_queue->add_properties( $properties );
			
			} // end if
			
			$response = $this->the_service_response( $properties , true, 'Properties added to Update Queue' );
			
		} else {
			
			$response = $this->the_service_response( array() , false, 'Could not Authenticate' );
			
		}// end if
		
		if ( $echo_response ){
			
			echo $response;
			
		} else {
			
			return $response;
			
		} // end if
		
	} // end get_updates_service
	
	
	protected function get_updates_service_args(){
		
		$service_args = array(
			'feed_id' => ( ! empty( $_GET['item'] ) ) ? $_GET['item'] : false,
			'start_date' => ( ! empty( $_POST['render_date'] ) )? $_POST['render_date'] : false,
			'minutes' => ( ! empty( $_POST['minutes'] ) )? $_POST['minutes'] : 15,
			'minutes_start' =>( ! empty( $_POST['minutes_start'] ) )? $_POST['minutes_start'] : 0,
		);
		
		return $service_args;
		
	} // end get_updates_args
	
	
	protected function do_preload_service( $service_args = array() ){
		
		include CRESTAPPPATH . 'includes/service-feed-populate.php';
		
	} // end do_preload_service
	
	
	protected function do_insert_queue_service( $service_args ){
		
		if ( ! $service_args ) $service_args = $this->do_insert_queue_service_args();
		
		require_once 'connect.class.php';
		$connect = new Connect();
		$connection = $connect->connect();
		
		require_once 'feed.class.php';
		$feed = new Feed( $connection );
		$feed->get_feed_by_id( $service_args['feed_id'] );
		
		if ( $feed->authenticate() ) {
			
			require_once 'update_queue.class.php';
			require_once 'crest-feed-properties.class.php';
			
			$update_queue = new Update_Queue( $connection );
			$crest_feed_properties = new Crest_Feed_Properties( $connection );
			
			$update_queue->add_properties( $service_args['properties'] );
			
			$crest_feed_properties->insert_properties( $service_args['properties'], $feed );
			
		} // end if
		
	} // end do_insert_queue
	
	
	protected function do_insert_queue_service_args(){
		
		$service_args = array(
			'feed_id' => ( ! empty( $_GET['item'] ) ) ? $_GET['item'] : false,
			'properties' => ( ! empty( $_POST['properties'] ) )? $_POST['properties'] : array(),
		);
		
		return $service_args;
		
	} // end get_updates_args
	
	
	public function do_get_details_service( $service_args, $echo_response = true, $die = true ){
		
		$this->do_remove_properties_service( false );
		
		$response = '';
		
		if ( ! $service_args ) $service_args = $this->do_get_details_service_args();
		
		require_once 'connect.class.php';
		$connect = new Connect();
		$connection = $connect->connect();
		
		require_once 'feed.class.php';
		$feed = new Feed( $connection );
		$feed->get_feed_by_id( 1 );
		
		if ( $feed->authenticate() ) {
			
			require_once 'update_queue.class.php';
			require_once 'property-factory.class.php';
			
			$update_queue = new Update_Queue( $connection );
			$property_factory = new Property_Factory( $connection );
			
			$update_properties = $update_queue->get_update_properties( 10 );
			
			$properties = $property_factory->get_crest_properties( $update_properties, $feed );
			
			foreach( $properties as $property ){
				
				$property->insert();
				
				$update_queue->remove_property( $property->get_field_value('Property_ID') );
				
			} // end foreach
			
			$response = $this->the_service_response( $update_properties , true, 'Properties Updated' );
			
		} else {
			
			$response = $this->the_service_response( array() , false, 'Could not Authenticate' );
			
		}// end if
		
		if ( $echo_response ){
			
			echo $response;
			
		} else {
			
			return $response;
			
		} // end if
		
	} // end do_get_details_service
	
	
	protected function do_get_details_service_args(){
		
		$service_args = array(
			'feed_id' => ( ! empty( $_GET['item'] ) ) ? $_GET['item'] : false,
		);
		
		return $service_args;
		
	} // end get_updates_args
	
	
	public function do_remove_properties_service( $service_args, $echo_response = true, $die = true ){
		
		if ( ! $service_args ) {
			
			$service_args = array(
				'count' 	=> 100,
				'feed_id' 	=> 1,
			);
			
		} // end if
		
		require_once 'connect.class.php';
		$connect = new Connect();
		$connection = $connect->connect();
		
		require_once 'feed.class.php';
		$feed = new Feed( $connection );
		$feed->get_feed_by_id( 1 );
		
		if ( $feed->authenticate() ) {
			
			require_once 'update_queue.class.php';
			require_once 'property-factory.class.php';
			
			$update_queue = new Update_Queue( $connection );
			$property_factory = new Property_Factory( $connection );
			
			$delete_properties = $update_queue->get_delete_properties();
			
			foreach( $delete_properties as $property_id ){
				
				$property_factory->delete_property_by_id( $property_id );
				
				$update_queue->remove_property( $property_id );
				
			} // end foreach
			
			$response = $this->the_service_response( $delete_properties , true, 'Properties Deleted' );
			
		} // end if
		
		
	} // end do_remove_properties_service
	
	
} // end Service_Properties