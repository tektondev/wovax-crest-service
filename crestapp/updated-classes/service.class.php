<?php

class Service {
	
	public function do_service( $service = false, $service_args = array() ){
		
		if ( method_exists( $this, 'the_service' ) ){
			
			$response = $this->the_service( $service, $service_args );
			
		} else {
			
			$response = array(
				'status' => false,
				'msg' => 'Service exists but not defined', 
			);
			
		} // end if
		
		return $response;
		
	} // end do_service
	
	
	public function the_service_response( $response, $status = true, $msg = '', $die = false ){
		
		$json = array(
			'status' => $status,
			'msg' => $msg,
			'response' => $response,
		);
		
		echo json_encode( $json );
		
		if ( $die ) die();
		
	} // end service_response
	
	
	protected function get_service(){
		
		$service = ( ! empty( $_GET['service'] ) ) ? $_GET['service'] : false;
		
		return $service;
		
	} // end get_service
	
}