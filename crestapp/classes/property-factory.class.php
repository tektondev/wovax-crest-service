<?php

class Property_Factory {
	
	protected $connection;
	protected $crest;
	protected $feed;
	
	
	public function __construct( $connection = false, $crest = false, $feed = false ){
		
		$this->connection = $connection;
		$this->crest = $crest;
		
		require_once CRESTAPPCLASSPATH . 'property.class.php';
		
		
	} // end __construct
	
	
	public function get_property(){
		
		$property = new Property( $this->connection, $this->feed, $this->crest  );
		
		return $property;
		
	} // end get_property
	
	
	public function get_properties_from_crest( $feed, $type, $property_ids ){
		
		$properties = array();
		
		$crest_properties = $this->crest->get_properties_detail( $feed, $type, $property_ids );
		
		//var_dump( $crest_properties );
		 
		foreach( $crest_properties as $crest_property ){
			 
			$property = $this->get_property();
			
			$property->set_from_crest( $crest_property );
			 
			$properties[] = $property;
			 
		} // end foreach
		
		return $properties;
		
	} // end get_properties_from_crest
	
	
	public function get_db_properties( $status = false ){
		
		$properties = array();
		
		if ( ! $status ){
		
			$sql = "SELECT * FROM crest_properties WHERE Status IN ('Active','Pending')";
			
		} else {
			
			$sql = "SELECT * FROM crest_properties";
			
		} // End if 
		
		$results = $this->connection->query( $sql );
		
		while( $row = $results->fetch_assoc() ) {
				
				$properties[] = '["' . $row['Property_ID'] . '","' . $row['SourcePropertyType'] . '"]';
				
			} // end while
		
		return $properties;
		
	} // End get_db_properties
	
	
}