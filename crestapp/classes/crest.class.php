<?php

class Crest {
	
	
	public function get_property_updates( $feed, $args, $types = array() ){
		
		$properties = array();
			
		if ( empty( $types ) ) $types = array( 'residential-sale','residential-rent','commercial-sale','commercial-lease');
		
		
		foreach( $types as $type ){
		
			$response_ids = $this->get_properties_delta( $feed, $args, $type );
			
			sleep( 1 );
			
			$properties = array_merge( $response_ids, $properties );
		
		} // end foreach
		
		return $properties;
		
	} // end get_crest_updates
	
	
	public function get_agent( $agent_id, $feed ){
		
		$agent = false;
		
		$cookie = explode( '=' , $feed->get_token() );
		
		//$soap_client = new DummySoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client = new SoapClient( 'http://solows.realogyfg.com/V1.3/BrokerageRW/OfficeStaffService.Svc?wsdl', array('trace' => 1) );
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->PersonIds = new stdClass();
		$params->PersonIds->guid = $agent_id;
		
		try {
			
			$response = $soap_client->PersonDetailGet( $params );
			
			$agent = $response->Persons->Person;
			
		} catch( Exception $e ) {
			
			// do nothing
			
		} // end catch
		
		return $agent;
		
	} // end get_agent
	
	
	protected function get_properties_delta( $feed, $args, $type ){
		
		ini_set( 'max_execution_time', 3600 );
		
		set_time_limit ( 3600 ); 
		
		$date = new DateTime( $args['end_time'] );
		
		$end_time = $date->format('Y-m-d\TH:i:s.u');
		
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
	
	
	public function get_properties_detail( $feed, $type, $property_ids ){
		
		$properties_detail = array();
		
		$sets = array_chunk( $property_ids, 10 );
		
		if ( $feed->authenticate() ){
		
			foreach( $sets as $set ){
				
				$temp_prop = $this->detail_get( $feed, $type, $set );
				
				$properties_detail = array_merge( $properties_detail, $temp_prop );
				
				sleep( 1 );
				
			} // end get_properties_detail
		
		} // end if
		
		return $properties_detail;
		
	} // end get_properties_detail
	
	
	public function single_detail_get( $feed, $type, $property_id, $log ){
		
		$property = false;
		
		$cookie = explode( '=' , $feed->get_token() );
		
		//$soap_client = new DummySoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client = new SoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->ListingIDs = new stdClass();
		$params->ListingIDs->guid = $property_id;
		
		try {
		
		switch( $type ){
			
			case 'ResidentialSale':
				$response = $soap_client->ResidentialSaleListingDetailGet( $params );
				if ( isset( $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail ) ) {
					$property = $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail;
				} // end if
				break;
			case 'CommercialSale':
				$response = $soap_client->CommercialSaleListingDetailGet( $params );
				if ( isset( $response->CommercialSaleListingDetails->CommercialSaleListingDetail ) ){
					$property = $response->CommercialSaleListingDetails->CommercialSaleListingDetail;
				} // end if
				break;
			case 'ResidentialRental':
				$response = $soap_client->ResidentialRentalListingDetailGet( $params );
				if ( isset( $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail ) ){
					$property = $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail;
				} // end if
				break;
			case 'CommercialLease':
				$response = $soap_client->CommercialLeaseListingDetailGet( $params );
				if ( isset( $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail ) ){
						$property = $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail;
				} // end if
				break;
				
		} // end switch
		
		} catch( Exception $e ){
			
			$log->add_record( 'Crest Property Failed: ' . $property_id . ', type: ' . $type  );
			
		}
		
		return $property;
		
	} // end delta_get
	
	
	public function detail_get( $feed, $type, $property_ids  ){
		
		$properties = array();
		
		$cookie = explode( '=' , $feed->get_token() );
		
		//$soap_client = new DummySoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client = new SoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->ListingIDs = new stdClass();
		$params->ListingIDs->guid = $property_ids;
		
		try {
		
		switch( $type ){
			
			case 'residential-sale':
				$response = $soap_client->ResidentialSaleListingDetailGet( $params );
				if ( isset( $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail ) ) {
					if ( is_array( $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail ) ) {
						$properties = $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail;
					} else {
						$properties = array( $response->ResidentialSaleListingDetails->ResidentialSaleListingDetail );
					} // end if
				} // end if
				break;
			case 'commercial-sale':
				$response = $soap_client->CommercialSaleListingDetailGet( $params );
				if ( isset( $response->CommercialSaleListingDetails->CommercialSaleListingDetail ) ){
					if ( is_array( $response->CommercialSaleListingDetails->CommercialSaleListingDetail ) ) {
						$properties = $response->CommercialSaleListingDetails->CommercialSaleListingDetail;
					} else {
						$properties = array($response->CommercialSaleListingDetails->CommercialSaleListingDetail);
					 } // end if
				} // end if
				break;
			case 'residential-rent':
				$response = $soap_client->ResidentialRentalListingDetailGet( $params );
				if ( isset( $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail ) ){
					 if ( is_array( $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail ) ) { 
						$properties = $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail;
					} else {
							$properties = array( $response->ResidentialRentalListingDetails->ResidentialRentalListingDetail);
					 } // end if
				} // end if
				break;
			case 'commercial-lease':
				$response = $soap_client->CommercialLeaseListingDetailGet( $params );
				if ( isset( $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail ) ){
					 if ( is_array( $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail ) ) {
						$properties = $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail;
					} else {
						$properties = array( $response->CommercialLeaseListingDetails->CommercialLeaseListingDetail );
					 } // end if
				} // end if
				break;
				
		} // end switch
		
		} catch( Exception $e ){
		}
		
		return $properties;
		
	} // end delta_get
	
	
	public function get_properties_by_office( $office, $feed, $type, $status ){
		
		$properties = array();
		
		$cookie = explode( '=' , $feed->get_token() );
		
		$soap_client = new SoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		//$soap_client = new DummySoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->OfficeID = $office->id;
		$params->ListingType = new stdClass();
		$params->ListingType->ListingType = $type;
		$params->ListingStatus = $status;
		
		try {
		
			$response = $soap_client->ListingSearch2( $params );
		
		} catch( Exception $e ){
			
			$response = false;
			
		} // end try
		
		if ( ( $response !== false ) && ( isset( $response->ListingSearchResult ) ) ){
			
			if ( is_object( $response->ListingSearchResult ) && isset( $response->ListingSearchResult->ListingID )  ) {
				
				$properties[ $response->ListingSearchResult->ListingID ] = $response->ListingSearchResult;
				
			} else if ( is_array( $response->ListingSearchResult ) ){
				
				foreach( $response->ListingSearchResult as $index => $property ){
					
					$properties[ $property->ListingID ] = $property;
					
				} // end foreach
				
			} // end if
			
		} // end if
		
		return $properties;
		
	} // end get_properties_by_office
	
	
	
	
	
	
	/*public $token = false;
	
	protected $feed_id = 1;
	
	protected $user = false;
	
	protected $pwd = false;
	
	protected $brand_code = false;
	
	protected $basic = false;
	
	
	public function set_feed_id( $feed_id ){ $this->feed_id = $feed_id; }
	public function set_feed_user(  $feed_user ){ $this->user = $feed_user; }
	public function set_feed_pwd( $feed_pwd ){ $this->pwd = $feed_pwd; }
	public function set_brand_code( $feed_brand_code ){ $this->brand_code = $feed_brand_code; }
	public function set_token( $value ) { $this->token = $value; }
	public function set_token_expires( $value ) { $this->token_expires = $value; }
	public function set_basic(){ $this->basic = base64_encode( $this->user . ':' . $this->pwd ); }
	
	
	public function set_feed( $feed ){
		$id = ( $feed['id']  )? $feed['id']  : '';
		$user = ( $feed['user']  )? $feed['id']  : ''; 
		$pwd = ( $feed['pwd']  )? $feed['id']  : '';
		$brand_code = ( $feed['brand_code']  )? $feed['id']  : ''; 
		$token =  ( $feed['token']  )? $feed['id']  : '';
		$token_expires = ( $feed['token_expires']  )? $feed['id']  : '';
		 
		$this->set_feed_id( $id );
		$this->set_feed_user( $user );
		$this->set_feed_pwd( $pwd );
		$this->set_brand_code( $brand_code );
		$this->set_token( $token );
		$this->set_token_expires( $token_expires );
		
		$this->set_basic();
	}
	
	
	public function authenticate() {
		
		if ( ! $this->basic ) $this->set_basic(); 
		
		$xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"><soapenv:Header/><soapenv:Body/></soapenv:Envelope>';

		$headers = array(
			'Content-Type: text/xml',
			'Accept-Encoding: gzip,deflate',
			'SOAPAction: "http://rfg.realogy.com/Btt/AuthenticationManagement/Services/2009/05/AuthenticationManagementServiceContract/Authenticate"',
			'Host: auth.ws.realogyfg.com',
			'Connection: Keep-Alive',
			'Cookie: OBBasicAuth=fromDialog; ObSSOCookie=loggedoutcontinue',
			//'Cookie2: $Version=1',
			'Authorization: Basic ' . $this->basic,
		);
		
		$process = curl_init( 'https://auth.ws.realogyfg.com/AuthenticationService/AuthenticationMgmt.svc' );
		
		curl_setopt($process, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		
		$response = curl_exec($process);
		
		//var_dump( $response );
		
		preg_match( '/<a:Token>(.*)<\/a:Token>/', $response, $matches, PREG_OFFSET_CAPTURE );
		
		if ( ! empty( $matches[1][0] ) ){
			
			$this->token = ( $matches[1][0] );
			
		} // end if
		
		
	} // end authenticate
	
	
	
	
	public function get_properties_delta_time( $start_time, $minutes, $brandcode, $types = false ){
		
		if ( ! $this->token ) $this->authenticate();
		
		$properties = array();
		
		if ( $this->token ){
			
			if ( ! $types ) $types = array( 'residential-sale','residential-rent','commercial-sale','commercial-lease');
			
			foreach( $types as $type ){
			
				$response_ids = $this->delta_get_time( $start_time, $minutes, $brandcode, $type );
				
				$properties = array_merge( $response_ids, $properties );
			
			} // end foreach
		
		} // end if
		
		return $properties;
		
	} // end get_properties_delta
	
	
	protected function save_token(){
		
		require_once 'feed.class.php';
		
		$feed = new Feed();
		
	} // end save token
	 
	
	
	protected function delta_get_time( $s_time, $minutes, $brandcode, $type = 'residential-sale' ){
		
		//var_dump( $s_time );
		
		if ( ! $this->token ) $this->authenticate();
		
		$date = new DateTime( $s_time );
		
		$end_time = $date->format('Y-m-d\TH:i:s.u');
		
		$date->modify( '-' . $minutes . ' minutes');
		
		$start_time = $date->format('Y-m-d\TH:i:s.u');
		
		$response = false;
		
		$cookie = explode( '=' , $this->token );
		
		$soap_client = new SoapClient( 'http://solows.realogyfg.com/V1.3/ListingRW/ListingService.Svc?wsdl', array('trace' => 1) );
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->DeltaCriteria = new stdClass();
		$params->DeltaCriteria->LastUpdateFromDate = $start_time;
		$params->DeltaCriteria->LastUpdateToDate = $end_time;
		$params->DeltaCriteria->BrandCode = $brandcode;
		
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
	
	public function get_properties_detail( $properties ){
		
		if ( ! $this->token ) $this->authenticate();
		
		$properties_detail = array();
		
		$types = $this->get_properties_by_type( $properties );
		
		foreach( $types as $type => $property_ids ){
			
			if ( ! empty( $property_ids ) ){
				
				$response_properties = $this->detail_get( $property_ids, $type );
				
				$properties_detail = array_merge( $properties_detail , $response_properties );
				
			} // end if
			
		} // end foreach
		
		return $properties_detail;
		
	} // end get_properties
	
	
	public function detail_get( $property_ids, $type ){
		
		if ( ! $this->token ) $this->authenticate();
		
		$properties = array();
		
		$cookie = explode( '=' , $this->token );
		
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
				if ( isset( $response->CommercialSaleListingDetails->CommercialSaleListingDetail ) && is_array( $response->CommercialSaleListingDetails->CommercialSaleListingDetail ) ) {
					$properties = $response->CommercialSaleListingDetails->CommercialSaleListingDetail;
				} // end if
				break;
				
		} // end switch
		
		return $properties;
		
	} // end delta_get
	
	
	protected function get_properties_by_type( $properties ){
		
		$types = array( 
			'residential-sale' => array(),
			'residential-rent' => array(),
			'commercial-sale' => array(),
			'commercial-lease' => array()
		);
		 
		
		foreach( $properties as $property_id => $property ){
			
			if ( array_key_exists( $property['type'] , $types ) ){
				
				$types[ $property['type'] ][] = $property_id;
				
			} // end if
			
		} // end foreach
		
		return $types;
		
	} // end get_properties_by_type*/
	
	
} // end Crest


class DummySoapClient extends SoapClient {
    function __construct($wsdl, $options) {
        parent::__construct($wsdl, $options);
    }
    function __doRequest($request, $location, $action, $version, $one_way = 0) {
        var_dump( $request );
		return parent::__doRequest($request, $location, $action, $version, $one_way);
    }
}