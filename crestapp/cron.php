<?php

require_once 'classes/endpoint.class.php';

class Cron extends Endpoint {
	
	public function __construct(){
		
		require_once 'config.php';
		
		$this->do_request();
		
	}
	
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'property-manager.class.php';
		$property_manager = new Property_Manager();
		
		$update_property_ids = $property_manager->get_feed_updates();

		$this->response( true, count( $update_property_ids ) . ' Properites Added to Queue', count( $update_property_ids ) );
		
		$detail_property_ids = $property_manager->get_property_details();
		
		$this->response( true, count( $detail_property_ids ) . ' Properites Parsed from Update Queue', $detail_property_ids ); 
		
	} // end do_request
	
	
} // end Cron

$cron = new Cron();