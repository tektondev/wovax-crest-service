<?php

require_once 'classes/endpoint.class.php';

class Cron extends Endpoint {
	
	public function __construct(){
		
		require_once 'config.php';
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		$this->do_request();
		
	}
	
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'log.class.php';
		
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		
		$connect = new Connect();
		
		$log = new Log( $connect->connect() );
		
		$update_properties = $this->get_update_properties( $connect, $log );
		
		if ( ! empty( $update_properties ) ){
			
			$this->do_add_properties( $update_properties, $connect, $log );
			
		} else {
			
			$this->do_update_properties( $connect, $log );
			
		} // end if
		
		
		//$office = $this->get_office( $connect );
		
		//var_dump( $feed );
		
		//require_once CRESTAPPCLASSPATH . 'property-manager.class.php';
		//$property_manager = new Property_Manager();
		
		//$update_property_ids = $property_manager->get_feed_updates();

		//$this->response( true, count( $update_property_ids ) . ' Properites Added to Queue', count( $update_property_ids ) );
		
		//$detail_property_ids = $property_manager->get_property_details();
		
		//$this->response( true, count( $detail_property_ids ) . ' Properites Parsed from Update Queue', $detail_property_ids ); 
		
	} // end do_request
	
	
	public function get_update_properties( $connect, $log ) {
		
		require_once CRESTAPPCLASSPATH . 'update-property-table.class.php';
		
		$update_queue = new Update_Property_Table( $connect->connect() );
		
		$properties = $update_queue->get_properties();
		
		return $properties;
		
	} // end get_update_properties
	
	
	protected function do_add_properties( $update_properties, $connect, $log ){
		
		$crest_properties = array();
		
		$feed = $this->get_feed( $connect );
		
		$feed->authenticate();
		
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		
		$crest = new Crest();
		
		foreach( $update_properties as $update_property ){
			
			$crest_property = $crest->single_detail_get( $feed, $update_property['type'], $update_property['Property_ID'], $log );
			
			if ( $crest_property ){
			
				require_once CRESTAPPCLASSPATH . 'property.class.php';
				
				$property = new Property( $connect->connect(), $feed, $crest );
				
				$property->set_from_crest( $crest_property );
				
				$property->set_field( 'SourcePropertyType', $update_property['type'] );
				
				$property->insert( $feed, $crest );
				
				$log->add_record( 'Property Inserted: ' . $update_property['Property_ID'] . ', ' . $update_property['type'] );
				
				$this->remove_update_property( $connect, $update_property['Property_ID'] );
			
			} // end if
			
		} // end foreach
		
	} // end do_add_properties
	
	
	protected function do_update_properties( $connect, $log ){
		
		$connection = $connect->connect();
		
		//$properties = array();
		
		//$sql = "SELECT * FROM crest_properties ORDER BY wovaxUpdated DESC LIMIT 5 ";
		
		$sql = "SELECT * FROM crest_properties ORDER BY wovaxUpdated ASC LIMIT 5 ";
		
		$results = $connection->query( $sql );
		
		$feed = $this->get_feed( $connect );
		
		$feed->authenticate();

		require_once CRESTAPPCLASSPATH . 'crest.class.php';

		require_once CRESTAPPCLASSPATH . 'property.class.php';
		
		$crest = new Crest();
		
		while( $db_property = $results->fetch_assoc() ) {
			
			$this->update_property( $db_property, $connection, $feed, $crest, $log );
			
		} // end while
		
		$active_sql = "SELECT * FROM crest_properties WHERE Status IN ('Active','Pending') ORDER BY wovaxUpdated ASC LIMIT 5 ";
		
		$active_results = $connection->query( $active_sql );
		
		while( $active_db_property = $active_results->fetch_assoc() ) {
			
			$this->update_property( $active_db_property, $connection, $feed, $crest, $log );
			
		} // end while
		
	} // End do_update_properties
	
	
	protected function update_property( $db_property, $connection, $feed, $crest, $log ){
		
		$db_property = $db_property;

			$property_id = $db_property['Property_ID'];

			$property_type = ( ! empty( $db_property['SorcePropertyType'] ) ) ? $_GET['SorcePropertyType'] : 'ResidentialSale';

			$property = new Property( $connection, $feed, $crest );
				
			$crest_property = $crest->single_detail_get( $feed, $property_type, $property_id, $log );
			
			if ( $crest_property ){
				
				$property->set_from_crest( $crest_property );
				
				$property->set_field( 'SourcePropertyType', $property_type );
				
				$force_update = ( ! empty( $_GET['f_update'] ) )? true : false;
				
				$property->insert_property( true, $force_update );
				
			} else {
				
				$sql = "UPDATE crest_properties SET wovaxUpdated=now() WHERE Property_ID='$property_id'";
				
				$connection->query( $sql );
				
				echo $property_id . ' up-to-date not In CREST <br />';
				
			}// End if
		
	} // end update_property
	
	
	protected function get_feed( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		
		$feed = new Feed( $connect->connect(), 1 );
		
		return $feed;
		
	} // end get_feed
	
	
	public function remove_update_property( $connect, $property_id ) {
		
		require_once CRESTAPPCLASSPATH . 'update-property-table.class.php';
		
		$update_queue = new Update_Property_Table( $connect->connect() );
		
		$properties = $update_queue->remove_property( $property_id );
		
	} // end get_update_properties
	
	
	/*protected function get_feed( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		
		$feed = new Feed( $connect->connect(), 1 );
		
		return $feed;
		
	} // end get_feed
	
	
	protected function get_office( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'office.class.php';
		
		$office = new Office( $connect->connect() );
		
		$office->set_last_updated();
		
	} // end get_office
	
	
	protected function get_properties( $feed, $office ){
		
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		
		$crest = new Crest();
		
		$properties = $crest->get_properties_by_office( $office, $feed );
		
	} // end get_properties*/
	
	
} // end Cron

$cron = new Cron();