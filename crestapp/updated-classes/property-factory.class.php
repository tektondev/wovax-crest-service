<?php

class Property_Factory {
	
	protected $connection = false;
	
	
	public function __construct( $connection = false ){
		
		require_once 'property.class.php';
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function get_property(){
		
		$property = new Property( $this->connection );
		
		return $property;
		
	} // end get_property
	
	
	public function get_property_from_crest( $crest_property ){
		
		$property = $this->get_property();
		
		$property->set_from_crest( $crest_property );
		
		return $property;
		
	} // end get_property_from_crest
	
	
	public function get_crest_properties( $update_properties, $feed ){
		
		$properties = array();
		
		$types = $this->get_properties_by_type( $update_properties );
		
		foreach( $types as $type => $property_ids ){
			
			if ( ! empty( $property_ids ) ){
				
				$response_properties = $this->get_crest_property_details( $property_ids, $type, $feed );
				
				if ( ! empty( $response_properties ) ){
					
					foreach( $response_properties as $crest_property_detail ){
						
						$properties[] = $this->get_property_from_crest( $crest_property_detail );
						
					} // end foreach
					
				} // end if
				
			} // end if
			
		} // end foreach
		
		return $properties;
		
	} // end get_crest_properties
	
	
	protected function get_properties_by_type( $properties ){
		
		$types = array( 
			'residential-sale' => array(),
			'residential-rent' => array(),
			'commercial-sale' => array(),
			'commercial-lease' => array()
		);
		 
		
		foreach( $properties as $property_id => $type ){
			
			if ( array_key_exists( $type , $types ) ){
				
				$types[ $type ][] = $property_id;
				
			} // end if
			
		} // end foreach
		
		return $types;
		
	} // end get_properties_by_type
	
	
	public function get_crest_property_details( $property_ids, $type, $feed  ){
		
		$properties = array();
		
		$token = $feed->get_token();
		
		$cookie = explode( '=' , $token );
		
		$soap_client = new SoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->ListingIDs = new stdClass();
		$params->ListingIDs->guid = $property_ids;
		
		switch( $type ){
			
			case 'residential-sale':
				$response = $soap_client->ResidentialSaleListingDetailGet( $params );
				if ( isset( $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail ) && is_array( $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail ) ) {
					$properties = $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail;
				} // end if
				break;
			case 'commercial-sale':
				$response = $soap_client->CommercialSaleListingDetailGet( $params );
				if ( isset( $response->CommercialSaleListingDetails->CommercialSaleListingDetail ) && is_array( $response->CommercialSaleListingDetails->CommercialSaleListingDetail ) ) {
					$properties = $response->CommercialSaleListingDetails->CommercialSaleListingDetail;
				} // end if
				break;
			case 'residential-rent':
				$response = $soap_client->ResidentialRentalListingDetailGet( $params );
				if ( isset( $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail ) && is_array( $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail ) ) {
					$properties = $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail;
				} // end if
				break;
			case 'commercial-lease':
				$response = $soap_client->CommercialLeaseListingDetailGet( $params );
				if ( isset( $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail ) && is_array( $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail ) ) {
					$properties = $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail;
				} // end if
				break;
				
		} // end switch
		
		return $properties;
		
	} // end delta_get
	
	
	public function delete_property_by_id( $property_id ){
		
		$tables = array(
			'crest_properties',
			'crest_property_agents',
			'crest_property_features',
			'crest_property_georegions',
			'crest_property_images',
			'crest_property_remarks',
			'crest_property_schools',
		);
		
		foreach( $tables as $table ){
			
			$sql = "DELETE FROM $table WHERE Property_ID='$property_id'";
			
			$this->connection->query( $sql );
			
		} // end foreach
		
	} // end delete_property_by_id
	
} // end Property_Factory