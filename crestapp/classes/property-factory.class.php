<?php

class Property_Factory {
	
	protected $connection;
	protected $crest;
	
	
	public function __construct( $connection = false, $crest = false ){
		
		$this->connection = $connection;
		$this->crest = $crest;
		
		require_once CRESTAPPCLASSPATH . 'property.class.php';
		
		
	} // end __construct
	
	
	public function get_property(){
		
		$property = new Property( $this->connection );
		
		return $property;
		
	} // end get_property
	
	
	public function get_properties_from_crest( $feed, $type, $property_ids ){
		
		$properties = array();
		
		$crest_properties = $this->crest->get_properties_detail( $feed, $type, $property_ids );
		 
		foreach( $crest_properties as $crest_property ){
			 
			$property = $this->get_property();
			
			$property->set_from_crest( $crest_property );
			 
			$properties[] = $property;
			 
		} // end foreach
		
		return $properties;
		
	} // end get_properties_from_crest
	
	
}