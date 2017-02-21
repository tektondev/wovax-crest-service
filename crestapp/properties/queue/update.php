<?php

error_reporting(E_ALL);
		
ini_set('display_errors', 1);

require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/classes/endpoint.class.php';

class Update extends Endpoint {
	
	public function __construct(){
		
		require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/config.php';
		
		$this->do_request();
		
	}
	
	
	public function do_request(){
		
		require_once CRESTAPPCLASSPATH . 'property-manager.class.php';
		$property_manager = new Property_Manager();
		
		$args = $this->get_args();
		
		$update_property_ids = $property_manager->get_property_updates( $args );

		$this->response( true, count( $update_property_ids ) . ' Properites Added For ' . $args['end_time'], count( $update_property_ids ) );
		
	} // end do_request
	
	
	protected function get_args(){
		
		$now = ( ! empty( $_POST['render_date'] ) ) ? new DateTime( $_POST['render_date'] ): new DateTime();
		
		$args = array(
			'save' => true, 
			'minutes' => ( ! empty( $_POST['minutes'] ) ) ? $_POST['minutes'] : 15,
			'end_time' => $now->format('Y-m-d'),
		);
		
		if ( ! empty( $_POST['type'] ) ) { $args['types'] = array( $_POST['type'] ); }
		
		return $args;
		
	} // end 
	
} // end Cron

$update = new Update();