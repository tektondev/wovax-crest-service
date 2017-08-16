<?php

require_once 'classes/endpoint.class.php';

class Cron extends Endpoint {
	
	public function __construct(){
		
		require_once 'config.php';
		
		error_reporting(E_ALL);
		
		ini_set("display_errors", 1);
		
		$this->do_request();
		
		
	}
	
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'log.class.php';
		
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		
		$connect = new Connect();
		
		$log = new Log( $connect->connect() );
		
		$feed = $this->get_feed( $connect );
		
		$feed->authenticate();
		
		$office = $this->get_office( $connect );
		
		$crest_properties = $this->get_crest_properties( $log, $feed, $office );
		
		//var_dump( $crest_properties );
		
		$local_property_ids = $this->get_db_properties( $connect );
		
		foreach( $crest_properties as $property_id => $crest_property ){
			
			if ( in_array( $property_id, $local_property_ids ) ) continue; 
			
			$this->add_update_property( $log, $connect, $crest_property );
			
		} // end foreach 
		
	} // end do_request
	
	
	protected function get_feed( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		
		$feed = new Feed( $connect->connect(), 1 );
		
		return $feed;
		
	} // end get_feed
	
	
	protected function get_office( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'office.class.php';
		
		$office = new Office( $connect->connect() );
		
		$office->set_last_updated();
		
		return $office;
		
	} // end get_office
	
	
	protected function get_crest_properties( $log, $feed, $office ){
		
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		
		$crest = new Crest();
		
		$properties = array();
		
		if ( $office->next_type && $office->next_status ){
		
			$type = $office->next_type;
			
			$status = $office->next_status;
			
			//var_dump( $type, $status );
			
			$properties = $crest->get_properties_by_office( $office, $feed, $type, $status  );
			
			$office->insert_type_status();
			
			if ( ! empty( $properties ) ){
				
				$log->add_record( 'Crest Request: ' . count( $properties ) . ' Properties Found for ' . $office->name . ', ' . $type . ', ' . $status );
				
			} else {
				
				$log->add_record( 'Crest Request: No Properties Found for ' . $office->name . ', ' . $type . ', ' . $status . ', ' . $office->id, true );
				
			} // end if
			
		} else {
			
			$office->insert_type_status();
			
			$log->add_record( 'No Type or Status: For ' . $office->name . ', ' . $office->next_type . ', ' . $office->next_status . ', ' . $office->id, true );
			
		}// end if
		
		return $properties;
		
	} // end get_properties
	
	
	protected function get_db_properties( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'properties.class.php';
		
		$property = new Properties( $connect->connect() );
		
		$property_ids = $property->get_db_properties();
		
		return $property_ids;
		
	} // end get_db_properties
	
	
	protected function add_update_property( $log, $connect, $property ){
		
		require_once CRESTAPPCLASSPATH . 'update-property-table.class.php';
		
		$Update_Queue = new Update_Property_Table( $connect->connect() );
		
		$id = $property->ListingID;
		
		$type = $property->ListingType;
		
		$status = $property->Status;
		
		if ( ! $Update_Queue->does_exist( $id ) ){
			
			$Update_Queue->add_property( $id, $type, $status );
			
			$log->add_record( 'Added To Update Queue: ' . $id );
			
		} // end if
		
	} // end add_update_property
	
	
} // end Cron

$cron = new Cron();