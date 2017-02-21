<?php

require_once 'endpoint.class.php';

class Properties_Queue_Process_Endpoint extends Endpoint {
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'update_queue.class.php';
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		require_once CRESTAPPCLASSPATH . 'property-factory.class.php';
		
		$crest = new Crest();
		$connect = new Connect();
		$feed = new Feed( $connect->connect(), 1 );
		$property_factory = new Property_Factory( $connect->connect(), $crest );
		
		$update_queue = new Update_Queue( $connect->connect(), $crest, $feed );
		
		$update_properties = $update_queue->get_update_properties_by_type();
		
		$properties = array();
		
		$remove_properties = array();
		
		foreach( $update_properties as $type => $property_ids ){
			
			$temp_properties = $property_factory->get_properties_from_crest( $feed, $type, $property_ids );
			
			$properties = array_merge( $properties , $temp_properties );
			
			foreach( $properties as $property ){
				
				$property->insert();
				
				$remove_properties[] = $property->get_field_value( 'Property_ID' );
				
			} // end foreach
			
		} //endforeach
		
		$update_queue->remove_properties( $remove_properties );
		
	} // end do_request
	
}