<?php

require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/classes/endpoint.class.php';

class Process extends Endpoint {
	
	public function __construct(){
		
		require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/config.php';;
		
		$this->do_request();
		
	}
	
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'property-manager.class.php';
		$property_manager = new Property_Manager();
		$detail_property_ids = $property_manager->get_property_details();
		
		$this->response( true, count( $detail_property_ids ) . ' Properites Added from Update Queue', $detail_property_ids ); 
		
	} // end do_request
	
}

$process = new Process();