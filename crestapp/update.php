<?php

class Update {
	
	protected $connect;
	
	protected $log;
	
	
	public function __construct(){
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		require_once 'config.php';
		
		require_once CRESTAPPCLASSPATH . 'log.class.php';
		
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		
		$this->connect = new Connect();
			
		$this->log = new Log( $this->connect->connect() );
		
		if ( ! isset( $_GET['property_id'] ) ){	
			
			$this->do_update();
		
		} else {
			
			$this->ajax_update();
			
		} // End if
		
	} // End __construct
	
	
	public function do_update(){
		
		require_once 'classes/property-factory.class.php';
		
		$property_factory = new Property_Factory( $this->connect->connect() );
		
		$js_properties = array();
		
		$properties = $property_factory->get_db_properties();
		
		include_once 'includes/property-update/property-update.php';
		
		//var_dump( $properties );
		
	} // End do_update
	
	
	public function ajax_update(){
		
		$updated_array = array();
		
		$feed = $this->get_feed( $this->connect );
		
		$feed->authenticate();
		
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		
		require_once CRESTAPPCLASSPATH . 'property.class.php';
		
		$crest = new Crest();
		
		$property_id = $_GET['property_id'];
		
		$property_type = ( ! empty($_GET['property_type'] ) ) ? $_GET['property_type'] : 'ResidentialSale';
		
		$property = new Property( $this->connect->connect(), $feed, $crest );
		
		if ( $property_type ){
			
			$crest_property = $crest->single_detail_get( $feed, $property_type, $property_id, $this->log );
			
			if ( $crest_property ){
				
				$property->set_from_crest( $crest_property );
				
				$property->set_field( 'SourcePropertyType', $property_type );
				
				$property->insert_property( true );
				
			} else {
				
				echo $property_id . ' property not in crest';
				
			}
			
		} // End if
		
	} // End ajax_update
	
	
	protected function get_feed( $connect ){
		
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		
		$feed = new Feed( $connect->connect(), 1 );
		
		return $feed;
		
	} // end get_feed
	
} 

$update = new Update();