<?php

class Endpoint {
	
	
	public function do_request(){
	} // end do_request
	
	
	protected function response( $status, $msg, $data ){
		
		$json = array(
			'status' 	=> $status,
			'msg' 		=> $msg,
			'data' 		=> $data,
		);
		
		echo json_encode( $json );	
		
	} // end response
	
}