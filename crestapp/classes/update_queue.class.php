<?php

class Update_Queue {
	
	
	protected $connection;
	protected $crest;
	protected $feed;
	
	protected $types = array( 'residential-sale','residential-rent','commercial-sale','commercial-lease');
	
	
	public function __construct( $connection, $crest, $feed ){
		
		$this->connection = $connection;
		$this->crest = $crest;
		$this->feed = $feed;
		
	} // end __construct
	
	
	public function get_update_properties_by_type( $types = array(), $count = 60 ){
		
		$properties_type = array();
		
		if ( empty( $types ) ) $types = $this->types;
		
		$properties = $this->get_update_properties( $count );
		
		foreach( $properties as $property_id => $type ){
			
			if ( empty( $properties_type[ $type ] ) ) $properties_type[ $type ] = array();
			
			$properties_type[ $type ][] = $property_id;
			
		} // end foreach
		
		return $properties_type;
		
	} // end get_update_properties_by_type
	
	
	public function get_update_properties( $count, $types = false ){ 
		
		$property_ids = array();
		
		$sql = "SELECT * FROM update_queue WHERE status='AC' LIMIT $count";
		
		$results = $this->connection->query( $sql );
		
		if ( $results->num_rows > 0) {

			while( $row = $results->fetch_assoc() ) {
				
				$property_ids[ $row['Property_ID'] ] = $row['type'];
				
			} // end while
			
		} // end if
		
		return $property_ids;
		
	} // end get_update_ids
	
	
	public function get_crest_updates( $args ){
		
		$properties = array();
		
		$now = new DateTime();
		
		$default_args = array(
			'save' 		=> false, // save updates in database
			'end_time' 	=> $now->format('Y-m-d H:i:s'), // ending point for request - default now
			'minutes' 	=> 15, // get updates from the previous count of minutes (1440 for day) 
		); // end $default_args
		
		$args = array_merge( $default_args, $args );
		
		if ( $this->feed->authenticate() ){
			
			$properties = $this->crest->get_property_updates( $this->feed, $args );
			
			if ( $args['save'] ){
				
				$this->save_properties( $this->feed->get_feed_id(), $properties );
				
			} // end if
		
		} // end if
		
		return $properties;
		
	} // end get_updates
	
	
	protected function save_properties( $feed_id, $properties ){
		
		if ( is_array( $properties ) ){
			
			foreach( $properties as $property_id => $property ){
				
				$type = $property['type'];
			
				$status = $property['status'];
					
				$queue_sql = "INSERT INTO update_queue (Property_ID, status, type, created) VALUES ( '$property_id','$status','$type', now() )";
					
				$this->connection->query( $queue_sql );
				
				if ( ! $this->check_feed_property_exists( $property_id, $feed_id ) ){
					
					$feed_sql = "INSERT INTO crest_feed_properties (feed_id, Property_ID) VALUES ( '$feed_id','$property_id')";
					
					$this->connection->query( $feed_sql );
					
				} // end if
				
			} // end foreach
			
		} // end if
		
	} // end save_properties
	
	
	protected function check_feed_property_exists( $property_id, $feed_id){
		
		$sql = "SELECT * FROM crest_feed_properties WHERE feed_id='$feed_id' AND Property_ID='$property_id'";
		
		$result = $this->connection->query( $sql );
		
		if ( $result->num_rows > 0 ) {
			
			return true;
			
		} // end if
		
		return false;
		
	} // end check_feed_property_exists
	
	
	public function remove_properties( $property_ids ){
		
		$values = array();
		
		foreach( $property_ids as $property_id ){
			
			$values[] = "'" . $property_id . "'";
			
		} // end foreach
		
		$sql = "DELETE FROM update_queue WHERE Property_ID IN (" . implode( ',', $values ) . ")";
		
		$this->connection->query( $sql );
		
	} // end remove_property
	
	
	
}