<?php

require_once 'endpoint.class.php';

class Properties_Cron_Endpoint extends Endpoint {
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'property-manager.class.php';
		$property_manager = new Property_Manager();
		
		$update_property_ids = $property_manager->get_property_updates();

		$this->response( true, count( $update_property_ids ) . ' Properites Added For ' . $args['end_time'], count( $update_property_ids ) );
		
		$detail_property_ids = $property_manager->get_property_details();
		
		$this->response( true, count( $detail_property_ids ) . ' Properites Added from Update Queue', $detail_property_ids ); 
		
	} // end do_request
	
}