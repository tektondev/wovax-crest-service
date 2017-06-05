<?php

class Update_Property_Table {
	
	protected $connection;
	
	public function __construct( $connection ){
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function add_property( $id, $type, $status ){
		
		$queue_sql = "INSERT INTO update_queue (Property_ID, status, type, created) VALUES ( '$id','$status','$type', now() )";
					
		$this->connection->query( $queue_sql );
		
	} // end add_property
	
	
	public function get_properties(){
		
		$properties = array();
		
		$sql = "SELECT * FROM update_queue LIMIT 10";
		
		$results = $this->connection->query( $sql );
		
		if ( $results->num_rows ) {
			
			while( $property = $results->fetch_assoc() ) {
				
				$properties[] = $property;
			
			} // end while
			
		} // end if
		
		return $properties;
		
	} // end get_properties
	
	
	public function remove_property( $property_id ){
		
		$sql = "DELETE FROM update_queue WHERE Property_ID='$property_id'";
		
		$this->connection->query( $sql );
		
	} // end remove_update_property
	
	
	public function does_exist( $property_id ){
		
		$sql = "SELECT * FROM update_queue WHERE Property_ID='$property_id'";
		
		$result = $this->connection->query( $sql );
		
		if ( $result->num_rows > 0 ) {
			
			return true;
			
		} // end if
		
		return false;
		
	} // end check_feed_property_exists
	
}