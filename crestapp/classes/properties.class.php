<?php

class Properties {
	
	protected $connection;
	
	public function __construct( $connection ){
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function get_db_properties( $ids_only = true ){
		
		$properties = array();
		
		$sql = "SELECT * FROM crest_properties";
		
		$results = $this->connection->query( $sql );
		
		while( $property = $results->fetch_assoc() ) {
			
			if ( $ids_only ){
				
				$properties[] = $property['Property_ID'];
				
			} else {
				
				$properties[ $property['Property_ID'] ] = $property;
				
			} // end if
			
		} // end while
		
		return $properties;
		
	} // end 
	
}