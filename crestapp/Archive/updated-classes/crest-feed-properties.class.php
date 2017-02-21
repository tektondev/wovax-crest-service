<?php

class Crest_Feed_Properties {
	
	
	protected $connection = false;
	
	
	public function __construct( $connection ){
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	
	public function insert_properties( $properties, $feed ){
		
		$feed_id = $feed->get_feed_id();
		
		if ( is_array( $properties ) ){
			
			foreach( $properties as $property_id => $property ){
				
				if ( ! $this->check_existing( $property_id, $feed_id ) ){
					
					$sql = "INSERT INTO crest_feed_properties (feed_id, Property_ID) VALUES ( '$feed_id','$property_id')";
					
					$this->connection->query( $sql );
					
				} // end if
				
			} // end foreach
			
		} // end if
		
	} // end insert_properties
	
	
	
	public function check_existing( $property_id, $feed_id ){
		
		$sql = "SELECT * FROM crest_feed_properties WHERE feed_id='feed_id' AND Property_ID='$property_id'";
		
		$result = $this->connection->query( $sql );
		
		if ( $result->num_rows > 0 ) {
			
			return true;
			
		} // end if
		
		return false;
		
	} // end check_existing
	
	
} // end Crest_Feed_Properties