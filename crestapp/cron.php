<?php

class Cron {
	
	//protected $feed_manager;
	
	//protected $crest;
	
	
	public function __construct(){
		
		require_once 'config.php';
		
		$this->do_get_properties_details();
		
		$this->do_get_updates();
		
	} // end __construct
	
	
	public function do_get_properties_details(){
		
		require_once 'updated-classes/service-properties.class.php';
		$service = new Service_Properties();
		
		$service_args = array(
			'feed_id' => 1,
		);
		
		$service->do_get_details_service( $service_args );
		
	} // end do_get_properties_details
	
	
	public function do_get_updates(){
		
		require_once 'updated-classes/connect.class.php';
		$connect = new Connect();
		$connection = $connect->connect();
		
		require_once 'updated-classes/feed.class.php';
		$feed = new Feed( $connection );
		$feed->get_feed_by_id( 1 );
		
		$feed->check_update();
		
		if ( $feed->check_update() ){
		
			require_once 'updated-classes/service-properties.class.php';
			$service = new Service_Properties();
			
			$now = new DateTime();
			
			$service_args = array(
				'feed_id' => 1,
				'start_date' => $now->format('Y-m-d H:i:s'),
				'minutes' => 20,
			);
			
			$service->do_updates_service( $service_args, true, false, true );
			
			$feed->set_updated_now();
		
		} // end if
		
	} // end do_get_updates
	
} // end Cron

$cron = new Cron();