<?php

class Property_Manager {
	
	protected $crest;
	protected $connect;
	protected $feed;
	protected $people_factory;
	
	
	public function __construct(){
		
		require_once CRESTAPPCLASSPATH . 'update_queue.class.php';
		require_once CRESTAPPCLASSPATH . 'crest.class.php';
		require_once CRESTAPPCLASSPATH . 'connect.class.php';
		require_once CRESTAPPCLASSPATH . 'feed.class.php';
		require_once CRESTAPPCLASSPATH . 'property-factory.class.php';
		
		$this->crest = new Crest();
		$this->connect = new Connect();
		$this->feed = new Feed( $this->connect->connect(), 1 );
		$this->feed->authenticate();
		$this->property_factory = new Property_Factory( $this->connect->connect(), $this->crest );
		
	}
	
	
	public function get_feed_updates(){
		
		if ( $this->feed->check_update() ){
			
			$updates = $this->get_property_updates( array() );
			
			$this->feed->set_updated_now();
			
			return $updates;
			
		} else {
			
			return array();
			
		}// end 
		
	} // end get_feed_updates
	
	
	public function get_property_details(){
		
		$update_queue = new Update_Queue( $this->connect->connect(), $this->crest, $this->feed );
		
		$update_properties = $update_queue->get_update_properties_by_type( array(), 30 );
		
		$properties = array();
		
		$remove_properties = array();
		
		foreach( $update_properties as $type => $property_ids ){
			
			$temp_properties = $this->property_factory->get_properties_from_crest( $this->feed, $type, $property_ids );
			
			$properties = array_merge( $properties , $temp_properties );
			
			foreach( $properties as $property ){
				
				//$property->to_db();
				
				$property->to_db();
				
				$remove_properties[] = $property->get_field_value( 'Property_ID' );
				
			} // end foreach
			
			$remove_properties = array_merge( $remove_properties, $property_ids );
			
		} //endforeach
		
		$update_queue->remove_properties( $remove_properties );
		
		return $remove_properties;
	
	}
	
	
	public function get_property_updates( $args = array() ){
		
		$update_queue = new Update_Queue( $this->connect->connect(), $this->crest, $this->feed );
		
		$now = new DateTime();
		
		$default_args = array(
			'save' => true, 
			'minutes' => 15,
			'end_time' => $now->format('Y-m-d'),
		);
		
		$args = array_merge( $default_args, $args );
		
		$updates = $update_queue->get_crest_updates( $args );
		
		return $updates;
		
	}
	
	
}