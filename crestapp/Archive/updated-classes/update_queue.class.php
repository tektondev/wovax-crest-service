<?php

class Update_Queue {
	
	protected $connection = false;
	
	
	public function __construct( $connection ){
		
		$this->connection = $connection;
		
	} // end __construct
	
	
	public function get_crest_updates( $feed, $args, $types = array() ){
		
		$properties = array();
			
		if ( empty( $types ) ) $types = array( 'residential-sale','residential-rent','commercial-sale','commercial-lease');
		
		
		foreach( $types as $type ){
		
			$response_ids = $this->delta_get_time( $args, $feed, $type );
			
			$properties = array_merge( $response_ids, $properties );
		
		} // end foreach
		
		return $properties;
		
	} // end get_crest_updates
	
	
	public function add_properties( $properties ) {
		
		if ( is_array( $properties ) ){
			
			foreach( $properties as $property_id => $property ){
				
				$type = $property['type'];
			
				$status = $property['status'];
				
				if ( ! $this->check_existing( $property_id ) ){
					
					$sql = "INSERT INTO update_queue (Property_ID, status, type, created) VALUES ( '$property_id','$status','$type', now() )";
					
					$this->connection->query( $sql );
					
				} else {
					
					$sql = "UPDATE update_queue SET status='$status',created=now() WHERE Property_ID='$property_id'";
					
					$this->connection->query( $sql );
					
				} // end if
				
			} // end foreach
			
		} // end if
		
	} // end add_properties
	
	
	public function get_update_properties( $count ){ 
		
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
	
	
	public function get_delete_properties( $count = 100 ){ 
		
		$property_ids = array();
		
		$sql = "SELECT * FROM update_queue WHERE status='DE' LIMIT $count";
		
		$results = $this->connection->query( $sql );
		
		if ( $results->num_rows > 0) {

			while( $row = $results->fetch_assoc() ) {
				
				$property_ids[] = $row['Property_ID'];
				
			} // end while
			
		} // end if
		
		return $property_ids;
		
	} // end get_update_ids
	
	
	protected function delta_get_time( $args, $feed, $type = 'residential-sale' ){
		
		ini_set( 'max_execution_time', 3600 );
		
		set_time_limit ( 3600 ); 
		
		$date = new DateTime( $args['start_date'] );
		
		$end_date = new DateTime( $args['start_date'] );
		
		if ( $args['minutes_start'] ) {
			
			$end_date->modify( '-' . $args['minutes_start'] . ' minutes');
			
		} // end if
		
		$end_time = $end_date->format('Y-m-d\TH:i:s.u');
		
		$date->modify( '-' . $args['minutes'] . ' minutes');
		
		$start_time = $date->format('Y-m-d\TH:i:s.u');
		
		$response = false;
		
		$token = $feed->get_token();
		
		$cookie = explode( '=' , $token );
		
		$soap_client = new SoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->DeltaCriteria = new stdClass();
		$params->DeltaCriteria->LastUpdateFromDate = $start_time;
		$params->DeltaCriteria->LastUpdateToDate = $end_time;
		$params->DeltaCriteria->BrandCode = $feed->get_brand_code();
		
		try {
		
			switch( $type ){
				
				case 'residential-sale':
					$response = $soap_client->ResidentialSaleListingDeltaGet( $params );
					break;
				case 'commercial-sale':
					$response = $soap_client->CommercialSaleListingDeltaGet( $params );
					break;
				case 'residential-rent':
					$response = $soap_client->ResidentialRentalListingDeltaGet( $params );
					break;
				case 'commercial-lease':
					$response = $soap_client->CommercialLeaseListingDeltaGet( $params );
					break;
					
			} // end switch
		
		} catch( Exception $e ) {
			
			$response_ids = array();
			
		} // end catch
		
		$response_ids = $this->get_ids_from_response( $response, $type );
		
		return $response_ids;
		
	} // end 
	
	
	protected function get_ids_from_response( $response, $type ){
		
		$ids = array();
		
		if ( isset( $response->UpdatedListings->UpdatedEntity ) && is_array( $response->UpdatedListings->UpdatedEntity ) ){
			
			$responses = $response->UpdatedListings->UpdatedEntity;
			
			foreach( $responses as $property ){
				
				$ids[ $property->EntityId ] = array( 'id' => $property->EntityId, 'status' => $property->Status, 'type' => $type );
				
			} // end foreach
			
		} // end if
		
		return $ids;
		
	} // end get_id_from_response
	
	
	public function remove_property( $property_id ){
		
		$sql = "DELETE FROM update_queue WHERE Property_ID='$property_id'";
		
		$this->connection->query( $sql );
		
	} // end remove_property
	
	
	public function check_existing( $property_id ){
		
		$sql = "SELECT * FROM update_queue WHERE Property_ID='$property_id'";
		
		$result = $this->connection->query( $sql );
		
		if ( $result->num_rows > 0 ) {
			
			return true;
			
		} // end if
		
		return false;
		
	} // end check_existing
	
} // end Update_Queue