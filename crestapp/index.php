<?php

class CREST_App {
	
	public function __construct(){
		
		require_once 'config.php';
		
		$this->do_request();
		
	} // end __constuct
	
	
	protected function do_request(){
		
		if ( isset( $_GET['service-category'] ) ){
			
			$service = false;
			
			switch( $_GET['service-category'] ){
				
				/*case 'guid':
					require_once 'classes/guid-service.class.php';
					$service = new GUID_Service();
					break;
				case 'feed':
					require_once 'classes/feed-service.class.php';
					$service = new Feed_Service();
					break;*/
				case 'properties':
					require_once 'updated-classes/service-properties.class.php';
					$service = new Service_Properties();
					//require_once 'classes/properties-service.class.php';
					//$service = new Properties_Service();
			
			} // end switch
			
			if ( $service ){
				
				$service->do_service();
				
			} // end if
			
		} else {
			
			header("Location: setup.php");
			
			die();
			
		}// end if
		
	}
	
	
} // end CREST_App

$crest_app = new CREST_App();
